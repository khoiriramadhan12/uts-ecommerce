<?php

namespace App\Modules\Order\Exceptions;

use Exception;
use Illuminate\Http\Request;

class EmptyCartException extends Exception
{
    public function __construct()
    {
        parent::__construct('Keranjang masih kosong. Tambahkan produk sebelum checkout.');
    }

    public function render(Request $request)
    {
        return redirect()->route('cart.index')->with('error', $this->getMessage());
    }
}
