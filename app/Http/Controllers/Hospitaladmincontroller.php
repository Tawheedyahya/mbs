<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class Hospitaladmincontroller extends Controller
{
    //
    public function index()
    {
        return view('home');
    }
    public function add_doctor_view()
    {
        return view('hospital_admin.hospital_admin_dashboard');
    }
    public function specialization_view()
    {
        $specialization = Specialization::where('hospital_id', auth()->user()->hospital->id)->get();
        // pr($specialization->toArray());
        return view('hospital_admin.specialization', compact('specialization'));
    }
    public function specialization_add(Request $request)
    {
        // return 'hi';
        if (empty($request->specialization)) {
            return back()->with('error', 'Must enter specialization type');
        }
        $id = auth()->user()->hospital?->id;
        if (!$id) {
            return back()->with('error', 'Hospital not found');
        }
        Specialization::create([
            'hospital_id' => $id,
            'specialization' => $request->specialization,
            'description' => $request->description ?? null,
        ]);
        return back()->with('success', 'Specialization added');
    }
    public function specialization_delete($id)
    {
        $specialization = Specialization::findOrFail($id);

        
        // $specialization->delete();

        return response()->json([
            'message' => 'Specialization deleted successfully'
        ]);
    }
}
