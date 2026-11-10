<?php

namespace App\Modules\Catalog\Exceptions;

use Exception;
use Illuminate\Http\Request;

class ProductNotFoundException extends Exception
{
    public function __construct(public readonly int $productId)
    {
        parent::__construct('Produk tidak ditemukan atau sudah tidak dijual.');
    }

    public function render(Request $request)
    {
        return back()->with('error', $this->getMessage());
    }
}
