<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class Hospitaladmincontroller extends Controller
{
    //
    public function index()
    {
        return view('home');
    }
    public function add_doctor_view()
    {

        $doctors = DB::table('doctors as d')->join('specializations as s', 's.id', '=', 'd.specialization_id')->where('d.hospital_id', auth()->user()->hospital_id)->select(
            'd.id as id',
            'd.name as name',
            'd.profile_photo',
            'd.doctor_code',
            'd.qualification',
            's.specialization',
            'd.phone'
        )->get();
        // pr($doctor);
        return view('hospital_admin.hospital_admin_dashboard', compact('doctors'));
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
    public function specialization_delete(Request $request)
    {
        if (!$request->id) {
            return response()->json([
                'success' => false,
                'message' => 'Missing specialization ID'
            ], 422);
        }

        $specialization = Specialization::find($request->id);

        if (!$specialization) {
            return response()->json([
                'success' => false,
                'message' => 'Specialization not found'
            ], 404);
        }
        $check = $this->updatedelete_check($request->id);
        if (!$check) {
            return response()->json([
                'success' => false,
                'msg' => 'Authorization error'
            ], 403);
        }

        if ($specialization->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Specialization deleted'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to delete specialization'
        ], 500);
    }
    public function specialization_edit(Request $request)
    {
        // pr($request->description);
        if (empty($request->id)) {
            return back()->with('error', 'Specialization ID is required');
        } elseif (empty($request->specialization)) {
            return back()->with('error', 'Specialization name is required');
        } elseif (empty($request->description)) {
            return back()->with('error', 'Specialization description is required');
        } else {
            $specialization = $this->updatedelete_check($request->id);
            if (!$specialization) {
                abort(403);
            } else {
                $specialization->update([
                    'specialization' => $request->specialization,
                    'description' => $request->description,
                ]);
                return back()->with('success', 'Specialization updated');
            }
        }
    }
    public function updatedelete_check($id)
    {
        $specialization = Specialization::find($id);

        if (!$specialization) {
            return false;
        }

        if ($specialization->hospital_id !== auth()->user()->hospital_id) {
            return false;
        }

        return $specialization;
    }
    public function doctor_form()
    {
        $specialization = Specialization::where('hospital_id', auth()->user()->hospital_id)->get();
        return view('hospital_admin.doctor_form', [
            'route' => 'hospital_admin.doctor_add',
            'title' => 'Create doctor',
            'button' => 'Submit',
            'specialization' => $specialization
        ]);
    }

    public function doctor_add(Request $request)
    {
        $id = $request->id;

        $user_id = null;
        if ($id) {
            $user_id = User::where('doctor_id', $id)
                ->where('hospital_id', auth()->user()->hospital_id)
                ->value('id');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user_id),
            ],
            'phone' => 'required|string|max:20',
            'gender' => 'required',
            'experience_years' => 'required|numeric',
            'specialization' => 'required',
            'qualification' => 'required|string',
            'status' => 'nullable',
            'profile_photo' => 'nullable|image|max:2048',
        ];

        if ($id) {
            Log::channel('hospital_admin')->info('Doctor update flow', ['doctor_id' => $id]);
            if ($request->filled('password')) {
                $rules['password'] = 'min:6';
            }
        } else {
            Log::channel('hospital_admin')->info('Doctor create flow');
            $rules['password'] = 'required|min:6';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();

        try {

            $doctor = $id ? Doctor::findOrFail($id) : new Doctor();

            if ($request->hasFile('profile_photo')) {
                if ($id && $doctor->profile_photo) {
                    Storage::disk('s3')->delete($doctor->profile_photo);
                }

                $photo = $request->file('profile_photo');
                $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                Storage::disk('s3')->putFileAs('doctors', $photo, $filename);
                $doctor->profile_photo = 'doctors/' . $filename;
            }

            $doctor->fill([
                'name' => $validated['name'],
                'hospital_id' => auth()->user()->hospital_id,
                'gender' => $validated['gender'],
                'doctor_code' => $id ? $doctor->doctor_code : $this->doctorcode(),
                'experience_years' => $validated['experience_years'],
                'phone' => $validated['phone'],
                'specialization_id' => $validated['specialization'],
                'qualification' => $validated['qualification'],
            ]);

            $doctor->save();

            $user = User::where('doctor_id', $doctor->id)->first();
            if (!$user) {
                $user = new User();
                $user->doctor_id = $doctor->id;
                $user->api_code = $this->uniquecode('user');
            }

            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->role = 'doctor';
            $user->hospital_id = auth()->user()->hospital_id;
            $user->status = $request->status ?? false;

            if (!empty($validated['password'])) {
                $user->password = bcrypt($validated['password']);
            }

            $user->save();

            DB::commit();

            Log::channel('hospital_admin')->info(
                $id ? 'Doctor updated successfully' : 'Doctor created successfully',
                [
                    'doctor_id' => $doctor->id,
                    'user_id' => $user->id,
                ]
            );

            return back()->with(
                'success',
                $id ? 'Doctor updated successfully' : 'Doctor created successfully'
            );
        } catch (\Throwable $e) {

            DB::rollBack();

            Log::channel('hospital_admin')->error('Doctor save failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'doctor_id' => $id,
            ]);

            return back()->with(
                'error',
                'Something went wrong while saving doctor details. Please try again.'
            );
        }
    }

    public function doctor_delete(Request $request)
    {
        if (!$request->id) {
            return response()->json([
                'success' => false,
                'message' => 'Missing doctor ID'
            ], 422);
        }

        $doctor = Doctor::find($request->id);

        if (!$doctor) {
            return response()->json([
                'success' => false,
                'message' => 'Doctor not found'
            ], 404);
        }

        if (!$this->canModifyDoctor($doctor)) {
            return response()->json([
                'success' => false,
                'message' => 'Authorization error'
            ], 403);
        }
        @Storage::disk('s3')->delete($doctor->profile_photo);
        $doctor->delete();

        return response()->json([
            'success' => true,
            'message' => 'Doctor deleted successfully'
        ]);
    }
    public function edit_doctor_view($id)
    {
        $specialization = Specialization::where('hospital_id', auth()->user()->hospital_id)->get();
        $doctor = Doctor::findOrFail($id);
        $check = $this->canModifyDoctor($doctor);
        if (!$check) {
            abort(403);
        }
        $data = DB::table('doctors as d')->join('users as u', 'u.doctor_id', '=', 'd.id')->where('d.id', '=', $id)->select('d.id', 'u.email', 'd.name', 'd.phone', 'd.gender', 'd.experience_years', 'd.qualification', 'd.specialization_id', 'u.status', 'd.profile_photo')->get()->toArray();
        // pr($data);
        return view('hospital_admin.doctor_form', [
            'title' => 'Update doctor',
            'route' => 'hospital_admin.doctors_update',
            'button' => 'Update',
            'specialization' => $specialization,
            'data' => (array)$data[0]
        ]);
    }
    public function canModifyDoctor(Doctor $doctor): bool
    {
        return $doctor->hospital_id === auth()->user()->hospital_id;
    }
}
