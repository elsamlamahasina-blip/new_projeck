<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseRequest extends FormRequest
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
            'warehouse_type' => ['required', 'string', 'in:batik,bahan,umum'],
            'location' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode gudang wajib diisi.',
            'name.required' => 'Nama gudang wajib diisi.',
            'warehouse_type.required' => 'Tipe gudang wajib ditentukan.',
            'warehouse_type.in' => 'Tipe gudang harus salah satu dari: batik, bahan, umum.',
        ];
    }
}
