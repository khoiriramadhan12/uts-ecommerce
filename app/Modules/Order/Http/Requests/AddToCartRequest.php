<?php

namespace App\Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'min:1'],
            'qty' => ['required', 'integer', 'min:1', 'max:99'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Produk tidak dikenali.',
            'qty.min' => 'Jumlah minimal 1.',
            'qty.max' => 'Jumlah maksimal 99 per produk.',
        ];
    }
}
