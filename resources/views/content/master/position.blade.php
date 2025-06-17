@include('partials.header')
@include('partials.navbar')

<div class="mt-4">
    <div class="row">
        <div class="col-12" style="min-height: 100vh; overflow-x: hidden;">
            <div class="button-container">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPositionModal">
                    Tambah
                    <i class="fas fa-plus ms-2"></i> <!-- Icon with margin to the left -->
                </button>


            </div>
            <div class="col-12" style="min-height: 100vh; overflow-x: hidden;">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Jabatan</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-4">
                            <table id="position" class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            No
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Nama</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Aksi</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($positions as $index => $position)
                                        @include('component.modal-edit-position')

                                        <tr>
                                            <td>
                                                <h6 class="mb-0 text-sm">{{ $index + 1 }}</h6>
                                            </td>
                                            <td style="white-space: normal; word-wrap: break-word;">
                                                <p class="text-xs font-weight-bold mb-0">{{ $position->name }}</p>
                                            </td>

                                            <td>
                                                <div class="d-flex justify-content-start">
                                                    <div class="action-buttons">
                                                        <button type="button"
                                                            class="btn btn-primary btn-sm text-white font-weight-bold text-xs edit-position"
                                                            data-bs-toggle="modal" data-bs-target="#editPositionModal"
                                                            data-id="{{ $position->id }}"
                                                            data-name="{{ $position->name }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm text-white font-weight-bold d-flex align-items-center btn-delete"
                                                            data-form-action="{{ route('master.delete-position', $position->id) }}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </div>
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
                            <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Dignity
                                Digital Space</a>

                        </div>
                    </div>

                </div>
            </div>
        </footer>
    </div>
    @include('component.modal-add-position')
    @include('partials.footer')

    <script>
        $(document).ready(function() {
            $('#position').DataTable({
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


            $('.edit-position').on('click', function() {
                var button = $(this);
                var modalTarget = button.data('bs-target'); // Menentukan modal mana yang akan digunakan

                var modal = $(modalTarget);
                var positionId = button.data('id');
                var formAction = "{{ route('master.update-position', ':id') }}";
                formAction = formAction.replace(':id', positionId);

                modal.find('form').attr('action', formAction); // Perbarui action pada form
                modal.find('#name').val(button.data('name'));

                modal.modal({
                    backdrop: 'static',
                    keyboard: false
                });

                // Pastikan background modal tetap transparan atau putih
                modal.find('.modal-content').css('background-color', 'white');

                modal.find('#num_days_' + positionId).val(button.data('num_days'));
            });


        });

        document.addEventListener('DOMContentLoaded', function() {
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
