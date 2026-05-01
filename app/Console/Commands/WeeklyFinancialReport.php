<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class WeeklyFinancialReport extends Command
{
    protected $signature   = 'mbs:weekly-financial {--week= : Override week start date (YYYY-MM-DD)}';
    protected $description = 'Send weekly financial report every Monday — individual report per hospital to their own configured email';

    public function handle(): void
    {
        $weekStart = $this->option('week')
            ? Carbon::parse($this->option('week'))->startOfDay()
            : Carbon::now()->subWeek()->startOfWeek();

        $weekEnd   = $weekStart->copy()->endOfWeek();
        $weekLabel = $weekStart->format('d M Y') . ' - ' . $weekEnd->format('d M Y');

        $this->info("Running weekly financial report for: {$weekLabel}");

        // Only hospitals with report_email configured
        $hospitals = DB::table('hospitals')
            ->where('db_status', 1)
            ->whereNotNull('report_email')
            ->where('report_email', '!=', '')
            ->get();

        if ($hospitals->isEmpty()) {
            $this->warn("No hospitals have report_email configured. Set it in WhatsApp Settings.");
            return;
        }

        foreach ($hospitals as $hospital) {

            // ── Get this hospital's own data only ────────────
            $income = DB::table('hospital_financials')
                ->where('hospital_id', $hospital->id)
                ->where('type', 'profit')
                ->whereBetween('entry_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
                ->sum('amount');

            $billing = DB::table('patient_billing_entries')
                ->where('hospital_id', $hospital->id)
                ->where('is_paid', true)
                ->whereBetween('created_at', [$weekStart->toDateTimeString(), $weekEnd->toDateTimeString()])
                ->sum('amount');

            $totalIncome  = round($income + $billing, 2);

            $totalExpense = round(DB::table('hospital_financials')
                ->where('hospital_id', $hospital->id)
                ->where('type', 'expense')
                ->whereBetween('entry_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
                ->sum('amount'), 2);

            $net = round($totalIncome - $totalExpense, 2);

            $totalBookings = DB::table('bookings')
                ->where('hospital_id', $hospital->id)
                ->whereBetween('booking_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
                ->count();

            $completed = DB::table('bookings')
                ->where('hospital_id', $hospital->id)
                ->where('status', 'completed')
                ->whereBetween('booking_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
                ->count();

            $noShow = DB::table('bookings')
                ->where('hospital_id', $hospital->id)
                ->where('status', 'no_show')
                ->whereBetween('booking_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
                ->count();

            $pending = DB::table('bookings')
                ->where('hospital_id', $hospital->id)
                ->where('status', 'pending')
                ->whereBetween('booking_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
                ->count();

            $noShowRate = $totalBookings > 0 ? round(($noShow / $totalBookings) * 100, 1) : 0;
            $netColor   = $net >= 0 ? '#27500A' : '#791F1F';
            $netBg      = $net >= 0 ? '#EAF3DE' : '#FCEBEB';

            // ── Revenue breakdown by billing type ────────────
            $billingByType = DB::table('patient_billing_entries')
                ->where('hospital_id', $hospital->id)
                ->where('is_paid', true)
                ->whereBetween('created_at', [$weekStart->toDateTimeString(), $weekEnd->toDateTimeString()])
                ->selectRaw('type, SUM(amount) as total, COUNT(*) as count')
                ->groupBy('type')
                ->orderByDesc('total')
                ->get();

            $typeRows = '';
            foreach ($billingByType as $t) {
                $typeRows .= "
                    <tr>
                        <td style='padding:8px 12px;border-bottom:0.5px solid #e5e7eb;color:#374151'>" . ucfirst(str_replace('_', ' ', $t->type)) . "</td>
                        <td style='padding:8px 12px;border-bottom:0.5px solid #e5e7eb;text-align:center;color:#6b7280'>{$t->count}</td>
                        <td style='padding:8px 12px;border-bottom:0.5px solid #e5e7eb;text-align:right;color:#27500A;font-weight:500'>RM " . number_format($t->total, 2) . "</td>
                    </tr>";
            }

            if (empty($typeRows)) {
                $typeRows = "<tr><td colspan='3' style='padding:12px;text-align:center;color:#9ca3af'>No billing entries this week</td></tr>";
            }

            // ── Build HTML email ─────────────────────────────
            $html = "
            <div style='font-family:Arial,sans-serif;max-width:640px;margin:0 auto'>

                <div style='background:#1363C6;padding:24px 28px;border-radius:8px 8px 0 0'>
                    <h2 style='color:#fff;margin:0;font-size:20px;font-weight:500'>Weekly Financial Report</h2>
                    <p style='color:rgba(255,255,255,0.8);margin:6px 0 0;font-size:14px'>{$hospital->hospital_name}</p>
                    <p style='color:rgba(255,255,255,0.6);margin:3px 0 0;font-size:12px'>{$weekLabel}</p>
                </div>

                <div style='background:#fff;padding:24px 28px;border:1px solid #e5e7eb;border-top:none'>

                    <div style='display:flex;gap:12px;flex-wrap:wrap;margin-bottom:20px'>
                        <div style='flex:1;min-width:100px;background:#f3f4f6;border-radius:8px;padding:14px;text-align:center'>
                            <div style='font-size:11px;color:#6b7280;text-transform:uppercase;letter-spacing:.05em'>Bookings</div>
                            <div style='font-size:24px;font-weight:500;color:#111827;margin-top:4px'>{$totalBookings}</div>
                        </div>
                        <div style='flex:1;min-width:100px;background:#EAF3DE;border-radius:8px;padding:14px;text-align:center'>
                            <div style='font-size:11px;color:#3B6D11;text-transform:uppercase;letter-spacing:.05em'>Income</div>
                            <div style='font-size:18px;font-weight:500;color:#27500A;margin-top:4px'>RM " . number_format($totalIncome, 2) . "</div>
                        </div>
                        <div style='flex:1;min-width:100px;background:#FCEBEB;border-radius:8px;padding:14px;text-align:center'>
                            <div style='font-size:11px;color:#A32D2D;text-transform:uppercase;letter-spacing:.05em'>Expenses</div>
                            <div style='font-size:18px;font-weight:500;color:#791F1F;margin-top:4px'>RM " . number_format($totalExpense, 2) . "</div>
                        </div>
                        <div style='flex:1;min-width:100px;background:{$netBg};border-radius:8px;padding:14px;text-align:center'>
                            <div style='font-size:11px;color:{$netColor};text-transform:uppercase;letter-spacing:.05em'>Net</div>
                            <div style='font-size:18px;font-weight:500;color:{$netColor};margin-top:4px'>RM " . number_format($net, 2) . "</div>
                        </div>
                        <div style='flex:1;min-width:100px;background:#FAEEDA;border-radius:8px;padding:14px;text-align:center'>
                            <div style='font-size:11px;color:#854F0B;text-transform:uppercase;letter-spacing:.05em'>No-show</div>
                            <div style='font-size:24px;font-weight:500;color:#633806;margin-top:4px'>{$noShowRate}%</div>
                        </div>
                    </div>

                    <table style='width:100%;border-collapse:collapse;font-size:13px;margin-bottom:20px'>
                        <tr style='background:#f9fafb'><td style='padding:8px 12px;border-bottom:0.5px solid #e5e7eb;color:#6b7280'>Completed</td><td style='padding:8px 12px;border-bottom:0.5px solid #e5e7eb;text-align:right;font-weight:500;color:#374151'>{$completed}</td></tr>
                        <tr><td style='padding:8px 12px;border-bottom:0.5px solid #e5e7eb;color:#6b7280'>No Show</td><td style='padding:8px 12px;border-bottom:0.5px solid #e5e7eb;text-align:right;font-weight:500;color:#A32D2D'>{$noShow}</td></tr>
                        <tr style='background:#f9fafb'><td style='padding:8px 12px;color:#6b7280'>Pending</td><td style='padding:8px 12px;text-align:right;font-weight:500;color:#374151'>{$pending}</td></tr>
                    </table>

                    <p style='font-size:13px;font-weight:500;color:#374151;margin:0 0 10px'>Revenue by type</p>
                    <table style='width:100%;border-collapse:collapse;font-size:13px'>
                        <thead>
                            <tr style='background:#1363C6;color:#fff'>
                                <th style='padding:8px 12px;text-align:left;font-weight:500'>Type</th>
                                <th style='padding:8px 12px;text-align:center;font-weight:500'>Count</th>
                                <th style='padding:8px 12px;text-align:right;font-weight:500'>Amount</th>
                            </tr>
                        </thead>
                        <tbody>{$typeRows}</tbody>
                    </table>
                </div>

                <div style='background:#f9fafb;padding:14px 28px;border:1px solid #e5e7eb;border-top:none;border-radius:0 0 8px 8px'>
                    <p style='color:#9ca3af;font-size:12px;margin:0'>Auto-generated every Monday by Speedbots Medical Booking System · {$weekLabel}</p>
                </div>
            </div>";

            // ── Send to this hospital's configured email only ─
            $recipients = array_filter(array_map('trim', explode(',', $hospital->report_email)));

            try {
                Mail::html($html, function ($mail) use ($recipients, $hospital, $weekLabel) {
                    $mail->to($recipients)
                         ->subject("Weekly Financial Report — {$hospital->hospital_name} | {$weekLabel}");
                });
                $this->info("  [{$hospital->hospital_name}] Sent to: " . implode(', ', $recipients));
            } catch (\Exception $e) {
                Log::error('Weekly financial report failed', [
                    'hospital' => $hospital->hospital_name,
                    'error'    => $e->getMessage(),
                ]);
                $this->error("  [{$hospital->hospital_name}] Failed: {$e->getMessage()}");
            }
        }

        $this->info("Weekly financial report complete.");
    }
}