<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kegiatan</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
        }

        .container {
            position: relative;
            padding: 0 20px;
        }

        .top-text {
            display: flex;
            justify-content: space-between;
        }

        .top-text .left,
        .top-text .right {
            text-align: center;
            position: absolute;
        }

        .top-text .left {
            left: 0;
        }

        .top-text .right {
            right: 50px;
        }

        .header {
            position: relative;
            text-align: center;
            margin-top: 10px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header img {
            position: absolute;
            width: 100px;
            top: 0;
        }

        .header .logo-left {
            left: 0;
        }

        .header .logo-right {
            right: 0;
        }

        .header .title {
            margin: 0 auto;
            display: inline-block;
            text-align: center;
            padding-top: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 2px 0;
            font-size: 14px;
        }

        .table-container {
            margin-top: 20px;
        }

        .table-container .title {
            text-align: center;
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
            /* Prevent row breaks inside tables */
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        td {
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        }

        td:nth-child(5) {
            max-width: 300px;
            word-wrap: break-word;
        }

        th {
            background-color: #f2f2f2;
        }

        .signature-container {
            margin-top: 30px;
            text-align: center;
            page-break-before: always;
            /* Ensure the signature section starts on a new page */
        }

        .signature-container p {
            margin: 5px 0;
            font-size: 14px;
        }

        .signature-row {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            page-break-inside: avoid;
            /* Prevent signature row from breaking */
        }

        .signature-box {
            width: 300px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin: 20px auto;
            height: 1px;
        }

        table thead th {
            text-align: center;
            font-size: 0.875rem;
        }

        table tbody {
            font-size: 0.875rem;
        }

        .activity-container {
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        }

        /* Prevent rows from breaking inside pages */
        table tbody tr {
            page-break-inside: avoid;
        }

        /* Force a page break after each table */
        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <!-- Replace with actual image paths -->
            <img src="{{ $logoLeft }}" alt="Logo Kiri" class="logo-left">
            <div class="title">
                <h1>PEMERINTAH KOTA MAKASSAR</h1>
                <h1>DINAS KESEHATAN</h1>
                <h1>UPT Puskesmas Tamangapa</h1>
                <p>Jl.Tamangapa Raya No.264 Kode Pos : 90235 Makassar</p>
                <p>Telp.0411-494014 Call Center : 081245193468</p>
                <p>email: Pkmtamangapa@gmail.com https://puskesmastamangapa.or.id</p>
            </div>
            <img src="{{ $logoRight }}" alt="Logo Kanan" class="logo-right">
        </div>

        <div class="table-container" style="margin: 0px">
            <div class="title">
                <p>LAPORAN HASIL KEGIATAN</p>
            </div>
            <div class="activity-container">
                <p>Nama Kegiatan: {{ $data->activity->name }}</p>
                <p>Lokasi: {{ $data->location }}</p>
                <p>Hasil Kegiatan: {{ $data->proofActivity[0]->value ?? '' }}</p>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Umur</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $counter = 1;
                    @endphp
                    @php $counter = 1; @endphp

                    @if (
                        !empty($data->proofActivity) &&
                            isset($data->proofActivity[0]->patients) &&
                            count($data->proofActivity[0]->patients) > 0)
                        @foreach ($data->proofActivity[0]->patients as $item)
                            @php
                                $patient = \App\Models\Patients::with('genderName')->find($item->patient_id);
                                $dob = \Carbon\Carbon::parse($patient->dob); // Convert dob to Carbon instance
                                $age = $dob->age; // Get the age in years
                                $months = $dob->diffInMonths(now()) % 12; // Get the months difference, excluding years
                            @endphp

                            @if ($patient)
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $patient->name }}</td>
                                    <td>{{ optional($patient->genderName)->name ?? '-' }}</td>
                                    <td>{{ $age }} thn {{ $months }} bln</td>
                                    <td>{{ $item->description ?? '-' }}</td>
                                </tr>
                                @php

                                @endphp
                            @else
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td colspan="4">Data pasien tidak tersedia</td>
                                </tr>
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">Bukti Belum diupload</td>
                        </tr>
                    @endif

                </tbody>
            </table>
        </div>
        @php
            if ($data && $data->proofActivity && isset($data->proofActivity[0])) {
                $advice = \App\Models\AdviceActivityProof::where('user_id', Auth::user()->id)
                    ->where('activity_proof_id', $data->proofActivity[0]->id)
                    ->first();
            } else {
                // Jika $activity atau $activity->proofActivity null, set $advice sebagai null
                $advice = null;
            }
        @endphp
        <!-- Foto dan saran -->
        <div class="activity-container ">
            <p>Bukti Foto :</p>
            <img src="{{ $bukti }}" alt="Bukti Foto" style="max-width: 200px; height: auto;">
            <p>KETERANGAN/SARAN : {{ $advice->advice ?? '' }}</p>
        </div>

        <!-- Tanggal dan tanda tangan -->
        <table style="width: 100%; text-align: center; border:none">

            <tr>
                <td style="width: 60%;border:none;">
                    <br>
                    <p style="padding:0px;margin:0px;padding-left:50px">Mengetahui,</p>
                    <p style="padding:0px;margin:0px;">PPTK UPT Puskesmas Tamangapa</p>
                    <br><br><br><br>
                    <p style="padding-bottom: 0px;margin-bottom:0px"><strong>Ratna Puspita Sari, Amd.Keb</strong></p>
                    <hr style="margin-right:200px">
                    <p style="padding-top: 0px;margin-top:0px">NIP. 198801262020122002</p>
                </td>
                <td style="width: 40%;border:none;vertical-align:top">
                    <p style="padding: 0; margin: 0; padding-left: 30px;">
                        Makassar,
                        {{ \Carbon\Carbon::parse($data->proofActivity[0]->activity->date)->locale('id')->isoFormat('D MMMM YYYY') }}
                    </p>
                    <p style="padding:0px;margin-bottom:100px;padding-left:80px">Pelaksana</p>
                    <hr style="margin-right:50px">
                </td>
            </tr>
        </table>

    </div>
</body>

</html>
