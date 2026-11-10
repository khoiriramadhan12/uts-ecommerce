<?php

namespace App\Modules\Order\Exceptions;

use Exception;
use Illuminate\Http\Request;

class OrderNotPayableException extends Exception
{
    public function __construct(public readonly string $status)
    {
        parent::__construct("Pesanan berstatus \"{$status}\" tidak bisa diproses untuk aksi ini.");
    }

    public function render(Request $request)
    {
        return back()->with('error', $this->getMessage());
    }
}
