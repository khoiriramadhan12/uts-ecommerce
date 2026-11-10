<?php

namespace App\Modules\Customer\Exceptions;

use Exception;
use Illuminate\Http\Request;

class AddressNotOwnedException extends Exception
{
    public function __construct(public readonly int $addressId)
    {
        parent::__construct('Alamat pengiriman tidak valid atau bukan milik akun Anda.');
    }

    public function render(Request $request)
    {
        return back()->withInput()->with('error', $this->getMessage());
    }
}
