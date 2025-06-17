<!-- Proof Activity Modal -->
<style>
    #loadingIndicator {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 1.5rem;
        color: #007bff;
        display: none;
        /* Hidden by default */
        z-index: 1000;
        /* Pastikan di atas tabel */
    }
</style>

<style media="screen">
.wrap-text {
white-space: normal !important;
word-wrap: break-word;
}
</style>

<div class="modal fade" id="proofActivityModal{{ $activity->id }}" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $activity->activity->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
                <form id="addActivityForm{{ $activity->id }}" action="{{ route('activityEmployee.store') }}"
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
                                // Jika $activity atau $activity->proofActivity null, set $advice sebagai null
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
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Simpan</button>
                    </div>
                </form>

                <div class="mt-4">
                    @if (!empty($activity->proofActivity) && isset($activity->proofActivity[0]->value))
                        <button type="button" id="addPatientBtn{{ $activity->id }}"
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
                    <table id="proofTable{{ $activity->id }}" class="display small" style="width: 100%;">
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
                                            <button type="button" id="btn-editproof{{ $activity->id }}"
                                                class="btn btn-primary btn-sm text-white font-weight-bold btn-editproof"
                                                data-id="{{ $item->id }}" data-des="{{ $item->description }}"
                                                style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button"
                                                class="btn btn-danger btn-sm text-white font-weight-bold btn-delete-modal-proof"
                                                data-id="{{ $item->id }}"
                                                style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
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

@include('component.modal-add-activity-patient')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

