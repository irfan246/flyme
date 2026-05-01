# Airline Management & Ticket Booking System

Project Laravel untuk website maskapai penerbangan dengan booking tiket online, role-based dashboard, master data operasional, pembayaran, e-ticket, dan laporan.

## Status Implementasi

Tahap 1 sampai 6 sudah diimplementasikan:

- Laravel terbaru sudah dibuat di root project.
- Database default diarahkan ke MySQL localhost dengan nama `airline_laravel`.
- Auth manual berbasis Blade: login, register customer, logout.
- Role-based access control: `customer`, `admin`, `manager`, `ceo`.
- Middleware `role` untuk proteksi route per role.
- Migration dasar dan domain maskapai: role, user, kota, bandara, pesawat, seat, rute, jadwal, kelas tiket, harga, booking, passenger, booking seat, payment, promo, FAQ, kontak, notifikasi, setting.
- Seeder role dan akun demo.
- Seeder data maskapai: kota, bandara, pesawat, seat layout, rute, jadwal, harga tiket, promo, FAQ, dan contoh booking confirmed.
- Dashboard untuk Customer, Admin, Manager, dan CEO.
- Layout publik, layout auth, layout dashboard, sidebar dinamis per role.
- Public site: landing, tentang, rute, pencarian tiket, promo, FAQ, kontak.
- Customer: cari penerbangan, booking online, pilih kursi visual, isi penumpang, upload bukti pembayaran, riwayat, cancel, e-ticket printable.
- Admin: CRUD master data, generate seat map, booking offline, transaksi, konfirmasi pembayaran/booking, cancel, cetak tiket, promo, FAQ, pesan kontak.
- Manager: laporan penjualan, okupansi, rute terlaris, pendapatan, approve/reject promo, export CSV, print PDF via browser.
- CEO: ringkasan bisnis, laporan strategis, grafik sederhana, performa admin/manager, export CSV, print PDF via browser.

## Akun Demo

Semua akun menggunakan password:

```text
password
```

Daftar akun:

```text
customer@example.com
admin@example.com
manager@example.com
ceo@example.com
```

## Setup Lokal

Project ini memakai Laravel 13.7.0. Pastikan MySQL sudah berjalan dan PHP memiliki ekstensi database aktif untuk MySQL (`pdo_mysql`). Untuk menjalankan Composer scripts di Laravel 13, aktifkan juga ekstensi XML/DOM (`php-xml` pada banyak distro Linux).

1. Install dependency:

```bash
composer install
```

Jika PHP lokal belum memiliki ekstensi XML/DOM untuk dependency dev, gunakan sementara hanya untuk mengunduh dependency:

```bash
composer install --ignore-platform-reqs --no-scripts
```

Setelah `php-xml` aktif, jalankan:

```bash
composer dump-autoload
```

2. Buat file environment:

```bash
cp .env.example .env
```

3. Generate application key:

```bash
php artisan key:generate
```

4. Buat database MySQL:

```sql
CREATE DATABASE airline_laravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

5. Pastikan konfigurasi `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=airline_laravel
DB_USERNAME=root
DB_PASSWORD=
```

6. Jalankan migration dan seeder:

```bash
php artisan migrate --seed
```

7. Jalankan server:

```bash
php artisan serve
```

Buka `http://127.0.0.1:8000`.

## File Penting Tahap 1

- `database/migrations/0001_01_01_000000_create_users_table.php`
- `database/migrations/2026_04_29_000100_create_airline_domain_tables.php`
- `database/seeders/DatabaseSeeder.php`
- `app/Models/*.php`
- `app/Http/Middleware/EnsureUserHasRole.php`
- `app/Http/Requests/Auth/LoginRequest.php`
- `app/Http/Requests/Auth/RegisterRequest.php`
- `app/Http/Controllers/AuthController.php`
- `app/Http/Controllers/DashboardController.php`
- `app/Http/Controllers/PublicPageController.php`
- `app/Http/Controllers/Customer/BookingController.php`
- `app/Http/Controllers/Admin/MasterDataController.php`
- `app/Http/Controllers/Admin/BookingManagementController.php`
- `app/Http/Controllers/Admin/PromoContentController.php`
- `app/Http/Controllers/Manager/ReportController.php`
- `app/Http/Controllers/Ceo/ExecutiveReportController.php`
- `app/Services/BookingService.php`
- `bootstrap/app.php`
- `routes/web.php`
- `resources/views/**/*.blade.php`

## Verifikasi di Lingkungan Ini

Berhasil:

```bash
php -l
php artisan route:list
php artisan view:cache
```

Belum bisa dijalankan di container ini:

```bash
php artisan migrate --seed
```

Penyebabnya PHP CLI lokal belum memiliki driver database (`pdo_mysql`, bahkan `pdo_sqlite` juga belum aktif). Setelah ekstensi MySQL aktif, perintah migrate/seed siap dijalankan ke database `airline_laravel`.
