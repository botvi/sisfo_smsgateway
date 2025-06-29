@extends('template-admin.layout')
@section('style')
<style>
    .search-container {
        position: relative;
        width: 100%;
    }
    
    .search-input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #ced4da;
        border-top: none;
        border-radius: 0 0 4px 4px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
    }
    
    .search-result-item {
        padding: 8px 12px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .search-result-item:hover {
        background-color: #f8f9fa;
    }
    
    .search-result-item.selected {
        background-color: #007bff;
        color: white;
    }
    
    .hidden-select {
        display: none;
    }
</style>
@endsection
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
                            <li class="breadcrumb-item active" aria-current="page">Monitoring Absensi</li>
                            <li class="breadcrumb-item active" aria-current="page">Kirim Pesan</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--breadcrumb-->

            <div class="row">
                <div class="col-xl-7 mx-auto">
                    <hr />
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <div><i class="bx bx-plus-circle me-1 font-22 text-primary"></i></div>
                                <h5 class="mb-0 text-primary">Kirim Pesan</h5>
                            </div>
                            <hr>
                            <form action="{{ route('monitoring-absensi.kirimpesan') }}" method="POST" class="row g-3" enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-12">
                                    <label for="siswa_search" class="form-label">Nama Siswa</label>
                                    <div class="search-container">
                                        <input type="text" class="form-control search-input" id="siswa_search" placeholder="Ketik untuk mencari siswa..." autocomplete="off">
                                        <div class="search-results" id="search_results"></div>
                                    </div>
                                    <!-- Hidden select untuk form submission -->
                                    <select class="hidden-select" id="siswa_id" name="siswa_id" required>
                                        <option value="">Pilih Siswa</option>
                                        @foreach ($siswa as $siswa)
                                            <option value="{{ $siswa->id }}" data-nama="{{ $siswa->nama_siswa }}">{{ $siswa->nama_siswa }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger">
                                        @foreach ($errors->get('siswa_id') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="nama_ortu" class="form-label">Nama Orang Tua</label>
                                    <input type="text" class="form-control" id="nama_ortu" name="nama_ortu" readonly>
                                </div>
                                <div class="col-md-12">
                                    <label for="pesan" class="form-label">Pesan</label>
                                    <textarea class="form-control" id="pesan" name="pesan" rows="3" required></textarea>
                                    <small class="text-danger">
                                        @foreach ($errors->get('pesan') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary px-5">Kirim</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('siswa_search');
    const searchResults = document.getElementById('search_results');
    const hiddenSelect = document.getElementById('siswa_id');
    const namaOrtuInput = document.getElementById('nama_ortu');
    
    // Data siswa dari select options
    const siswaData = [];
    hiddenSelect.querySelectorAll('option').forEach(option => {
        if (option.value) {
            siswaData.push({
                id: option.value,
                nama: option.textContent
            });
        }
    });
    
    let selectedIndex = -1;
    
    // Fungsi untuk menampilkan hasil pencarian
    function showSearchResults(query) {
        if (!query.trim()) {
            searchResults.style.display = 'none';
            return;
        }
        
        const filteredSiswa = siswaData.filter(siswa => 
            siswa.nama.toLowerCase().includes(query.toLowerCase())
        );
        
        if (filteredSiswa.length === 0) {
            searchResults.innerHTML = '<div class="search-result-item">Tidak ada hasil</div>';
        } else {
            searchResults.innerHTML = filteredSiswa.map((siswa, index) => 
                `<div class="search-result-item" data-id="${siswa.id}" data-index="${index}">${siswa.nama}</div>`
            ).join('');
        }
        
        searchResults.style.display = 'block';
        selectedIndex = -1;
    }
    
    // Event listener untuk input pencarian
    searchInput.addEventListener('input', function() {
        showSearchResults(this.value);
    });
    
    // Event listener untuk keyboard navigation
    searchInput.addEventListener('keydown', function(e) {
        const items = searchResults.querySelectorAll('.search-result-item');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
            updateSelection(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectedIndex = Math.max(selectedIndex - 1, -1);
            updateSelection(items);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (selectedIndex >= 0 && items[selectedIndex]) {
                selectSiswa(items[selectedIndex]);
            }
        } else if (e.key === 'Escape') {
            searchResults.style.display = 'none';
            selectedIndex = -1;
        }
    });
    
    // Fungsi untuk update selection visual
    function updateSelection(items) {
        items.forEach((item, index) => {
            item.classList.toggle('selected', index === selectedIndex);
        });
    }
    
    // Event listener untuk klik pada hasil pencarian
    searchResults.addEventListener('click', function(e) {
        if (e.target.classList.contains('search-result-item')) {
            selectSiswa(e.target);
        }
    });
    
    // Fungsi untuk memilih siswa
    function selectSiswa(element) {
        const siswaId = element.dataset.id;
        const siswaNama = element.textContent;
        
        searchInput.value = siswaNama;
        hiddenSelect.value = siswaId;
        searchResults.style.display = 'none';
        
        // Ambil data orang tua
        if (siswaId) {
            fetch(`/monitoring-absensi/get-ortu/${siswaId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        namaOrtuInput.value = data.data.nama_ortu;
                    } else {
                        namaOrtuInput.value = '';
                        alert('Data orang tua tidak ditemukan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    namaOrtuInput.value = '';
                    alert('Terjadi kesalahan saat mengambil data orang tua');
                });
        } else {
            namaOrtuInput.value = '';
        }
    }
    
    // Event listener untuk menutup hasil pencarian ketika klik di luar
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
            selectedIndex = -1;
        }
    });
    
    // Event listener untuk focus pada input
    searchInput.addEventListener('focus', function() {
        if (this.value.trim()) {
            showSearchResults(this.value);
        }
    });
});
</script>
@endsection
