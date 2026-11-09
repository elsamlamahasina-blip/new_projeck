<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sku' => ['required', 'string', 'max:50'],
            'size' => ['required', 'string', 'max:20'],
            'pattern' => ['required', 'string', 'max:100'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'current_stock' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'sku.required' => 'SKU varian wajib diisi.',
            'size.required' => 'Ukuran varian wajib ditentukan.',
            'pattern.required' => 'Motif batik pada varian wajib diisi.',
            'cost_price.required' => 'Harga beli / HPP wajib diisi.',
            'selling_price.required' => 'Harga jual wajib diisi.',
            'min_stock.required' => 'Stok minimum peringatan wajib diisi.',
        ];
    }
}
