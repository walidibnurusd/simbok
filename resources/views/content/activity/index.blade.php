@include('partials.header')
@include('partials.navbar')
@include('component.modal-print-kegiatan')

<style>
    /* General styling for larger screens */
    .button-container {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding: 1rem;
        width: 100%;
    }

    .form-select {
        position: relative;
        padding-right: 2.5rem;
        /* Memberi ruang ekstra untuk ikon dropdown */
    }

    @media (max-width: 768px) {

        /* Restrict 100% width only within .main-content */
        .main-content .button-container {
            flex-direction: column;
            align-items: stretch;
            gap: 0.5rem;
            width: 100%;
            margin: 0 auto;
            padding: 1rem;
        }

        .main-content .form-select,
        .main-content .custom-button {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .main-content .btn {
            width: 100%;
            margin-bottom: 0.5rem;
            white-space: nowrap;
        }

        .main-content .button-container {
            padding: 1rem;
            border-radius: 8px;
        }
    }

    @media (max-width: 576px) {

        /* Keep similar logic for even smaller screens */
        .main-content .button-container {
            flex-direction: column;
            gap: 0.5rem;
            width: 100%;
            padding: 1rem;
            margin: 0 auto;
        }

        .main-content .form-select,
        .main-content .btn {
            width: 100%;
            white-space: nowrap;
        }

        .main-content .form-select,
        .main-content .custom-button {
            max-width: 100%;
        }
    }

    /* Ensure sidebar has a fixed width */
    .sidebar {
        width: 250px;
        /* adjust as necessary */
        position: fixed;
        /* Ensures it stays in place */
    }
</style>
<div class="mt-4">
    <div class="row">
        <div class="col-12" style="min-height: 100vh; overflow-x: hidden;">
            <div class="button-container mb-4">
                <form id="filterForm" action="{{ route('activity.index') }}" method="GET"
                    class="d-flex flex-column flex-md-row gap-2">
                    <select id="programFilter" class="form-select form-select-sm custom-select" name="program">
                        <option value="all">All Programs</option>
                        @foreach ($programs as $program)
                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                        @endforeach
                    </select>

                    <select id="yearFilter" class="form-select form-select-sm custom-select" name="year">
                        <option disabled selected>Pilih Tahun</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                    </select>

                    <select id="monthFilter" class="form-select form-select-sm custom-select" name="month">
                        <option disabled selected>Pilih Bulan</option>
                        <option value="Januari">Januari</option>
                        <option value="Februari">Februari</option>
                        <option value="Maret">Maret</option>
                        <option value="April">April</option>
                        <option value="Mei">Mei</option>
                        <option value="Juni">Juni</option>
                        <option value="Juli">Juli</option>
                        <option value="Agustus">Agustus</option>
                        <option value="September">September</option>
                        <option value="Oktober">Oktober</option>
                        <option value="November">November</option>
                        <option value="Desember">Desember</option>
                    </select>

                    {{-- <button class="btn btn-secondary btn-sm custom-button" type="submit">Cari</button> --}}
                </form>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success btn-sm custom-button" data-bs-toggle="modal"
                        data-bs-target="#addActivityModal">Tambah</button>
                    <button type="button" class="btn btn-warning btn-sm custom-button" data-bs-toggle="modal"
                        data-bs-target="#printModal">Print</button>
                </div>
            </div>


            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Kegiatan Lain</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-4">
                        <table id="activity" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No
                                    </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Waktu</th>
                                    <th style="width: 50%"
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Nama Kegiatan</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Jumlah</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Program</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Layanan</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Proses</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach ($activities as $index => $activity)
                                    @include('component.modal-edit-activity')

                                    <tr>
                                        <td>
                                            <h6 class="mb-0 text-sm">{{ $index + 1 }}</h6>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $activity->month }}</p>
                                            <p class="text-xs font-weight-bold mb-0">{{ $activity->year }}</p>
                                        </td>
                                        <td style="white-space: normal; word-wrap: break-word;">
                                            <p class="text-xs font-weight-bold mb-0">{{ $activity->name }}</p>
                                        </td>
                                        <td>

                                            <button
                                                class="btn btn-primary btn-sm text-white font-weight-bold text-xs add-detail-activity"
                                                data-bs-toggle="modal"
                                                data-bs-target="#addDetailActivityModal{{ $activity->id }}"
                                                data-id="{{ $activity->id }}" data-name="{{ $activity->name }}"
                                                data-month="{{ $activity->month }}" data-year="{{ $activity->year }}"
                                                data-program="{{ $activity->programs->id }}"
                                                data-service="{{ $activity->services->id }}"
                                                id="num_days_{{ $activity->id }}"
                                                style="padding: 8px 8px; font-size: 10px;">
                                                {{ $activity->num_days }} Hari
                                            </button>
                                            @include('component.modal-add-detail-activity')

                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $activity->programs->name }}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $activity->services->name }}
                                            </p>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button type="button"
                                                    class="btn btn-primary btn-sm text-white font-weight-bold text-xs edit-activity"
                                                    data-bs-toggle="modal" data-bs-target="#editActivityModal"
                                                    data-id="{{ $activity->id }}" data-name="{{ $activity->name }}"
                                                    data-month="{{ $activity->month }}"
                                                    data-year="{{ $activity->year }}"
                                                    data-program="{{ $activity->programs->id }}"
                                                    data-service="{{ $activity->services->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                             
                                                <button type="button"
                                                    class="btn btn-danger btn-sm text-white font-weight-bold d-flex align-items-center btn-delete"
                                                    data-form-action="{{ route('activity.destroy', $activity->id) }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach --}}
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
                        <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Dignity
                            Digital Space</a>

                    </div>
                </div>

            </div>
        </div>
    </footer>
