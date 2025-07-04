@php
    $master = new App\Http\Controllers\DependentDropDownController();
    $provinces = $master->provinces();
    $cities = [];
    $marritals = $master->marritalStatusData();
    $occupations = $master->occupationData();
    $educations = $master->educationData();
    $genders = $master->genderData();
    $defaultProvinceId = $provinces->firstWhere('name', 'SULAWESI SELATAN')->id; // Assuming this gets the correct ID
    $cities = $master->citiesData($defaultProvinceId);
    $defaultCityId = $cities->firstWhere('name', 'KOTA MAKASSAR')->id;
    $districts = $master->districtsData($defaultCityId);
    $defaultDistrictId = $districts->firstWhere('name', 'MANGGALA')->id;
    $villages = $master->villagesData($defaultDistrictId);
@endphp

<div class="modal fade" style="z-index: 9999;" id="editPatientModal{{ $row->id }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Using modal-lg for a moderate width -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data</h5> <!-- Updated the title -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('patient.update', $row->id) }}" method="POST" class="px-3"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row g-2"> <!-- Reduced gutter space between columns -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nik">NIK</label>
                                <input type="text" class="form-control" id="nik" name="nik"
                                    placeholder="NIK" value="{{ old('nik', $row->nik) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nama Pasien</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Nama Pasien" value="{{ old('name', $row->name) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Telpon/WA</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    placeholder="Telpon/WA" value="{{ old('phone', $row->phone) }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="marriage_status">Status Menikah</label>
                                <select class="form-control" id="marriage_status" name="marriage_status" required>
                                    <option value="">Pilih</option>
                                    @foreach ($marritals as $marrital)
                                        <option value="{{ $marrital->id }}"
                                            {{ old('marriage_status', $row->marrital_status) == $marrital->id ? 'selected' : '' }}>
                                            {{ $marrital->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="blood_type">Golongan Darah</label>
                                <select class="form-control" id="blood_type" name="blood_type" required>
                                    <option value="">Pilih</option>
                                    <option value="A"
                                        {{ old('blood_type', $row->blood_type) == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B"
                                        {{ old('blood_type', $row->blood_type) == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="AB"
                                        {{ old('blood_type', $row->blood_type) == 'AB' ? 'selected' : '' }}>AB</option>
                                    <option value="O"
                                        {{ old('blood_type', $row->blood_type) == 'O' ? 'selected' : '' }}>O</option>
                                    <option value="Tidak Diketahui"
                                        {{ old('blood_type', $row->blood_type) == 'Tidak Diketahui' ? 'selected' : '' }}>
                                        Tidak Diketahui</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="education">Pendidikan</label>
                                <select class="form-control" id="education" name="education" required>
                                    <option value="">Pilih</option>
                                    @foreach ($educations as $education)
                                        <option value="{{ $education->id }}"
                                            {{ old('education', $row->education) == $education->id ? 'selected' : '' }}>
                                            {{ $education->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="occupation">Pekerjaan</label>
                                <select class="form-control" id="occupation" name="occupation" required>
                                    <option value="">Pilih</option>
                                    @foreach ($occupations as $occupation)
                                        <option value="{{ $occupation->id }}"
                                            {{ old('occupation', $row->occupation) == $occupation->id ? 'selected' : '' }}>
                                            {{ $occupation->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gender">Jenis Kelamin</label>
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="">Pilih</option>
                                    @foreach ($genders as $gender)
                                        <option value="{{ $gender->id }}"
                                            {{ old('gender', $row->gender) == $gender->id ? 'selected' : '' }}>
                                            {{ $gender->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="place_birth">Tempat Lahir</label>
                                <input type="text" class="form-control" id="place_birth" name="place_birth"
                                    placeholder="Tempat lahir" value="{{ old('place_birth', $row->place_birth) }}"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dob">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="dob" name="dob"
                                    value="{{ old('dob', $row->dob) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="province">Provinsi Asal</label>
                                <select class="form-control" id="province" name="province" required>
                                    <option value=""></option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}"
                                            {{ old('province', $row->indonesia_province_id) == $province->id ? 'selected' : '' }}>
                                            {{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city">Kabupaten/Kota</label>
                                <select class="form-control" id="city" name="city" required>
                                    <option value=""></option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ old('city', $row->indonesia_city_id) == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="district">Kecamatan</label>
                                <select class="form-control" id="district" name="district" required>
                                    <option value=""></option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->id }}"
                                            {{ old('district', $row->indonesia_district_id) == $district->id ? 'selected' : '' }}>
                                            {{ $district->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="village">Kelurahan/Desa</label>
                                <select class="form-control" id="village" name="village" required>
                                    <option value="">Pilih</option>
                                    @foreach ($villages as $village)
                                        <option value="{{ $village->id }}"
                                            {{ old('village', $row->indonesia_village_id) == $village->id ? 'selected' : '' }}>
                                            {{ $village->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="rw">RW</label>
                                <select class="form-control" id="rw" name="rw" required>
                                    <option value="">Pilih</option>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}"
                                            {{ old('rw', $row->rw) == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="address">Alamat/Jalan</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    placeholder="Jalan" value="{{ old('address', $row->address) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="no_rm">NOMOR RM</label>
                                <input type="text" class="form-control" id="no_rm" name="no_rm"
                                    placeholder="Nomor RM" value="{{ old('no_rm', $row->no_rm) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Data</button>

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#province').change(function() {
            var provinceId = $(this).val();
            var citySelect = $('#city');

            if (provinceId) {
                $.ajax({
                    url: "{{ url('/cities') }}/" + provinceId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        citySelect.empty();
                        citySelect.append('<option value="">Pilih</option>');
                        $.each(data, function(key, value) {
                            citySelect.append('<option value="' + value.id + '">' +
                                value.name + '</option>');
                        });
                        $('#district').empty().append('<option value="">Pilih</option>');
                        $('#village').empty().append('<option value="">Pilih</option>');
                    },
                    error: function() {
                        alert('Gagal mengambil data kota/kabupaten');
                    }
                });
            } else {
                citySelect.empty();
                citySelect.append('<option value="">Pilih</option>');
            }
        });

        $('#city').change(function() {
            var cityId = $(this).val();
            var districtSelect = $('#district');

            if (cityId) {
                $.ajax({
                    url: "{{ url('/districts') }}/" + cityId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        districtSelect.empty();
                        districtSelect.append('<option value="">Pilih</option>');
                        $.each(data, function(key, value) {
                            districtSelect.append('<option value="' + value.id +
                                '">' + value.name + '</option>');
                        });
                        $('#village').empty().append('<option value="">Pilih</option>');
                    },
                    error: function() {
                        alert('Gagal mengambil data kecamatan');
                    }
                });
            } else {
                districtSelect.empty();
                districtSelect.append('<option value="">Pilih</option>');
            }
        });

        $('#district').change(function() {
            var districtId = $(this).val();
            var villageSelect = $('#village');

            if (districtId) {
                $.ajax({
                    url: "{{ url('/villages') }}/" + districtId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        villageSelect.empty();
                        villageSelect.append('<option value="">Pilih</option>');
                        $.each(data, function(key, value) {
                            villageSelect.append('<option value="' + value.id +
                                '">' + value.name + '</option>');
                        });
                    },
                    error: function() {
                        alert('Gagal mengambil data kelurahan/desa');
                    }
                });
            } else {
                villageSelect.empty();
                villageSelect.append('<option value="">Pilih</option>');
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
