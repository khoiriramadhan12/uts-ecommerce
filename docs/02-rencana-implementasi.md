# Rencana Implementasi — UTS Backend Development

Dokumen ini melanjutkan `01-rancangan.md`. Semua perintah dijalankan dari folder
`uts-ecommerce/`.

> Status implementasi: aplikasi sudah dibangun dan seluruh pengujian lulus
> (`php artisan test`: 16 test). Database yang dipakai saat ini **SQLite**, karena
> server MySQL/MariaDB di mesin pengembangan tidak dapat dijalankan tanpa akses
> administrator. Migrasi dibuat portabel sehingga pindah ke MySQL hanya perlu
> mengubah `.env`.

---

## 1. Persiapan Lingkungan

| Kebutuhan | Status di mesin ini | Tindakan |
|---|---|---|
| PHP 8.4+ | PHP 8.5.0 (Herd Lite) | siap |
| Composer | 2.8.12 | siap |
| Node + NPM | v24, npm 11 | siap untuk Vite/Tailwind |
| Git | tersedia | siap |
| Database | SQLite aktif, MySQL belum bisa dijalankan | lihat bagian 1.1 |

### 1.1 Opsi database

SQLite sudah dipakai dan tidak butuh langkah tambahan (file `database/database.sqlite`).
Kalau dosen mewajibkan MySQL, gunakan salah satu opsi berikut:

**Opsi A — MariaDB dari XAMPP (paling cepat, versi 10.4):**

```bash
/Applications/XAMPP/xamppfiles/bin/mysql.server start
/Applications/XAMPP/xamppfiles/bin/mysql -u root -e "CREATE DATABASE uts_ecommerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

**Opsi B — MySQL 8 via Homebrew (paling sesuai dokumentasi Laravel):**

```bash
brew install mysql
brew services start mysql
mysql -u root -e "CREATE DATABASE uts_ecommerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

**Hindari** MySQL 5.7 bawaan MAMP untuk Laravel 13.

Isi `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=uts_ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

---

## 2. Membuat Project

```bash
composer create-project laravel/laravel:^13.0 uts-ecommerce
cd uts-ecommerce
composer require laravel/sanctum
php artisan install:api          # opsional, hanya jika butuh endpoint token
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

Jika tag `^13.0` belum tersedia di Packagist, cek versi lebih dulu:

```bash
composer show laravel/framework --all | head -20
```

---

## 3. Urutan Pengerjaan

Urutan ini menjaga setiap commit tetap bisa dijalankan.

1. **Fondasi**
   - Buat `app/Modules/` dan tiga folder modul.
   - Daftarkan tiga Service Provider di `bootstrap/providers.php`.
   - Tambah kolom `role` pada migration `users`.
   - Middleware `EnsureUserHasRole`, alias `role` di `bootstrap/app.php`.
2. **Autentikasi**
   - Controller login/register/logout, Form Request, layout Blade, route guest.
   - Seeder akun admin dan customer.
3. **CatalogModule**
   - Migration `categories`, `products`, `tags`, `product_tag`, factory, seeder.
   - CRUD admin, contract + service, custom exception stok.
4. **CustomerModule**
   - Migration `customers`, `addresses`, factory, seeder.
   - Profil dan alamat, contract `CustomerProfileContract`.
5. **OrderModule**
   - Migration `carts`, `cart_items`, `orders`, `order_items`.
   - Cart service, checkout dengan `DB::transaction`, pembatalan.
   - Event `OrderPlaced` / `OrderPaid` / `OrderCancelled` + listener log.
6. **Penyelesaian**
   - `vendor/bin/pint`
   - Feature test minimal: checkout sukses, checkout stok kurang, akses admin oleh customer.
   - Screenshot untuk laporan, rekam video demo.

---

## 4. Struktur Direktori Target

```text
uts-ecommerce/
├── app/
│   ├── Http/Middleware/EnsureUserHasRole.php
│   ├── Models/User.php
│   └── Modules/
│       ├── Catalog/
│       ├── Customer/
│       └── Order/
├── bootstrap/
│   ├── app.php
│   └── providers.php
├── database/
│   ├── migrations/        # hanya tabel users, cache, jobs
│   └── seeders/DatabaseSeeder.php
├── resources/views/
│   ├── layouts/app.blade.php
│   ├── auth/{login,register}.blade.php
│   ├── catalog/           # admin + katalog publik
│   ├── customer/          # profil + alamat
│   └── order/             # cart, checkout, pesanan
├── routes/web.php         # route umum + auth
└── docs/
    ├── 01-rancangan.md
    └── 02-rencana-implementasi.md
```

Migration dan route modul **tidak** ditaruh di `database/migrations` dan `routes/`.
Keduanya dimuat oleh Service Provider modul:

```php
public function boot(): void
{
    $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
    $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
}
```

---

## 5. Konvensi Git

Format pesan commit:

```text
feat(catalog): tambah CRUD produk
feat(order): checkout dengan validasi stok
fix(customer): cek kepemilikan alamat
test(order): feature test checkout gagal
docs(laporan): tambah ERD bab II
```

Aturan:

- Satu commit satu tujuan, jangan menumpuk di akhir.
- Setiap anggota commit dengan akun Git masing-masing agar kontribusi terlihat.
- Branch: `main` stabil, kerja di `feat/<modul>` lalu merge.

---

## 6. Skenario Uji untuk Laporan (BAB IV)

| No | Skenario | Hasil yang diharapkan | Screenshot |
|---|---|---|---|
| 1 | Login admin, buka `/admin/products` | halaman CRUD tampil | ya |
| 2 | Login customer, buka `/admin/products` | 403 / redirect dengan pesan | ya |
| 3 | Simpan produk dengan harga `-1` | pesan validasi pada field harga | ya |
| 4 | Tambah produk ke keranjang | item muncul, qty bertambah | ya |
| 5 | Checkout dengan stok cukup | order `pending`, stok berkurang | ya |
| 6 | Checkout dengan qty > stok | alert "Stok ... tinggal X", stok tidak berubah, tidak ada order baru | ya |
| 7 | Batalkan order `pending` | status `cancelled`, stok kembali | ya |
| 8 | Checkout ke alamat milik customer lain | alert "Alamat tidak valid" | ya |
| 9 | `php artisan test` | semua test lulus | ya |
| 10 | `git log --oneline --graph --all` | commit per anggota terlihat | ya |

---

## 7. Deliverable Akhir

1. Repository GitHub **public** berisi kode dan folder `docs/`.
2. `UTS_BackendDevelopment_<Kelas>_<NamaKelompok>.pdf` diunggah ke Waskita.
3. Video demo YouTube **unlisted**, maksimal 10 menit, link di laporan dan di README.
4. Form peer assessment.
