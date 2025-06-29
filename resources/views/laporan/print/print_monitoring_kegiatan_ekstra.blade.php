<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Monitoring Kegiatan Ekstrakurikuler</title>
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
        .filter-info {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
        }
        .filter-info p {
            margin: 2px 0;
            font-size: 11px;
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
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .kegiatan-content {
            max-width: 300px;
            word-wrap: break-word;
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
        <h1>DATA KEGIATAN EKSTRAKURIKULER SISWA/I</h1>
        <h1>SMP NEGERI 2 INUMAN</h1>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
    </div>

    <!-- Informasi Filter -->
    @if($ketuaExtraInfo || $tanggalAwal || $tanggalAkhir)
        <div class="filter-info">
            <p><strong>Filter yang diterapkan:</strong></p>
            @if($ketuaExtraInfo)
                <p>• Ketua Ekstrakurikuler: {{ $ketuaExtraInfo->nama }}</p>
            @endif
            @if($tanggalAwal)
                <p>• Tanggal Awal: {{ date('d/m/Y', strtotime($tanggalAwal)) }}</p>
            @endif
            @if($tanggalAkhir)
                <p>• Tanggal Akhir: {{ date('d/m/Y', strtotime($tanggalAkhir)) }}</p>
            @endif
        </div>
    @endif

    <p><strong>Total Data:</strong> {{ $kegiatan->count() }} kegiatan</p>

    @if($kegiatan->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Tanggal</th>
                    <th style="width: 60%;">Kegiatan</th>
                    <th style="width: 20%;">Ketua Ekstrakurikuler</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kegiatan as $index => $k)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $k->created_at ? date('d/m/Y H:i', strtotime($k->created_at)) : '-' }}</td>
                        <td class="kegiatan-content">
                            <a href="{{ route('monitoring-ekstra.detail', $k->id) }}">{{ route('monitoring-ekstra.detail', $k->id) }}</a>
                        </td>
                        <td>{{ $k->ketuaExtra->nama ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; font-style: italic;">Tidak ada data kegiatan ekstrakurikuler yang ditemukan.</p>
    @endif

    <div class="footer">
        <p>Inuman, {{ date('d/m/Y H:i:s') }}</p>
        <br>
        <br>
        <p>{{ $k->ketuaExtra->nama ?? 'Ketua Ekstrakurikuler' }}</p>
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