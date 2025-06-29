@extends('template-admin.layout')

@section('title', 'Laporan Daftar Siswa dan Orang Tua')

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0">Laporan Daftar Siswa dan Orang Tua</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- Filter Form -->
                                <form method="GET" action="{{ route('laporan.daftar-siswa-ortu') }}" class="mb-4">
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
                                        <div class="col-md-4 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary me-2">
                                                <i class="bx bx-filter-alt"></i> Filter
                                            </button>
                                            <a href="{{ route('laporan.daftar-siswa-ortu') }}"
                                                class="btn btn-secondary me-2">
                                                <i class="bx bx-refresh"></i> Reset
                                            </a>
                                            @if ($selectedKelas || $siswa->count() > 0)
                                                <a href="{{ route('laporan.print-daftar-siswa-ortu') }}?kelas_id={{ $selectedKelas }}"
                                                    target="_blank" class="btn btn-success">
                                                    <i class="bx bx-printer"></i> Cetak
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>

                                <!-- Data Table -->
                                @if ($siswa->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>No</th>
                                                    <th>NISN</th>
                                                    <th>Nama Siswa</th>
                                                    <th>Tanggal Lahir</th>
                                                    <th>Alamat</th>
                                                    <th>Kelas</th>
                                                    <th>Wali Kelas</th>
                                                    <th>Nama Orang Tua/Wali</th>
                                                    <th>No. HP Orang Tua</th>
                                                    <th>Alamat Orang Tua</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($siswa as $index => $s)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $s->nisn_siswa }}</td>
                                                        <td>{{ $s->nama_siswa }}</td>
                                                        <td>{{ $s->tanggal_lahir_siswa ? date('d/m/Y', strtotime($s->tanggal_lahir_siswa)) : '-' }}
                                                        </td>
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
                                    </div>

                                    <div class="mt-3">
                                        <p class="text-muted">
                                            Total data: <strong>{{ $siswa->count() }}</strong> siswa
                                            @if ($selectedKelas)
                                                dari kelas:
                                                <strong>{{ $kelas->where('id', $selectedKelas)->first()->nama_kelas ?? '' }}</strong>
                                            @endif
                                        </p>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="bx bx-info-circle text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-2">Tidak ada data siswa yang ditemukan.</p>
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
