<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl text-stone-800 leading-tight">
                    {{ $isEdit ? 'Edit Informasi Produk Batik' : 'Tambah Produk Batik Baru' }}
                </h2>
                <p class="text-xs text-stone-500 mt-1">
                    {{ $isEdit ? 'Perbarui informasi induk katalog produk' : 'Daftarkan model batik dan inisialisasi varian ukuran & motif' }}
                </p>
            </div>
            <a href="{{ route('master.products.index') }}" class="text-stone-600 hover:text-stone-900 text-sm font-medium">
                &larr; Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6 sm:p-8"
            x-data="{
                code: '{{ old('code', $product->code) }}',
                variants: {{ json_encode(old('variants', $isEdit ? [] : [
                    ['size' => 'M', 'pattern' => 'Motif Utama', 'cost_price' => 150000, 'selling_price' => 250000, 'min_stock' => 5],
                    ['size' => 'L', 'pattern' => 'Motif Utama', 'cost_price' => 150000, 'selling_price' => 250000, 'min_stock' => 5],
                ])) }},
                addVariant() {
                    let nextSku = this.code ? (this.code + '-VAR-' + (this.variants.length + 1)) : '';
                    this.variants.push({
                        sku: nextSku,
                        size: 'All Size',
                        pattern: 'Motif Standar',
                        cost_price: 150000,
                        selling_price: 250000,
                        min_stock: 5
                    });
                },
                removeVariant(idx) {
                    if (this.variants.length > 1) {
                        this.variants.splice(idx, 1);
                    }
                }
            }">

            <form method="POST" action="{{ $isEdit ? route('master.products.update', $product) : route('master.products.store') }}" class="space-y-6">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Kode Produk -->
                    <div>
                        <label for="code" class="block text-sm font-semibold text-stone-700">
                            Kode Produk <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="code" id="code" x-model="code" required
                            placeholder="Contoh: BTK-TL-001"
                            class="mt-1 block w-full rounded-lg border {{ $errors->has('code') ? 'border-rose-400 focus:ring-rose-400' : 'border-stone-300 focus:ring-amber-500' }} text-sm uppercase">
                        @error('code')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Produk -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-stone-700">
                            Nama Model Batik <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                            placeholder="Contoh: Batik Tulis Sekar Jagad Pamekasan"
                            class="mt-1 block w-full rounded-lg border {{ $errors->has('name') ? 'border-rose-400 focus:ring-rose-400' : 'border-stone-300 focus:ring-amber-500' }} text-sm">
                        @error('name')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="category" class="block text-sm font-semibold text-stone-700">
                            Kategori Batik <span class="text-rose-500">*</span>
                        </label>
                        <select name="category" id="category" required
                            class="mt-1 block w-full rounded-lg border border-stone-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                            <option value="batik_tulis" {{ old('category', $product->category) === 'batik_tulis' ? 'selected' : '' }}>Batik Tulis</option>
                            <option value="batik_cap" {{ old('category', $product->category) === 'batik_cap' ? 'selected' : '' }}>Batik Cap</option>
                            <option value="kain" {{ old('category', $product->category) === 'kain' ? 'selected' : '' }}>Kain Batik Lembaran</option>
                            <option value="pakaian" {{ old('category', $product->category) === 'pakaian' ? 'selected' : '' }}>Pakaian Jadi (Kemeja/Dress/Gamis)</option>
                        </select>
                        @error('category')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Satuan Ukuran (UoM) -->
                    <div>
                        <label for="uom" class="block text-sm font-semibold text-stone-700">
                            Satuan Ukuran (UoM) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="uom" id="uom" value="{{ old('uom', $product->uom ?? 'pcs') }}" required
                            placeholder="pcs, lembar, meter"
                            class="mt-1 block w-full rounded-lg border border-stone-300 text-sm focus:ring-amber-500">
                        @error('uom')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-stone-700">
                        Deskripsi / Filosofi Motif Batik
                    </label>
                    <textarea name="description" id="description" rows="3"
                        placeholder="Deskripsi keunikan motif, filosofi khas Pamekasan Madura, bahan kain mori, dsb."
                        class="mt-1 block w-full rounded-lg border border-stone-300 text-sm focus:ring-amber-500">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Aktif -->
                <div class="flex items-center">
                    <label class="relative flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                            class="w-4 h-4 text-amber-600 border-stone-300 rounded focus:ring-amber-500">
                        <span class="text-sm font-semibold text-stone-700">Status Produk Aktif Dijual</span>
                    </label>
                </div>

                @if(!$isEdit)
                    <!-- Varian Produk Awal -->
                    <div class="pt-6 border-t border-stone-200">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h3 class="font-bold text-stone-800 text-base">Inisialisasi Varian Batik</h3>
                                <p class="text-xs text-stone-500">Setiap produk memiliki variasi ukuran (S/M/L/XL/All Size) dan motif.</p>
                            </div>
                            <button type="button" @click="addVariant()"
                                class="inline-flex items-center px-3 py-1.5 bg-amber-50 border border-amber-300 text-amber-900 rounded-lg text-xs font-semibold hover:bg-amber-100 transition">
                                + Tambah Varian
                            </button>
                        </div>

                        <div class="space-y-3">
                            <template x-for="(v, idx) in variants" :key="idx">
                                <div class="p-3 bg-stone-50 rounded-lg border border-stone-200 grid grid-cols-1 sm:grid-cols-6 gap-3 items-center">
                                    <div>
                                        <label class="block text-[11px] font-semibold text-stone-600 uppercase">Ukuran</label>
                                        <select :name="'variants[' + idx + '][size]'" x-model="v.size" class="w-full text-xs rounded border-stone-300 py-1.5">
                                            <option value="S">S</option>
                                            <option value="M">M</option>
                                            <option value="L">L</option>
                                            <option value="XL">XL</option>
                                            <option value="XXL">XXL</option>
                                            <option value="All Size">All Size</option>
                                        </select>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block text-[11px] font-semibold text-stone-600 uppercase">Motif / Corak</label>
                                        <input type="text" :name="'variants[' + idx + '][pattern]'" x-model="v.pattern" required placeholder="Nama corak motif" class="w-full text-xs rounded border-stone-300 py-1.5">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-semibold text-stone-600 uppercase">HPP (Rp)</label>
                                        <input type="number" :name="'variants[' + idx + '][cost_price]'" x-model="v.cost_price" required min="0" class="w-full text-xs rounded border-stone-300 py-1.5">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-semibold text-stone-600 uppercase">Harga Jual (Rp)</label>
                                        <input type="number" :name="'variants[' + idx + '][selling_price]'" x-model="v.selling_price" required min="0" class="w-full text-xs rounded border-stone-300 py-1.5">
                                    </div>
                                    <div class="flex items-center justify-between gap-1">
                                        <div>
                                            <label class="block text-[11px] font-semibold text-stone-600 uppercase">Stok Min</label>
                                            <input type="number" :name="'variants[' + idx + '][min_stock]'" x-model="v.min_stock" min="0" class="w-16 text-xs rounded border-stone-300 py-1.5">
                                        </div>
                                        <button type="button" @click="removeVariant(idx)" class="mt-4 text-rose-500 hover:text-rose-700 p-1" title="Hapus varian">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-stone-200">
                    <a href="{{ route('master.products.index') }}" class="px-5 py-2.5 rounded-lg border border-stone-300 text-stone-700 text-sm font-medium hover:bg-stone-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-amber-600 text-white text-sm font-semibold hover:bg-amber-700 shadow-sm transition">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Produk & Varian' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
