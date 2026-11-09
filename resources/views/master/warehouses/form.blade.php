<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl text-stone-800 leading-tight">
                    {{ $isEdit ? 'Edit Lokasi Gudang' : 'Tambah Gudang Baru' }}
                </h2>
                <p class="text-xs text-stone-500 mt-1">
                    {{ $isEdit ? 'Perbarui informasi dan kapasitas gudang' : 'Konfigurasi lokasi penyimpanan inventaris toko dan workshop batik' }}
                </p>
            </div>
            <a href="{{ route('master.warehouses.index') }}" class="text-stone-600 hover:text-stone-900 text-sm font-medium">
                &larr; Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6 sm:p-8">
            <form method="POST" action="{{ $isEdit ? route('master.warehouses.update', $warehouse) : route('master.warehouses.store') }}" class="space-y-6">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Kode Gudang -->
                    <div>
                        <label for="code" class="block text-sm font-semibold text-stone-700">
                            Kode Gudang <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="code" id="code" value="{{ old('code', $warehouse->code) }}" required
                            placeholder="Contoh: GDG-BTK atau GDG-BHN"
                            class="mt-1 block w-full rounded-lg border {{ $errors->has('code') ? 'border-rose-400 focus:ring-rose-400' : 'border-stone-300 focus:ring-amber-500' }} text-sm uppercase">
                        @error('code')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Gudang -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-stone-700">
                            Nama Gudang <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $warehouse->name) }}" required
                            placeholder="Contoh: Gudang Utama Batik Pamekasan"
                            class="mt-1 block w-full rounded-lg border {{ $errors->has('name') ? 'border-rose-400 focus:ring-rose-400' : 'border-stone-300 focus:ring-amber-500' }} text-sm">
                        @error('name')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipe Gudang -->
                    <div>
                        <label for="warehouse_type" class="block text-sm font-semibold text-stone-700">
                            Tipe Gudang <span class="text-rose-500">*</span>
                        </label>
                        <select name="warehouse_type" id="warehouse_type" required
                            class="mt-1 block w-full rounded-lg border border-stone-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                            <option value="batik" {{ old('warehouse_type', $warehouse->warehouse_type) === 'batik' ? 'selected' : '' }}>Gudang Batik Jadi (Pakaian, Kain Jadi)</option>
                            <option value="bahan" {{ old('warehouse_type', $warehouse->warehouse_type) === 'bahan' ? 'selected' : '' }}>Gudang Bahan Baku (Mori, Lilin, Pewarna)</option>
                            <option value="lainnya" {{ old('warehouse_type', $warehouse->warehouse_type) === 'lainnya' ? 'selected' : '' }}>Gudang Lainnya / Transit</option>
                        </select>
                        @error('warehouse_type')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-stone-400">Pemisahan gudang batik vs bahan (kebutuhan RPS & temuan artikel).</p>
                    </div>

                    <!-- Lokasi Fisik -->
                    <div>
                        <label for="location" class="block text-sm font-semibold text-stone-700">
                            Lokasi / Lantai / Ruangan
                        </label>
                        <input type="text" name="location" id="location" value="{{ old('location', $warehouse->location) }}"
                            placeholder="Contoh: Lantai 2 Toko Utama, Jl. Jokotole"
                            class="mt-1 block w-full rounded-lg border border-stone-300 text-sm focus:ring-amber-500">
                        @error('location')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kapasitas -->
                    <div>
                        <label for="capacity" class="block text-sm font-semibold text-stone-700">
                            Estimasi Kapasitas Simpan (pcs / unit)
                        </label>
                        <input type="number" min="0" name="capacity" id="capacity"
                            value="{{ old('capacity', $warehouse->capacity ?? 5000) }}"
                            class="mt-1 block w-full rounded-lg border border-stone-300 text-sm focus:ring-amber-500">
                        @error('capacity')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Aktif -->
                    <div class="flex items-center pt-6">
                        <label class="relative flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $warehouse->is_active ?? true) ? 'checked' : '' }}
                                class="w-4 h-4 text-amber-600 border-stone-300 rounded focus:ring-amber-500">
                            <span class="text-sm font-semibold text-stone-700">Status Gudang Aktif</span>
                        </label>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-stone-700">
                        Deskripsi / Keterangan Gudang
                    </label>
                    <textarea name="description" id="description" rows="3"
                        placeholder="Contoh: Khusus penyimpanan kain batik tulis halus dan sarung batik sutra."
                        class="mt-1 block w-full rounded-lg border border-stone-300 text-sm focus:ring-amber-500">{{ old('description', $warehouse->description) }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-stone-200">
                    <a href="{{ route('master.warehouses.index') }}" class="px-5 py-2.5 rounded-lg border border-stone-300 text-stone-700 text-sm font-medium hover:bg-stone-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-amber-600 text-white text-sm font-semibold hover:bg-amber-700 shadow-sm transition">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Gudang' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
