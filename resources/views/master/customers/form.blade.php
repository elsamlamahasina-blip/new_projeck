<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl text-stone-800 leading-tight">
                    {{ $isEdit ? 'Edit Data Pelanggan' : 'Tambah Pelanggan Baru' }}
                </h2>
                <p class="text-xs text-stone-500 mt-1">
                    {{ $isEdit ? 'Perbarui data profil dan ketentuan harga pelanggan' : 'Daftarkan pelanggan baru dengan klasifikasi umum atau reseller' }}
                </p>
            </div>
            <a href="{{ route('master.customers.index') }}" class="text-stone-600 hover:text-stone-900 text-sm font-medium">
                &larr; Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6 sm:p-8">
            <form method="POST" action="{{ $isEdit ? route('master.customers.update', $customer) : route('master.customers.store') }}" class="space-y-6">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Kode Pelanggan -->
                    <div>
                        <label for="code" class="block text-sm font-semibold text-stone-700">
                            Kode Pelanggan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="code" id="code" value="{{ old('code', $customer->code) }}" required
                            placeholder="Contoh: CUST-001 atau RES-01"
                            class="mt-1 block w-full rounded-lg border {{ $errors->has('code') ? 'border-rose-400 focus:ring-rose-400' : 'border-stone-300 focus:ring-amber-500' }} text-sm uppercase">
                        @error('code')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Pelanggan -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-stone-700">
                            Nama Pelanggan / Toko <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $customer->name) }}" required
                            placeholder="Contoh: Toko Batik Madura Cantik"
                            class="mt-1 block w-full rounded-lg border {{ $errors->has('name') ? 'border-rose-400 focus:ring-rose-400' : 'border-stone-300 focus:ring-amber-500' }} text-sm">
                        @error('name')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipe Pelanggan -->
                    <div>
                        <label for="customer_type" class="block text-sm font-semibold text-stone-700">
                            Tipe Pelanggan <span class="text-rose-500">*</span>
                        </label>
                        <select name="customer_type" id="customer_type" required
                            class="mt-1 block w-full rounded-lg border border-stone-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                            <option value="umum" {{ old('customer_type', $customer->customer_type) === 'umum' ? 'selected' : '' }}>Umum (Ritel Standar)</option>
                            <option value="reseller" {{ old('customer_type', $customer->customer_type) === 'reseller' ? 'selected' : '' }}>Reseller (Grosir / Mitra Toko)</option>
                        </select>
                        @error('customer_type')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-stone-400">Menentukan level aturan diskon otomatis pada Sales & POS.</p>
                    </div>

                    <!-- PIC / Kontak -->
                    <div>
                        <label for="contact_name" class="block text-sm font-semibold text-stone-700">
                            Nama Narahubung (PIC)
                        </label>
                        <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name', $customer->contact_name) }}"
                            placeholder="Contoh: Ibu Hj. Maryam"
                            class="mt-1 block w-full rounded-lg border border-stone-300 text-sm focus:ring-amber-500">
                        @error('contact_name')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Telepon / WhatsApp -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-stone-700">
                            Nomor Telepon / WhatsApp
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $customer->phone) }}"
                            placeholder="Contoh: 087812345678"
                            class="mt-1 block w-full rounded-lg border {{ $errors->has('phone') ? 'border-rose-400' : 'border-stone-300' }} text-sm focus:ring-amber-500">
                        @error('phone')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-stone-700">
                            Alamat Email
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}"
                            placeholder="Contoh: info@batikcantik.com"
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
                        <input type="text" name="city" id="city" value="{{ old('city', $customer->city) }}"
                            placeholder="Contoh: Pamekasan, Surabaya, Jakarta"
                            class="mt-1 block w-full rounded-lg border border-stone-300 text-sm focus:ring-amber-500">
                        @error('city')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Diskon Khusus (%) -->
                    <div>
                        <label for="special_discount" class="block text-sm font-semibold text-stone-700">
                            Diskon Khusus Pelanggan (%)
                        </label>
                        <input type="number" step="0.1" min="0" max="100" name="special_discount" id="special_discount"
                            value="{{ old('special_discount', $customer->special_discount ?? 0) }}"
                            class="mt-1 block w-full rounded-lg border border-stone-300 text-sm focus:ring-amber-500">
                        @error('special_discount')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-stone-400">Diskon tambahan tetap (misal: 5% atau 10% untuk reseller loyal).</p>
                    </div>

                    <!-- Batas Piutang (Credit Limit) -->
                    <div>
                        <label for="credit_limit" class="block text-sm font-semibold text-stone-700">
                            Batas Plafon Piutang (Rp)
                        </label>
                        <input type="number" step="10000" min="0" name="credit_limit" id="credit_limit"
                            value="{{ old('credit_limit', $customer->credit_limit ?? 0) }}"
                            placeholder="0 jika wajib lunas tunai"
                            class="mt-1 block w-full rounded-lg border border-stone-300 text-sm focus:ring-amber-500">
                        @error('credit_limit')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Aktif -->
                    <div class="flex items-center pt-6">
                        <label class="relative flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $customer->is_active ?? true) ? 'checked' : '' }}
                                class="w-4 h-4 text-amber-600 border-stone-300 rounded focus:ring-amber-500">
                            <span class="text-sm font-semibold text-stone-700">Status Pelanggan Aktif</span>
                        </label>
                    </div>
                </div>

                <!-- Alamat Lengkap -->
                <div>
                    <label for="address" class="block text-sm font-semibold text-stone-700">
                        Alamat Lengkap / Alamat Kirim
                    </label>
                    <textarea name="address" id="address" rows="3"
                        placeholder="Contoh: Jl. Jokotole No. 88, Pamekasan, Jawa Timur"
                        class="mt-1 block w-full rounded-lg border border-stone-300 text-sm focus:ring-amber-500">{{ old('address', $customer->address) }}</textarea>
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
                        placeholder="Ketentuan pengiriman, ekspedisi langganan, dsb."
                        class="mt-1 block w-full rounded-lg border border-stone-300 text-sm focus:ring-amber-500">{{ old('notes', $customer->notes) }}</textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-stone-200">
                    <a href="{{ route('master.customers.index') }}" class="px-5 py-2.5 rounded-lg border border-stone-300 text-stone-700 text-sm font-medium hover:bg-stone-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-amber-600 text-white text-sm font-semibold hover:bg-amber-700 shadow-sm transition">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Pelanggan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
