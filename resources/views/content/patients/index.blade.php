@include('partials.header')
@include('partials.navbar')

<div class="mt-4">
    <div class="row">
        <div class="col-12" style="min-height: 100vh; overflow-x: hidden;">
            <div class="button-container">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPatientModal">
                    Tambah
                    <i class="fas fa-plus ms-2"></i> <!-- Icon with margin to the left -->
                </button>
                <button type="button" class="btn btn-warning">
                    Print
                    <i class="fas fa-file-import ms-2"></i> <!-- Updated icon with margin to the left -->
                </button>
                <button type="button" class="btn btn-info" id="btn-sync">
                    Sinkronisasi
                    <i class="fas fa-sync-alt ms-2"></i>
                </button>
                
            </div>

            @include('component.modal-add-patient')
            <!-- Modal -->


            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Daftar Data Pasien</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-4">
                        <table id="patient" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No
                                    </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        NIK</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        NAMA</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        ALAMAT</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        TEMPAT/TGL.LAHIR</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        JK</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        TELEPON</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        NIKAH</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        No RM</th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        TANGGAL INPUT</th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        AKSI</th>
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
    const table = new DataTable("#patient", {
        processing: true,
        serverSide: true,
        ajax: "{{ route('patients.data') }}",
        columns: [
            { 
                data: "DT_RowIndex", 
                name: "DT_RowIndeSx", 
                orderable: false, 
                searchable: false,
                render: function(data, type, row) {
                    return `<h6 class="text-xs font-weight-bold mb-0 text-center">${data}</h6>`;
                }
            },
            { 
                data: "nik", 
                name: "nik",
                render: function(data, type, row) {
                    return `<p class="text-xs font-weight-bold mb-0 text-center">${row.nik ?? '-'}</p>`;
                }
            },
            { 
                data: "name", 
                name: "name",
                render: function(data, type, row) {
                    return `<p class="text-xs font-weight-bold mb-0 text-center">${row.name ?? '-'}</p>`;
                }
            },
            { 
                data: "address", 
                name: "address",
                render: function(data, type, row) {
                    return `<p class="text-xs font-weight-bold mb-0 text-center">${row.address ?? '-'}</p>`;
                }
            },
            { 
                data: "place_birth", 
                name: "place_birth",
                render: function(data, type, row) {
                    return `<p class="text-xs font-weight-bold mb-0 text-center">${row.place_birth ?? '-'}</p>`;
                }
            },
            { 
                data: "gender", 
                name: "gender",
                render: function(data, type, row) {
                    return `<p class="text-xs font-weight-bold mb-0 text-center">${row.gender ?? '-'}</p>`;
                }
            },
            { 
                data: "phone", 
                name: "phone",
                render: function(data, type, row) {
                    return `<p class="text-xs font-weight-bold mb-0 text-center">${row.phone ?? '-'}</p>`;
                }
            },
            { 
                data: "marital_status", 
                name: "marital_status",
                render: function(data, type, row) {
                    return `<p class="text-xs font-weight-bold mb-0 text-center">${row.marital_status ?? '-'}</p>`;
                }
            },
            { 
                data: "no_rm", 
                name: "no_rm",
                render: function(data, type, row) {
                    return `<p class="text-xs font-weight-bold mb-0 text-center">${row.no_rm ?? '-'}</p>`;
                }
            },
            { 
                data: "created_at", 
                name: "created_at",
                render: function(data, type, row) {
                    return `<p class="text-xs font-weight-bold mb-0 text-center">${row.created_at ?? '-'}</p>`;
                }
            },
            { 
                data: "actions", 
                name: "actions", 
                orderable: false, 
                searchable: false
            }
        ],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            paginate: {
                previous: "‹",
                next: "›",
                first: "«",
                last: "»"
            }
        },
        responsive: true,
        order: [[9, "desc"]], // Urutkan berdasarkan created_at (terbaru di atas)
    });

    // Handle delete button with SweetAlert2
    document.addEventListener("click", function(event) {
        if (event.target.closest(".btn-delete")) {
            let button = event.target.closest(".btn-delete");
            let formAction = button.getAttribute("data-form-action");

            Swal.fire({
                title: "Konfirmasi Hapus",
                text: "Apakah Anda yakin ingin menghapus pasien ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.createElement("form");
                    form.method = "POST";
                    form.action = formAction;

                    let csrfToken = document.createElement("input");
                    csrfToken.type = "hidden";
                    csrfToken.name = "_token";
                    csrfToken.value = "{{ csrf_token() }}";
                    form.appendChild(csrfToken);

                    let methodField = document.createElement("input");
                    methodField.type = "hidden";
                    methodField.name = "_method";
                    methodField.value = "DELETE";
                    form.appendChild(methodField);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
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
{{-- <script>
    document.addEventListener('click', function(event) {
    if (event.target.closest('.btn-delete')) {
        event.preventDefault();
        
        let button = event.target.closest('.btn-delete');
        let formAction = button.getAttribute('data-form-action');

        Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: 'Apakah Anda yakin ingin menghapus pasien ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'swal2-popup-custom'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = formAction;

                let csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfToken);

                let methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
});

</script> --}}
<script>
    document.getElementById('btn-sync').addEventListener('click', function () {
        Swal.fire({
            title: 'Sinkronisasi Data',
            text: 'Mengambil dan menyimpan data pasien...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch("{{ route('sync.patients') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                title: 'Berhasil!',
                text: data.message,
                icon: 'success',
                confirmButtonText: 'OK'
            });

            // Reload DataTable atau halaman jika perlu
            location.reload();
        })
        .catch(error => {
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat sinkronisasi.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            console.error('Error syncing patients:', error);
        });
    });
</script>

@include('partials.footer')
