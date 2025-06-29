<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Daftar Siswa dan Orang Tua</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        .info-section {
            margin-bottom: 15px;
        }
        .info-section p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .footer p {
            margin: 5px 0;
        }
        @media print {
            body {
                margin: 0;
                padding: 10px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DAFTAR SISWA DAN ORANG TUA</h1>
        <p>Sistem Monitoring Siswa</p>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="info-section">
        @if($kelasInfo)
            <p><strong>Kelas:</strong> {{ $kelasInfo->nama_kelas }}</p>
            <p><strong>Wali Kelas:</strong> {{ $kelasInfo->waliKelas->nama ?? 'Belum ada wali kelas' }}</p>
        @else
            <p><strong>Filter:</strong> Semua Kelas</p>
        @endif
        <p><strong>Total Data:</strong> {{ $siswa->count() }} siswa</p>
    </div>

    @if($siswa->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 3%;">No</th>
                    <th style="width: 8%;">NISN</th>
                    <th style="width: 15%;">Nama Siswa</th>
                    <th style="width: 8%;">Tgl Lahir</th>
                    <th style="width: 15%;">Alamat Siswa</th>
                    <th style="width: 8%;">Kelas</th>
                    <th style="width: 12%;">Wali Kelas</th>
                    <th style="width: 12%;">Nama Orang Tua</th>
                    <th style="width: 10%;">No. HP</th>
                    <th style="width: 9%;">Alamat Orang Tua</th>
                </tr>
            </thead>
            <tbody>
                @foreach($siswa as $index => $s)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $s->nisn_siswa }}</td>
                        <td>{{ $s->nama_siswa }}</td>
                        <td>{{ $s->tanggal_lahir_siswa ? date('d/m/Y', strtotime($s->tanggal_lahir_siswa)) : '-' }}</td>
                        <td>{{ $s->alamat_siswa }}</td>
                        <td>{{ $s->masterKelas->nama_kelas ?? '-' }}</td>
                        <td>{{ $s->masterKelas->waliKelas->nama ?? '-' }}</td>
                        <td>{{ $s->orangTuaWali->nama_ortu ?? '-' }}</td>
                        <td>{{ $s->orangTuaWali->no_hp_ortu ?? '-' }}</td>
                        <td>{{ $s->orangTuaWali->alamat_ortu ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; font-style: italic;">Tidak ada data siswa yang ditemukan.</p>
    @endif

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
        <p>Oleh: {{ auth()->user()->nama ?? 'Sistem' }}</p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Cetak Laporan
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Tutup
        </button>
    </div>
</body>
</html>