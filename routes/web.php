<?php

use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\{
    DashboardController,
    LoginController,
    ProfilController,
    LaporanController,
};
use App\Http\Controllers\admin\{
    ManagemenAkunController,
    MasterKelasController,
    SmsApiController,
};
use App\Http\Controllers\wali_kelas\{
    OrangTuaWalidanSiswaController,
    MonitoringAbsensiController,
};
use App\Http\Controllers\guru_bk\{
    MasterPelanggaranController,
    MonitoringPelanggaranController,
};
use App\Http\Controllers\ketua_ekstrakurikuler\{
    MonitoringEkstraController,
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');



Route::get('/run-admin', function () {
    Artisan::call('db:seed', [
        '--class' => 'SuperAdminSeeder'
    ]);

    return "AdminSeeder has been create successfully!";
});

Route::get('/', [LoginController::class, 'showLoginForm'])->name('formlogin');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
Route::put('/profil/update', [ProfilController::class, 'update'])->name('profil.update');

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::resource('managemen-akun', ManagemenAkunController::class);
    Route::resource('master-kelas', MasterKelasController::class);
    Route::get('sms-api', [SmsApiController::class, 'index'])->name('sms-api.index');
    Route::post('sms-api', [SmsApiController::class, 'storeorupdate'])->name('sms-api.storeorupdate');
});


Route::middleware(['auth', 'role:wali_kelas'])->group(function () {

    Route::resource('orang-tua-wali-dan-siswa', OrangTuaWalidanSiswaController::class);
    Route::get('monitoring-absensi', [MonitoringAbsensiController::class, 'index'])->name('monitoring-absensi.index');
    Route::get('monitoring-absensi/formkirimpesan', [MonitoringAbsensiController::class, 'formkirimpesan'])->name('monitoring-absensi.formkirimpesan');
    Route::post('monitoring-absensi/kirimpesan', [MonitoringAbsensiController::class, 'kirimpesan'])->name('monitoring-absensi.kirimpesan');
    Route::get('monitoring-absensi/get-ortu/{siswa_id}', [MonitoringAbsensiController::class, 'getOrangTua'])->name('monitoring-absensi.get-ortu');
});

Route::middleware(['auth', 'role:guru_bk'])->group(function () {
    Route::resource('master-pelanggaran', MasterPelanggaranController::class);
    Route::get('monitoring-pelanggaran', [MonitoringPelanggaranController::class, 'index'])->name('monitoring-pelanggaran.index');
    Route::get('monitoring-pelanggaran/formkirimpesan', [MonitoringPelanggaranController::class, 'formkirimpesan'])->name('monitoring-pelanggaran.formkirimpesan');
    Route::post('monitoring-pelanggaran/kirimpesan', [MonitoringPelanggaranController::class, 'kirimpesan'])->name('monitoring-pelanggaran.kirimpesan');
    Route::get('monitoring-pelanggaran/get-ortu/{siswa_id}', [MonitoringPelanggaranController::class, 'getOrangTua'])->name('monitoring-pelanggaran.get-ortu');
});

Route::middleware(['auth', 'role:ketua_ekstrakurikuler'])->group(function () {
    Route::get('monitoring-ekstra', [MonitoringEkstraController::class, 'index'])->name('monitoring-ekstra.index');
    Route::get('monitoring-ekstra/formkirimpesan', [MonitoringEkstraController::class, 'formkirimpesan'])->name('monitoring-ekstra.formkirimpesan');
    Route::post('monitoring-ekstra/kirimpesan', [MonitoringEkstraController::class, 'kirimpesan'])->name('monitoring-ekstra.kirimpesan');
});

Route::get('monitoring-ekstra/detail/{id}', [MonitoringEkstraController::class, 'detail'])->name('monitoring-ekstra.detail');
// Route untuk Laporan
Route::middleware(['auth'])->group(function () {
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/daftar-siswa-ortu', [LaporanController::class, 'daftarSiswaDanOrtu'])->name('laporan.daftar-siswa-ortu');
    Route::get('laporan/print-daftar-siswa-ortu', [LaporanController::class, 'printDaftarSiswaDanOrtu'])->name('laporan.print-daftar-siswa-ortu');
    Route::get('laporan/monitoring-absensi', [LaporanController::class, 'monitoringAbsensi'])->name('laporan.monitoring-absensi');
    Route::get('laporan/print-monitoring-absensi', [LaporanController::class, 'printMonitoringAbsensi'])->name('laporan.print-monitoring-absensi');
    Route::get('laporan/monitoring-pelanggaran', [LaporanController::class, 'monitoringPelanggaran'])->name('laporan.monitoring-pelanggaran');
    Route::get('laporan/print-monitoring-pelanggaran', [LaporanController::class, 'printMonitoringPelanggaran'])->name('laporan.print-monitoring-pelanggaran');
    Route::get('laporan/monitoring-kegiatan-ekstra', [LaporanController::class, 'monitoringKegiatanEkstra'])->name('laporan.monitoring-kegiatan-ekstra');
    Route::get('laporan/print-monitoring-kegiatan-ekstra', [LaporanController::class, 'printMonitoringKegiatanEkstra'])->name('laporan.print-monitoring-kegiatan-ekstra');
});