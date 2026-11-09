<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:30'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:batik_tulis,batik_cap,kain,pakaian'],
            'uom' => ['required', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['sometimes', 'boolean'],

            // Validasi varian awal opsional
            'variants' => ['nullable', 'array'],
            'variants.*.sku' => ['required_with:variants', 'string', 'max:50'],
            'variants.*.size' => ['required_with:variants', 'string', 'max:20'],
            'variants.*.pattern' => ['required_with:variants', 'string', 'max:100'],
            'variants.*.cost_price' => ['nullable', 'numeric', 'min:0'],
            'variants.*.selling_price' => ['nullable', 'numeric', 'min:0'],
            'variants.*.min_stock' => ['nullable', 'integer', 'min:0'],
            'variants.*.current_stock' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode produk wajib diisi.',
            'name.required' => 'Nama produk wajib diisi.',
            'category.required' => 'Kategori produk batik wajib dipilih.',
            'category.in' => 'Kategori harus berupa: batik_tulis, batik_cap, kain, atau pakaian.',
            'uom.required' => 'Satuan produk (UOM) wajib diisi.',
        ];
    }
}
