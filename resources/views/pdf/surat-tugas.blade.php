<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Tugas</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
        }

        .container {
            position: relative;
            padding: 0 20px;
            /* Padding to ensure content doesn't touch edges */
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
            text-align: center;
            left: 0;
        }

        .top-text .right {
            text-align: center;
            right: 100;
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
        }

        table {
            width: 100%;
            border-collapse: collapse;
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
            /* Kolom keterangan */
            max-width: 300px;
            /* Tentukan lebar maksimum kolom */
            word-wrap: break-word;
            overflow-wrap: break-word;
        }


        th {
            background-color: #f2f2f2;
        }

        .signature-container {
            margin-top: 30px;
            text-align: center;
        }

        .signature-container p {
            margin: 5px 0;
            font-size: 14px;
        }

        .signature-row {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
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
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="data:image/png;base64,{{ $logoLeft }}" alt="Logo Kiri" class="logo-left">
            <div class="title">
                <h1>PEMERINTAH KOTA MAKASSAR</h1>
                <h1>DINAS KESEHATAN</h1>
                <h1>UPT Puskesmas Tamangapa</h1>
                <p>Jl.Tamangapa Raya No.264 Kode Pos : 90235 Makassar</p>
                <p>Telp.0411-494014 Call Center : 081245193468</p>
                <p>email: Pkmtamangapa@gmail.com https://puskesmastamangapa.or.id</p>
            </div>
            <img src="data:image/png;base64,{{ $logoRight }}" alt="Logo Kanan" class="logo-right">
        </div>
        <div class="table-container">
            <div class="title">
                <p>SURAT TUGAS</p>
                <p>Nomor:
                    <span style="visibility: hidden">445/3688/ST-BOK/PKM-TMP/X1/2024</span>
                </p>

            </div>
            <div>
                <p>Bertanda Tangan dibawah ini:</p>
                <p>Nama : dr. Fatimah Radhi, M.Kes</p>
                <p>NIP : 198511252011012009 </p>
                <p>Jabatan : Kepala UPT Puskesmas Tamangapa</p>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Pangkat/Gol</th>
                        <th>Jabatan</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $counter = 1; // Initialize counter for numbering rows
                    @endphp

                    {{-- @foreach ($data->proofActivity[0]->patients as $item)
                        @php
                            $patient = \App\Models\Patients::with('genderName')
                                ->where('id', $item->patient_id)
                                ->first();
                        @endphp --}}
                    <tr>
                        <td>{{ $counter++ }}</td> <!-- Display row number and increment counter -->
                        <td>{{ Auth::user()->name }}</td> <!-- Display patient name -->
                        <td>{{ Auth::user()->nip }}</td> <!-- Display patient gender -->
                        <td>{{ Auth::user()->detail->groups->name ?? '' }}</td> <!-- Display patient age -->
                        <td>{{ Auth::user()->detail->positions->name ?? '' }}</td>
                        <!-- Display other patient fields as needed -->
                    </tr>
                    {{-- @endforeach --}}


                </tbody>
            </table>
        </div>
        <div>
            @php
                use App\Models\ActivityDetail;
                use Illuminate\Support\Facades\Auth;
                use Carbon\Carbon;

                // Get the logged-in user ID
                $userId = Auth::id();

                // Fetch ActivityDetails where the activity_id matches and the user ID is in the 'employees' column
                $ads = ActivityDetail::where('activity_id', $data->activity->id)
                    ->whereJsonContains('employees', (string) $userId) // Assuming employees is a JSON or array field
                    ->get();

                // Group by month and year
                $groupedByMonth = $ads->groupBy(fn($ad) => Carbon::parse($ad['date'])->format('m-Y'));

                // Extract and count all unique days, now using Carbon objects for correct sorting
                $allDates = $ads
                    ->pluck('date')
                    ->map(fn($date) => Carbon::parse($date)) // Convert each date to a Carbon instance
                    ->unique()
                    ->sort(); // Sort the dates to ensure the smallest date comes first

                // Get the total number of unique days
                $totalDays = $allDates->count();

                // Format the grouped dates as required
                $formattedDates = $groupedByMonth
                    ->map(function ($dates, $monthYear) {
                        $datesInMonth = $dates
                            ->pluck('date')
                            ->map(fn($date) => Carbon::parse($date)->locale('id')->format('d')) // Extract only the day
                            ->join(', ');
                        return $datesInMonth .
                            ' ' .
                            Carbon::parse($dates->first()['date'])
                                ->locale('id')
                                ->isoFormat('MMMM YYYY');
                    })
                    ->join('; ');

                // Get the first date (the smallest date) in the format you requested (e.g., 23, 25 Januari 2025)
                $firstDate = $allDates->isNotEmpty() ? $allDates->first()->format('d-m-Y') : null;

                // Format the days as you want (e.g., 23, 25 Januari 2025)
                $formattedDays =
                    $allDates
                        ->map(function ($date) {
                            return Carbon::parse($date)->locale('id')->format('d'); // Only extract the day part
                        })
                        ->join(', ') .
                    ' ' .
                    Carbon::parse($firstDate)->locale('id')->isoFormat('MMMM YYYY');
            @endphp

            <p>Melaksanakan Kegiatan : {{ $data->activity->name }} Selama {{ $totalDays }} Hari</p>

            <p>
                Tanggal:
                {{ $formattedDays }}
            </p>
            <p>
                Lokasi:
                {{ $ads->pluck('location')->join(', ') }}
            </p>

            <p>Demikian Surat Tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab </p>
        </div>
        <div class="container">

            <div class="top-text">
                <div class="right">
                    <p>Dikeluarkan di : Makassar</p>
                    <p>Pada Tanggal :{{ \Carbon\Carbon::parse($firstDate)->format('d-m-Y') }}</p>

                </div>
            </div>
        </div>
        <div class="signature-container">


        </div>
        <div class="container">

            <div class="top-text">
                <div class="left">
                </div>
                <div class="right" style="margin-top:30px">
                    <p style="margin-bottom: 30px">Kepala Puskesmas</p>
                    <br>
                    <p style="padding: 0;margin:0">dr. Fatimah Radhi, M.Kes</p>
                    <p style="padding: 0;margin:0">198511252011012009</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
