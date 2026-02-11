<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Doctor;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Doctorcontroller extends Controller
{
    //
    public  function index()
    {
        return view('home');
    }
    public function schedule()
    {
        $doctorId = auth()->user()->doctor_id;

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        $hours = DB::table('schedules')
            ->where('doctor_id', $doctorId)
            ->get()
            ->keyBy('day');
        // pr($hours);
        return view('doctors.schedule', compact('days', 'hours'));
    }
    public function schedule_save(Request $request)
    {
        $doctorId = auth()->user()->doctor_id;

        foreach ($request->schedule as $day => $data) {

            DB::table('schedules')->updateOrInsert(
                [
                    'doctor_id' => $doctorId,
                    'day' => $day
                ],
                [
                    'start_time' => $data['start_time'] ?? null,
                    'end_time' => $data['end_time'] ?? null,
                    'is_off' => (empty($data['start_time']) || empty($data['end_time']) || isset($data['is_off'])) ? 1 : 0,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        return back()->with('success', 'Schedule saved');
    }
    public function overall_bookings(Request $request)
    {
        $doctorId = auth()->user()->doctor_id;

        // Base query
        $query = Booking::where('doctor_id', $doctorId);

        // 🔍 DATE SEARCH (YYYY-MM-DD)
        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }
        // FILTER LOGIC
        switch ($request->filter) {

            case 'today':
                $query->whereDate('booking_date', today());
                break;

            case 'pending':
                $query->where('status', 'pending');
                break;

            case 'accepted':
                $query->where('status', 'accepted')
                    ->latest()
                    ->limit(20);
                break;

            case 'rejected':
                $query->where('status', 'rejected')
                    ->latest()
                    ->limit(20);
                break;

            default:
                // Default = pending + today (SAFE)
                $query->where(function ($q) {
                    $q->where('status', 'pending')
                        ->orWhereDate('booking_date', today());
                });
                break;
        }

        $booking_list = $query->latest()->get();
        // pr($booking_list->toArray());
        return view('doctors.overall_bookings', compact('booking_list'));
    }
    public function update_status(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json([
                'msg' => 'Booking not found',
                'success' => false
            ], 404);
        }

        if ($booking->doctor_id != auth()->user()->doctor_id) {
            return response()->json([
                'msg' => 'Authorization error',
                'success' => false
            ], 403);
        }

        $booking->status = $request->status;
        $booking->save();

        try {
            $hospital = \App\Models\Hospital::find($booking->hospital_id);

            if ($hospital && $hospital->token && $hospital->flow_id) {

                // Use phone number directly as contact_id
                $contactId = $booking->patient_phone;

                \Log::channel('doctor')->info('Speedbots sending flow', [
                    'contact_id' => $contactId,
                    'flow_id' => $hospital->flow_id,
                ]);

                $flowResponse = Http::withHeaders([
                    'X-ACCESS-TOKEN' => $hospital->token,
                ])->post("https://app.speedbots.io/api/contacts/{$contactId}/send/{$hospital->flow_id}");

                \Log::channel('doctor')->info('Speedbots flow response', [
                    'status' => $flowResponse->status(),
                    'body' => $flowResponse->json(),
                ]);
            }
        } catch (\Exception $e) {
            \Log::channel('doctor')->error('Speedbots API error', [
                'message' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'msg' => 'Booking status updated successfully.',
            'success' => true,
        ]);
    }
    public function add_slot(Request $request)
    {
        // return 'hi';
        if (!$request->input('slot')) {
            return back()->with('error', 'Field is required');
        }
        $round_off = ceil($request->input('slot'));
        $update = Doctor::where('id', auth()->user()->doctor_id) // Find the doctor by id
            ->update([
                'slot' => $round_off // Update the slot field
            ]);

        if ($update) {
            return back()->with('success', 'Slots duration set');
        } else {
            return back()->with('error', 'Failed to set slots duration');
        }
    }
}
