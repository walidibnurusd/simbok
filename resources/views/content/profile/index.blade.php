@include('partials.header')
@include('partials.navbar')


<div class="container mt-4">
    <div class="row">
        <div class="col-12" style="min-height: 100vh; overflow-x: hidden;">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Profile</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-4">
                        <!-- Profile Information -->
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img src="{{ asset('assets/img/avatar.png') }}" alt="Profile Picture"
                                    class="profile-picture mb-3">
                                <h5>{{ $user->name }}</h5>
                                <p class="text-muted">{{ $user->email }}</p>
                            </div>
                            <div class="col-md-8">
                                <h5>Informasi Pengguna</h5>
                                <table class="table">
                                    <tbody>
                                        @if (!$user->role == 'admin')
                                            <tr>
                                                <th scope="row">NIP</th>
                                                <td>{{ $user->detail->nip }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th scope="row">Nama Pengguna</th>
                                            <td>{{ $user->name }}</td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Email</th>
                                            <td>{{ $user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Phone</th>
                                            <td>{{ $user->no_hp }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Address</th>
                                            <td>{{ $user->address }}</td>
                                        </tr>
                                        <tr></tr>
                                    </tbody>
                                </table>
                                <button class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editProfileModal">Edit Profile</button>
                                <button class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#changePasswordModal">Ganti Password</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('component.modal-edit-profile')
@include('component.modal-change-password')

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
    $(document).ready(function() {
        $('#example').DataTable({
            "pagingType": "full_numbers", // You can change this to suit your needs
            "responsive": true,
            "lengthMenu": [10, 25, 50, 100] // Set the number of rows per page
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
