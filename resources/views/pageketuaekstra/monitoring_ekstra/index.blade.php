    @extends('template-admin.layout')

    @section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Forms</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Monitoring Kegiatan Ekstrakurikuler</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--breadcrumb-->
            <h6 class="mb-0 text-uppercase">Data Monitoring Kegiatan Ekstrakurikuler</h6>
            <hr/>
            <div class="card">
                <div class="card-body">
                        <a href="{{ route('monitoring-ekstra.formkirimpesan') }}" class="btn btn-primary mb-3">Kirim Pesan</a>
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Ketua Ekstrakurikuler</th>
                                    <th>Kegiatan</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monitoringEkstra as $index => $data)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $data->ketuaExtra ? $data->ketuaExtra->nama : 'N/A' }}</td>
                                    <td>
                                        @if(strlen($data->kegiatan) > 100)
                                            {{ substr(strip_tags($data->kegiatan), 0, 100) }}...
                                        @else
                                            {{ strip_tags($data->kegiatan) }}
                                        @endif
                                    </td>
                                    <td>{{ $data->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('monitoring-ekstra.detail', $data->id) }}" class="btn btn-info btn-sm" target="_blank">
                                            <i class="bx bx-show"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Ketua Ekstrakurikuler</th>
                                    <th>Kegiatan</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data ini akan dihapus secara permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    @endsection