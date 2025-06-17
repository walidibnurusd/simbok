<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityDetail;
use App\Models\ActivityEmployee;
use App\Models\ActivityPatient;
use App\Models\ActivityProof;
use App\Models\ActivityCheck;
use App\Models\AdviceActivityProof;
use App\Models\Program;
use App\Models\Patients;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;
use Mpdf\Mpdf;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $programs = Program::all();

        if ($request->ajax()) {
            $query = Activity::with('programs', 'services');

            if ($request->filled('program') && $request->program !== 'all') {
                $query->where('program', $request->program);
            }

            if ($request->filled('year')) {
                $query->where('year', $request->year);
            }

            if ($request->filled('month')) {
                $query->where('month', $request->month);
            }

            return DataTables::of($query)
                ->addIndexColumn() // Nomor urut
                ->addColumn('num_days', function ($activity) {
                    return '
                        <button class="btn btn-primary btn-sm add-detail-activity"
                            data-bs-toggle="modal"
                            data-bs-target="#addDetailActivityModal' .
                        $activity->id .
                        '"
                            data-id="' .
                        $activity->id .
                        '" data-name="' .
                        $activity->name .
                        '"
                            data-month="' .
                        $activity->month .
                        '" data-year="' .
                        $activity->year .
                        '"
                            data-program="' .
                        $activity->programs->id .
                        '" data-service="' .
                        $activity->services->id .
                        '">
                            ' .
                        $activity->num_days .
                        ' Hari
                        </button>
                        ' .
                        $this->modalAddDetail($activity) .
                        '';
                })
                ->addColumn('action', function ($activity) {
                    return '
                        <div  class="d-flex flex-column">
                            <button type="button" class="btn btn-primary btn-sm mb-1"
                                data-bs-toggle="modal" data-bs-target="#editActivityModal' .
                        $activity->id .
                        '">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button type="button" class="btn btn-danger btn-sm btn-delete"
                                data-form-action="' .
                        route('activity.destroy', $activity->id) .
                        '">
                                <i class="fas fa-trash-alt"></i>
                            </button>

                            ' .
                        $this->modalEditActivity($activity) .
                        ' <!-- Tambahkan Modal Edit -->
                        </div>';
                })
                ->rawColumns(['num_days', 'action'])
                ->make(true);
        }

        return view('content.activity.index', compact('programs'));
    }

    // Fungsi untuk modal edit
    private function modalEditActivity($activity)
    {
        return view('component.modal-edit-activity', compact('activity'))->render();
    }

    // Fungsi untuk modal tambah detail
    private function modalAddDetail($activity)
    {
        return view('component.modal-add-detail-activity', compact('activity'))->render();
    }

    public function indexEmployee(Request $request)
    {
        if ($request->ajax()) {
            $userId = Auth::id(); // ID user yang sedang login

            $query = ActivityDetail::with(['proofActivity', 'employeesActivity.employee', 'proofActivity.patients', 'activity'])->whereRaw("JSON_CONTAINS(employees, '\"$userId\"')");

            // Filter berdasarkan tahun jika dipilih
            if ($request->filled('year')) {
                $query->whereYear('date', $request->year);
            }

            // Filter berdasarkan bulan jika dipilih
            if ($request->filled('month')) {
                $query->whereMonth('date', $request->month);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return '<p class="text-xs font-weight-bold mb-0">' . $row->date . '</p>';
                })
                ->addColumn('activity_name', function ($row) {
                    return '<p class="text-xs font-weight-bold mb-0">' . ($row->activity->name ?? '-') . '</p>';
                })
                ->addColumn('location', function ($row) {
                    return '<p class="text-xs font-weight-bold mb-0">' . $row->location . '</p>';
                })
                ->addColumn('proof_image', function ($row) {
                    if (!empty($row->proofActivity) && isset($row->proofActivity[0]->image)) {
                        $imageUrl = asset('storage/' . $row->proofActivity[0]->image);
                        return '<img src="' . $imageUrl . '" class="img-thumbnail" width="100" height="100" style="object-fit: cover;">';
                    }
                    return '<p class="text-xs font-weight-bold mb-0">-</p>';
                })
                ->addColumn('proof_value', function ($row) {
                    return '<p class="text-xs font-weight-bold mb-0">' . ($row->proofActivity[0]->value ?? '-') . '</p>';
                })
                ->addColumn('proof_advice', function ($row) {
                    if (!empty($row->proofActivity) && isset($row->proofActivity[0]->id)) {
                        $advice = AdviceActivityProof::where('user_id', Auth::user()->id)
                            ->where('activity_proof_id', $row->proofActivity[0]->id)
                            ->first();
                    }

                    return '<p class="text-xs font-weight-bold mb-0">' . ($advice->advice ?? '-') . '</p>';
                })
                ->addColumn('patient_count', function ($row) {
                    if (!empty($row->proofActivity)) {
                        return implode(', ', array_column($row->proofActivity->toArray(), 'patient_count'));
                    }
                    return '<p class="text-xs font-weight-bold mb-0">-</p>';
                })
                ->addColumn('action', function ($activity) {
                    $modalId = 'proofActivityModal' . $activity->id;

                    return '
                            <div>
							<a href="' .
								route('activityEmployee.proof', $activity->id) .
								'" class="btn btn-primary btn-sm text-white font-weight-bold text-xs">
							Bukti</a><br />
                                <a href="' .
                        route('activityEmployee.print', $activity->id) .
                        '" class="btn btn-warning btn-sm text-white font-weight-bold text-xs proof-activity" target="_blank">Print</a><br />
                                <a href="' .
                        route('activityEmployee.print.st', $activity->id) .
                        '" class="btn btn-success btn-sm text-white font-weight-bold text-xs proof-activity" target="_blank">Surat Tugas</a><br />
                            </div>
                        ';
                        // view('component.modal-proof-activity', compact('activity'))->render();
                })

                ->rawColumns(['date', 'activity_name', 'location', 'proof_image', 'proof_value', 'proof_advice', 'patient_count', 'action'])
                ->make(true);
        }

        return view('content.activity.index-employee');
    }

    public function monitoring(Request $request)
    {
        // Inisialisasi query untuk ActivityDetail
        $query = ActivityDetail::query();

        // Jika ada request 'year', tambahkan kondisi filter berdasarkan tahun
        if ($request->filled('year-activity')) {
            $query->whereYear('date', $request->input('year-activity'));
        }

        // Jika ada request 'month', tambahkan kondisi filter berdasarkan bulan
        if ($request->filled('month-activity')) {
            $query->whereMonth('date', $request->input('month-activity'));
        }

        // Jika ada request 'date', tambahkan kondisi filter berdasarkan tanggal penuh (Y-m-d)
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        // Pastikan activity memiliki proofActivity
        $query->whereHas('proofActivity');

        // Ambil data yang sudah di-filter, termasuk relasinya
        $activities = $query->with('proofActivity', 'employeesActivity.employee', 'proofActivity.patients')->get();

        // Ambil semua data pasien
        $patients = Patients::with('genderName')->get();

        // Kembalikan view dengan data yang sudah difilter
        return view('content.activity.monitoring-activity', compact('activities', 'patients'));
    }

    // Controller
    public function getDates(Request $request)
    {
        $dates = [];

        if ($request->filled('year') && $request->filled('month')) {
            $year = $request->year;
            $month = $request->month;

            // Menambahkan log untuk memastikan year dan month terisi
            \Log::info('Querying dates for year: ' . $year . ' month: ' . $month);

            $dates = ActivityDetail::whereYear('date', $year)->whereMonth('date', $month)->selectRaw('DISTINCT date as date')->orderBy('date', 'asc')->get()->pluck('date');

            // Log hasil query
            \Log::info('Dates found: ' . json_encode($dates));
        }

        // Mengembalikan JSON
        return response()->json($dates);
    }

    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string',
                'program' => 'required|integer',
                'service' => 'required|integer',
                'month' => 'required|string',
                'year' => 'required|integer',
            ]);

            // Create a new activity record
            $activity = new Activity();
            $activity->program = $validatedData['program'];
            $activity->name = $validatedData['name'];
            $activity->service = $validatedData['service'];
            $activity->month = $validatedData['month'];
            $activity->year = $validatedData['year'];
            $activity->num_days = 0;
            $activity->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Tambah data kegiatan berhasil');
        } catch (Exception $e) {
            // Log the error
            Log::error('Error adding activity: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->withErrors('An error occurred while adding the activity. Please try again.');
        }
    }
    public function storeProof(Request $request)
    {
        try {
            // Validasi data yang dimasukkan
            $validatedData = $request->validate([
                'activity_id' => 'required|integer',
                'image' => 'image|mimes:png,jpg,jpeg|max:2048',
                'advice' => 'required|string',
                'value' => 'required|string',
            ]);

            // Inisialisasi variabel untuk jalur foto
            $photoPath = null;
            $fileName = null;
            $userId = Auth::user()->id;

            // Proses upload gambar jika ada file yang di-upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');

                // Cek apakah file valid
                if ($file->isValid()) {
                    // Generate nama file unik
                    $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

                    // Simpan file ke direktori 'public/storage/proof'
                    $photoPath = $file->move(public_path('storage/proof'), $fileName);

                    // Log path untuk keperluan debugging
                    Log::info('Photo uploaded to: ' . $photoPath);
                } else {
                    Log::error('Uploaded file is not valid.');
                }
            }

            // Cari apakah activity_id sudah ada di tabel
            $activity = ActivityProof::where('activity_id', $validatedData['activity_id'])->first();

            if ($activity) {
                // Jika activity_id sudah ada, update data yang ada
                $activity->advice = $validatedData['advice'];
                $activity->value = $validatedData['value'];

                // Update gambar jika ada gambar baru
                if ($fileName) {
                    $activity->image = 'proof/' . $fileName;
                }

                $activity->save();
                $adviceActivity = AdviceActivityProof::where('activity_proof_id', $activity->id)->where('user_id', $userId)->first();

                if ($adviceActivity) {
                    $adviceActivity->advice = $validatedData['advice'];
                    $adviceActivity->activity_proof_id = $activity->id;
                    $adviceActivity->save();
                } else {
                    $adviceActivity = new AdviceActivityProof();
                    $adviceActivity->advice = $validatedData['advice'];
                    $adviceActivity->activity_proof_id = $activity->id;
                    $adviceActivity->user_id = $userId;
                    $adviceActivity->save();
                }
                return redirect()->back()->with('success', 'Data kegiatan diperbarui dengan sukses.');
            } else {
                // Jika activity_id tidak ditemukan, buat record baru
                $activity = new ActivityProof();
                $activity->activity_id = $validatedData['activity_id'];
                $activity->advice = $validatedData['advice'];
                $activity->value = $validatedData['value'];

                // Tambahkan gambar jika ada
                if ($fileName) {
                    $activity->image = 'proof/' . $fileName;
                }

                $activity->save();
                $adviceActivity = AdviceActivityProof::where('activity_proof_id', $activity->id)->where('user_id', $userId)->first();
                if ($adviceActivity) {
                    $adviceActivity->advice = $validatedData['advice'];
                    $adviceActivity->activity_proof_id = $activity->id;
                    $adviceActivity->save();
                } else {
                    $adviceActivity = new AdviceActivityProof();
                    $adviceActivity->advice = $validatedData['advice'];
                    $adviceActivity->activity_proof_id = $activity->id;
                    $adviceActivity->user_id = $userId;
                    $adviceActivity->save();
                }

                return redirect()->back()->with('success', 'Tambah bukti data kegiatan berhasil.');
            }
        } catch (Exception $e) {
            // Log kesalahan jika terjadi
            Log::error('Error adding or updating activity: ' . $e->getMessage());

            // Redirect dengan pesan error
            return redirect()->back()->withErrors('Terjadi kesalahan saat menambah atau memperbarui data kegiatan. Silakan coba lagi.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string',
                'program' => 'required|integer',
                'service' => 'required|integer',
                'month' => 'required|string',
                'year' => 'required|integer',
            ]);

            // Find the activity record by ID
            $activity = Activity::findOrFail($id);
            $activity->program = $validatedData['program'];
            $activity->name = $validatedData['name'];
            $activity->service = $validatedData['service'];
            $activity->month = $validatedData['month'];
            $activity->year = $validatedData['year'];
            $activity->num_days = 0;
            $activity->save();
            // Redirect back with a success message
            return redirect()->back()->with('success', 'Ubah data kegiatan berhasil');
        } catch (Exception $e) {
            // Log the error
            Log::error('Error updating activity: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->withErrors('An error occurred while updating the activity. Please try again.');
        }
    }

    public function destroy($id)
    {
        // Find the activity by ID or fail
        $activity = Activity::findOrFail($id);

        // Hapus semua ActivityDetail yang terkait
        $activity->details()->each(function ($detail) {
            // Hapus semua ActivityEmployee yang terkait dengan ActivityDetail
            $detail->employeesActivity()->delete();

            // Hapus ActivityDetail itu sendiri
            $detail->delete();
        });

        // Hapus Activity itu sendiri
        $activity->delete();

        // Redirect with a success message
        return redirect()->route('activity.index')->with('success', 'Hapus kegiatan berhasil');
    }
    public function storeDetail(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'activityId' => 'required',
                'date' => 'required',
                'location' => 'required',
                'employee' => 'required|array', // Ensure it's an array of employee IDs
            ]);

            // Remove duplicates from the employee array to prevent redundancy
            $validatedData['employee'] = array_unique($validatedData['employee']);

            if ($request->activityDetailId) {
                $activity = ActivityDetail::find($request->activityDetailId);
                $activity->activity_id = $validatedData['activityId'];
                $activity->date = $validatedData['date'];
                $activity->location = $validatedData['location'];
                $activity->employees = json_encode($validatedData['employee']);
                $activity->save();

                foreach ($validatedData['employee'] as $employeeId) {
                    $activityEmployee = ActivityEmployee::where('activity_id', $activity->id)->first();
                    $activityEmployee->employee_id = $employeeId;
                    $activityEmployee->save();
                }
            } else {
                $activity = new ActivityDetail();
                $activity->activity_id = $validatedData['activityId'];
                $activity->date = $validatedData['date'];
                $activity->location = $validatedData['location'];
                $activity->employees = json_encode($validatedData['employee']);
                $activity->save();

                // Create activity employees
                foreach ($validatedData['employee'] as $employeeId) {
                    $activityEmployee = new ActivityEmployee();
                    $activityEmployee->activity_id = $activity->id;
                    $activityEmployee->employee_id = $employeeId;
                    $activityEmployee->save();
                }

                // Update the number of days for the activity
                $ac_num = Activity::with('details')->where('id', $activity->activity_id)->first();
                $ac_num->num_days = $ac_num->details->count();
                $ac_num->save();
            }

            // Return response with updated data
            return response()->json([
                'success' => true,
                'message' => 'Tambah data detail kegiatan berhasil',
                'activityDetails' => $activity->activity->details,
            ]);
        } catch (Exception $e) {
            Log::error('Error adding activity: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'error_message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding the activity. Please try again.',
            ]);
        }
    }

    public function printPDF(Request $request)
    {
        // Validasi data request
        $validatedData = $request->validate([
            'year' => 'required|integer|digits:4',
            'month' => 'required|string',
        ]);

        // Daftar bulan dalam bahasa Indonesia
        $months = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        // Ambil nama bulan dari input
        $monthName = $months[$validatedData['month']] ?? null;

        if (!$monthName) {
            return redirect()->back()->withErrors('Bulan yang dipilih tidak valid.');
        }

        // Ambil data aktivitas berdasarkan tahun dan bulan
        $data = Activity::with('details', 'details.employeesActivity.employee')->where('year', $validatedData['year'])->where('month', $monthName)->get();

        // Pastikan data ada
        if ($data->isEmpty()) {
            return redirect()->back()->withErrors('Tidak ada data untuk bulan dan tahun ini.');
        }

        // Path ke logo
        $logoLeft = public_path('assets/img/logo-mks.png');
        $logoRight = public_path('assets/img/logo-puskesmas.png');

        // Data yang akan dikirim ke view PDF
        $pdfData = [
            'logoLeft' => $logoLeft,
            'logoRight' => $logoRight,
            'data' => $data,
            'month' => $monthName,
            'year' => $validatedData['year'],
        ];

        // Render tampilan ke dalam HTML
        $html = view('pdf.activity', $pdfData)->render();

        // Inisialisasi MPDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'Arial',
        ]);

        // Tambahkan HTML ke dalam PDF
        $mpdf->WriteHTML($html);

        // Nama file PDF
        $fileName = 'activity-report-' . $validatedData['year'] . '-' . str_pad($validatedData['month'], 2, '0', STR_PAD_LEFT) . '.pdf';

        // Tampilkan PDF di browser
        return $mpdf->Output($fileName, 'I'); // 'I' = tampil di browser, 'D' = download langsung
    }
    public function deleteDetail($id)
    {
        // Temukan detail aktivitas berdasarkan ID
        $detail = ActivityDetail::find($id);
        $ac = Activity::where('id', $detail->activity_id)->first();
        $ac->num_days = $ac->num_days - 1; // Mengurangi num_days dan menetapkannya kembali
        $ac->save(); // Menyimpan perubahan ke database
        if (!$detail) {
            return response()->json(['message' => 'Detail not found'], 404);
        }

        // Temukan aktivitas yang terkait dengan detail
        // $activityId = $detail->activity_id;
        $activityEmployee = ActivityEmployee::where('activity_id', $id)->get();

        try {
            // Hapus referensi dari ActivityEmployee
            foreach ($activityEmployee as $employee) {
                $employee->delete();
            }

            // Hapus detail aktivitas
            $detail->delete();

            return response()->json(['message' => 'Detail deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete detail', 'error' => $e->getMessage()], 500);
        }
    }
    public function getDetail($id)
    {
        $detail = ActivityDetail::find($id);

        if (!$detail) {
            return response()->json(['message' => 'Detail not found'], 404);
        }

        return response()->json($detail);
    }

    public function storePatientProof(Request $request)
    {
        // Log the incoming request data
        Log::info('storePatientProof called', [
            'idPatient' => $request->input('idPatient'),
            'idProof' => $request->input('idProof'),
            'idProofEdit' => $request->input('idProofEdit'),
            'notes' => $request->input('notes'),
        ]);

        // Validate the incoming request data
        $request->validate([
            'idPatient' => 'required',
            'idProof' => 'required|exists:activity_proofs,id',
            'notes' => 'required|string|max:1000',
        ]);

        // Retrieve the patient by their ID
        try {
            $patient = Patients::findOrFail($request->input('idPatient'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Patient not found.',
                ],
                404,
            );
        }
        $proofId = $request->input('idProof');
        $proofIdEdit = $request->input('idProofEdit');

        // Log the patient and proof information
        Log::info('Patient and Proof Info', [
            'patient_id' => $patient->id,
            'proof_id' => $proofId,
            'proof_id_edit' => $proofIdEdit,
        ]);

        // Check if it's an edit scenario, where we have an existing ActivityPatient record to update
        if ($proofIdEdit) {
            // Find the existing ActivityPatient record by proofIdEdit
            $check = ActivityPatient::find($proofIdEdit);

            // Log the result of the query
            Log::info('ActivityPatient found', [
                'activity_patient_found' => $check ? true : false,
                'proof_id_edit' => $proofIdEdit,
                'activity_patient' => $check ? $check->toArray() : null, // Log the existing record
            ]);

            if ($check) {
                // Update the patient_id and description (notes) for the existing record
                $check->patient_id = $patient->id;
                $check->activity_proof_id = $proofId; // Ensure the proof ID is correct
                $check->description = $request->input('notes'); // Update notes
                $check->save(); // Save the updated record

                // Log after saving the updated record
                Log::info('ActivityPatient record updated', [
                    'id' => $check->id,
                    'patient_id' => $check->patient_id,
                    'activity_proof_id' => $check->activity_proof_id,
                    'description' => $check->description,
                ]);
            } else {
                // Log error if ActivityPatient is not found
                Log::error('ActivityPatient not found for editing', [
                    'proof_id_edit' => $proofIdEdit,
                ]);

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Activity record not found.',
                    ],
                    404,
                );
            }
        } else {
            // If it's not an edit scenario, create a new ActivityPatient record
            $activity = new ActivityPatient();
            $activity->patient_id = $patient->id;
            $activity->activity_proof_id = $proofId;
            $activity->description = $request->input('notes');

            // Save the new activity record
            $activity->save();

            // Log after saving the new record
            Log::info('New ActivityPatient record created', [
                'id' => $activity->id,
                'patient_id' => $activity->patient_id,
                'activity_proof_id' => $activity->activity_proof_id,
                'description' => $activity->description,
            ]);
        }

		return redirect()->back()->with(['success' => 'Patient activity data has been successfully saved']);
        // Return a success response
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Patient activity data has been successfully saved.',
        // ]);
    }

    public function storeCheckActivity(Request $request)
    {
        // Cek apakah sudah ada data dengan activity_id yang sama
        $activity = ActivityCheck::where('activity_id', $request->input('activity_id'))->first();

        if ($activity) {
            // Jika ada, update data yang ada
            $activity->photo = $request->input('photo');
            $activity->letter_assign = $request->input('letter_assign');
            $activity->document = $request->input('document');
        } else {
            // Jika tidak ada, buat entri baru
            $activity = new ActivityCheck();
            $activity->activity_id = $request->input('activity_id');
            $activity->photo = $request->input('photo');
            $activity->letter_assign = $request->input('letter_assign');
            $activity->document = $request->input('document');
        }

        // Save the activity
        $activity->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Data cek aktivitas berhasil disimpan.');
    }

    public function deletePatientProof(Request $request)
    {
        $proofId = $request->input('id');

        // Find the proof record by its ID
        $proof = ActivityPatient::find($proofId);

        if ($proof) {
            $proof->delete();
            return response()->json(['success' => true, 'message' => 'Proof deleted successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Proof not found'], 404);
        }
    }
    public function printPDFProof($id)
    {
        // Ambil data aktivitas berdasarkan ID
        $activities = ActivityDetail::with('proofActivity', 'employeesActivity.employee', 'proofActivity.patients')->where('id', $id)->first();

        if (!$activities) {
            return abort(404, 'Data aktivitas tidak ditemukan');
        }

        // Cek apakah ada bukti aktivitas dan gambar tersedia
        $bukti = null;
        if (!empty($activities->proofActivity) && isset($activities->proofActivity[0]->image)) {
            $buktiPath = public_path('storage/' . $activities->proofActivity[0]->image);
            if (file_exists($buktiPath)) {
                $bukti = $buktiPath;
            }
        }

        $data = [
            'logoLeft' => public_path('assets/img/logo-mks.png'), // Path ke logo kiri
            'logoRight' => public_path('assets/img/logo-puskesmas.png'), // Path ke logo kanan
            'data' => $activities, // Data aktivitas
            'bukti' => $bukti, // Path ke gambar bukti jika ada
        ];

        // Membuat PDF menggunakan view
        $pdf = pdf::loadView('pdf.activity-proof', $data);

        // Menghasilkan PDF dan menampilkan di browser
        return $pdf->stream('activity-proof.pdf');
    }

    public function printSuratTugas($id)
    {
        // Create options for Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $activities = ActivityDetail::with('proofActivity', 'employeesActivity.employee', 'proofActivity.patients')->where('id', $id)->first();
        // Generate HTML with embedded images as Data URI
        // dd($activities->proofActivity[0]->image);
        $bukti = null;

        if (isset($activities->proofActivity[0]->image) && file_exists(public_path('storage/' . $activities->proofActivity[0]->image))) {
            $bukti = base64_encode(file_get_contents(public_path('storage/' . $activities->proofActivity[0]->image)));
        }
        $logoLeft = base64_encode(file_get_contents(public_path('assets/img/logo-mks.png')));
        $logoRight = base64_encode(file_get_contents(public_path('assets/img/logo-puskesmas.png')));

        // Filter data based on the selected year and month
        $activities = ActivityDetail::with('proofActivity', 'employeesActivity.employee', 'proofActivity.patients')->where('id', $id)->first();
        // $data = Activity::with('details', 'details.employeesActivity.employee')
        //     ->where('year', $validatedData['year'])
        //     ->where('month', $monthNumber)
        //     ->get();

        $html = view('pdf.surat-tugas', [
            'logoLeft' => $logoLeft,
            'logoRight' => $logoRight,
            'data' => $activities,
            'bukti' => $bukti,
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->stream('output-pdf.pdf', [
            'Attachment' => 0, // 0 for inline display, 1 for download
        ]);
    }
    public function getPatientsProof(Request $request, $id)
    {
        if ($request->ajax()) {
            $patients = ActivityPatient::with(['patient.genderName', 'activityProof.activity'])
                ->where('activity_proof_id', $id)
                ->get();
            foreach ($patients as $patient) {
                \Log::info('Activity Patient ID: ' . $patient->id);
                \Log::info('Patient ID: ' . ($patient->patient->id ?? 'No patient'));
            }

            return DataTables::of($patients)
                ->addIndexColumn()
                ->addColumn('no', function ($row) {
                    static $counter = 1; // Gunakan static agar nomor urut tetap berjalan
                    return $counter++;
                })
                ->addColumn('nik', function ($row) {
                    return $row->patient->nik ?? '-';
                })
                ->addColumn('name', function ($row) {
                    return $row->patient->name ?? '-';
                })
                ->addColumn('phone', function ($row) {
                    return $row->patient->phone ?? '-';
                })
                ->addColumn('gender', function ($row) {
                    return $row->patient->genderName->name ?? '-';
                })
                ->addColumn('age', function ($row) {
                    return $row->patient->dob ? \Carbon\Carbon::parse($row->patient->dob)->age : '-';
                })
                ->addColumn('dob', function ($row) {
                    return $row->patient->dob ? \Carbon\Carbon::parse($row->patient->dob)->format('Y-m-d') : null;
                })
                ->addColumn('address', function ($row) {
                    return $row->patient->address ?? '-';
                })
                ->addColumn('description', function ($row) {
                    return $row->description ?? '-';
                })
                ->addColumn('actions', function ($row) {
                    return '
                        <button type="button" id="btn-editproof' .
                        $row->activityProof->activity->id .
                        '"
                            class="btn btn-primary btn-sm text-white font-weight-bold btn-editproof"
                            data-id="' .
                        $row->id .
                        '"
                            data-des="' .
                        $row->description .
                        '"
                            style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button"
                            class="btn btn-danger btn-sm text-white font-weight-bold btn-delete-modal-proof"
                            data-id="' .
                        $row->id .
                        '"
                            style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    ';
                })
                ->rawColumns(['actions'])
                ->toJson();
        }
    }

	public function proof(ActivityDetail $activityDetail) {
		return view('content.activity.proof', [
			'activity' => $activityDetail
		]);
	}

	public function deleteActivityPatient(ActivityPatient $activityPatient) {
		$activityPatient->delete();
		return redirect()->back()->with(['success' => 'Data berhasil dihapus']);
	}
}
