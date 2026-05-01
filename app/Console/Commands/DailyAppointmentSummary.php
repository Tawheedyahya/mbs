<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DailyAppointmentSummary extends Command
{
    protected $signature   = 'mbs:daily-summary {--date= : Override date (YYYY-MM-DD)}';
    protected $description = 'Send daily appointment summary at 8AM via WhatsApp + Email to each outlet';

    public function handle(): void
    {
        $date = $this->option('date') ?? Carbon::today()->toDateString();
        $this->info("Running daily appointment summary for: {$date}");

        // Only process hospitals that have at least one notification method configured
        $hospitals = DB::table('hospitals')
            ->where('db_status', 1)
            ->where(function($q) {
                $q->whereNotNull('summary_email')->where('summary_email', '!=', '')
                  ->orWhere(function($q2) {
                      $q2->whereNotNull('summary_whatsapp')->where('summary_whatsapp', '!=', '')
                         ->whereNotNull('summary_flow_id')->where('summary_flow_id', '!=', '');
                  });
            })
            ->get();

        foreach ($hospitals as $hospital) {
            $bookings = DB::table('bookings as b')
                ->leftJoin('doctors as d', 'd.id', '=', 'b.doctor_id')
                ->where('b.hospital_id', $hospital->id)
                ->whereDate('b.booking_date', $date)
                ->whereNotIn('b.status', ['cancelled', 'rejected'])
                ->select(
                    'b.patient_name',
                    'b.patient_phone',
                    'b.start_time',
                    'b.cause',
                    'b.status',
                    'd.name as doctor_name'
                )
                ->orderBy('b.start_time')
                ->get();

            if ($bookings->isEmpty()) {
                $this->line("  [{$hospital->hospital_name}] No appointments today — skipping.");
                continue;
            }

            // ── Build summary text ───────────────────────────────
            $dateFormatted = Carbon::parse($date)->format('d M Y (l)');
            $lines = [];
            $lines[] = "📋 *Daily Appointment Summary*";
            $lines[] = "🏥 *{$hospital->hospital_name}*";
            $lines[] = "📅 *{$dateFormatted}*";
            $lines[] = "Total: *{$bookings->count()} appointment(s)*";
            $lines[] = "─────────────────────";

            foreach ($bookings as $i => $b) {
                $time    = Carbon::parse($b->start_time)->format('h:i A');
                $doctor  = $b->doctor_name ?? '—';
                $service = $b->cause       ?? '—';
                $lines[] = "";
                $lines[] = "*" . ($i + 1) . ". {$b->patient_name}*";
                $lines[] = "🕐 {$time}";
                $lines[] = "📞 {$b->patient_phone}";
                $lines[] = "👨‍⚕️ {$doctor}";
                $lines[] = "🩺 {$service}";
            }

            $lines[]  = "";
            $lines[]  = "─────────────────────";
            $lines[]  = "_Sent by Speedbots MBS_";
            $message  = implode("\n", $lines);

            // ── Send WhatsApp via Speedbots ──────────────────────
            // Step 1: Create contact → Step 2: Set custom field → Step 3: Send flow
            if (!empty($hospital->summary_whatsapp) && !empty($hospital->token) && !empty($hospital->summary_flow_id)) {
                $phones      = array_map('trim', explode(',', $hospital->summary_whatsapp));
                $flowId      = $hospital->summary_flow_id;
                $token       = $hospital->token;
                $fieldId     = $hospital->summary_field_id ?? null; // custom field ID e.g. 915759

                // Build the summary text to set in custom field
                $dateFormatted2 = Carbon::parse($date)->format('l, d M Y');
                $summaryText    = "Daily Appointment Summary\n"
                    . "{$hospital->hospital_name}\n"
                    . "{$dateFormatted2}\n\n"
                    . "You have {$bookings->count()} appointment(s) today.\n"
                    . "Please check your system for full details.\n\n"
                    . config('app.url') . "/login";

                foreach ($phones as $phone) {
                    $cleanPhone = preg_replace('/[^0-9+]/', '', $phone);
                    if (empty($cleanPhone)) continue;

                    try {
                        // CALL 1: Create contact (safe if already exists)
                        Http::timeout(10)->withoutVerifying()
                            ->withHeaders([
                                'X-ACCESS-TOKEN' => $token,
                                'Content-Type'   => 'application/json',
                                'accept'         => 'application/json',
                            ])
                            ->post('https://app.speedbots.io/api/contacts', [
                                'phone' => $cleanPhone,
                            ]);

                        // CALL 2: Set custom field with summary text (if field ID configured)
                        if (!empty($fieldId)) {
                            Http::timeout(10)->withoutVerifying()
                                ->withHeaders([
                                    'X-ACCESS-TOKEN' => $token,
                                    'Content-Type'   => 'application/x-www-form-urlencoded',
                                    'accept'         => 'application/json',
                                ])
                                ->asForm()
                                ->post("https://app.speedbots.io/api/contacts/{$cleanPhone}/custom_fields/{$fieldId}", [
                                    'value' => $summaryText,
                                ]);

                            Log::info('Daily summary custom field set', [
                                'hospital' => $hospital->hospital_name,
                                'phone'    => $cleanPhone,
                                'field_id' => $fieldId,
                            ]);
                        }

                        // CALL 3: Send the flow
                        $response = Http::timeout(10)->withoutVerifying()
                            ->withHeaders([
                                'X-ACCESS-TOKEN' => $token,
                                'accept'         => 'application/json',
                            ])
                            ->post("https://app.speedbots.io/api/contacts/{$cleanPhone}/send/{$flowId}");

                        Log::info('Daily summary flow sent', [
                            'hospital' => $hospital->hospital_name,
                            'phone'    => $cleanPhone,
                            'flow_id'  => $flowId,
                            'status'   => $response->status(),
                        ]);

                        $this->line("  [{$hospital->hospital_name}] WhatsApp sent to {$cleanPhone}");

                    } catch (\Exception $e) {
                        Log::error('Daily summary WhatsApp failed', [
                            'hospital' => $hospital->hospital_name,
                            'phone'    => $cleanPhone,
                            'error'    => $e->getMessage(),
                        ]);
                        $this->error("  [{$hospital->hospital_name}] WhatsApp failed: {$e->getMessage()}");
                    }
                }
            } elseif (empty($hospital->summary_flow_id)) {
                $this->warn("  [{$hospital->hospital_name}] Skipped WhatsApp — no summary_flow_id configured in WhatsApp Settings.");
            }

            // ── Send Email ───────────────────────────────────────
            $emailList = [];
            if (!empty($hospital->summary_email)) {
                $emailList = array_map('trim', explode(',', $hospital->summary_email));
            }

            if (!empty($emailList)) {
                try {
                    // Build HTML table for email
                    $rows = '';
                    foreach ($bookings as $i => $b) {
                        $time   = Carbon::parse($b->start_time)->format('h:i A');
                        $bg     = $i % 2 === 0 ? '#f9fafb' : '#ffffff';
                        $rows  .= "
                            <tr style='background:{$bg}'>
                                <td style='padding:10px 14px;border-bottom:1px solid #e5e7eb'>" . ($i + 1) . "</td>
                                <td style='padding:10px 14px;border-bottom:1px solid #e5e7eb;font-weight:600'>{$b->patient_name}</td>
                                <td style='padding:10px 14px;border-bottom:1px solid #e5e7eb'>{$time}</td>
                                <td style='padding:10px 14px;border-bottom:1px solid #e5e7eb'>{$b->patient_phone}</td>
                                <td style='padding:10px 14px;border-bottom:1px solid #e5e7eb'>" . ($b->doctor_name ?? '—') . "</td>
                                <td style='padding:10px 14px;border-bottom:1px solid #e5e7eb'>" . ($b->cause ?? '—') . "</td>
                            </tr>";
                    }

                    $html = "
                    <div style='font-family:Arial,sans-serif;max-width:700px;margin:0 auto'>
                        <div style='background:#1363C6;padding:24px 32px;border-radius:8px 8px 0 0'>
                            <h2 style='color:#fff;margin:0;font-size:20px'>📋 Daily Appointment Summary</h2>
                            <p style='color:rgba(255,255,255,0.8);margin:6px 0 0'>{$hospital->hospital_name} — {$dateFormatted}</p>
                        </div>
                        <div style='background:#fff;padding:24px 32px;border:1px solid #e5e7eb;border-top:none'>
                            <p style='color:#374151;margin:0 0 16px'>Total appointments today: <strong>{$bookings->count()}</strong></p>
                            <table style='width:100%;border-collapse:collapse;font-size:14px'>
                                <thead>
                                    <tr style='background:#1363C6;color:#fff'>
                                        <th style='padding:10px 14px;text-align:left'>#</th>
                                        <th style='padding:10px 14px;text-align:left'>Patient</th>
                                        <th style='padding:10px 14px;text-align:left'>Time</th>
                                        <th style='padding:10px 14px;text-align:left'>Phone</th>
                                        <th style='padding:10px 14px;text-align:left'>Doctor</th>
                                        <th style='padding:10px 14px;text-align:left'>Service</th>
                                    </tr>
                                </thead>
                                <tbody>{$rows}</tbody>
                            </table>
                        </div>
                        <div style='background:#f9fafb;padding:16px 32px;border:1px solid #e5e7eb;border-top:none;border-radius:0 0 8px 8px'>
                            <p style='color:#9ca3af;font-size:12px;margin:0'>Sent automatically by Speedbots Medical Booking System</p>
                        </div>
                    </div>";

                    Mail::html($html, function ($mail) use ($emailList, $hospital, $dateFormatted) {
                        $mail->to($emailList)
                             ->subject("📋 Daily Appointments — {$hospital->hospital_name} | {$dateFormatted}");
                    });

                    $this->line("  [{$hospital->hospital_name}] Email sent to: " . implode(', ', $emailList));

                } catch (\Exception $e) {
                    Log::error('Daily summary email failed', [
                        'hospital' => $hospital->hospital_name,
                        'error'    => $e->getMessage(),
                    ]);
                    $this->error("  [{$hospital->hospital_name}] Email failed: {$e->getMessage()}");
                }
            }
        }

        $this->info("Daily summary complete.");
    }
}