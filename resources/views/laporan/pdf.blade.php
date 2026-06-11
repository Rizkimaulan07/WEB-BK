<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Kasus</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 12px; }
        h1 { text-align: center; margin-bottom: 5px; }
        .subtitle { text-align: center; font-size: 10px; color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; }
        .footer { text-align: center; margin-top: 20px; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <h1>LAPORAN KASUS SISWA</h1>
    <div class="subtitle">Sistem Bimbingan & Konseling</div>
    <div class="subtitle">Tanggal cetak: {{ date('d/m/Y H:i:s') }} | Total Kasus: {{ $totalKasus }}</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Deskripsi</th>
                <th>Guru BK</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kasus as $k)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $k->tanggal_kejadian->format('d/m/Y') }}</td>
                <td>{{ $k->siswa->nis }}</td>
                <td>{{ $k->siswa->nama }}</td>
                <td>{{ $k->siswa->kelas }}</td>
                <td>{{ $k->kategori->nama }}</td>
                <td>{{ $k->status }}</td>
                <td>{{ $k->deskripsi }}</td>
                <td>{{ $k->guruBK->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak oleh: {{ auth()->user()->name }} | {{ now()->format('d F Y H:i:s') }}
    </div>
</body>
</html>