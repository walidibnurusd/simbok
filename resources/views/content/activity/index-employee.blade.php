@include('partials.header')
@include('partials.navbar')
@include('component.modal-print-kegiatan')

<style media="screen">
.wrap-text {
white-space: normal !important;
word-wrap: break-word;
}
</style>

<div class="mt-4">
    <div class="row">
        <div class="col-12" style="min-height: 100vh; overflow-x: hidden;">
            <div class="button-container mb-4 pl-2 d-flex gap-2 align-items-center" style="margin-right: 20px;">
                <form id="filterForm" action="{{ route('activityEmployee.index') }}" method="GET" class="d-flex ">
                    <select id="yearFilter" class="form-select form-select-sm custom-select" name="year"
                        style="width: auto;padding-right:30px">
                        <option disabled selected>Pilih Tahun</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                    </select>

                    <select id="monthFilter" class="form-select form-select-sm custom-select" name="month"
                        style="width: auto;padding-right:30px">
                        <option disabled selected>Pilih Bulan</option>
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                        <option value="4">April</option>
                        <option value="5">Mei</option>
                        <option value="6">Juni</option>
                        <option value="7">Juli</option>
                        <option value="8">Agustus</option>
                        <option value="9">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>

                    {{-- <button class="btn btn-secondary btn-sm custom-button" type="submit">Cari</button> --}}
                </form>
            </div>

            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Dokumen Kegiatan BOK</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-4">
                        <table id="activityProof" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No
                                    </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Tanggal</th>
                                    <th width="20%"
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Nama Kegiatan</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Lokasi</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Bukti Foto</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Hasil</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Saran</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Jumlah</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

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
{{--
<div id="editBuktiKegiatanModal"></div>

<div id="editPatientActivityModal"></div> --}}


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@include('partials.footer')

<script>
    $(document).ready(function() {
    var table = $('#activityProof').DataTable({
        processing: true,
        serverSide: true,
        paging: true,
        ajax: {
            url: "{{ route('activityEmployee.index') }}",
            data: function(d) {
                d.year = $('#yearFilter').val();
                d.month = $('#monthFilter').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'date', name: 'date' },
            { data: 'activity_name', name: 'activity_name', className: 'wrap-text' },
            { data: 'location', name: 'location' },
            { data: 'proof_image', name: 'proof_image', orderable: false, searchable: false },
            { data: 'proof_value', name: 'proof_value', className: 'wrap-text' },
            { data: 'proof_advice', name: 'proof_advice', className: 'wrap-text' },
            { data: 'patient_count', name: 'patient_count' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        language: {
            paginate: {
                previous: "‹",
                next: "›",
                first: "«",
                last: "»"
            }
        },
        order: [[1, "desc"]]
    });

    // Filter saat dropdown diubah
    $('#yearFilter, #monthFilter').change(function() {
        table.ajax.reload();
    });
});

</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check for success message
        @if (session('success'))
            Swal.fire({
                title: 'Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif

        // Check for validation errors
        @if ($errors->any())
            Swal.fire({
                title: 'Error!',
                html: '<ul>' +
                    '@foreach ($errors->all() as $error)' +
                    '<li>{{ $error }}</li>' +
                    '@endforeach' +
                    '</ul>',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        @endif
    });
</script>
{{--
<script>
	function loadEditBuktiKegiatanModal(button) {
		$('#editBuktiKegiatanModal').empty();

		const url = button.getAttribute('data-url');
		const activityId = button.getAttribute('data-id');
		const modalContent = $('#editBuktiKegiatanModal');

		modalContent.html('<div class="p-4 text-center">Loading...</div>');

		modalContent.load(url, function(response, status, xhr) {
			if (status === "error") {
				modalContent.html('<div class="text-danger p-4">Failed to load modal content.</div>');
				console.error('Error loading modal:', xhr.statusText);
			} else {
				$('#proofActivityModal'+activityId).modal('show');
			}
		});
	}

	function loadEditPatientActivityModal(button) {
		$('#editPatientActivityModal').empty();

		const url = button.getAttribute('data-url');
		const activityId = button.getAttribute('data-activity-id');
		const modalContent = $('#editPatientActivityModal');

		modalContent.html('<div class="p-4 text-center">Loading...</div>');

		modalContent.load(url, function(response, status, xhr) {
			if (status === "error") {
				modalContent.html('<div class="text-danger p-4">Failed to load modal content.</div>');
				console.error('Error loading modal:', xhr.statusText);
			} else {
				$j('#addPatientActivityModal'+activityId).modal('show');
			}
		});
	}
</script> --}}
