<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Monitoring Pelanggaran</title>
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
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            color: white;
        }
        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; color: #000; }
        .badge-danger { background-color: #dc3545; }
        .badge-info { background-color: #17a2b8; }
        .badge-secondary { background-color: #6c757d; }
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
        <h1>LAPORAN MONITORING PELANGGARAN</h1>
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
        @if($pelanggaranInfo)
            <p><strong>Jenis Pelanggaran:</strong> {{ $pelanggaranInfo->nama_pelanggaran }}</p>
            <p><strong>Tingkat:</strong> {{ $pelanggaranInfo->tingkat_pelanggaran }}</p>
            <p><strong>Poin:</strong> {{ $pelanggaranInfo->poin_pelanggaran }} poin</p>
        @endif
        <p><strong>Total Data:</strong> {{ $monitoringPelanggaran->count() }} record</p>
    </div>

    @if($monitoringPelanggaran->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 3%;">No</th>
                    <th style="width: 8%;">Tanggal</th>
                    <th style="width: 12%;">Nama Siswa</th>
                    <th style="width: 7%;">NISN</th>
                    <th style="width: 8%;">Kelas</th>
                    <th style="width: 10%;">Wali Kelas</th>
                    <th style="width: 15%;">Jenis Pelanggaran</th>
                    <th style="width: 8%;">Tingkat</th>
                    <th style="width: 6%;">Poin</th>
                    <th style="width: 10%;">Guru BK</th>
                    <th style="width: 8%;">Orang Tua</th>
                    <th style="width: 5%;">No. HP</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monitoringPelanggaran as $index => $mp)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $mp->created_at ? date('d/m/Y H:i', strtotime($mp->created_at)) : '-' }}</td>
                        <td>{{ $mp->siswa->nama_siswa ?? '-' }}</td>
                        <td>{{ $mp->siswa->nisn_siswa ?? '-' }}</td>
                        <td>{{ $mp->siswa->masterKelas->nama_kelas ?? '-' }}</td>
                        <td>{{ $mp->siswa->masterKelas->waliKelas->nama ?? '-' }}</td>
                        <td>{{ $mp->pelanggaran->nama_pelanggaran ?? '-' }}</td>
                        <td>
                            @php
                                $tingkatClass = '';
                                switch($mp->pelanggaran->tingkat_pelanggaran ?? '') {
                                    case 'Ringan':
                                        $tingkatClass = 'badge-success';
                                        break;
                                    case 'Sedang':
                                        $tingkatClass = 'badge-warning';
                                        break;
                                    case 'Berat':
                                        $tingkatClass = 'badge-danger';
                                        break;
                                    default:
                                        $tingkatClass = 'badge-secondary';
                                }
                            @endphp
                            <span class="badge {{ $tingkatClass }}">{{ $mp->pelanggaran->tingkat_pelanggaran ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $mp->pelanggaran->poin_pelanggaran ?? 0 }} poin</span>
                        </td>
                        <td>{{ $mp->guruBk->nama ?? '-' }}</td>
                        <td>{{ $mp->siswa->orangTuaWali->nama_ortu ?? '-' }}</td>
                        <td>{{ $mp->siswa->orangTuaWali->no_hp_ortu ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; font-style: italic;">Tidak ada data monitoring pelanggaran yang ditemukan.</p>
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