<script>
    var $j = jQuery.noConflict();

    $j(document).ready(function() {
        // Initialize DataTable
        var table = $j('#proofTable{{ $activity->id }}').DataTable({
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
        table.on('page', function() {
            var currentPage = table.page();
            localStorage.setItem('currentPage', currentPage); // Save the current page
        });

        // When the page reloads, restore the saved page
        var currentPage = localStorage.getItem('currentPage');
        if (currentPage !== null) {
            table.page(parseInt(currentPage)).draw('page'); // Go to the saved page
        }

        // Image preview for adding proof
        document.getElementById('proofFile{{ $activity->id }}').addEventListener('change', function() {
            const [file] = this.files;
            const imgPreview = document.getElementById('imgPreview{{ $activity->id }}');
            if (file) {
                imgPreview.src = URL.createObjectURL(file);
                imgPreview.style.display = 'block';
            } else {
                imgPreview.style.display = 'none';
            }
        });
        // $j(document).on('click', '#refreshProofTableBtn{{ $activity->id }}', function() {
        //     console.log("Tombol Refresh Ditekan");

        //     // Tampilkan indikator loading
        //     $j('#loadingIndicator').show();

        //     // Clear the DataTable
        //     table.clear();

        //     // Menambahkan ulang data tabel secara manual
        //     $j('#proofTable{{ $activity->id }} tbody tr').each(function() {
        //         var rowData = [];
        //         $j(this).find('td').each(function() {
        //             rowData.push($j(this).text());
        //         });
        //         table.row.add(rowData);
        //     });

        //     // Redraw DataTable
        //     table.draw();

        //     // Sembunyikan indikator loading setelah selesai
        //     setTimeout(function() {
        //         $j('#loadingIndicator').hide();
        //         console.log("DataTable Diperbarui");
        //     }, 500); // Anda dapat menyesuaikan waktu sesuai kebutuhan
        // });



        $j(document).on('click', '#refreshProofTableBtn{{ $activity->id }}', function() {
            console.log("Tombol Refresh Ditekan");

            // Tampilkan indikator loading
            $j('#loadingIndicator').show();

            // Clear DataTable sebelum memuat data baru
            table.clear().draw();

            // Ambil data baru dengan AJAX
            $j.ajax({
                url: '{{ route('patients.data.proof', ['id' => $activity->proofActivity[0]->id ?? 0]) }}',
                method: 'GET',
                success: function(response) {
                    console.log("Response:", response); // Debug response

                    if (Array.isArray(response.data)) {
                        response.data.forEach(function(rowData) {
                            let patientData = JSON.stringify({
                                id: rowData.patient.id,
                                nik: rowData.nik,
                                name: rowData.name,
                                phone: rowData.phone,
                                gender_name: {
                                    name: rowData.gender
                                },
                                dob: rowData.dob,
                                address: rowData.address,
                                description: rowData.description
                            });
                            console.log(patientData);

                            let rowNode = $j('<tr>')
                                .attr('data-patient', patientData)
                                .append(`
                                <td>${rowData.no}</td>
                                <td>${rowData.nik}</td>
                                <td>${rowData.name}</td>
                                <td>${rowData.phone}</td>
                                <td>${rowData.gender}</td>
                                <td>${rowData.age} tahun</td>
                                <td>${rowData.description}</td>
                                <td>
                                    <button type="button" id="btn-editproof{{ $activity->id }}"
                                        class="btn btn-primary btn-sm text-white font-weight-bold btn-editproof"
                                        data-id="${rowData.id}" data-des="${rowData.description}"
                                        style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button"
                                        class="btn btn-danger btn-sm text-white font-weight-bold btn-delete-modal-proof"
                                        data-id="${rowData.id}"
                                        style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            `);

                            table.row.add(rowNode).draw(false);
                        });

                        setTimeout(function() {
                            $j('#loadingIndicator').hide();
                            console.log("DataTable Updated");
                        }, 500);
                    } else {
                        console.error("Response data is not an array", response);
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Error fetching data:", error);
                    $j('#loadingIndicator').hide();
                }
            });
        });


        $j(document).off('click', '.btn-delete-modal-proof').on('click', '.btn-delete-modal-proof', function() {
            var proofId = $j(this).data('id');
            if (confirm('Are you sure you want to delete this proof?')) {
                $j.ajax({
                    url: '{{ route('activityEmployee.deleteProof') }}',
                    type: 'POST',
                    data: {
                        id: proofId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            location.reload(); // Reload the page to see changes
                        } else {
                            alert('Failed to delete proof: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred: ' + error);
                    }
                });
            }
        });
        $j('#addActivityFormPatient{{ $activity->id }}').on('submit', function(event) {
            event.preventDefault();

            let formData = new FormData(this);

            // Debugging awal
            console.log('Form sedang dikirim...');

            $j.ajax({
                url: "{{ route('activityEmployee.storePatientProof') }}", // Pastikan URL yang benar
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log('Response sukses:', response);
                    alert('Data berhasil disimpan!');

                    // Tidak perlu reload halaman, langsung buka modal proof
                    var proofActivityModal = new bootstrap.Modal(document.getElementById(
                        'proofActivityModal{{ $activity->id }}'));
                    proofActivityModal.show(); // Menampilkan modal proofActivityModal

                    // Pastikan DataTable tetap pada halaman yang sama
                    var currentPage = table.page();
                    localStorage.setItem('currentPage',
                        currentPage); // Simpan halaman aktif

                    // Pastikan modal pasien tidak lagi terbuka
                    addPatientActivityModal.hide();
                },
                error: function(xhr, status, error) {
                    console.log('Terjadi kesalahan:', xhr.responseText, status, error);
                    alert('Terjadi kesalahan: ' + xhr.responseText);
                }
            });
        });

        // After page reload, check if modal should be shown
        var modalId = localStorage.getItem('showModal');
        var currentPage = localStorage.getItem('currentPage'); // Get the active page

        // Make sure the modal is shown after the page reload
        if (modalId) {
            var modalElement = document.getElementById(modalId);
            if (modalElement) {
                var modal = new bootstrap.Modal(modalElement);

                // Ensure the modal is shown after the DataTable is fully redrawn
                table.on('draw', function() {
                    modal.show(); // Show the modal after DataTable is redrawn
                });
            }

            // Clear the localStorage items after modal is shown
            localStorage.removeItem('showModal');
            localStorage.removeItem('currentPage');
        }

        // After page reload, ensure the DataTable remains on the correct page
        if (currentPage) {
            table.page(parseInt(currentPage)).draw('page'); // Set the page to the one stored in localStorage
        }
        // Modal handling logic
        var proofActivityModal = new bootstrap.Modal(document.getElementById(
            'proofActivityModal{{ $activity->id }}'));
        var addPatientActivityModal = new bootstrap.Modal(document.getElementById(
            'addPatientActivityModal{{ $activity->id }}'));
        var patientsActivityModal = new bootstrap.Modal(document.getElementById(
            'patientsActivityModal{{ $activity->id }}'));
        var isPatientsModalActive = false;

        $j(document).on('click', '#addPatientBtn{{ $activity->id }}', function() {
            clearPatientForm();
            proofActivityModal.hide();
            addPatientActivityModal.show();
        });
        $j(document).on('click', '#btn-editproof{{ $activity->id }}', function() {
            proofActivityModal.hide();
            addPatientActivityModal.show();
        });

        addPatientActivityModal._element.addEventListener('hidden.bs.modal', function() {
            if (!isPatientsModalActive) {
                proofActivityModal.show();
            }

        });

        function clearPatientForm() {
            $j('#addActivityFormPatient{{ $activity->id }}').trigger('reset'); // Reset the entire form
            $j('#idPatient{{ $activity->id }}').val(''); // Clear hidden fields
            $j('#idProofEdit{{ $activity->id }}').val('');
            $j('#patientName{{ $activity->id }}').text(''); // Clear patient display fields
            $j('#patientAddress{{ $activity->id }}').text('');
            $j('#patientGender{{ $activity->id }}').text('');
            $j('#patientAge{{ $activity->id }}').text('');
        }

		$j(document).on('click', '#searchPatientBtn{{ $activity->id }}', function () {
		    isPatientsModalActive = true;
		    addPatientActivityModal.hide();

		    const tableSelector = '#patientTable{{ $activity->id }}';

		    // Check if DataTable already exists and destroy it
		    if ($j.fn.DataTable.isDataTable(tableSelector)) {
		        $j(tableSelector).DataTable().clear().destroy();
		    }

		    // Reinitialize DataTable
		    $j(tableSelector).DataTable({
		        processing: true,
		        serverSide: true,
		        ajax: {
		            url: '{{ route('dt.patients') }}',
		        },
		        order: [[1, 'desc']],
		        columns: [
		            { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, sortable: false },
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
		        pageLength: 10,
		        lengthMenu: [10, 25, 50, 100],
		        drawCallback: function (settings) {
		            var totalPages = settings.json.recordsTotal / settings._iDisplayLength;
		            console.log('Total Pages:', totalPages);
		        }
		    });

		    patientsActivityModal.show();
		});

        patientsActivityModal._element.addEventListener('hidden.bs.modal', function() {
            isPatientsModalActive = false;
            addPatientActivityModal.show();
        });
        // Event listener untuk edit proof button
        $j('#proofTable{{ $activity->id }} tbody').on('click', '.btn-editproof', function() {
            var patientData = $j(this).closest('tr').data('patient'); // Get patient data
            var proofId = $j(this).data('id'); // Retrieve proof ID
            var description = $j(this).data('des'); // Retrieve description

            if (patientData && proofId) {
                $j('#idPatient{{ $activity->id }}').val(patientData.id); // Set patient ID
                $j('#idProofEdit{{ $activity->id }}').val(proofId); // Set proof ID
                $j('#patientName{{ $activity->id }}').text(patientData.name);
                $j('#patientAddress{{ $activity->id }}').text(patientData.address);
                $j('#patientGender{{ $activity->id }}').text(patientData.gender_name.name);

                // Calculate age
                var dob = new Date(patientData.dob);
                var age = calculateAge(dob);
                $j('#patientAge{{ $activity->id }}').text(age + ' tahun');
                $j('#notes{{ $activity->id }}').val(description);
                console.log(patientData.id);
                // Display modal
                if (addPatientActivityModal) {
                    addPatientActivityModal.show();
                }
            } else {
                console.error('Missing patient data or proof ID');
            }
        });




    });
</script>
