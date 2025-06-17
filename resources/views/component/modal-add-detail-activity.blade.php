@php
    $master = new App\Http\Controllers\DependentDropDownController();
    $employees = $master->employeeData();
@endphp

<div class="modal fade" id="addDetailActivityModal{{ $activity->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $activity->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="overflow-y:auto">
                <form id="addActivityForm{{ $activity->id }}"
                    action="{{ route('activity.storeDetail', $activity->id) }}" method="POST" class="px-3">
                    @csrf
                    <input type="hidden" name="activityId" value="{{ $activity->id }}">
                    <input type="hidden" id="activityDetailId{{ $activity->id }}" name="activityDetailId">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date">Tanggal</label>
                                <input type="text" class="form-control" id="date{{ $activity->id }}" name="date"
                                    placeholder="Tanggal Surat" value="{{ old('date') }}" onfocus="(this.type='date')"
                                    onblur="if(this.value==''){this.type='text'}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="location">Lokasi</label>
                                <input class="form-control" id="location{{ $activity->id }}" name="location"
                                    placeholder="Lokasi" value="{{ old('location') }}" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="employee">Anggota Pelaksana</label>
                            <div class="form-group">
                                <select class="form-control @error('employee') is-invalid @enderror"
                                    id="employee{{ $activity->id }}" name="employee[]" multiple="multiple" required>
                                    {{-- @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}"
                                            {{ in_array($employee->id, old('employee', $activity->employee ?? [])) ? 'selected' : '' }}>
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach --}}
                                </select>

                                @error('employee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-start">
                            <button type="button" class="btn btn-secondary me-2">Batal</button>
                            <button type="submit" class="btn btn-danger">Simpan</button>
                        </div>
                    </div>
                </form>
                <!-- Table for displaying details -->
                <div class="mt-4">
                    <table id="detailsTable{{ $activity->id }}" class="display small" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Lokasi</th>
                                <th>Anggota Pelaksana</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activity->details as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->date }}</td>
                                    <td>{{ $item->location }}</td>
                                    <td>
                                        @if ($item->employees)
                                            @php
                                                $employeeIds = json_decode($item->employees, true);
                                                $employeeNames = \App\Models\User::whereIn('id', $employeeIds)
                                                    ->pluck('name')
                                                    ->toArray();
                                            @endphp
                                            {{ implode(', ', $employeeNames) }}
                                        @else
                                            No employees assigned
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-primary btn-sm text-white font-weight-bold d-flex align-items-center btn-edit"
                                            data-id="{{ $item->id }}"
                                            style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button"
                                            class="btn btn-danger btn-sm text-white font-weight-bold d-flex align-items-center btn-delete-modal"
                                            data-id="{{ $item->id }}"
                                            style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>

            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS (versi yang mendukung jQuery) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
