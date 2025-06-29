@extends('template-admin.layout')

@section('title', 'Menu Laporan')

@section('content')
    <div class="page-wrapper">

        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0">Menu Laporan</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Pilih Jenis Laporan</h5>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-primary">
                                            <div class="card-body text-center">
                                                <i class="bx bx-user-plus text-primary" style="font-size: 3rem;"></i>
                                                <h5 class="card-title mt-3">Daftar Siswa dan Orang Tua</h5>
                                                <p class="card-text">Laporan daftar siswa beserta data orang tua/wali dengan
                                                    filter berdasarkan kelas.</p>
                                                <a href="{{ route('laporan.daftar-siswa-ortu') }}" class="btn btn-primary">
                                                    <i class="bx bx-file"></i> Lihat Laporan
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <div class="card border-warning">
                                            <div class="card-body text-center">
                                                <i class="bx bx-calendar-check text-warning" style="font-size: 3rem;"></i>
                                                <h5 class="card-title mt-3">Monitoring Absensi</h5>
                                                <p class="card-text">Laporan monitoring kehadiran siswa dengan filter
                                                    berdasarkan kelas dan periode.</p>
                                                <a href="{{ route('laporan.monitoring-absensi') }}" class="btn btn-warning">
                                                    <i class="bx bx-file"></i> Lihat Laporan
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <div class="card border-danger">
                                            <div class="card-body text-center">
                                                <i class="bx bx-error-circle text-danger" style="font-size: 3rem;"></i>
                                                <h5 class="card-title mt-3">Monitoring Pelanggaran</h5>
                                                <p class="card-text">Laporan monitoring pelanggaran siswa dengan filter
                                                    berdasarkan kelas dan periode.</p>
                                                <a href="{{ route('laporan.monitoring-pelanggaran') }}" class="btn btn-danger">
                                                    <i class="bx bx-file"></i> Lihat Laporan
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <div class="card border-success">
                                            <div class="card-body text-center">
                                                <i class="bx bx-trophy text-success" style="font-size: 3rem;"></i>
                                                <h5 class="card-title mt-3">Monitoring Ekstrakurikuler</h5>
                                                <p class="card-text">Laporan monitoring kegiatan ekstrakurikuler siswa
                                                    dengan filter berdasarkan periode.</p>
                                                <a href="{{ route('laporan.monitoring-kegiatan-ekstra') }}" class="btn btn-success">
                                                    <i class="bx bx-file"></i> Lihat Laporan
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
