@extends('template-admin.layout')

@section('title', 'Laporan Monitoring Kegiatan Ekstrakurikuler')

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">Laporan Monitoring Kegiatan Ekstrakurikuler</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Form Filter -->
                            <form method="GET" action="{{ route('laporan.monitoring-kegiatan-ekstra') }}" class="mb-4">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="ketua_extra_id" class="form-label">Ketua Ekstrakurikuler</label>
                                        <select name="ketua_extra_id" id="ketua_extra_id" class="form-select">
                                            <option value="">Semua Ketua Ekstrakurikuler</option>
                                            @foreach($ketuaExtra as $ke)
                                                <option value="{{ $ke->id }}" {{ $selectedKetuaExtra == $ke->id ? 'selected' : '' }}>
                                                    {{ $ke->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                                        <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" value="{{ $tanggalAwal }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="{{ $tanggalAkhir }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bx bx-search"></i> Filter
                                            </button>
                                            <a href="{{ route('laporan.monitoring-kegiatan-ekstra') }}" class="btn btn-secondary">
                                                <i class="bx bx-refresh"></i> Reset
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- Tombol Cetak -->
                            <div class="mb-4">
                                <a href="{{ route('laporan.print-monitoring-kegiatan-ekstra', request()->query()) }}" target="_blank" class="btn btn-success">
                                    <i class="bx bx-printer"></i> Cetak
                                </a>
                            </div>

                            @if($kegiatan->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Kegiatan</th>
                                                <th>Ketua Ekstrakurikuler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kegiatan as $index => $k)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $k->created_at ? date('d/m/Y H:i', strtotime($k->created_at)) : '-' }}</td>
                                                    <td>
                                                        <a href="{{ route('monitoring-ekstra.detail', $k->id) }}">{{ route('monitoring-ekstra.detail', $k->id) }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $k->ketuaExtra->nama ?? '-' }}</td>
                                                </tr>

                                                <!-- Modal untuk menampilkan kegiatan lengkap -->
                                                @if(strlen(strip_tags($k->kegiatan)) > 100)
                                                    <div class="modal fade" id="modalKegiatan{{ $k->id }}" tabindex="-1" aria-labelledby="modalKegiatanLabel{{ $k->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modalKegiatanLabel{{ $k->id }}">Detail Kegiatan</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <strong>Tanggal:</strong> {{ $k->created_at ? date('d/m/Y H:i', strtotime($k->created_at)) : '-' }}
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <strong>Ketua Ekstrakurikuler:</strong> {{ $k->ketuaExtra->nama ?? '-' }}
                                                                    </div>
                                                                    <div>
                                                                        <strong>Kegiatan:</strong>
                                                                        <div class="mt-2">
                                                                            {!! $k->kegiatan !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    <p class="text-muted">
                                        Total data: <strong>{{ $kegiatan->count() }}</strong> kegiatan
                                    </p>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bx bx-info-circle text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-2">Tidak ada data kegiatan ekstrakurikuler yang ditemukan.</p>
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