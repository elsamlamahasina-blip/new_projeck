<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'customer_type' => ['required', 'string', 'in:umum,reseller'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:25'],
            'address' => ['nullable', 'string', 'max:1000'],
            'special_discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode pelanggan wajib diisi.',
            'name.required' => 'Nama pelanggan wajib diisi.',
            'customer_type.required' => 'Tipe pelanggan wajib dipilih.',
            'customer_type.in' => 'Tipe pelanggan harus umum atau reseller.',
            'email.email' => 'Format email pelanggan tidak valid.',
            'special_discount.min' => 'Diskon khusus tidak boleh negatif.',
            'special_discount.max' => 'Diskon khusus maksimal 100%.',
        ];
    }
}
