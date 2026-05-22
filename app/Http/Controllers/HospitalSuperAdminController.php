<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\HospitalFinancial;
use App\Models\PatientBillingEntry;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Models\Patient;
class HospitalSuperAdminController extends Controller
{
    public function dashboard()
    {
        return view('home');
    }

    private function hospitalIds()
    {
        return auth()->user()
            ->clientGroup
            ->hospitals
            ->pluck('id');
    }

    public function analytics()
    {
        $hospitalIds = $this->hospitalIds();

        $hospitals = Hospital::whereIn('id', $hospitalIds)
            ->orderBy('hospital_name')
            ->get()
            ->map(function ($hospital) {
                $profit = HospitalFinancial::where('hospital_id', $hospital->id)
                    ->where('type', 'profit')
                    ->sum('amount');

                $expense = HospitalFinancial::where('hospital_id', $hospital->id)
                    ->where('type', 'expense')
                    ->sum('amount');

                $paidBilling = PatientBillingEntry::where('hospital_id', $hospital->id)
                    ->where('is_paid', 1)
                    ->sum('amount');

                return [
                    'hospital' => $hospital,
                    'doctors' => Doctor::where('hospital_id', $hospital->id)->count(),
                    'bookings' => Booking::where('hospital_id', $hospital->id)->count(),
                    'pending' => Booking::where('hospital_id', $hospital->id)->where('status', 'pending')->count(),
                    'accepted' => Booking::where('hospital_id', $hospital->id)->where('status', 'accepted')->count(),
                    'completed' => Booking::where('hospital_id', $hospital->id)->where('status', 'completed')->count(),
                    'rescheduled' => Booking::where('hospital_id', $hospital->id)->where('status', 'rescheduled')->count(),
                    'rejected' => Booking::where('hospital_id', $hospital->id)->where('status', 'rejected')->count(),
                    'no_show' => Booking::where('hospital_id', $hospital->id)->where('status', 'no_show')->count(),
                    'income' => $paidBilling,
                    'expense' => $expense,
                    'net' => $paidBilling - $expense,
                ];
            });

        return view('hospital_super_admin.analytics', compact('hospitals'));
    }

    public function hospitals()
    {
        $hospitalIds = $this->hospitalIds();

        $hospitals = Hospital::whereIn('id', $hospitalIds)
            ->orderBy('hospital_name')
            ->get()
            ->map(function ($hospital) {
                $login = User::where('hospital_id', $hospital->id)
                    ->where('role', 'hospital_admin')
                    ->first();

                return [
                    'hospital' => $hospital,
                    'login' => $login,
                ];
            });

        return view('hospital_super_admin.hospitals', compact('hospitals'));
    }

    public function showHospital($id)
    {
        $hospitalIds = $this->hospitalIds();

        abort_unless($hospitalIds->contains($id), 403);

        $hospital = Hospital::findOrFail($id);

        $profit = HospitalFinancial::where('hospital_id', $hospital->id)
            ->where('type', 'profit')
            ->sum('amount');

        $expense = HospitalFinancial::where('hospital_id', $hospital->id)
            ->where('type', 'expense')
            ->sum('amount');

        $paidBilling = PatientBillingEntry::where('hospital_id', $hospital->id)
            ->where('is_paid', 1)
            ->sum('amount');

        $analytics = [
            'doctors' => Doctor::where('hospital_id', $hospital->id)->count(),
            'bookings' => Booking::where('hospital_id', $hospital->id)->count(),
            'pending' => Booking::where('hospital_id', $hospital->id)->where('status', 'pending')->count(),
            'accepted' => Booking::where('hospital_id', $hospital->id)->where('status', 'accepted')->count(),
            'completed' => Booking::where('hospital_id', $hospital->id)->where('status', 'completed')->count(),
            'rescheduled' => Booking::where('hospital_id', $hospital->id)->where('status', 'rescheduled')->count(),
            'rejected' => Booking::where('hospital_id', $hospital->id)->where('status', 'rejected')->count(),
            'no_show' => Booking::where('hospital_id', $hospital->id)->where('status', 'no_show')->count(),
            'income' => $paidBilling,
            'expense' => $expense,
            'net' => $paidBilling - $expense,
        ];

        return view('hospital_super_admin.hospital_analytics', compact('hospital', 'analytics'));
    }

    public function bookings()
    {
        $hospitalIds = $this->hospitalIds();

        $hospitals = Hospital::whereIn('id', $hospitalIds)
            ->orderBy('hospital_name')
            ->get()
            ->map(function ($hospital) {

                return [
                    'hospital' => $hospital,
                    'bookings_count' => Booking::where('hospital_id', $hospital->id)->count(),
                    'pending_count' => Booking::where('hospital_id', $hospital->id)
                        ->where('status', 'pending')
                        ->count(),
                ];
            });

        return view(
            'hospital_super_admin.bookings',
            compact('hospitals')
        );
    }

