<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for product variants.
     */
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('sku', 50)->unique();
            $table->string('size', 20)->default('All Size'); // S, M, L, XL, XXL, All Size, 2m, 2.5m
            $table->string('pattern', 100); // Motif: Sekar Jagad, Tarum, Gentongan, Lok Can, dll.
            $table->decimal('cost_price', 12, 2)->default(0.00); // Harga Beli / HPP
            $table->decimal('selling_price', 12, 2)->default(0.00); // Harga Jual
            $table->integer('min_stock')->default(5); // Batas minimum stok (Reordering Rule)
            $table->integer('current_stock')->default(0); // Stok aktual real-time
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Cegah duplikasi kombinasi varian ukuran dan motif di bawah satu produk
            $table->unique(['product_id', 'size', 'pattern'], 'uq_prod_size_pattern');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
