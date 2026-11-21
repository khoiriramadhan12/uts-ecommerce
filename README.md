# Tokoku

Aplikasi e-commerce sederhana untuk UTS mata kuliah Back End Development. Dibangun dengan Laravel 13 sebagai modular monolith: satu aplikasi, satu deployment, tetapi batas antar modul ditegakkan lewat contract dan domain event.

- Nama: Khoiri Ramadhan
- NIM: 25.01.5289
- Program Studi Teknik Informatika, Universitas AMIKOM Yogyakarta
- Video demo: https://youtu.be/qdUbsR4oYdc

## Modul

Kode domain ada di `app/Modules`, dipisah per bounded context.

| Modul | Isi |
|---|---|
| Catalog | Produk, kategori, tag, stok, katalog publik |
| Customer | Profil pelanggan, alamat pengiriman |
| Order | Keranjang, checkout, pembayaran, pembatalan |

Order tidak mengakses model milik modul lain. Semua akses lewat `ProductCatalogContract` dan `CustomerProfileContract`. Efek samping setelah transaksi dikirim lewat domain event `OrderPlaced`, `OrderPaid`, dan `OrderCancelled`.

## Fitur

- Autentikasi Laravel Sanctum, dua role: admin dan customer.
- Protected route dengan middleware role. Customer yang membuka halaman admin mendapat HTTP 403.
- CRUD produk, kategori, dan tag, termasuk relasi many-to-many dengan pivot.
- Keranjang dengan perubahan jumlah dan subtotal otomatis.
- Checkout di dalam `DB::transaction` dengan `lockForUpdate`, sehingga stok tidak bisa minus dan pesanan tidak tersimpan setengah jadi.
- Pembayaran simulasi dan pembatalan pesanan, stok dikembalikan lewat event.
- Validasi 13 Form Request dan 6 custom exception.

## Cara menjalankan

Butuh PHP 8.3 atau lebih baru, Composer, dan SQLite.

```bash
git clone https://github.com/khoiriramadhan12/uts-ecommerce.git
cd uts-ecommerce
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

Buka http://127.0.0.1:8000.

## Akun demo

| Role | Email | Password |
|---|---|---|
| Admin | admin@example.com | password |
| Customer | customer@example.com | password |

Seeder membuat satu pelanggan dengan satu alamat utama. Untuk menguji penolakan alamat milik akun lain, daftarkan akun kedua lewat halaman register.

## Pengujian

```bash
php artisan test
```

16 tes, 63 asersi, mencakup autentikasi, RBAC, alur checkout, dan smoke test halaman.

Cek gaya kode:

```bash
vendor/bin/pint --test
```

## Dokumen

- Video demo (YouTube, unlisted): https://youtu.be/qdUbsR4oYdc
- Laporan UTS: dilampirkan terpisah saat pengumpulan
