@include('partials.header')
@include('partials.navbar')

<style media="screen">
.wrap-text {
white-space: normal !important;
word-wrap: break-word;
}
</style>

<div class="mt-4">
    <div class="row">
        <div class="col-12" style="min-height: 100vh; overflow-x: hidden;">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Dokumen Kegiatan BOK</h6>
                </div>
                <div class="card-body">
					<form action="{{ route('activityEmployee.store') }}"
	                    method="POST" enctype="multipart/form-data">
	                    @csrf
	                    <input type="hidden" name="activity_id" value="{{ $activity->id }}">
	                    <div class="row g-2">
	                        <div class="col-md-12">
	                            <div
	                                style="display: grid; grid-template-columns: 150px 10px 1fr; gap: 5px; text-align: left;">
	                                <div><strong>Tanggal</strong></div>
	                                <div>:</div>
	                                <div>{{ \Carbon\Carbon::parse($activity->date)->format('d-m-Y') }}</div>

	                                <div><strong>Lokasi</strong></div>
	                                <div>:</div>
	                                <div>{{ $activity->location }}</div>

	                                <div><strong>Anggota Tim</strong></div>
	                                <div>:</div>
	                                <div>
	                                    @if ($activity->employees)
	                                        @php
	                                            $employeeIds = json_decode($activity->employees, true);
	                                            $employeeNames = \App\Models\User::whereIn('id', $employeeIds)
	                                                ->pluck('name')
	                                                ->toArray();
	                                        @endphp
	                                        {{ implode(', ', $employeeNames) }}
	                                    @else
	                                        No employees assigned
	                                    @endif
	                                </div>
	                            </div>
	                        </div>
	                    </div>

	                    <div class="mb-3 mt-1">
	                        <div><strong>Bukti Foto</strong></div>
	                        <input type="file" class="form-control" id="proofFile{{ $activity->id }}" name="image"
	                            accept="image/*">
	                        <small class="form-text text-muted">Allowed file types: png, jpg, jpeg.</small>

	                        @if (!empty($activity->proofActivity[0]->image))
	                            <p class="mt-2">Foto Saat Ini:</p>
	                            <img id="imgPreview{{ $activity->id }}"
	                                src="{{ asset('storage/' . $activity->proofActivity[0]->image) }}" alt="Foto Saat Ini"
	                                class="img-fluid mb-3" style="max-height: 200px;">
	                        @else
	                            <p class="mt-2">Preview Foto:</p>
	                            <img id="imgPreview{{ $activity->id }}" src="#" alt="Preview Foto"
	                                class="img-fluid mb-3" style="max-height: 200px; display: none;">
	                        @endif
	                    </div>

	                    <div class="row">
	                        <div class="col-md-6">
	                            <div class="mb-3">
	                                <div><strong>Keterangan Hasil Kegiatan</strong></div>
	                                <input type="text" class="form-control" name="value"
	                                    value="{{ $activity->proofActivity[0]->value ?? '' }}" placeholder="Keterangan">
	                            </div>
	                        </div>
	                        @php

	                            if ($activity && $activity->proofActivity && isset($activity->proofActivity[0])) {
	                                $advice = \App\Models\AdviceActivityProof::where('user_id', Auth::user()->id)
	                                    ->where('activity_proof_id', $activity->proofActivity[0]->id)
	                                    ->first();
	                            } else {
	                                $advice = null;
	                            }
	                        @endphp

	                        <div class="col-md-6">
	                            <div class="mb-3">
	                                <div><strong>Saran</strong></div>
	                                <input type="text" class="form-control" name="advice"
	                                    value="{{ $advice->advice ?? '' }}" placeholder="Saran">
	                            </div>
	                        </div>
	                    </div>
	                    <div class="d-flex justify-content-start">
							<a href="{{route('activityEmployee.index')}}" class="btn btn-secondary me-2">Batal</a>
	                        <button type="submit" class="btn btn-danger">Simpan</button>
	                    </div>
	                </form>

	                <div class="mt-4">
	                    @if (!empty($activity->proofActivity) && isset($activity->proofActivity[0]->value))
	                        <button type="button" onclick="showPatientModal()"
	                            class="btn btn-success btn-sm text-white mt-2"
	                            style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
	                            Tambah Pasien
	                        </button>
	                        <button type="button" id="refreshProofTableBtn{{ $activity->id }}"
	                            class="btn btn-primary btn-sm text-white mt-2 ms-2"
	                            style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
	                            Refresh
	                        </button>
	                    @endif

	                    <div id="loadingIndicator" style="display: none;">
	                        <i class="fas fa-spinner fa-spin"></i> Memuat Data...
	                    </div>
	                    <table id="proofTable" class="display small" style="width: 100%;">
	                        <thead>
	                            <tr>
	                                <th>#</th>
	                                <th>NIK</th>
	                                <th>Nama</th>
	                                <th>Telp</th>
	                                <th>Jenis Kelamin</th>
	                                <th>Umur</th>
	                                <th>Keterangan</th>
	                                <th>Aksi</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                            @foreach ($activity->proofActivity as $proof)
	                                @foreach ($proof->patients as $item)
	                                    @php
	                                        $patient = \App\Models\Patients::with('genderName')
	                                            ->where('id', $item->patient_id)
	                                            ->first();
	                                    @endphp
	                                    <tr data-patient="{{ json_encode($patient) }}">
	                                        <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
	                                        <td>{{ $patient->nik }}</td>
	                                        <td>{{ $patient->name }}</td>
	                                        <td>{{ $patient->phone }}</td>
	                                        <td>{{ $patient->genderName->name }}</td>
	                                        <td>{{ \Carbon\Carbon::parse($patient->dob)->age }} tahun</td>
	                                        <td class="wrap-text">{{ $item->description }}</td>
	                                        <td>
												<button type="button" class="btn-edit-patient-activity btn btn-primary btn-sm text-white font-weight-bold"
												 	data-des="{{ $item->description }}" data-id="{{ $item->id }}" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
													<i class="fas fa-edit"></i>
												</button>
	                                            <a href="{{route('activityEmployee.deleteActivityPatient', $item->id)}}"
	                                                class="btn btn-danger btn-sm text-white font-weight-bold"
	                                                style="font-size: 0.75rem; padding: 0.25rem 0.5rem;"
													onclick="return confirm('Yakin ingin menghapus data ini?');">
													<i class="fas fa-trash-alt"></i>
	                                            </a>
	                                        </td>
	                                    </tr>
	                                @endforeach
	                            @endforeach
	                        </tbody>
	                    </table>
	                </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer pt-3">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-lg-between">
                <div class="col-lg-6 mb-lg-0 mb-4">
                    <div class="copyright text-center text-sm text-muted text-lg-start">
                        ©
                        <script>
                            document.write(new Date().getFullYear())
                        </script>, made with <i class="fa fa-heart"></i> by
                        <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Dignity Digital
                            Space</a>

                    </div>
                </div>

            </div>
        </div>
    </footer>
