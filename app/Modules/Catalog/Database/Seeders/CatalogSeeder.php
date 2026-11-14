<?php

namespace App\Modules\Catalog\Database\Seeders;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            ['name' => 'Elektronik', 'description' => 'Perangkat elektronik dan aksesori.'],
            ['name' => 'Pakaian', 'description' => 'Pakaian pria dan wanita.'],
            ['name' => 'Buku', 'description' => 'Buku cetak dan referensi.'],
            ['name' => 'Rumah Tangga', 'description' => 'Peralatan rumah tangga.'],
        ])->mapWithKeys(function (array $row) {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($row['name'])],
                ['name' => $row['name'], 'description' => $row['description'], 'is_active' => true],
            );

            return [$category->slug => $category];
        });

        $tags = collect(['promo', 'baru', 'best-seller', 'garansi', 'hemat', 'premium'])
            ->mapWithKeys(fn (string $name) => [
                $name => Tag::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => Str::title($name)],
                ),
            ]);

        $products = [
            [
                'category' => 'elektronik', 'sku' => 'ELK-001', 'stock' => 18, 'price' => 1499000,
                'name' => 'Keychron K8 Pro TKL Mechanical Keyboard',
                'description' => 'Keyboard mekanik wireless 80% dengan switch Gateron Brown hot-swappable, koneksi Bluetooth 5.1 dan USB-C, serta baterai 4000 mAh. Menggunakan keycap PBT double-shot dan kompatibel dengan Windows maupun macOS.',
            ],
            [
                'category' => 'elektronik', 'sku' => 'ELK-002', 'stock' => 42, 'price' => 249000,
                'name' => 'Logitech M330 Silent Plus Wireless Mouse',
                'description' => 'Mouse wireless senyap dengan sensor 1000 DPI dan tombol klik yang 90% lebih senyap. Satu baterai AA bertahan hingga 24 bulan, cocok untuk bekerja di kantor, kafe, atau ruang belajar bersama.',
            ],
            [
                'category' => 'elektronik', 'sku' => 'ELK-003', 'stock' => 9, 'price' => 1499000,
                'name' => 'LG 24MP400 Monitor IPS 24 Inci Full HD',
                'description' => 'Monitor 23,8 inci dengan panel IPS Full HD 1920x1080, refresh rate 75 Hz, dan dukungan AMD FreeSync. Tersedia port HDMI dan D-Sub, plus mode Reader serta Flicker Safe untuk mengurangi kelelahan mata.',
            ],
            [
                'category' => 'elektronik', 'sku' => 'ELK-004', 'stock' => 0, 'price' => 699000,
                'name' => 'HyperX Cloud Stinger 2 Gaming Headset',
                'description' => 'Headset gaming wired dengan driver 50 mm, mikrofon noise-cancelling yang dapat dilipat, dan bobot ringan 275 gram. Konektor 3,5 mm kompatibel dengan PC, PlayStation, Xbox, dan Nintendo Switch.',
            ],
            [
                'category' => 'pakaian', 'sku' => 'PKN-001', 'stock' => 60, 'price' => 199000,
                'name' => 'Uniqlo U Crew Neck T-Shirt',
                'description' => 'Kaos lengan pendek pria berbahan katun heavyweight dengan potongan relax fit. Jahitan rapat, tidak mudah melar, dan tersedia dalam warna netral yang mudah dipadukan untuk pemakaian harian.',
            ],
            [
                'category' => 'pakaian', 'sku' => 'PKN-002', 'stock' => 25, 'price' => 399000,
                'name' => 'Uniqlo Flannel Checked Shirt',
                'description' => 'Kemeja flanel lengan panjang dengan motif kotak-kotak klasik dari katun brushed yang lembut. Potongan regular fit, nyaman dipakai sendiri maupun sebagai lapisan luar jaket.',
            ],
            [
                'category' => 'pakaian', 'sku' => 'PKN-003', 'stock' => 14, 'price' => 329000,
                'name' => 'Erigo Bomber Canvas Jacket',
                'description' => 'Jaket bomber pria dari kain kanvas tebal dengan lapisan dalam quilted. Dilengkapi rib pada kerah, manset, dan pinggang, dua kantong depan, serta ritsleting logam yang awet.',
            ],
            [
                'category' => 'buku', 'sku' => 'BKU-001', 'stock' => 12, 'price' => 380000,
                'name' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'description' => 'Buku karya Robert C. Martin yang membahas prinsip penulisan kode bersih: penamaan, fungsi, komentar, penanganan error, dan refactoring. Edisi bahasa Inggris, 464 halaman, diterbitkan Prentice Hall.',
            ],
            [
                'category' => 'buku', 'sku' => 'BKU-002', 'stock' => 7, 'price' => 520000,
                'name' => 'Refactoring: Improving the Design of Existing Code (2nd Edition)',
                'description' => 'Edisi kedua karya Martin Fowler yang memuat katalog teknik refactoring dengan contoh JavaScript. Membahas cara memperbaiki struktur kode tanpa mengubah perilakunya, 448 halaman, diterbitkan Addison-Wesley.',
            ],
            [
                'category' => 'buku', 'sku' => 'BKU-003', 'stock' => 10, 'price' => 465000,
                'name' => 'Laravel: Up & Running (3rd Edition)',
                'description' => 'Panduan praktis Laravel karya Matt Stauffer yang mencakup routing, Eloquent, Blade, autentikasi, queue, dan deployment. Edisi ketiga, lebih dari 500 halaman, diterbitkan O\'Reilly Media.',
            ],
            [
                'category' => 'rumah-tangga', 'sku' => 'RMT-001', 'stock' => 33, 'price' => 249000,
                'name' => 'Xiaomi Mi LED Desk Lamp 1S',
                'description' => 'Lampu meja LED dengan empat mode pencahayaan dan tingkat kecerahan yang diatur lewat tombol sentuh. Dilengkapi port USB untuk mengisi daya, hemat energi, dan lengan lipat yang fleksibel.',
            ],
            [
                'category' => 'rumah-tangga', 'sku' => 'RMT-002', 'stock' => 6, 'price' => 899000,
                'name' => 'IKEA BILLY Rak Buku Putih 80x28x202 cm',
                'description' => 'Rak buku setinggi 202 cm dengan lima susun yang ketinggiannya dapat diatur. Rangka partikel board berlapis finishing putih, mudah dirakit, dan bisa ditambah unit lain sesuai kebutuhan ruangan.',
            ],
        ];

        foreach ($products as $i => $row) {
            $product = Product::firstOrCreate(
                ['sku' => $row['sku']],
                [
                    'category_id' => $categories[$row['category']]->id,
                    'name' => $row['name'],
                    'slug' => Str::slug($row['name']),
                    'description' => $row['description'],
                    'price' => $row['price'],
                    'stock' => $row['stock'],
                    'is_active' => true,
                ],
            );

            if (! $product->wasRecentlyCreated) {
                $slug = Str::slug($row['name']);

                if (Product::where('slug', $slug)->whereKeyNot($product->id)->exists()) {
                    $slug .= '-'.$product->id;
                }

                $product->update([
                    'name' => $row['name'],
                    'slug' => $slug,
                    'description' => $row['description'],
                    'price' => $row['price'],
                ]);
            }

            $product->tags()->syncWithoutDetaching([
                $tags[$i % 2 === 0 ? 'promo' : 'baru']->id,
                $tags[$i % 3 === 0 ? 'best-seller' : 'hemat']->id,
            ]);
        }
    }
}
