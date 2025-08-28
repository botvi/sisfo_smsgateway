<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Sistem Monitoring Siswa

Sistem monitoring siswa yang dibangun dengan Laravel untuk mengelola data siswa, absensi, pelanggaran, dan kegiatan ekstrakurikuler.

## Fitur Utama

### 1. Monitoring Pelanggaran (Guru BK)
- **Pilihan Siswa Multiple dengan Checkbox**: Guru BK dapat memilih beberapa siswa sekaligus menggunakan checkbox
- **Pengiriman SMS Otomatis**: Sistem mengirim SMS ke orang tua siswa dengan delay 2 detik antar pengiriman
- **Pesan Otomatis**: Pesan dapat dibuat otomatis berdasarkan jenis pelanggaran atau diinput manual
- **Progress Bar**: Menampilkan progress pengiriman SMS secara real-time
- **Search Siswa**: Fitur pencarian siswa untuk memudahkan pemilihan

### 2. Monitoring Absensi (Wali Kelas)
- **Pilihan Siswa Multiple**: Wali kelas dapat memilih beberapa siswa sekaligus
- **Pengiriman SMS**: Kirim notifikasi absensi ke orang tua siswa
- **Progress Tracking**: Monitoring progress pengiriman SMS

### 3. Monitoring Kegiatan Ekstrakurikuler
- **Tracking Kegiatan**: Monitoring kegiatan ekstrakurikuler siswa
- **Notifikasi**: Kirim notifikasi ke orang tua

## Cara Penggunaan

### Monitoring Pelanggaran dengan Multiple Siswa

1. **Akses Form**: Login sebagai Guru BK → Monitoring Pelanggaran → Kirim Pesan
2. **Pilih Siswa**: 
   - Gunakan fitur "Pilih Semua" untuk memilih semua siswa
   - Atau pilih siswa secara individual dengan checkbox
   - Gunakan fitur search untuk mencari siswa tertentu
3. **Pilih Pelanggaran**: Pilih jenis pelanggaran dari dropdown
4. **Pesan**: 
   - Biarkan kosong untuk pesan otomatis
   - Atau tulis pesan manual dengan placeholder `[NAMA_SISWA]`
5. **Kirim**: Klik "Kirim Pesan" untuk memulai pengiriman
6. **Progress**: Monitor progress pengiriman dengan progress bar
7. **Delay**: Sistem akan mengirim SMS satu per satu dengan delay 2 detik

### Fitur Checkbox Siswa

- **Select All**: Checkbox untuk memilih semua siswa yang terlihat
- **Individual Selection**: Pilih siswa satu per satu
- **Search Integration**: Checkbox akan terupdate sesuai hasil pencarian
- **Accordion View**: Setiap siswa dapat di-expand untuk melihat detail

### Format Pesan Otomatis

```
Kepada Yth. Bapak/Ibu Orang Tua dari [NAMA_SISWA]

Dengan hormat, kami memberitahukan bahwa putra/putri Anda telah melakukan pelanggaran:
- Jenis Pelanggaran: [JENIS_PELANGGARAN]
- Tingkat Pelanggaran: [TINGKAT_PELANGGARAN]
- Poin Pelanggaran: [POIN_PELANGGARAN]

Mohon perhatian dan bimbingan untuk putra/putri Anda.

Terima kasih.
Guru BK
```

## Teknologi yang Digunakan

- **Backend**: Laravel 10
- **Database**: MySQL
- **Frontend**: Bootstrap 5, JavaScript ES6
- **SMS API**: SMS Text API
- **Authentication**: Laravel Sanctum
- **Role Management**: Custom middleware

## Struktur Database

### Tabel Utama
- `users` - Data pengguna (admin, guru, wali kelas)
- `siswas` - Data siswa
- `orang_tua_walis` - Data orang tua/wali
- `master_kelas` - Data kelas
- `pelanggarans` - Data jenis pelanggaran
- `monitoring_pelanggarans` - Data monitoring pelanggaran
- `monitoring_absensis` - Data monitoring absensi
- `sms_apis` - Konfigurasi SMS API

## API Endpoints

### Monitoring Pelanggaran
- `POST /monitoring-pelanggaran/kirimpesan` - Kirim SMS single siswa
- `POST /monitoring-pelanggaran/kirimpesan-multiple` - Kirim SMS multiple siswa
- `GET /monitoring-pelanggaran/get-ortu/{siswa_id}` - Ambil data orang tua

### Monitoring Absensi
- `POST /monitoring-absensi/kirimpesan` - Kirim SMS absensi
- `GET /monitoring-absensi/get-ortu/{siswa_id}` - Ambil data orang tua

## Keamanan

- **Authentication**: Login required untuk semua fitur
- **Role-based Access**: Setiap role memiliki akses terbatas
- **CSRF Protection**: Token CSRF untuk semua form
- **Input Validation**: Validasi input server-side
- **SQL Injection Protection**: Eloquent ORM dengan prepared statements

## Deployment

1. Clone repository
2. Install dependencies: `composer install`
3. Copy `.env.example` ke `.env`
4. Setup database dan konfigurasi SMS API
5. Run migrations: `php artisan migrate`
6. Run seeders: `php artisan db:seed`
7. Setup storage: `php artisan storage:link`

## Kontribusi

Untuk berkontribusi pada project ini, silakan:
1. Fork repository
2. Buat branch fitur baru
3. Commit perubahan
4. Push ke branch
5. Buat Pull Request

## Lisensi

Project ini menggunakan lisensi MIT. Lihat file LICENSE untuk detail lebih lanjut.