    public function hospitalBookings(Request $request, $hospitalId)
    {
        $hospitalIds = $this->hospitalIds();

        if (!$hospitalIds->contains($hospitalId)) {
            abort(403);
        }

        $hospital = Hospital::findOrFail($hospitalId);

        $query = Booking::where('hospital_id', $hospitalId);

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        // Status filter
        switch ($request->filter) {

            case 'today':
                $query->whereDate('booking_date', today());
                break;

            case 'unverified':
                $query->where('status', 'unverified');
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

            case 'cancelled':
                $query->where('status', 'cancelled')
                    ->latest()
                    ->limit(20);
                break;

            case 'no_show':
                $query->where('status', 'no_show')
                    ->latest()
                    ->limit(20);
                break;

            case 'rescheduled':
                $query->where('status', 'rescheduled')
                    ->latest()
                    ->limit(20);
                break;

            case 'completed':
                $query->where('status', 'completed')
                    ->latest()
                    ->limit(20);
                break;

            default:
                // Default = Pending + Today's bookings
                $query->where(function ($q) {
                    $q->where('status', 'pending')
                    ->orWhereDate('booking_date', today());
                });
                break;
        }

        $booking_list = $query->latest()->get();

        $doctors = Doctor::where('hospital_id', $hospitalId)->get();

        $stats = [
            'total' => Booking::where('hospital_id', $hospitalId)->count(),

            'pending' => Booking::where('hospital_id', $hospitalId)
                ->where('status', 'pending')
                ->count(),

            'accepted' => Booking::where('hospital_id', $hospitalId)
                ->where('status', 'accepted')
                ->count(),

            'completed' => Booking::where('hospital_id', $hospitalId)
                ->where('status', 'completed')
                ->count(),

            'rescheduled' => Booking::where('hospital_id', $hospitalId)
                ->where('status', 'rescheduled')
                ->count(),

            'rejected_no_show' => Booking::where('hospital_id', $hospitalId)
                ->whereIn('status', ['rejected', 'no_show'])
                ->count(),
        ];

        return view(
            'hospital_super_admin.hospital_bookings',
            compact('hospital', 'booking_list', 'doctors', 'stats')
        );
    }

    public function updateBookingStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'string',
                Rule::in([
                    'unverified',
                    'pending',
                    'accepted',
                    'rejected',
                    'cancelled',
                    'no_show',
                    'completed',
                    'rescheduled'
                ])
            ]
        ]);

        try {

            $booking = Booking::findOrFail($id);

            $oldStatus = $booking->status;

            $booking->status = $validated['status'];

            if ($validated['status'] === 'completed') {

                $booking->completed_at = now();

                // Optional: same billing logic as hospital admin
                $patient = Patient::where(
                    'phone_no',
                    $booking->patient_phone
                )->first();

                $doctor = Doctor::find($booking->doctor_id);

                if ($patient && $doctor && $doctor->consultation_fee > 0) {

                    PatientBillingEntry::create([
                        'patient_id'   => $patient->id,
                        'hospital_id'  => $booking->hospital_id,
                        'booking_id'   => $booking->id,
                        'type'         => 'consultation',
                        'description'  => 'Consultation with Dr. ' . $doctor->name,
                        'amount'       => $doctor->consultation_fee,
                        'is_past_note' => false,
                        'is_paid'      => false,
                    ]);
                }
            }

            $booking->save();

            return response()->json([
                'success' => true,
                'msg' => 'Booking status updated successfully'
            ]);

        } catch (\Exception $e) {

            \Log::error('Hospital super admin booking update failed', [
                'booking_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'msg' => 'Failed to update status'
            ], 500);
        }
    }

    public function reschedule(Request $request, $id)
    {
        $request->validate([
            'new_date' => 'required|date',
            'new_time' => 'required',
        ]);

        try {

            $booking = Booking::findOrFail($id);

            // Optional security check
            if (!$this->hospitalIds()->contains($booking->hospital_id)) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Unauthorized'
                ], 403);
            }

            $booking->booking_date = $request->new_date;
            $booking->start_time = $request->new_time;
            $booking->status = 'rescheduled';

            $booking->save();

            return response()->json([
                'success' => true,
                'msg' => 'Booking rescheduled successfully'
            ]);

        } catch (\Exception $e) {

            \Log::error('Hospital super admin reschedule failed', [
                'booking_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'msg' => 'Failed to reschedule booking'
            ], 500);
        }
    }

    public function assignDoctor(Request $request, $id)
    {
        try {

            $request->validate([
                'doctor_id' => 'required|exists:doctors,id'
            ]);

            $booking = Booking::findOrFail($id);

            // security check (VERY IMPORTANT)
            if (!$this->hospitalIds()->contains($booking->hospital_id)) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Unauthorized access'
                ], 403);
            }

            $booking->doctor_id = $request->doctor_id;
            $booking->save();

            return response()->json([
                'success' => true,
                'msg' => 'Doctor assigned successfully'
            ]);

        } catch (\Exception $e) {

            \Log::error('Super admin assign failed', [
                'booking_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'msg' => 'Failed to assign doctor'
            ], 500);
        }
    }
}