<?php

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Contracts\ProductCatalogContract;
use App\Modules\Catalog\DTO\ProductSnapshot;
use App\Modules\Catalog\Exceptions\InsufficientStockException;
use App\Modules\Catalog\Exceptions\ProductNotFoundException;
use App\Modules\Catalog\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductCatalogService implements ProductCatalogContract
{
    public function getForCheckout(int $productId): ProductSnapshot
    {
        $product = Product::with('category')->find($productId);

        if ($product === null) {
            throw new ProductNotFoundException($productId);
        }

        return new ProductSnapshot(
            id: $product->id,
            name: $product->name,
            sku: $product->sku,
            price: (float) $product->price,
            stock: $product->stock,
            isActive: $product->is_active,
            categoryName: $product->category?->name ?? '',
        );
    }

    public function reserveStock(int $productId, int $qty): void
    {
        if ($qty < 1) {
            throw new InsufficientStockException('Produk', 0, $qty);
        }

        DB::transaction(function () use ($productId, $qty): void {
            $product = Product::whereKey($productId)->lockForUpdate()->first();

            if ($product === null || ! $product->is_active) {
                throw new ProductNotFoundException($productId);
            }

            if ($product->stock < $qty) {
                throw new InsufficientStockException($product->name, $product->stock, $qty);
            }

            $product->decrement('stock', $qty);
        });
    }

    public function releaseStock(int $productId, int $qty): void
    {
        if ($qty < 1) {
            return;
        }

        Product::whereKey($productId)->increment('stock', $qty);
    }
}
