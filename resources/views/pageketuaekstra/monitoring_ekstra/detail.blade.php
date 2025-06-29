<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kegiatan Ekstrakurikuler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', serif;
            line-height: 1.6;
            background-color: #f8f9fa;
        }
        .surat-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .kop-surat {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        .kop-surat::before {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 5px;
        }
      
        .nama-sekolah {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .alamat-sekolah {
            font-size: 14px;
            opacity: 0.9;
        }
        .nomor-surat {
            background: #f8f9fa;
            padding: 15px 30px;
            border-bottom: 2px solid #dee2e6;
        }
        .isi-surat {
            padding: 30px;
            min-height: 400px;
        }
        .tanggal-surat {
            text-align: right;
            margin-bottom: 20px;
        }
        .perihal {
            font-weight: bold;
            margin-bottom: 20px;
        }
        .isi-kegiatan {
            text-align: justify;
            margin-bottom: 30px;
        }
        .ttd-section {
            text-align: right;
            margin-top: 50px;
        }
        .nama-ttd {
            font-weight: bold;
            margin-top: 80px;
        }
        .jabatan-ttd {
            font-size: 14px;
            color: #6c757d;
        }
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        @media print {
            .print-btn {
                display: none;
            }
            body {
                background: white;
            }
            .surat-container {
                box-shadow: none;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="print-btn">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="bi bi-printer"></i> Cetak Surat
        </button>
    </div>

    <div class="surat-container">
        <!-- Kop Surat -->
        <div class="kop-surat">
            <div class="nama-sekolah">SEKOLAH MENENGAH PERTAMA</div>
            <div class="nama-sekolah">SMP N 2 INUMAN</div>
            <div class="alamat-sekolah">
                Pulau Busuk Jaya, Kec. Inuman, Kabupaten Kuantan Singingi, Riau 29561
            </div>
        </div>

        <!-- Nomor Surat -->
        <div class="nomor-surat">
            <div class="row">
                <div class="col-md-6 text-end">
                    <strong>Tanggal:</strong> {{ $monitoringEkstra ? $monitoringEkstra->created_at->format('d F Y') : date('d F Y') }}
                </div>
            </div>
        </div>

        <!-- Isi Surat -->
        <div class="isi-surat">
            <div class="perihal">
                <strong>Perihal:</strong> Informasi Kegiatan Ekstrakurikuler
            </div>

            <div class="isi-kegiatan">
                <p>Kepada Yth.<br>
                Bapak/Ibu Orang Tua/Wali Murid<br>
                <strong>di Tempat</strong></p>

                <p>Dengan hormat,</p>

                <p>Sehubungan dengan program pengembangan bakat dan minat siswa melalui kegiatan ekstrakurikuler, kami bermaksud menginformasikan kegiatan ekstrakurikuler yang akan dilaksanakan sebagai berikut:</p>

                @if($monitoringEkstra)
                    <div class="alert alert-info">
                        {!! $monitoringEkstra->kegiatan !!}
                    </div>
                @else
                    <div class="alert alert-warning">
                        <strong>Informasi Kegiatan:</strong><br>
                        Detail kegiatan ekstrakurikuler akan diinformasikan lebih lanjut.
                    </div>
                @endif

                <p>Demikian informasi ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>
            </div>

            <!-- Tanda Tangan -->
            <div class="ttd-section">
                <div class="nama-ttd">
                    {{ $monitoringEkstra && $monitoringEkstra->ketuaExtra ? $monitoringEkstra->ketuaExtra->nama : 'Nama Ketua Ekstrakurikuler' }}
                </div>
                <div class="jabatan-ttd">Ketua Ekstrakurikuler</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