</div>

<div class="modal fade" id="addPatientModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
				<form action="{{route('activityEmployee.storePatientProof')}}" method="POST" class="px-3">
                    @csrf
                    <input type="hidden" name="idPatient" id="patientModal-patientId">
                    <input type="hidden" name="idProof" id="patientModal-proofId" value="{{ $activity->proofActivity[0]->id ?? '' }}">
                    <input type="hidden" name="idProofEdit" id="patientModal-editProofId">
                    <div class="row g-2 mb-2">
                        <div class="col-md-12">
                            <div
                                style="display: grid; grid-template-columns: 150px 10px 1fr; gap: 5px; text-align: left;">
                                <div><strong>Nama Lengkap</strong></div>
                                <div>:</div>
                                <div id="patientModal-patientName"></div>

                                <div><strong>Alamat</strong></div>
                                <div>:</div>
                                <div id="patientModal-patientAddress"></div>

                                <div><strong>Jenis Kelamin</strong></div>
                                <div>:</div>
                                <div id="patientModal-patientGender"></div>

                                <div><strong>Umur</strong></div>
                                <div>:</div>
                                <div id="patientModal-patientAge"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">Keterangan</label>
                        <textarea class="form-control" id="patientModal-notes" name="notes" placeholder="Keterangan" required></textarea>
                    </div>

					<table id="patientTable" class="display small" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Tempat/Tanggal Lahir</th>
                                <th>JK</th>
                                <th>Telepon</th>
                                <th>Nikah</th>
                                <th>No.RM</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script>
var table = $('#proofTable').DataTable({
	"language": {
		"info": "_PAGE_ dari _PAGES_ halaman",
		"paginate": {
			"previous": "<",
			"next": ">",
			"first": "<<",
			"last": ">>"
		}
	},
	"responsive": true,
	"lengthMenu": [10, 25, 50, 100],
	"stateSave": true,
});

let patientTableInstance = null;

function showPatientModal() {
    if (!patientTableInstance) {
        patientTableInstance = $('#patientTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("dt.patients") }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nik', name: 'nik' },
                { data: 'name', name: 'name' },
                { data: 'address', name: 'address' },
                { data: 'tempat_tanggal_lahir', name: 'tempat_tanggal_lahir' },
                { data: 'gender', name: 'gender' },
                { data: 'phone', name: 'phone' },
                { data: 'nikah', name: 'nikah' },
                { data: 'no_rm', name: 'no_rm' },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
            ],
            responsive: true
        });
    } else {
        patientTableInstance.ajax.reload(); // Reload if already initialized
    }

	$('#addPatientModal').modal('show')
}

$('#proofTable').on('click', '.btn-edit-patient-activity', function () {
	var patientData = $(this).closest('tr').data('patient');
	var proofId = $(this).data('id');
	var description = $(this).data('des');
	$('#patientModal-patientName').text(patientData.name)
	$('#patientModal-patientAddress').text(patientData.address)
	$('#patientModal-patientGender').text(patientData.gender_name.name)
	$('#patientModal-patientAge').text(calculateAge(new Date(patientData.dob)))
	$('#patientModal-patientId').val(patientData.id);
	$('#patientModal-editProofId').val(proofId);
	showPatientModal()
});

$('#patientTable').on('click', '.btn-select-patient', function () {
    var rowData = patientTableInstance.row($(this).closest('tr')).data();
	console.log(rowData);
	$('#patientModal-patientName').text(rowData.name)
	$('#patientModal-patientAddress').text(rowData.address)
	$('#patientModal-patientGender').text(rowData.gender)
	$('#patientModal-patientAge').text(calculateAge(new Date(rowData.dob)))
	$('#patientModal-patientId').val(rowData.id);

	$(this).closest('.modal-content').find('.modal-body').animate({ scrollTop: 0 }, 'fast');
});

$('#addPatientModal').on('hidden.bs.modal', function () {
    // Example actions:
    $('#patientModal-patientName').text('');
    $('#patientModal-patientAddress').text('');
    $('#patientModal-patientGender').text('');
    $('#patientModal-patientAge').text('');
    $('#patientModal-patientId').val('');
	$('#patientModal-editProofId').val('');

    console.log('Modal closed and data cleared.');
});

function calculateAge(dob) {
	var diff = Date.now() - dob.getTime();
	var ageDate = new Date(diff);
	return Math.abs(ageDate.getUTCFullYear() - 1970);
}
</script>