<script>
    var $j = jQuery.noConflict();

    $j('#detailsTable{{ $activity->id }}').DataTable({
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
        "lengthMenu": [10, 25, 50, 100]
    });

    function loadEmployees(activityId, itemId, selectedDate, selectedEmployeeIds = []) {
        var url = "/api/employee-data?date=" + selectedDate + "&activity_id=" + itemId;

        console.log("Fetching employees from: " + url);

        $j.ajax({
            url: url,
            type: "GET",
            success: function(response) {
                console.log("Employee data received:", response);
                var select = $j("#employee" + activityId);
                select.empty(); // Kosongkan select terlebih dahulu

                let options = [];

                if (response.length > 0) {
                    response.forEach(function(employee) {
                        let employeeIdStr = employee.id.toString(); // Konversi ID ke string

                        // Cek apakah ID employee sudah terpilih sebelumnya
                        let isSelected = selectedEmployeeIds.includes(employeeIdStr);

                        // Buat opsi untuk Select2
                        let newOption = new Option(employee.name, employeeIdStr, isSelected,
                            isSelected);
                        options.push(newOption);
                    });

                    // Tambahkan opsi ke dropdown setelah semua data diproses
                    select.append(options);
                } else {
                    select.append(new Option("Tidak ada data", ""));
                }

                // Periksa apakah opsi telah dimasukkan sebelum Select2 diperbarui
                console.log("Options in Select2 before triggering change:", select.html());

                // Tunggu hingga opsi selesai dimuat sebelum menyeleksi
                setTimeout(() => {
                    console.log("Triggering change on Select2...");
                    select.trigger("change");

                    // Hapus dan buat ulang Select2 untuk memastikan daftar opsi dimuat dengan benar
                    setTimeout(() => {
                        select.select2('destroy').select2({
                            allowClear: true,
                            width: "100%",
                            dropdownParent: $j("#addDetailActivityModal" +
                                activityId)
                        });
                    }, 500);
                }, 300);
            },
            error: function(xhr) {
                console.error("Error fetching employees:", xhr);
                alert("Gagal mengambil data pegawai.");
            }
        });
    }

    // Event listener ketika tanggal dipilih
    $j("#date{{ $activity->id }}").on("change", function() {
        var selectedDate = $j(this).val();
        if (selectedDate) {
            loadEmployees("{{ $activity->id }}", '', selectedDate,
                '',
            );
        }
    });

    // Inisialisasi Select2
    $j("#employee{{ $activity->id }}").select2({
        allowClear: true,
        width: "100%",
        dropdownParent: $j("#addDetailActivityModal{{ $activity->id }}")
    });

    $j('#addDetailActivityModal{{ $activity->id }}').on('click', '.btn-secondary', function(event) {
        event.preventDefault(); // Prevent default behavior which might close the modal

        // Manually clear each input field
        $j('#activityDetailId{{ $activity->id }}').val(''); // Clear the date field
        $j('#date{{ $activity->id }}').val(''); // Clear the date field
        $j('#location{{ $activity->id }}').val(''); // Clear the location field
        $j('#employee{{ $activity->id }}').val(null).trigger('change'); // Reset Select2 field

        // Optionally, if you have other types of inputs like textareas, radios, etc.
        $j('#addActivityForm').find('textarea').val('');
        $j('#addActivityForm').find('input[type="radio"], input[type="checkbox"]').prop('checked', false);
    });

    $j(document).ready(function() {
        $j('#addActivityForm{{ $activity->id }}').submit(function(event) {
            event.preventDefault(); // Prevent form submission to keep modal open

            var formData = new FormData(this);
            var url = $(this).attr('action');

            $j.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        alert("Data berhasil disimpan!");

                        // Simpan activity_id ke sessionStorage untuk membuka modal yang sesuai setelah reload
                        sessionStorage.setItem('activityId', '{{ $activity->id }}');

                        // Optionally, update the table dynamically instead of reloading
                        // location.reload(); // Optionally reload page
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    } else {
                        alert("Terjadi kesalahan. Coba lagi.");
                    }
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan saat mengirim data.');
                }
            });
        });

        // Open modal after page reload if activityId is set
        if (sessionStorage.getItem('activityId')) {
            var activityId = sessionStorage.getItem('activityId');

            // Show the modal again after page reload for the specific activity
            $('#addDetailActivityModal' + activityId).modal('show');

            // Clear the session storage flag
            sessionStorage.removeItem('activityId');
        }

        $j('#detailsTable{{ $activity->id }}').on('click', '.btn-edit', function() {
            var id = $j(this).data('id');
            var url = '{{ route('activity.getDetail', ':id') }}';
            url = url.replace(':id', id);

            console.log('Fetching details from:', url); // Debugging log

            $j.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    console.log('Fetched details:', response); // Debugging log

                    if (response) {
                        var formattedDate = new Date(response.date).toISOString().split(
                            'T')[0];

                        $j('#date{{ $activity->id }}').val(formattedDate);
                        $j('#location{{ $activity->id }}').val(response.location);
                        $j('#activityDetailId{{ $activity->id }}').val(response.id);

                        // Konversi employees ke array string jika masih berbentuk JSON string
                        var employeeIds = [];
                        if (typeof response.employees === 'string') {
                            try {
                                employeeIds = JSON.parse(response.employees);
                            } catch (e) {
                                console.error('Error parsing employee IDs JSON:', e);
                            }
                        } else if (Array.isArray(response.employees)) {
                            employeeIds = response.employees;
                        } else {
                            console.error('Unexpected format for employee IDs:', response
                                .employees);
                        }

                        // Pastikan setiap ID dalam format string agar cocok dengan Select2
                        employeeIds = employeeIds.map(id => id.toString());

                        console.log("Selected Employees (Converted to String):",
                            employeeIds); // Debugging log

                        // Load employees dari API berdasarkan tanggal, lalu pilih sesuai yang tersimpan
                        loadEmployees("{{ $activity->id }}", id, formattedDate,
                            employeeIds,
                        );
                    } else {
                        console.error('Response is empty or incorrect');
                        alert('Tidak ada data yang ditemukan.');
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching details:', xhr);
                    alert('Terjadi kesalahan saat memuat data item.');
                }
            });

        });


        $j('#detailsTable{{ $activity->id }}').on('click', '.btn-delete-modal', function() {
            var id = $j(this).data('id');
            var action = '{{ route('activity.deleteDetail', ':id') }}';
            action = action.replace(':id', id);

            if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                // Jika konfirmasi hapus diklik, kirimkan permintaan DELETE
                $j.ajax({
                    url: action,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert('Item telah dihapus.');
                        // Reload halaman atau lakukan tindakan lain setelah sukses
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan saat menghapus item.');
                    }
                });
            }
        });
    });
</script>
<style>
   .modal-backdrop {
     background-color: rgba(0, 0, 0, 0.05) !important;
 }
    .modal-title {
        white-space: normal;
        word-wrap: break-word;
        font-size: 1.25rem;
        /* Ukuran font dapat disesuaikan */
    }

    .select2-container {
        z-index: 1060 !important;
        /* Pastikan lebih tinggi dari modal */
    }

    .select2-dropdown {
        z-index: 1061 !important;
        /* Lebih tinggi dari container */
    }

    .select2-container .select2-selection--single {
        z-index: 1060 !important;
        /* Pastikan selection
                     juga memiliki z-index yang sesuai */
    }
</style>
</div>
</div>
</div>
