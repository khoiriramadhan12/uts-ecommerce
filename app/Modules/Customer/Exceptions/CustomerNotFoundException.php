<?php

namespace App\Modules\Customer\Exceptions;

use Exception;
use Illuminate\Http\Request;

class CustomerNotFoundException extends Exception
{
    public function __construct(public readonly int $userId)
    {
        parent::__construct('Profil pelanggan tidak ditemukan. Hubungi admin.');
    }

    public function render(Request $request)
    {
        return redirect()->route('home')->with('error', $this->getMessage());
    }
}
