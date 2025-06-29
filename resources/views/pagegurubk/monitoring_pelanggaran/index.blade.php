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
                            <li class="breadcrumb-item active" aria-current="page">Monitoring Pelanggaran</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--breadcrumb-->
            <h6 class="mb-0 text-uppercase">Data Monitoring Pelanggaran</h6>
            <hr/>
            <div class="card">
                <div class="card-body">
                        <a href="{{ route('monitoring-pelanggaran.formkirimpesan') }}" class="btn btn-primary mb-3">Kirim Pesan</a>
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Pelanggaran</th>
                                    <th>Tanggal Pelanggaran</th>
                                    <th>Tingkat Pelanggaran</th>
                                    <th>Poin Pelanggaran</th>
                                
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monitoringPelanggaran as $index => $data)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $data->siswa->nama_siswa }}</td>
                                    <td>{{ $data->pelanggaran->nama_pelanggaran }}</td>
                                    <td>{{ $data->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $data->pelanggaran->tingkat_pelanggaran }}</td>
                                    <td>{{ $data->pelanggaran->poin_pelanggaran }}</td>
                                  
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Pelanggaran</th>
                                    <th>Tanggal Pelanggaran</th>
                                    <th>Tingkat Pelanggaran</th>
                                    <th>Poin Pelanggaran</th>
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