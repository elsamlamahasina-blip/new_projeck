<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'customer_type',
        'email',
        'phone',
        'address',
        'special_discount',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'special_discount' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}
