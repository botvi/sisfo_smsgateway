<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Monitoring Absensi</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
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
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            color: white;
        }
        .status-hadir { background-color: #28a745; }
        .status-sakit { background-color: #ffc107; color: #000; }
        .status-izin { background-color: #17a2b8; }
        .status-alpha { background-color: #dc3545; }
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
        <h1>DATA ABSENSI SISWA/I</h1>
        <h1>SMP NEGERI 2 INUMAN</h1>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="info-section">
        @if($kelasInfo)
            <p><strong>Kelas:</strong> {{ $kelasInfo->nama_kelas }}</p>
            <p><strong>Wali Kelas:</strong> {{ $kelasInfo->waliKelas->nama ?? 'Belum ada wali kelas' }}</p>
        @else
            <p><strong>Filter:</strong> Semua Kelas</p>
        @endif
        @if($selectedStatus)
            <p><strong>Status:</strong> {{ ucfirst($selectedStatus) }}</p>
        @endif
        @if($tanggalAwal && $tanggalAkhir)
            <p><strong>Periode:</strong> {{ date('d/m/Y', strtotime($tanggalAwal)) }} - {{ date('d/m/Y', strtotime($tanggalAkhir)) }}</p>
        @endif
        <p><strong>Total Data:</strong> {{ $absensi->count() }} record</p>
    </div>

    @if($absensi->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 3%;">No</th>
                    <th style="width: 10%;">Tanggal</th>
                    <th style="width: 15%;">Nama Siswa</th>
                    <th style="width: 8%;">NISN</th>
                    <th style="width: 8%;">Kelas</th>
                    <th style="width: 12%;">Wali Kelas</th>
                    <th style="width: 8%;">Status</th>
                    <th style="width: 20%;">Pesan</th>
                    <th style="width: 12%;">Orang Tua</th>
                    <th style="width: 14%;">No. HP</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absensi as $index => $a)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $a->tanggal_pengiriman ? date('d/m/Y H:i', strtotime($a->tanggal_pengiriman)) : '-' }}</td>
                        <td>{{ $a->siswa->nama_siswa ?? '-' }}</td>
                        <td>{{ $a->siswa->nisn_siswa ?? '-' }}</td>
                        <td>{{ $a->siswa->masterKelas->nama_kelas ?? '-' }}</td>
                        <td>{{ $a->waliKelas->nama ?? '-' }}</td>
                        <td>
                            @php
                                $statusClass = '';
                                $statusText = '';
                                switch($a->status) {
                                    case 'hadir':
                                        $statusClass = 'status-hadir';
                                        $statusText = 'Hadir';
                                        break;
                                    case 'sakit':
                                        $statusClass = 'status-sakit';
                                        $statusText = 'Sakit';
                                        break;
                                    case 'izin':
                                        $statusClass = 'status-izin';
                                        $statusText = 'Izin';
                                        break;
                                    case 'alpha':
                                        $statusClass = 'status-alpha';
                                        $statusText = 'Alpha';
                                        break;
                                    default:
                                        $statusClass = 'status-alpha';
                                        $statusText = $a->status ?? '-';
                                }
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                        </td>
                        <td>{{ $a->pesan ?? '-' }}</td>
                        <td>{{ $a->siswa->orangTuaWali->nama_ortu ?? '-' }}</td>
                        <td>{{ $a->siswa->orangTuaWali->no_hp_ortu ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; font-style: italic;">Tidak ada data monitoring absensi yang ditemukan.</p>
    @endif

    <div class="footer">
        <p>Inuman, {{ date('d/m/Y H:i:s') }}</p>
        <br>
        <br>
        <p>{{ $kelasInfo->waliKelas->nama ?? 'Wali Kelas' }}</p>
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