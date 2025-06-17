<div class="modal fade" id="patientsActivityModal{{ $activity->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Daftar Pasien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
                <div class="mt-4">
                    <table id="patientTable{{ $activity->id }}" class="display small" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Tempat/Tanggal Lahir</th>
                                <th>JK</th>
                                <th>Telepon</th>
                                <th>Nikah</th>
                                <th>No.RM</th>
                                <th>Aksi</th>
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

<script>
    var $j = jQuery.noConflict();

    // $j('#patientTable{{ $activity->id }}').DataTable({
    //     "language": {
    //         "info": "_PAGE_ dari _PAGES_ halaman",
    //         "paginate": {
    //             "previous": "<",
    //             "next": ">",
    //             "first": "<<",
    //             "last": ">>"
    //         }
    //     },
    //     "responsive": true,
    //     "lengthMenu": [10, 25, 50, 100]
    // });
    document.addEventListener('DOMContentLoaded', function() {
        var patientsActivityModal = new bootstrap.Modal(document.getElementById(
            'patientsActivityModal{{ $activity->id }}'));
        var addPatientActivityModalElem = document.getElementById(
        'addPatientActivityModal{{ $activity->id }}');
        var patientsActivityModalElem = document.getElementById('patientsActivityModal{{ $activity->id }}');

        if (addPatientActivityModalElem && patientsActivityModalElem) {
            var addPatientActivityModal = new bootstrap.Modal(addPatientActivityModalElem);
            var patientsActivityModal = new bootstrap.Modal(patientsActivityModalElem);
        }

    });

    function calculateAge(dob) {
        var diff = Date.now() - dob.getTime();
        var ageDate = new Date(diff);
        return Math.abs(ageDate.getUTCFullYear() - 1970);
    }


    $j('#patientTable{{ $activity->id }} tbody').on('click', '.select-patient-btn', function() {
		var table = $j(this).closest('table').DataTable();
		var patient = table.row($j(this).closest('tr')).data();
        console.log(patient);

        var proofId = "{{ optional($activity->proofActivity->first())->id ?? '' }}";


        if (patient) {
            $j('#idPatient{{ $activity->id }}').val(patient.id);
            $j('#idProof{{ $activity->id }}').val(proofId);
            $j('#patientName{{ $activity->id }}').text(patient.name);
            $j('#patientAddress{{ $activity->id }}').text(patient.address);
            $j('#patientGender{{ $activity->id }}').text(patient.gender_name.name);

            var dob = new Date(patient.dob);
            var age = calculateAge(dob);
            $j('#patientAge{{ $activity->id }}').text(age + ' tahun');

            if (addPatientActivityModal) {
                addPatientActivityModal.show();
            }
        }
    });
</script>

<style>
    .modal-full {
        width: 60%;
        max-width: 60%;
        margin: 0;
        /* Modal tetap terpusat */
    }

    .modal-header .btn-close {
        background-color: #dc3545;
        /* Warna merah */
        border: none;
        padding: 5px 10px;
        color: white;
        font-size: 16px;
        font-weight: bold;
    }

    .modal-header .btn-close:hover {
        background-color: #c82333;
        /* Warna merah gelap saat di-hover */
    }
</style>