</div>
@include('component.modal-add-activity')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@include('partials.footer')
<script>
    $(document).ready(function() {
        var table = $('#activity').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('activity.index') }}",
                data: function(d) {
                    d.year = $('#yearFilter').val();
                    d.month = $('#monthFilter').val();
                    d.program = $('#programFilter').val();
                }
            },
            columns: [
                { 
                    data: 'DT_RowIndex', 
                    name: 'DT_RowIndex', 
                    orderable: false, 
                    searchable: false,
                    render: function(data) {
                        return `<p class="text-xs font-weight-bold mb-0">${data}</p>`;
                    }
                },
                { 
                    data: 'month', 
                    name: 'month',
                    render: function(data) {
                        return `<p class="text-xs font-weight-bold mb-0">${data}</p>`;
                    }
                },
                { 
                    data: 'name', 
                    name: 'name',
                    render: function(data) {
                        return `<p class="text-xs font-weight-bold mb-0" style="white-space: normal; word-wrap: break-word;">${data}</p>`;
                    }
                },
                { 
                    data: 'num_days', 
                    name: 'num_days', 
                    orderable: false, 
                    searchable: false,
                    render: function(data) {
                        return data;
                    }
                },
                { 
                    data: 'programs.name', 
                    name: 'programs.name',
                    render: function(data) {
                        return `<p class="text-xs font-weight-bold mb-0">${data}</p>`;
                    }
                },
                { 
                    data: 'services.name', 
                    name: 'services.name',
                    render: function(data) {
                        return `<p class="text-xs font-weight-bold mb-0">${data}</p>`;
                    }
                },
                { 
                    data: 'action', 
                    name: 'action', 
                    orderable: false, 
                    searchable: false,
                    render: function(data) {
                        return data;
                    }
                }
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
    
        // Reload tabel ketika filter diubah
        $('#yearFilter, #monthFilter, #programFilter').change(function() {
            table.ajax.reload();
        });
    
        // Fungsi hapus data dengan SweetAlert
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
    
            var formAction = $(this).data('form-action');
    
            Swal.fire({
                title: "Apakah kamu yakin?",
                text: "Data akan dihapus secara permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, Hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: formAction,
                        type: "POST", // HARUS POST, bukan DELETE langsung
                        data: {
                            _method: "DELETE", // Spoofing DELETE method
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire("Berhasil!", response.success, "success");
                            table.ajax.reload(null, false); // false agar tidak kembali ke halaman pertama
                        },
                        error: function(xhr) {
                            Swal.fire("Gagal!", "Terjadi kesalahan saat menghapus data.", "error");
                        }
                    });
                }
            });
        });
    });
    </script>
    
