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
        }

        .top-text {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
        }

        .top-text .left,
        .top-text .right {
            position: absolute;
        }

        .top-text .left {
            text-align: left;
            left: 0;
        }

        .top-text .right {
            text-align: right;
            right: 0;
        }

        .header {
            position: relative;
            text-align: center;
            margin-top: 60px;
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
            /* Menambahkan teks tebal */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            /* Membuat semua kolom memiliki lebar tetap */
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            word-wrap: break-word;
            /* Membungkus kata jika melebihi batas kolom */
            overflow-wrap: break-word;
            /* Pastikan kata yang panjang dibungkus */
        }

        th {
            background-color: #f2f2f2;
        }

        td {
            white-space: normal;
            /* Mengizinkan teks untuk berpindah baris */
        }

        .signature-container {
            margin-top: 30px;
            text-align: center;
        }

        .signature-container p {
            margin: 5px 0;
            font-size: 14px;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 300px;
            margin: 20px auto;
        }

        table thead th {
            text-align: center;
            font-size: 0.875rem;
            /* Adjust font size as needed */
        }

        tbody {
            page-break-inside: avoid;
            /* Menghindari pemutusan baris di dalam tbody */
        }


        table tbody {
            font-size: 0.875rem;
            /* Adjust font size as needed */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="top-text">
            <div class="left">Laporan Kegiatan</div>
            <div class="right">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}</div>
        </div>
        <table style="width: 100%; border-bottom: 2px solid black; margin-bottom: 10px;">
            <tr>
                <!-- Logo Kiri -->
                <td style="width: 20%; text-align: left;border:none">
                    <img src="{{ $logoLeft }}" alt="Logo Kiri" style="width: 100px;">
                </td>
        
                <!-- Judul di Tengah -->
                <td style="width: 60%; text-align: center;border:none">
                    <h2 style="margin: 5px 0;">PEMERINTAH KOTA MAKASSAR</h2>
                    <h2 style="margin: 5px 0;">DINAS KESEHATAN</h2>
                    <h3 style="margin: 5px 0;">UPT Puskesmas Tamangapa</h3>
                    <p style="margin: 2px 0;">Jl. Tamangapa Raya No.264 Kode Pos: 90235 Makassar</p>
                    <p style="margin: 2px 0;">Telp. 0411-494014 | Call Center: 081245193468</p>
                    <p style="margin: 2px 0;">
                        Email: <a href="mailto:pkmtamangapa@gmail.com">pkmtamangapa@gmail.com</a> | 
                        <a href="https://puskesmastamangapa.or.id" target="_blank">puskesmastamangapa.or.id</a>
                    </p>
                </td>
        
                <!-- Logo Kanan -->
                <td style="width: 20%; text-align: right;border:none">
                    <img src="{{ $logoRight }}" alt="Logo Kanan" style="width: 100px;">
                </td>
            </tr>
        </table>
        
        <div class="table-container">
            <div class="title">
                <p>KEGIATAN PEGAWAI</p>
                <p>BULAN {{ strtoupper($month) }} TAHUN {{ $year }}</p>
            </div>
            <table style="table-layout: fixed; width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Lokasi</th>
                        <th>Tanggal</th>
                        <th>NIP</th>
                        <th>Nama Pegawai</th>
                    </tr>
                </thead>
                
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($data as $item)
                        @php
                            $details = $item->details;
                            $detailCount = $details->count();
                        @endphp

                        @if ($detailCount > 0)
                            @foreach ($details as $index => $detail)
                                @if ($detail->employees)
                                    @php
                                        $employeeIds = json_decode($detail->employees, true);
                                        $employees = \App\Models\User::whereIn('id', $employeeIds)->get([
                                            'nip',
                                            'name',
                                        ]);
                                    @endphp

                                    @foreach ($employees as $employeeIndex => $employee)
                                        <tr>
                                            <!-- Lokasi dan Tanggal hanya tampil di baris pertama -->
                                            @if ($employeeIndex === 0)
                                                <td rowspan="{{ $employees->count() }}">
                                                    {{ $index === 0 ? $no : '' }}
                                                </td>
                                                <td rowspan="{{ $employees->count() }}">
                                                    {{ $index === 0 ? $item->name : '' }}
                                                </td>
                                                <td rowspan="{{ $employees->count() }}">
                                                    {{ $detail->location }}
                                                </td>
                                                <td rowspan="{{ $employees->count() }}">
                                                    {{ \Carbon\Carbon::parse($detail->date)->locale('id')->isoFormat('D MMMM YYYY') }}
                                                </td>
                                            @endif

                                            <!-- NIP dan Nama Pegawai ditampilkan di setiap baris -->
                                            <td>{{ $employee->nip }}</td>
                                            <td>{{ $employee->name }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td colspan="4">No employees assigned</td>
                                    </tr>
                                @endif
                            @endforeach
                            @php $no++; @endphp
                        @else
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td colspan="4">No details available</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>

            </table>

        </div>
        <div class="signature-container">
            <p>Makassar, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}</p>
            <p>Mengetahui,</p>
            <p>Kepala Puskesmas</p>
            <p style="margin-top: 100px">dr. Fatimah Radhi, M.Kes</p>
            <p>NIP.198511252011012009</p>
        </div>
    </div>
</body>

</html>
