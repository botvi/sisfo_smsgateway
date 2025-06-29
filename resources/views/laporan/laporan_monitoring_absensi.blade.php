@extends('template-admin.layout')

@section('title', 'Laporan Monitoring Absensi')

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">Laporan Monitoring Absensi</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Filter Form -->
                            <form method="GET" action="{{ route('laporan.monitoring-absensi') }}" class="mb-4">
                                <div class="row">
                                    <div class="col-md-3">
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
                                  
                                    <div class="col-md-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="bx bx-filter-alt"></i> Filter
                                        </button>
                                        <a href="{{ route('laporan.monitoring-absensi') }}" class="btn btn-secondary me-2">
                                            <i class="bx bx-refresh"></i> Reset
                                        </a>
                                        @if($selectedKelas || $selectedStatus || $tanggalAwal || $tanggalAkhir || $absensi->count() > 0)
                                            <a href="{{ route('laporan.print-monitoring-absensi') }}?kelas_id={{ $selectedKelas }}&status={{ $selectedStatus }}&tanggal_awal={{ $tanggalAwal }}&tanggal_akhir={{ $tanggalAkhir }}" 
                                               target="_blank" class="btn btn-success">
                                                <i class="bx bx-printer"></i> Cetak
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>

                            <!-- Data Table -->
                            @if($absensi->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal Pengiriman</th>
                                                <th>Nama Siswa</th>
                                                <th>NISN</th>
                                                <th>Kelas</th>
                                                <th>Wali Kelas</th>
                                                <th>Status</th>
                                                <th>Pesan</th>
                                                <th>Orang Tua/Wali</th>
                                                <th>No. HP Orang Tua</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($absensi as $index => $a)
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
                                                                    $statusClass = 'success';
                                                                    $statusText = 'Hadir';
                                                                    break;
                                                                case 'sakit':
                                                                    $statusClass = 'warning';
                                                                    $statusText = 'Sakit';
                                                                    break;
                                                                case 'izin':
                                                                    $statusClass = 'info';
                                                                    $statusText = 'Izin';
                                                                    break;
                                                                case 'alpha':
                                                                    $statusClass = 'danger';
                                                                    $statusText = 'Alpha';
                                                                    break;
                                                                default:
                                                                    $statusClass = 'secondary';
                                                                    $statusText = $a->status ?? '-';
                                                            }
                                                        @endphp
                                                        <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                                    </td>
                                                    <td>{{ $a->pesan ?? '-' }}</td>
                                                    <td>{{ $a->siswa->orangTuaWali->nama_ortu ?? '-' }}</td>
                                                    <td>{{ $a->siswa->orangTuaWali->no_hp_ortu ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mt-3">
                                    <p class="text-muted">
                                        Total data: <strong>{{ $absensi->count() }}</strong> record
                                        @if($selectedKelas)
                                            dari kelas: <strong>{{ $kelas->where('id', $selectedKelas)->first()->nama_kelas ?? '' }}</strong>
                                        @endif
                                        @if($selectedStatus)
                                            dengan status: <strong>{{ ucfirst($selectedStatus) }}</strong>
                                        @endif
                                        @if($tanggalAwal && $tanggalAkhir)
                                            periode: <strong>{{ date('d/m/Y', strtotime($tanggalAwal)) }} - {{ date('d/m/Y', strtotime($tanggalAkhir)) }}</strong>
                                        @endif
                                    </p>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bx bx-info-circle text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-2">Tidak ada data monitoring absensi yang ditemukan.</p>
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