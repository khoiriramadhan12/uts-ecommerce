<?php

namespace App\Modules\Catalog\Contracts;

use App\Modules\Catalog\DTO\ProductSnapshot;
use App\Modules\Catalog\Exceptions\InsufficientStockException;
use App\Modules\Catalog\Exceptions\ProductNotFoundException;

/**
 * Satu-satunya pintu masuk modul lain ke data katalog.
 */
interface ProductCatalogContract
{
    /**
     * @throws ProductNotFoundException
     */
    public function getForCheckout(int $productId): ProductSnapshot;

    /**
     * Potong stok secara atomik. Dipanggil di dalam transaksi pemanggil.
     *
     * @throws ProductNotFoundException
     * @throws InsufficientStockException
     */
    public function reserveStock(int $productId, int $qty): void;

    /**
     * Kembalikan stok saat pesanan dibatalkan.
     */
    public function releaseStock(int $productId, int $qty): void;
}
