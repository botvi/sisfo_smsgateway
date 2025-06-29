@extends('template-admin.layout')

@section('title', 'Laporan Monitoring Pelanggaran')

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">Laporan Monitoring Pelanggaran</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Filter Form -->
                            <form method="GET" action="{{ route('laporan.monitoring-pelanggaran') }}" class="mb-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="kelas_id" class="form-label">Filter Kelas</label>
                                        <select name="kelas_id" id="kelas_id" class="form-select">
                                            <option value="">Semua Kelas</option>
                                            @foreach ($kelas as $k)
                                                <option value="{{ $k->id }}"
                                                    {{ $selectedKelas == $k->id ? 'selected' : '' }}>
                                                    {{ $k->nama_kelas }} -
                                                    {{ $k->waliKelas->nama ?? 'Belum ada wali kelas' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="pelanggaran_id" class="form-label">Jenis Pelanggaran</label>
                                        <select name="pelanggaran_id" id="pelanggaran_id" class="form-select">
                                            <option value="">Semua Jenis Pelanggaran</option>
                                            @foreach ($pelanggaran as $p)
                                                <option value="{{ $p->id }}"
                                                    {{ $selectedPelanggaran == $p->id ? 'selected' : '' }}>
                                                    {{ $p->nama_pelanggaran }} ({{ $p->tingkat_pelanggaran }} - {{ $p->poin_pelanggaran }} poin)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="bx bx-filter-alt"></i> Filter
                                        </button>
                                        <a href="{{ route('laporan.monitoring-pelanggaran') }}" class="btn btn-secondary me-2">
                                            <i class="bx bx-refresh"></i> Reset
                                        </a>
                                        @if($selectedKelas || $selectedPelanggaran || $monitoringPelanggaran->count() > 0)
                                            <a href="{{ route('laporan.print-monitoring-pelanggaran') }}?kelas_id={{ $selectedKelas }}&pelanggaran_id={{ $selectedPelanggaran }}" 
                                               target="_blank" class="btn btn-success">
                                                <i class="bx bx-printer"></i> Cetak
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>

                            <!-- Data Table -->
                            @if($monitoringPelanggaran->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Nama Siswa</th>
                                                <th>NISN</th>
                                                <th>Kelas</th>
                                                <th>Wali Kelas</th>
                                                <th>Jenis Pelanggaran</th>
                                                <th>Tingkat</th>
                                                <th>Poin</th>
                                                <th>Guru BK</th>
                                                <th>Orang Tua/Wali</th>
                                                <th>No. HP Orang Tua</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($monitoringPelanggaran as $index => $mp)
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
                                                                    $tingkatClass = 'success';
                                                                    break;
                                                                case 'Sedang':
                                                                    $tingkatClass = 'warning';
                                                                    break;
                                                                case 'Berat':
                                                                    $tingkatClass = 'danger';
                                                                    break;
                                                                default:
                                                                    $tingkatClass = 'secondary';
                                                            }
                                                        @endphp
                                                        <span class="badge bg-{{ $tingkatClass }}">{{ $mp->pelanggaran->tingkat_pelanggaran ?? '-' }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $mp->pelanggaran->poin_pelanggaran ?? 0 }} poin</span>
                                                    </td>
                                                    <td>{{ $mp->guruBk->nama ?? '-' }}</td>
                                                    <td>{{ $mp->siswa->orangTuaWali->nama_ortu ?? '-' }}</td>
                                                    <td>{{ $mp->siswa->orangTuaWali->no_hp_ortu ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mt-3">
                                    <p class="text-muted">
                                        Total data: <strong>{{ $monitoringPelanggaran->count() }}</strong> record
                                        @if($selectedKelas)
                                            dari kelas: <strong>{{ $kelas->where('id', $selectedKelas)->first()->nama_kelas ?? '' }}</strong>
                                        @endif
                                        @if($selectedPelanggaran)
                                            jenis pelanggaran: <strong>{{ $pelanggaran->where('id', $selectedPelanggaran)->first()->nama_pelanggaran ?? '' }}</strong>
                                        @endif
                                    </p>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bx bx-info-circle text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-2">Tidak ada data monitoring pelanggaran yang ditemukan.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection