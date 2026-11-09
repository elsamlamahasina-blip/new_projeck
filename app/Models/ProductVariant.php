<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'size',
        'pattern',
        'cost_price',
        'selling_price',
        'min_stock',
        'current_stock',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'cost_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'min_stock' => 'integer',
            'current_stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relasi ke produk induk.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
