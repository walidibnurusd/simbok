<?php

namespace App\Http\Controllers;

use App\Models\Patients;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PatientsController extends Controller
{
   

public function syncPatients()
{
    try {
        $client = new Client();
        $response = $client->get('https://pkm-tamangapa.com.simpusdignityspace.cloud/api/patients', [
            'headers' => [
                'API-KEY' => 'eeNzQPk2nZ/gvOCbkGZ6FDPAOMcDJlxY',
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        if (isset($data['patients']) && is_array($data['patients'])) {
            foreach ($data['patients'] as $patientData) {
                // Cek apakah nilai marrital_status valid
                $isValidMaritalStatus = DB::table('marrital_statuses')
                    ->where('id', $patientData['marrital_status'])
                    ->exists();

                // Jika tidak ada, set NULL atau nilai default (misalnya 1 untuk 'Single')
                $maritalStatus = $isValidMaritalStatus ? $patientData['marrital_status'] : null;

                Patients::updateOrCreate(
                    ['nik' => $patientData['nik']], 
                    [
                        'name' => $patientData['name'],
                        'dob' => $patientData['dob'],
                        'place_birth' => $patientData['place_birth'],
                        'gender' => $patientData['gender'],
                        'phone' => $patientData['phone'],
                        'marrital_status' => $maritalStatus,
                        'no_rm' => $patientData['no_rm'],
                        'blood_type' => $patientData['blood_type'],
                        'occupation' => $patientData['occupation'],
                        'education' => $patientData['education'],
                        'address' => $patientData['address'],
                        'rw' => $patientData['rw'],
                    ]
                );
            }
            return response()->json(['message' => 'Data patients synchronized successfully!'], 200);
        }

        return response()->json(['message' => 'No data found!'], 404);
    } catch (\Exception $e) {
        Log::error('Error syncing patients: ' . $e->getMessage());
        return response()->json(['message' => 'Error fetching data!'], 500);
    }
}


public function getPatients(Request $request)
{
    if ($request->ajax()) {
        $patients = Patients::with(['genderName', 'marritalStatus'])->select('patients.*');

        return DataTables::of($patients)
            ->addIndexColumn()
            ->addColumn('gender', function ($row) {
                return $row->genderName->name ?? '-';
            })
            ->addColumn('marital_status', function ($row) {
                return $row->marritalStatus->name ?? '-';
            })
            ->addColumn('actions', function ($row) {
                return '
                    <div class="d-flex flex-column">
                        <button type="button" class="btn btn-primary btn-sm mb-1"
                            data-bs-toggle="modal" data-bs-target="#editPatientModal' . $row->id . '">
                            <i class="fas fa-edit"></i> 
                        </button>
                        <button type="button" class="btn btn-danger btn-sm btn-delete"
                            data-form-action="' . route('patient.destroy', $row->id) . '">
                            <i class="fas fa-trash-alt"></i> 
                        </button>
                    </div>
                    ' . view('component.modal-edit-patient', compact('row'))->render() . '
                ';
            })
            
            ->rawColumns(['actions'])
            ->make(true);
    }
}

    
    public function index()
    {
        $patients = Patients::all();
        return view('content.patients.index', compact('patients'));
    }
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'nik' => 'string|max:16',
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'gender' => 'required|integer',
                'place_birth' => 'required|string|max:255',
                'dob' => 'required|date',
                'no_rm' => 'required|string|max:255',
                'marriage_status' => 'required|integer',
                'blood_type' => 'required|string',
                'education' => 'required|integer',
                'occupation' => 'required|integer',
                'province' => 'required|integer',
                'city' => 'required|integer',
                'district' => 'required|integer',
                'village' => 'required|integer',
                'rw' => 'required|integer',
                'address' => 'required|string|max:255',
            ]);
    
            // Create a new patient record
            $patient = new Patients();
            $patient->nik = $validatedData['nik'];
            $patient->name = $validatedData['name'];
            $patient->phone = $validatedData['phone'];
            $patient->gender = $validatedData['gender'];
            $patient->place_birth = $validatedData['place_birth'];
            $patient->dob = $validatedData['dob'];
            $patient->no_rm = $validatedData['no_rm'];
            $patient->marrital_status = $validatedData['marriage_status'];
            $patient->blood_type = $validatedData['blood_type'];
            $patient->education = $validatedData['education'];
            $patient->occupation = $validatedData['occupation'];
            $patient->indonesia_province_id = $validatedData['province'];
            $patient->indonesia_city_id = $validatedData['city'];
            $patient->indonesia_district_id = $validatedData['district'];
            $patient->indonesia_village_id = $validatedData['village'];
            $patient->rw = $validatedData['rw'];
            $patient->address = $validatedData['address'];
    // dd($patient);
            $patient->save();
    
            // Redirect back with a success message
            return redirect()->back()->with('success', 'Patient data added successfully.');
    
        } catch (Exception $e) {
            // Log the error
            Log::error('Error adding patient: ' . $e->getMessage());
    
            // Redirect back with an error message
            return redirect()->back()->withErrors('An error occurred while adding the patient. Please try again.');
        }
    }
    

    public function update(Request $request, $id)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'nik' => 'string|max:16',
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'gender' => 'required|integer',
                'place_birth' => 'required|string|max:255',
                'dob' => 'required|date',
                'no_rm' => 'required|string|max:255',
                'marriage_status' => 'required|integer',
                'blood_type' => 'required|string',
                'education' => 'required|integer',
                'occupation' => 'required|integer',
                'province' => 'required|integer',
                'city' => 'required|integer',
                'district' => 'required|integer',
                'village' => 'required|integer',
                'rw' => 'required|integer',
                'address' => 'required|string|max:255',
            ]);
    
            // Find the patient record by ID
            $patient = Patients::findOrFail($id);
            // dd($patient);
            // Update the patient record with validated data
            $patient->nik = $validatedData['nik'];
            $patient->name = $validatedData['name'];
            $patient->phone = $validatedData['phone'];
            $patient->gender = $validatedData['gender'];
            $patient->place_birth = $validatedData['place_birth'];
            $patient->dob = $validatedData['dob'];
            $patient->no_rm = $validatedData['no_rm'];
            $patient->marrital_status = $validatedData['marriage_status'];
            $patient->blood_type = $validatedData['blood_type'];
            $patient->education = $validatedData['education'];
            $patient->occupation = $validatedData['occupation'];
            $patient->indonesia_province_id = $validatedData['province'];
            $patient->indonesia_city_id = $validatedData['city'];
            $patient->indonesia_district_id = $validatedData['district'];
            $patient->indonesia_village_id = $validatedData['village'];
            $patient->rw = $validatedData['rw'];
            $patient->address = $validatedData['address'];
    
            // Save the updated patient record
            $patient->save();
    
            // Redirect back with a success message
            return redirect()->back()->with('success', 'Patient data updated successfully.');
    
        } catch (Exception $e) {
            // Log the error
            Log::error('Error updating patient: ' . $e->getMessage());
    
            // Redirect back with an error message
            return redirect()->back()->withErrors('An error occurred while updating the patient. Please try again.');
        }
    }
    
// PatientController.php
public function destroy($id)
{
    // Find the patient by ID or fail
    $patient = Patients::findOrFail($id);

    // Delete the patient
    $patient->delete();

    // Redirect with a success message
    return redirect()->route('patient.index')->with('success', 'Patient deleted successfully.');
}


}
