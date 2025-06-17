@include('partials.header')
@include('partials.navbar')
@include('component.modal-add-employee')

<div class="mt-4">
    <div class="row">
        <div class="col-12" style="min-height: 100vh; overflow-x: hidden;">
            <!-- Button to Open Create User Details Modal -->
            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                data-bs-target="#createUserDetailsModal">
                <i class="fas fa-plus"></i> Tambah
            </button>
            <a type="button" href="" class="btn btn-warning btn-sm text-white font-weight-bold text-xs">
                Print
            </a>

            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Pegawai</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-4">
                        <table id="employee" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NIP
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama
                                        Pegawai</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Email
                                        Pegawai</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Telepon/WA</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Jenis Kelamin</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Agama</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">TTL
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Jabatan</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Pangkat</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Profesi</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Foto
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($users as $index => $user)
                                    @include('component.modal-edit-employee')
                                    <tr>
                                        <td class="align-middle text-center text-sm">
                                            <h6 class="mb-0 text-sm">{{ $index + 1 }}</h6>
                                            <!-- Displaying row number -->
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-xs font-weight-bold mb-0">{{ $user->detail->nip ?? '-' }}</p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ $user->detail->employee_name ?? '-' }}
                                            </p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ $user->email ?? '-' }}
                                            </p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ $user->detail->phone_wa ?? '-' }}</p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ $user->detail->genders->name ?? '-' }}</p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ $user->detail->religions->name ?? '-' }}</p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ $user->detail->place_of_birth ?? '-' }}
                                                / {{ $user->detail->date_of_birth ?? '-' }}</p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ $user->detail->positions->name ?? '-' }}</p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ $user->detail->ranks->name ?? '-' }}</p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ $user->detail->professions->name ?? '-' }}</p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            @if ($user->detail->photo)
                                                <img src="{{ asset('storage/' . $user->detail->photo) }}"
                                                    alt="Photo" class="img-thumbnail"
                                                    style="width: 100px; height: 100px; object-fit: cover;">
                                            @else
                                                <p class="text-xs font-weight-bold mb-0">No Photo</p>
                                            @endif
                                        </td>

                                        <td class="align-middle">
                                            <button type="button"
                                                class="mb-2 btn btn-primary btn-sm text-white font-weight-bold text-xs"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editEmployeeModal{{ $user->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button"
                                                class="btn btn-danger btn-sm text-white font-weight-bold d-flex align-items-center btn-delete"
                                                data-form-action="{{ route('employee.destroy', $user->id) }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
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
                        </script>,
                        made with <i class="fa fa-heart"></i> by
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
<script>
    $(document).ready(function() {
        const table = new DataTable("#employee", {
            language: {
                info: "_PAGE_ dari _PAGES_ halaman",
                paginate: {
                    previous: "<",
                    next: ">",
                    first: "<<",
                    last: ">>"
                }
            },
            responsive: true,
           
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-delete').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();

                var formAction = this.getAttribute('data-form-action');

                Swal.fire({
                    title: 'Konfirmasi Penghapusan',
                    text: 'Apakah Anda yakin ingin menghapus pegawai ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create and submit the form
                        console.log("Submitting form to:",
                            formAction); // Debugging Line
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
