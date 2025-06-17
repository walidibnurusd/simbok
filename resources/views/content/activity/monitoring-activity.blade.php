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
                <form action="{{ route('activityMonitoring.index') }}"
                    method="GET"class="d-flex flex-column flex-md-row gap-2">
                    <select class="form-select form-select-sm custom-select" name="year-activity">
                        <option disabled selected>Pilih Tahun</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                    </select>

                    <select class="form-select form-select-sm custom-select" name="month-activity">
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
                    @php
                        $dates = $dates ?? [];
                    @endphp
                    <select class="form-select form-select-sm custom-select" name="date" id="date"
                        style="width: auto; padding-right: 30px;">
                        <option disabled selected>Pilih Tanggal</option>
                        @foreach ($dates as $date)
                            <option value="{{ $date }}">{{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}
                            </option>
                        @endforeach
                    </select>




                    <button class="btn btn-secondary btn-sm custom-button" type="submit">Cari</button>
                </form>
            </div>

            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Monitoring Kegiatan</h6>
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
                                    <th
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
                                        Pelaksana</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Ceklist</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($activities as $index => $activity)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0 text-sm">{{ $index + 1 }}</h6>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $activity->date }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $activity->activity->name }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $activity->location }}</p>
                                        </td>

                                        <td>
                                            @if ($activity->proofActivity[0]->image)
                                                <img src="{{ asset('storage/' . $activity->proofActivity[0]->image) }}"
                                                    alt="Photo" class="img-thumbnail"
                                                    style="width: 100px; height: 100px; object-fit: cover;">
                                            @else
                                                <p class="text-xs font-weight-bold mb-0"></p>
                                            @endif
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ $activity->proofActivity[0]->value }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ $activity->proofActivity[0]->advice }}</p>
                                        </td>

                                        <td>
                                            @if ($activity->proofActivity->isNotEmpty())
                                                @foreach ($activity->proofActivity as $proof)
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $proof->patient_count }}
                                                    </p>
                                                @endforeach
                                            @else
                                                <p class="text-xs font-weight-bold mb-0">-</p>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($activity->employeesActivity->isNotEmpty())
                                                @foreach ($activity->employeesActivity as $employee)
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $employee->employee->name }}
                                                    </p>
                                                @endforeach
                                            @else
                                                <p class="text-xs font-weight-bold mb-0">-</p>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($activity->checkActivity)
                                                <p class="text-xs font-weight-bold mb-0">
                                                    Foto : {{ $activity->checkActivity->photo == 1 ? 'Y' : 'T' }}
                                                </p>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    Surat Tugas :
                                                    {{ $activity->checkActivity->letter_assign == 1 ? 'Y' : 'T' }}
                                                </p>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    Dokumen/LPJG :
                                                    {{ $activity->checkActivity->document == 1 ? 'Y' : 'T' }}
                                                </p>
                                            @else
                                                <p class="text-xs font-weight-bold mb-0">-</p>
                                            @endif
                                        </td>

                                        <td>
                                            <div>
                                                @if ($activity->checkActivity)
                                                    <button type="button"
                                                        class="btn btn-primary btn-sm text-white font-weight-bold text-xs proof-activity"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#checkActivityModal{{ $activity->id }}"
                                                        data-id="{{ $activity->id }}"
                                                        data-name="{{ $activity->name }}">
                                                        Cek
                                                    </button>
                                                    @include('component.modal-check-activity', [
                                                        'activity' => $activity,
                                                    ])
                                                    <a type="button"
                                                        href="{{ route('activityEmployee.print', $activity->id) }}"
                                                        class="btn btn-warning btn-sm text-white font-weight-bold text-xs proof-activity"
                                                        target="_blank">
                                                        Print
                                                    </a>
                                                @else
                                                    <button type="button"
                                                        class="btn btn-primary btn-sm text-white font-weight-bold text-xs proof-activity"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#checkActivityModal{{ $activity->id }}"
                                                        data-id="{{ $activity->id }}"
                                                        data-name="{{ $activity->name }}">
                                                        Cek
                                                    </button>
                                                    @include('component.modal-check-activity', [
                                                        'activity' => $activity,
                                                    ])
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
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

@include('partials.footer')


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const table = new DataTable("#activityProof", {
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

            order: [
                [9, "desc"]
            ],
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('select[name="year-activity"], select[name="month-activity"]').change(function() {
            console.log('Change detected');
            var year = $('select[name="year-activity"]').val();
            var month = $('select[name="month-activity"]').val();
            console.log('Year Select:', $(
                'select[name="year-activity"]')); // Cek apakah select element ditemukan
            console.log('Month Select:', $(
                'select[name="month-activity"]')); // Cek apakah select element ditemukan

            var year = $('select[name="year-activity"]').val();
            var month = $('select[name="month-activity"]').val();
            console.log('Selected Year:', year);
            console.log('Selected Month:', month);

            if (year && month) {
                $.ajax({
                    url: '/kegiatan/get-dates', // Pastikan URL ini benar
                    method: 'GET',
                    data: {
                        year: year,
                        month: month
                    },
                    success: function(data) {
                        console.log(data);
                        var dateSelect = $('select[name="date"]');
                        dateSelect.empty();
                        dateSelect.append(
                            '<option disabled selected>Pilih Tanggal</option>');

                        // Pastikan response dari server berformat array atau collection
                        $.each(data, function(key, value) {
                            dateSelect.append('<option value="' + value + '">' +
                                value + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        console.log('Status:', status);
                        console.log('Response:', xhr.responseText);
                    }
                });
            }

        });

    });

    // Initialize proof activity modals dynamically
    @foreach ($activities as $activity)
        $('#proofActivityModal{{ $activity->id }}').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var activityName = button.data('name');
            var modal = $(this);
            modal.find('.modal-title').text(activityName);
        });
    @endforeach

    // Delete button confirmation
    document.querySelectorAll('.btn-delete').forEach(function(button) {
    button.addEventListener('click', function(event) {
        event.preventDefault();

        var formAction = this.getAttribute('data-form-action');

        Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: 'Apakah Anda yakin ingin menghapus kegiatan ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit the form
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = formAction;

                var csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = "{{ csrf_token() }}";
                form.appendChild(csrfToken);

                var methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);

                document.body.appendChild(form);
                form.submit();
            }
        });
    });
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
