<?php

namespace App\Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'qty' => ['required', 'integer', 'min:1', 'max:99'],
        ];
    }

    public function messages(): array
    {
        return [
            'qty.min' => 'Jumlah minimal 1. Hapus item jika tidak ingin membeli.',
            'qty.max' => 'Jumlah maksimal 99 per produk.',
        ];
    }
}
