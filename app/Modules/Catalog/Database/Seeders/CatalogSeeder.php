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
            ['category' => 'elektronik', 'sku' => 'ELK-001', 'name' => 'Keyboard Mekanik TKL', 'price' => 650000, 'stock' => 18],
            ['category' => 'elektronik', 'sku' => 'ELK-002', 'name' => 'Mouse Wireless Silent', 'price' => 185000, 'stock' => 42],
            ['category' => 'elektronik', 'sku' => 'ELK-003', 'name' => 'Monitor 24 Inci IPS', 'price' => 1750000, 'stock' => 9],
            ['category' => 'elektronik', 'sku' => 'ELK-004', 'name' => 'Headset USB Gaming', 'price' => 420000, 'stock' => 0],
            ['category' => 'pakaian', 'sku' => 'PKN-001', 'name' => 'Kaos Polos Cotton Combed', 'price' => 85000, 'stock' => 60],
            ['category' => 'pakaian', 'sku' => 'PKN-002', 'name' => 'Kemeja Flanel Lengan Panjang', 'price' => 210000, 'stock' => 25],
            ['category' => 'pakaian', 'sku' => 'PKN-003', 'name' => 'Jaket Bomber Kanvas', 'price' => 320000, 'stock' => 14],
            ['category' => 'buku', 'sku' => 'BKU-001', 'name' => 'Clean Code', 'price' => 285000, 'stock' => 12],
            ['category' => 'buku', 'sku' => 'BKU-002', 'name' => 'Refactoring Edisi Kedua', 'price' => 410000, 'stock' => 7],
            ['category' => 'buku', 'sku' => 'BKU-003', 'name' => 'Laravel Up and Running', 'price' => 375000, 'stock' => 10],
            ['category' => 'rumah-tangga', 'sku' => 'RMT-001', 'name' => 'Lampu Meja LED', 'price' => 125000, 'stock' => 33],
            ['category' => 'rumah-tangga', 'sku' => 'RMT-002', 'name' => 'Rak Buku Minimalis', 'price' => 540000, 'stock' => 6],
        ];

        foreach ($products as $i => $row) {
            $product = Product::firstOrCreate(
                ['sku' => $row['sku']],
                [
                    'category_id' => $categories[$row['category']]->id,
                    'name' => $row['name'],
                    'slug' => Str::slug($row['name']),
                    'description' => 'Produk contoh untuk demo UTS. '.$row['name'].' berkualitas dan siap dikirim.',
                    'price' => $row['price'],
                    'stock' => $row['stock'],
                    'is_active' => true,
                ],
            );

            $product->tags()->syncWithoutDetaching([
                $tags[$i % 2 === 0 ? 'promo' : 'baru']->id,
                $tags[$i % 3 === 0 ? 'best-seller' : 'hemat']->id,
            ]);
        }
    }
}
