# Toko UTS — Sistem E-Commerce Sederhana

Tugas UTS mata kuliah **DT199 Backend Development** (Semester Ganjil 2026/2027).
Aplikasi e-commerce modular monolith dibangun dengan **Laravel 13**, **Laravel Sanctum**,
**Blade**, dan **PHP 8.4+**.

## Studi Kasus

Sistem E-Commerce Sederhana dengan tiga modul:

| Modul | Isi |
|---|---|
| `CatalogModule` | kategori, produk, stok, tag |
| `CustomerModule` | profil pelanggan, alamat pengiriman |
| `OrderModule` | keranjang, checkout, pesanan, item pesanan |

Dokumen rancangan lengkap (bounded context, ERD, kontrak, pembagian tugas) ada di
[`docs/01-rancangan.md`](docs/01-rancangan.md). Rencana implementasi ada di
[`docs/02-rencana-implementasi.md`](docs/02-rencana-implementasi.md).

## Menjalankan

```bash
composer install
cp .env.example .env
php artisan key:generate

touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

Buka `http://127.0.0.1:8000`.

### Akun demo

| Role | Email | Password |
|---|---|---|
| Admin | `admin@example.com` | `password` |
| Customer | `customer@example.com` | `password` |

## Struktur Modular

```text
app/Modules/
├── Catalog/
│   ├── Contracts/ProductCatalogContract.php
│   ├── Database/{Migrations,Factories,Seeders}
│   ├── Http/{Controllers,Requests}
│   ├── Models/{Category,Product,Tag}
│   ├── Providers/CatalogServiceProvider.php
│   ├── Routes/web.php
│   └── Services/ProductCatalogService.php
├── Customer/
│   ├── Contracts/CustomerProfileContract.php
│   ├── Listeners/CreateCustomerProfile.php
│   └── ...
└── Order/
    ├── Events/{OrderPlaced,OrderPaid,OrderCancelled}.php
    ├── Services/{CartService,CheckoutService,OrderService}.php
    └── ...
```

Setiap modul mendaftarkan migration dan route lewat Service Provider sendiri
(`loadMigrationsFrom` dan `loadRoutesFrom`).

### Komunikasi antar modul

- **Contract** (sinkron, di dalam transaksi):
  `ProductCatalogContract` untuk baca produk, potong stok, dan kembalikan stok;
  `CustomerProfileContract` untuk profil dan validasi kepemilikan alamat.
- **Domain Event** (setelah commit): `OrderPlaced` dan `OrderCancelled` dipakai
  modul katalog untuk memperbarui statistik penjualan; listener order menulis log.

`OrderModule` tidak pernah meng-import model `Product` atau `Customer` secara langsung.

## Fitur Wajib

- CRUD produk, kategori, dan tag oleh admin.
- Keranjang belanja dan checkout dengan validasi stok.
- Snapshot harga, nama produk, dan alamat saat checkout.
- Pembayaran simulasi dan pembatalan pesanan (stok dikembalikan).
- RBAC dua role (`admin`, `customer`) lewat middleware `role`.
- Form Request di setiap form dan custom exception yang menampilkan pesan di view.
- `DB::transaction` pada checkout dan pembatalan.
- Migration, seeder, factory, relasi One-to-Many dan Many-to-Many (pivot `product_tag`).

## Pengujian

```bash
php artisan test
vendor/bin/pint --test
```

Cakupan: alur checkout, rollback saat stok kurang, pembatalan pesanan, kepemilikan
alamat, RBAC, autentikasi, dan smoke test seluruh halaman.

## Database

Default memakai **SQLite** agar mudah dijalankan tanpa server tambahan. Migrasi
ditulis portabel sehingga bisa dipindah ke **MySQL** hanya dengan mengubah `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=uts_ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

Laravel 13 membutuhkan MySQL 8+ (atau MariaDB 10.4+). MySQL 5.7 bawaan MAMP
tidak disarankan.

## Lisensi

Kode ini dibuat untuk keperluan akademik.
