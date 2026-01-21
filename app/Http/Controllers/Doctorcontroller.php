<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                    'is_off' => isset($data['is_off']),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        return back()->with('success', 'Schedule saved');
    }
}
