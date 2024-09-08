<!DOCTYPE html>
<html>
<head>
    <title>Jadwal Siaran Radio Hari Ini</title>
    <style>
        /* Bootstrap-like styling */
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 14px;
            color: #333;
        }

        h2 {
            font-size: 24px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #dee2e6;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <h2>Jadwal Siaran Radio Hari Ini</h2>
    
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nama Siaran</th>
                <th>Deskripsi</th>
                <th>Tanggal</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $broadcast)
            <tr>
                <td>{{ $broadcast->broadcast_name }}</td>
                <td>{{ $broadcast->description }}</td>
                <td>{{ \Carbon\Carbon::parse($broadcast->date)->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($broadcast->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($broadcast->end_time)->format('H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- <h3>Detail Persetujuan</h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Peran</th>
                <th>Jumlah Disetujui</th>
                <th>Total Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $broadcast)
            <tr>
                <td>Koordinator Siaran</td>
                <td>{{ $broadcast->koordinator_siaran_approved_count }}</td>
                <td>{{ $broadcast->koordinator_siaran_total_count }}</td>
            </tr>
            <tr>
                <td>Kepala Bidang Siaran</td>
                <td>{{ $broadcast->kabid_approved_count }}</td>
                <td>{{ $broadcast->kabid_total_count }}</td>
            </tr>
            <tr>
                <td>Kepala Stasiun</td>
                <td>{{ $broadcast->kepala_siaran_approved_count }}</td>
                <td>{{ $broadcast->kepala_siaran_total_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table> -->
</body>
</html>
