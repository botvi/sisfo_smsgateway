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
                            <li class="breadcrumb-item active" aria-current="page">Data Orang Tua/Wali dan Siswa</li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Data</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--breadcrumb-->

            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <hr />
                    <form action="{{ route('orang-tua-wali-dan-siswa.store') }}" method="POST" class="row g-3" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Card Data Orang Tua/Wali -->
                        <div class="col-xl-6">
                            <div class="card border-top border-0 border-4 border-primary">
                                <div class="card-body p-5">
                                    <div class="card-title d-flex align-items-center">
                                        <div><i class="bx bx-user me-1 font-22 text-primary"></i></div>
                                        <h5 class="mb-0 text-primary">Data Orang Tua/Wali</h5>
                                    </div>
                                    <hr>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="nama_ortu" class="form-label">Nama Orang Tua/Wali</label>
                                            <input type="text" class="form-control" id="nama_ortu" name="nama_ortu" value="{{ old('nama_ortu') }}" required>
                                            <small class="text-danger">
                                                @foreach ($errors->get('nama_ortu') as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </small>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="no_hp_ortu" class="form-label">No. HP</label>
                                            <input type="text" class="form-control" id="no_hp_ortu" name="no_hp_ortu" value="{{ old('no_hp_ortu') }}" required>
                                            <small class="text-danger">
                                                @foreach ($errors->get('no_hp_ortu') as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </small>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="tanggal_lahir_ortu" class="form-label">Tanggal Lahir</label>
                                            <input type="date" class="form-control" id="tanggal_lahir_ortu" name="tanggal_lahir_ortu" value="{{ old('tanggal_lahir_ortu') }}" required>
                                            <small class="text-danger">
                                                @foreach ($errors->get('tanggal_lahir_ortu') as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </small>
                                        </div>
                                        <div class="col-12">
                                            <label for="alamat_ortu" class="form-label">Alamat</label>
                                            <textarea class="form-control" id="alamat_ortu" name="alamat_ortu" rows="3" required>{{ old('alamat_ortu') }}</textarea>
                                            <small class="text-danger">
                                                @foreach ($errors->get('alamat_ortu') as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Data Siswa -->
                        <div class="col-xl-6">
                            <div class="card border-top border-0 border-4 border-success">
                                <div class="card-body p-5">
                                    <div class="card-title d-flex align-items-center">
                                        <div><i class="bx bx-user-pin me-1 font-22 text-success"></i></div>
                                        <h5 class="mb-0 text-success">Data Siswa</h5>
                                    </div>
                                    <hr>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="nama_siswa" class="form-label">Nama Siswa</label>
                                            <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" value="{{ old('nama_siswa') }}" required>
                                            <small class="text-danger">
                                                @foreach ($errors->get('nama_siswa') as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </small>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="tanggal_lahir_siswa" class="form-label">Tanggal Lahir</label>
                                            <input type="date" class="form-control" id="tanggal_lahir_siswa" name="tanggal_lahir_siswa" value="{{ old('tanggal_lahir_siswa') }}" required>
                                            <small class="text-danger">
                                                @foreach ($errors->get('tanggal_lahir_siswa') as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </small>
                                        </div>
                                       
                                        <div class="col-md-6">
                                            <label for="nisn_siswa" class="form-label">NISN</label>
                                            <input type="number" class="form-control" id="nisn_siswa" name="nisn_siswa" value="{{ old('nisn_siswa') }}" maxlength="10" required>
                                            <small class="text-danger">
                                                @foreach ($errors->get('nisn_siswa') as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </small>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="master_kelas_id" class="form-label">Kelas</label>
                                            <select class="form-control" id="master_kelas_id" name="master_kelas_id" readonly>
                                                @foreach($masterKelas as $kelas)
                                                    <option value="{{ $kelas->id }}" {{ old('master_kelas_id') == $kelas->id ? 'selected' : '' }}>
                                                        {{ $kelas->nama_kelas }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="text-danger">
                                                @foreach ($errors->get('master_kelas_id') as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </small>
                                        </div>
                                        <div class="col-12">
                                            <label for="alamat_siswa" class="form-label">Alamat Siswa</label>
                                            <textarea class="form-control" id="alamat_siswa" name="alamat_siswa" rows="3" required>{{ old('alamat_siswa') }}</textarea>
                                            <small class="text-danger">
                                                @foreach ($errors->get('alamat_siswa') as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('orang-tua-wali-dan-siswa.index') }}" class="btn btn-secondary px-5">
                                    <i class="bx bx-arrow-back me-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="bx bx-save me-1"></i> Simpan Data
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
