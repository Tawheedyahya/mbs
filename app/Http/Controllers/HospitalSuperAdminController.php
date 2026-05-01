<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\HospitalFinancial;
use App\Models\PatientBillingEntry;
use App\Models\User;

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
}