<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
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
            'category' => ['required', 'string', 'in:kain,pewarna,aksesori,lainnya'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:25'],
            'address' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode vendor wajib diisi.',
            'name.required' => 'Nama vendor wajib diisi.',
            'category.required' => 'Kategori bahan vendor wajib dipilih.',
            'category.in' => 'Kategori vendor harus salah satu dari: kain, pewarna, aksesori, lainnya.',
            'email.email' => 'Format alamat email tidak valid.',
        ];
    }
}
