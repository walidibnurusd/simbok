<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Gender;
use App\Models\Region;
use App\Models\MarritalStatus;
use App\Models\Education;
use App\Models\Profession;
use App\Models\EmployeeStatus;
use App\Models\Position;
use App\Models\Rank;
use App\Models\Group;
use App\Models\Religion;
use App\Models\UserDetail;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        $users = User::with('detail', 'detail.genders', 'detail.educations', 'detail.professions', 'detail.religions', 'detail.marritalStatuss', 'detail.employeeStatuss', 'detail.positions', 'detail.ranks', 'detail.groups')->where('role', 'user')->get();
        $genders = Gender::all();
        $religions = Religion::all();
        $maritalStatuses = MarritalStatus::all();
        $educations = Education::all();
        $professions = Profession::all();
        $employeeStatuses = EmployeeStatus::all();
        $positions = Position::all();
        $ranks = Rank::all();
        $groups = Group::all();
        return view('content.employee.index', compact('users', 'genders', 'religions', 'maritalStatuses', 'educations', 'professions', 'employeeStatuses', 'positions', 'ranks', 'groups'));
    }
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'nip' => 'string|max:18|unique:user_details',
                'employee_name' => 'nullable',
                'phone_wa' => 'nullable',
                'gender' => 'nullable',
                'religion' => 'nullable',
                'marrital_status' => 'nullable',
                'place_of_birth' => 'nullable',
                'date_of_birth' => 'nullable',
                'current_address' => 'nullable',
                'education' => 'nullable',
                'profession' => 'nullable',
                'employee_status' => 'nullable',
                'position' => 'nullable',
                'rank' => 'nullable',
                'tmt_pangkat' => 'nullable',
                'group' => 'nullable',
                'tmt_golongan' => 'nullable',
                'photo' => 'nullable',
                'password' => 'nullable',
            ]);

            // Create the user record
            $count = User::where('role', 'user')->count();
            $user = new User();
            $emailName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', explode(' ', $validatedData['employee_name'])[0])); // Ambil nama pertama saja
            $user->email = $emailName . '@gmail.com';
            $user->name = $validatedData['employee_name'];
            $user->role = 'user';
            $user->nip = $validatedData['nip'];
            $user->address = '';
            $user->no_hp = '';
            $user->password = bcrypt($validatedData['password']);
            $user->save();

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');

                // Check if the file was uploaded successfully
                if ($file->isValid()) {
                    // Generate a short unique file name
                    $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

                    // Attempt to store the file in public/storage/photos
                    $photoPath = $file->move(public_path('storage/photos'), $fileName);

                    // Log the path to check if it's being stored
                    Log::info('Photo uploaded to: ' . $photoPath);
                } else {
                    Log::error('Uploaded file is not valid.');
                }
            }

            // Create the user detail record with explicit column assignment
            $userDetail = new UserDetail();
            $userDetail->user_id = $user->id;
            $userDetail->nip = $validatedData['nip'];
            $userDetail->employee_name = $validatedData['employee_name'];
            $userDetail->phone_wa = $validatedData['phone_wa'];
            $userDetail->gender = $validatedData['gender'];
            $userDetail->religion = $validatedData['religion'];
            $userDetail->marrital_status = $validatedData['marrital_status'];
            $userDetail->place_of_birth = $validatedData['place_of_birth'];
            $userDetail->date_of_birth = $validatedData['date_of_birth'];
            $userDetail->current_address = $validatedData['current_address'];
            $userDetail->education = $validatedData['education'];
            $userDetail->profession = $validatedData['profession'];
            $userDetail->employee_status = $validatedData['employee_status'];
            $userDetail->position = $validatedData['position'];
            $userDetail->rank = $validatedData['rank'];
            $userDetail->tmt_pangkat = $validatedData['tmt_pangkat'];
            $userDetail->group = $validatedData['group'];
            $userDetail->tmt_golongan = $validatedData['tmt_golongan'];
            $userDetail->photo = $photoPath ? 'photos/' . $fileName : null;

            $userDetail->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Data pegawai berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($e->validator->errors()->has('nip')) {
                // Handle the NIP already exists case
                return redirect()
                    ->back()
                    ->withErrors(['nip' => 'NIP sudah terdaftar, silakan gunakan NIP yang berbeda.'])
                    ->withInput();
            }

            // If the validation error is not related to NIP, rethrow it
            throw $e;
        } catch (Exception $e) {
            // Log the error
            Log::error('Error adding user detail: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->withErrors('Terjadi kesalahan saat menambahkan data pegawai. Silakan coba lagi.');
        }
    }

    public function destroy($id)
    {
        try {
            // Find the user and their details
            $user = User::findOrFail($id);
            $userDetail = $user->detail;

            // Delete the user's photo if it exists
            if ($userDetail && $userDetail->photo) {
                Storage::disk('public')->delete($userDetail->photo);
            }
            $user->activityEmployees()->delete();
            // Delete the user detail
            if ($userDetail) {
                $userDetail->delete();
            }

            // Delete the user
            $user->delete();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Data pegawai berhasil dihapus.');
        } catch (Exception $e) {
            // Log the error
            Log::error('Error deleting user: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->withErrors('Terjadi kesalahan saat menghapus data pegawai. Silakan coba lagi.');
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            // Find the corresponding user detail
            $userDetail = $user->detail;

            // Validate the request data
            $validatedData = $request->validate([
                'nip' => 'string|max:18|unique:user_details,nip,' . $userDetail->id,
                'employee_name' => 'nullable',
                'phone_wa' => 'nullable',
                'gender' => 'nullable',
                'religion' => 'nullable',
                'marrital_status' => 'nullable',
                'place_of_birth' => 'nullable',
                'date_of_birth' => 'nullable',
                'current_address' => 'nullable',
                'education' => 'nullable',
                'profession' => 'nullable',
                'employee_status' => 'nullable',
                'position' => 'nullable',
                'rank' => 'nullable',
                'tmt_pangkat' => 'nullable',
                'group' => 'nullable',
                'tmt_golongan' => 'nullable',
                'photo' => 'nullable',
                'password' => 'nullable',
            ]);

            // Find the user and user detail records
            $user = User::findOrFail($id);

            $userDetail = $user->detail;

            // Update User fields
            $user->name = $validatedData['employee_name'];
            $user->nip = $validatedData['nip'];
            if ($request->filled('password')) {
                $user->password = bcrypt($validatedData['password']);
            }
            $user->save();

            // Handle photo upload
            $photoPath = $userDetail->photo; // Default to existing photo

            if ($request->hasFile('photo')) {
                // Delete the old photo if it exists
                if ($userDetail->photo && Storage::exists($userDetail->photo)) {
                    Storage::delete($userDetail->photo);
                }

                // Store the new photo in the 'photos' directory in storage/app
                $file = $request->file('photo');
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

                // Attempt to store the file in public/storage/photos
                $photoPath = $file->move(public_path('storage/photos'), $fileName);
                $userDetail->photo = 'photos/' . $fileName;
            }

            // Update UserDetail fields
            $userDetail->nip = $validatedData['nip'];
            $userDetail->employee_name = $validatedData['employee_name'];
            $userDetail->phone_wa = $validatedData['phone_wa'];
            $userDetail->gender = $validatedData['gender'];
            $userDetail->religion = $validatedData['religion'];
            $userDetail->marrital_status = $validatedData['marrital_status'];
            $userDetail->place_of_birth = $validatedData['place_of_birth'];
            $userDetail->date_of_birth = $validatedData['date_of_birth'];
            $userDetail->current_address = $validatedData['current_address'];
            $userDetail->education = $validatedData['education'];
            $userDetail->profession = $validatedData['profession'];
            $userDetail->employee_status = $validatedData['employee_status'];
            $userDetail->position = $validatedData['position'];
            $userDetail->rank = $validatedData['rank'];
            $userDetail->tmt_pangkat = $validatedData['tmt_pangkat'];
            $userDetail->group = $validatedData['group'];
            $userDetail->tmt_golongan = $validatedData['tmt_golongan'];

            $userDetail->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Data pegawai berhasil diperbarui.');
        } catch (Exception $e) {
            // Log the error
            Log::error('Error updating user detail: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->withErrors('Terjadi kesalahan saat memperbarui data pegawai. Silakan coba lagi.');
        }
    }
}
