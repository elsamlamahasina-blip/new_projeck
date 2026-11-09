<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl text-stone-800 leading-tight">
                    {{ $isEdit ? 'Edit Data Vendor' : 'Tambah Vendor Baru' }}
                </h2>
                <p class="text-xs text-stone-500 mt-1">
                    {{ $isEdit ? 'Perbarui informasi vendor pemasok' : 'Daftarkan pemasok bahan baku batik dengan kode unik' }}
                </p>
            </div>
            <a href="{{ route('master.vendors.index') }}" class="text-stone-600 hover:text-stone-900 text-sm font-medium">
                &larr; Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6 sm:p-8">
            <form method="POST" action="{{ $isEdit ? route('master.vendors.update', $vendor) : route('master.vendors.store') }}" class="space-y-6">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Kode Vendor -->
                    <div>
                        <label for="code" class="block text-sm font-semibold text-stone-700">
                            Kode Vendor <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="code" id="code" value="{{ old('code', $vendor->code) }}" required
                            placeholder="Contoh: VND-MORI-01"
                            class="mt-1 block w-full rounded-lg border {{ $errors->has('code') ? 'border-rose-400 focus:ring-rose-400' : 'border-stone-300 focus:ring-amber-500' }} text-sm uppercase">
                        @error('code')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-stone-400">Kode unik sebagai identitas unik vendor.</p>
                    </div>

                    <!-- Nama Vendor -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-stone-700">
                            Nama Vendor / Perusahaan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $vendor->name) }}" required
                            placeholder="Contoh: PT Mori Madura Tekstil"
                            class="mt-1 block w-full rounded-lg border {{ $errors->has('name') ? 'border-rose-400 focus:ring-rose-400' : 'border-stone-300 focus:ring-amber-500' }} text-sm">
                        @error('name')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="category" class="block text-sm font-semibold text-stone-700">
                            Kategori Pasokan
                        </label>
                        <input type="text" name="category" id="category" value="{{ old('category', $vendor->category) }}"
                            placeholder="Contoh: Kain Mori, Pewarna Alam, Malam/Lilin"
                            class="mt-1 block w-full rounded-lg border border-stone-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                        @error('category')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Kontak PIC -->
                    <div>
                        <label for="contact_name" class="block text-sm font-semibold text-stone-700">
                            Nama Narahubung (PIC)
                        </label>
                        <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name', $vendor->contact_name) }}"
                            placeholder="Contoh: Bpk. H. Abdurrahman"
                            class="mt-1 block w-full rounded-lg border border-stone-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                        @error('contact_name')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Telepon / WhatsApp -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-stone-700">
                            Nomor Telepon / WhatsApp
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $vendor->phone) }}"
                            placeholder="Contoh: 08123456789"
                            class="mt-1 block w-full rounded-lg border border-stone-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                        @error('phone')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-stone-700">
                            Alamat Email
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $vendor->email) }}"
                            placeholder="Contoh: purchasing@morimadura.co.id"
                            class="mt-1 block w-full rounded-lg border {{ $errors->has('email') ? 'border-rose-400' : 'border-stone-300' }} text-sm focus:ring-amber-500">
                        @error('email')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kota -->
                    <div>
                        <label for="city" class="block text-sm font-semibold text-stone-700">
                            Kota / Kabupaten
                        </label>
                        <input type="text" name="city" id="city" value="{{ old('city', $vendor->city) }}"
                            placeholder="Contoh: Pamekasan, Pekalongan, Solo"
                            class="mt-1 block w-full rounded-lg border border-stone-300 text-sm focus:ring-amber-500">
                        @error('city')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Aktif -->
                    <div class="flex items-center pt-6">
                        <label class="relative flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $vendor->is_active ?? true) ? 'checked' : '' }}
                                class="w-4 h-4 text-amber-600 border-stone-300 rounded focus:ring-amber-500">
                            <span class="text-sm font-semibold text-stone-700">Status Vendor Aktif</span>
                        </label>
                    </div>
                </div>

                <!-- Alamat Lengkap -->
                <div>
                    <label for="address" class="block text-sm font-semibold text-stone-700">
                        Alamat Lengkap
                    </label>
                    <textarea name="address" id="address" rows="3"
                        placeholder="Contoh: Jl. Raya Proppo No. 45, Pamekasan, Madura"
                        class="mt-1 block w-full rounded-lg border border-stone-300 text-sm focus:ring-amber-500">{{ old('address', $vendor->address) }}</textarea>
                    @error('address')
                        <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Catatan Tambahan -->
                <div>
                    <label for="notes" class="block text-sm font-semibold text-stone-700">
                        Catatan Tambahan
                    </label>
                    <textarea name="notes" id="notes" rows="2"
                        placeholder="Syarat pembayaran, diskon volume, dll."
                        class="mt-1 block w-full rounded-lg border border-stone-300 text-sm focus:ring-amber-500">{{ old('notes', $vendor->notes) }}</textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-stone-200">
                    <a href="{{ route('master.vendors.index') }}" class="px-5 py-2.5 rounded-lg border border-stone-300 text-stone-700 text-sm font-medium hover:bg-stone-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-amber-600 text-white text-sm font-semibold hover:bg-amber-700 shadow-sm transition">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Vendor' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
