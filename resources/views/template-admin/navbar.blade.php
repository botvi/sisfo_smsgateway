<ul class="metismenu" id="menu">
    <li class="menu-label text-white">DASHBOARD</li>
    <li>
        <a href="{{ route('dashboard') }}">
            <div class="parent-icon"><i class='bx bx-home-circle text-white'></i></div>
            <div class="menu-title text-white">DASHBOARD</div>
        </a>
    </li>
    <li>
        <a href="{{ route('profil') }}">
            <div class="parent-icon"><i class='bx bx-user-circle text-white'></i></div>
            <div class="menu-title text-white">PROFIL</div>
        </a>
    </li>
    @if (Auth::user()->role == 'admin')
    <li class="menu-label text-white">MASTER DATA</li>
    <li>
        <li>
            <a href="{{ route('managemen-akun.index') }}">
                <div class="parent-icon"><i class='bx bx-arrow-to-right text-white'></i></div>
                <div class="menu-title text-white">MANAJEMEN AKUN</div>
            </a>
        </li>
        <li>
            <a href="{{ route('master-kelas.index') }}">
                <div class="parent-icon"><i class="bx bx-arrow-to-right text-white"></i></div>
                <div class="menu-title text-white">DATA KELAS</div>
            </a>
        </li>
        <li>
            <a href="{{ route('sms-api.index') }}">
                <div class="parent-icon"><i class="bx bx-arrow-to-right text-white"></i></div>
                <div class="menu-title text-white">API SMS GATEWAY</div>
            </a>
        </li>
    </li>
    @endif

    @if (Auth::user()->role == 'wali_kelas')
    <li class="menu-label text-white">DATA</li>
    <li>
        <a href="{{ route('orang-tua-wali-dan-siswa.index') }}">
            <div class="parent-icon"><i class="bx bx-arrow-to-right text-white"></i></div>
            <div class="menu-title text-white">DATA ORANG TUA/WALI DAN SISWA</div>
        </a>
    </li>
    <li>
        <a href="{{ route('monitoring-absensi.index') }}">
            <div class="parent-icon"><i class="bx bx-arrow-to-right text-white"></i></div>
            <div class="menu-title text-white">MONITORING ABSENSI</div>
        </a>
    </li>
    @endif
    @if (Auth::user()->role == 'guru_bk')
    <li class="menu-label text-white">DATA</li>
    <li>
        <a href="{{ route('master-pelanggaran.index') }}">
            <div class="parent-icon"><i class="bx bx-arrow-to-right text-white"></i></div>
            <div class="menu-title text-white">MASTER PELANGGARAN</div>
        </a>
    </li>
    <li>
        <a href="{{ route('monitoring-pelanggaran.index') }}">
            <div class="parent-icon"><i class="bx bx-arrow-to-right text-white"></i></div>
            <div class="menu-title text-white">MONITORING PELANGGARAN</div>
        </a>
    </li>
    @endif

    @if (Auth::user()->role == 'ketua_ekstrakurikuler')
    <li class="menu-label text-white">DATA</li>
    <li>
        <a href="{{ route('monitoring-ekstra.index') }}">
            <div class="parent-icon"><i class="bx bx-arrow-to-right text-white"></i></div>
            <div class="menu-title text-white">MONITORING KEGIATAN EKSTRAKURIKULER</div>
        </a>
    </li>
    @endif
    @if (Auth::user()->role == 'admin' || Auth::user()->role == 'kepala_sekolah')
    <li class="menu-label text-white">LAPORAN</li>
    <li>
        <a href="{{ route('laporan.index') }}">
            <div class="parent-icon"><i class='bx bx-file text-white'></i></div>
            <div class="menu-title text-white">MENU LAPORAN</div>
        </a>
    </li>
    @endif
    </ul>
