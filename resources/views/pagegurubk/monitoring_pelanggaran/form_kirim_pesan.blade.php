@extends('template-admin.layout')
@section('style')
<style>
    .checkbox-container {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 10px;
    }
    
    .checkbox-item {
        display: flex;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .checkbox-item:last-child {
        border-bottom: none;
    }
    
    .checkbox-item input[type="checkbox"] {
        margin-right: 10px;
    }
    
    .checkbox-item label {
        margin: 0;
        cursor: pointer;
        flex: 1;
    }
    
    .select-all-container {
        padding: 10px 0;
        border-bottom: 2px solid #007bff;
        margin-bottom: 10px;
    }
    
    .progress-container {
        display: none;
        margin: 20px 0;
    }
    
    .progress-bar {
        height: 20px;
        background-color: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        background-color: #007bff;
        width: 0%;
        transition: width 0.3s ease;
    }
    
    .status-text {
        margin-top: 10px;
        font-size: 14px;
        color: #6c757d;
    }
    
    .search-container {
        margin-bottom: 15px;
    }
    
    .search-input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .accordion-button:not(.collapsed) {
        background-color: #e7f1ff;
        color: #0c63e4;
    }
    
    .accordion-button:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .hidden-siswa {
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
                            <li class="breadcrumb-item active" aria-current="page">Monitoring Pelanggaran</li>
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
                            <form action="{{ route('monitoring-pelanggaran.kirimpesan') }}" method="POST" class="row g-3" enctype="multipart/form-data" id="kirimPesanForm">
                                @csrf
                                <div class="col-md-12">
                                    <label class="form-label">Pilih Siswa</label>
                                    
                                    <!-- Search Input -->
                                    <div class="search-container">
                                        <input type="text" class="form-control search-input" id="searchSiswa" placeholder="Cari nama siswa..." autocomplete="off">
                                    </div>
                                    
                                    <div class="accordion" id="accordionSiswa">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingAll">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAll" aria-expanded="false" aria-controls="collapseAll">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                                        <label class="form-check-label" for="selectAll">
                                                            <strong>Pilih Semua</strong>
                                                        </label>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseAll" class="accordion-collapse collapse" aria-labelledby="headingAll" data-bs-parent="#accordionSiswa">
                                                <div class="accordion-body">
                                                    @foreach ($siswa as $siswa)
                                                        <div class="accordion-item siswa-item" data-nama="{{ strtolower($siswa->nama_siswa) }}">
                                                            <h2 class="accordion-header" id="headingSiswa{{ $siswa->id }}">
                                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSiswa{{ $siswa->id }}" aria-expanded="false" aria-controls="collapseSiswa{{ $siswa->id }}">
                                                                    <input class="form-check-input siswa-checkbox me-2" type="checkbox" name="siswa_ids[]" value="{{ $siswa->id }}" id="siswa_{{ $siswa->id }}">
                                                                    <label class="form-check-label" for="siswa_{{ $siswa->id }}">
                                                                        {{ $siswa->nama_siswa }} - {{ $siswa->orangTuaWali->nama_ortu ?? 'Tidak ada data orang tua' }}
                                                                    </label>
                                                                </button>
                                                            </h2>
                                                            <div id="collapseSiswa{{ $siswa->id }}" class="accordion-collapse collapse" aria-labelledby="headingSiswa{{ $siswa->id }}" data-bs-parent="#accordionSiswa">
                                                                <div class="accordion-body">
                                                                    <ul class="mb-0">
                                                                        <li><strong>Nama Siswa:</strong> {{ $siswa->nama_siswa }}</li>
                                                                        <li><strong>Nama Orang Tua:</strong> {{ $siswa->orangTuaWali->nama_ortu ?? 'Tidak ada data orang tua' }}</li>
                                                                        <li><strong>No HP Orang Tua:</strong> {{ $siswa->orangTuaWali->no_hp_ortu ?? '-' }}</li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-danger">
                                        @foreach ($errors->get('siswa_ids') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="pelanggaran_id" class="form-label">Pelanggaran</label>
                                    <select class="form-control" id="pelanggaran_id" name="pelanggaran_id" required>
                                        <option value="">Pilih Pelanggaran</option>
                                        @foreach ($pelanggaran as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_pelanggaran }} - {{ $p->tingkat_pelanggaran }} - {{ $p->poin_pelanggaran }}</option>
                                    @endforeach
                                    </select>
                                    <small class="text-danger">
                                        @foreach ($errors->get('pelanggaran_id') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="pesan" class="form-label">Pesan (Opsional - akan diisi otomatis jika kosong)</label>
                                    <textarea class="form-control" id="pesan" name="pesan" rows="6" placeholder="Pesan akan diisi otomatis berdasarkan pelanggaran yang dipilih..."></textarea>
                                    <small class="text-muted">Jika dikosongkan, sistem akan membuat pesan otomatis berdasarkan data pelanggaran yang dipilih.</small>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="col-12 progress-container" id="progressContainer">
                                    <label class="form-label">Progress Pengiriman</label>
                                    <div class="progress-bar">
                                        <div class="progress-fill" id="progressFill"></div>
                                    </div>
                                    <div class="status-text" id="statusText">Memulai pengiriman...</div>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary px-5" id="submitBtn">Kirim Pesan</button>
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
    const selectAllCheckbox = document.getElementById('selectAll');
    const siswaCheckboxes = document.querySelectorAll('.siswa-checkbox');
    const submitBtn = document.getElementById('submitBtn');
    const progressContainer = document.getElementById('progressContainer');
    const progressFill = document.getElementById('progressFill');
    const statusText = document.getElementById('statusText');
    const searchInput = document.getElementById('searchSiswa');
    const siswaItems = document.querySelectorAll('.siswa-item');
    const pelanggaranSelect = document.getElementById('pelanggaran_id');
    const pesanTextarea = document.getElementById('pesan');
    
    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        siswaItems.forEach(item => {
            const namaSiswa = item.dataset.nama;
            if (namaSiswa.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
        
        // Update select all state after search
        updateSelectAllState();
    });
    
    // Select All functionality
    selectAllCheckbox.addEventListener('change', function() {
        const visibleCheckboxes = document.querySelectorAll('.siswa-checkbox:not([style*="display: none"])');
        visibleCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectAllState();
    });
    
    // Update select all when individual checkboxes change
    siswaCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllState();
        });
    });
    
    // Function to update select all state
    function updateSelectAllState() {
        const visibleCheckboxes = document.querySelectorAll('.siswa-checkbox:not([style*="display: none"])');
        const checkedCount = document.querySelectorAll('.siswa-checkbox:checked').length;
        const totalCount = visibleCheckboxes.length;
        
        if (checkedCount === 0) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        } else if (checkedCount === totalCount) {
            selectAllCheckbox.checked = true;
            selectAllCheckbox.indeterminate = false;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    }
    
    // Event listener untuk perubahan pada select pelanggaran
    pelanggaranSelect.addEventListener('change', function() {
        const selectedPelanggaran = this.value;
        
        if (selectedPelanggaran) {
            // Ambil data pelanggaran untuk membuat pesan otomatis
            const pelanggaranText = this.options[this.selectedIndex].text;
            
            // Parse data pelanggaran dari text option
            const pelanggaranParts = pelanggaranText.split(' - ');
            const namaPelanggaran = pelanggaranParts[0];
            const tingkatPelanggaran = pelanggaranParts[1];
            const poinPelanggaran = pelanggaranParts[2];
            
            // Buat pesan otomatis template
            const autoMessage = `Kepada Yth. Bapak/Ibu Orang Tua dari [NAMA_SISWA]

Dengan hormat, kami memberitahukan bahwa putra/putri Anda telah melakukan pelanggaran:
- Jenis Pelanggaran: ${namaPelanggaran}
- Tingkat Pelanggaran: ${tingkatPelanggaran}
- Poin Pelanggaran: ${poinPelanggaran}

Mohon perhatian dan bimbingan untuk putra/putri Anda.

Terima kasih.
Guru BK`;
            
            // Isi textarea dengan pesan otomatis template
            pesanTextarea.value = autoMessage;
        }
    });
    
    // Form submission
    document.getElementById('kirimPesanForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const checkedSiswa = document.querySelectorAll('.siswa-checkbox:checked');
        const pelanggaranId = pelanggaranSelect.value;
        const pesan = pesanTextarea.value;
        
        if (checkedSiswa.length === 0) {
            alert('Pilih minimal satu siswa');
            return;
        }
        
        if (!pelanggaranId) {
            alert('Pilih pelanggaran terlebih dahulu');
            return;
        }
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.textContent = 'Mengirim...';
        
        // Show progress
        progressContainer.style.display = 'block';
        
        // Start sending messages
        sendMessagesSequentially(Array.from(checkedSiswa), pelanggaranId, pesan);
    });
    
    function sendMessagesSequentially(siswaList, pelanggaranId, pesan) {
        const totalSiswa = siswaList.length;
        let currentIndex = 0;
        
        function sendNext() {
            if (currentIndex >= totalSiswa) {
                // All messages sent
                statusText.textContent = 'Semua pesan telah dikirim!';
                submitBtn.disabled = false;
                submitBtn.textContent = 'Kirim Pesan';
                
                // Redirect after 2 seconds
                setTimeout(() => {
                    window.location.href = '{{ route("monitoring-pelanggaran.index") }}';
                }, 2000);
                return;
            }
            
            const currentSiswa = siswaList[currentIndex];
            const siswaId = currentSiswa.value;
            const siswaNama = currentSiswa.nextElementSibling.textContent.split(' - ')[0];
            
            // Update progress
            const progress = ((currentIndex + 1) / totalSiswa) * 100;
            progressFill.style.width = progress + '%';
            statusText.textContent = `Mengirim pesan ke ${siswaNama} (${currentIndex + 1}/${totalSiswa})`;
            
            // Send message
            fetch('{{ route("monitoring-pelanggaran.kirimpesan-multiple") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    siswa_ids: [siswaId],
                    pelanggaran_id: pelanggaranId,
                    pesan: pesan
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log(`Pesan ke ${siswaNama}:`, data);
                currentIndex++;
                
                // Wait 2 seconds before sending next message
                setTimeout(sendNext, 2000);
            })
            .catch(error => {
                console.error(`Error sending to ${siswaNama}:`, error);
                currentIndex++;
                
                // Continue with next message even if current fails
                setTimeout(sendNext, 2000);
            });
        }
        
        // Start sending
        sendNext();
    }
});
</script>
@endsection
