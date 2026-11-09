<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-mono font-medium text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                        {{ $product->code }}
                    </span>
                    <h2 class="font-bold text-xl text-stone-800 leading-tight">
                        {{ $product->name }}
                    </h2>
                </div>
                <p class="text-xs text-stone-500 mt-1">
                    Detail katalog produk, konfigurasi varian ukuran (S-XL) dan motif batik khas Pamekasan.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('master.products.edit', $product) }}" class="inline-flex items-center px-3 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-semibold rounded-lg transition">
                    Edit Info Produk
                </a>
                <a href="{{ route('master.products.index') }}" class="inline-flex items-center px-3 py-2 bg-stone-800 hover:bg-stone-700 text-white text-xs font-semibold rounded-lg transition">
                    &larr; Kembali ke Katalog
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6" x-data="{
        showAddModal: false,
        showEditModal: false,
        editVariant: {
            id: null,
            sku: '',
            size: 'M',
            pattern: '',
            cost_price: 0,
            selling_price: 0,
            min_stock: 5,
            is_active: 1
        },
        openEdit(v) {
            this.editVariant = Object.assign({}, v);
            this.showEditModal = true;
        }
    }">

        <!-- Product Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-xl border border-stone-200 shadow-sm">
                <div class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Kategori Batik</div>
                <div class="mt-2 text-lg font-bold text-amber-800">
                    {{ ucfirst(str_replace('_', ' ', $product->category)) }}
                </div>
                <div class="text-xs text-stone-400 mt-1">Satuan: {{ $product->uom }}</div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-stone-200 shadow-sm">
                <div class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Jumlah Varian</div>
                <div class="mt-2 text-2xl font-black text-stone-900">
                    {{ $product->variants->count() }} <span class="text-sm font-normal text-stone-500">item</span>
                </div>
                <div class="text-xs text-stone-400 mt-1">Kombinasi motif & ukuran</div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-stone-200 shadow-sm">
                <div class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Total Stok Tersedia</div>
                <div class="mt-2 text-2xl font-black text-emerald-700">
                    {{ $product->variants->sum('current_stock') }} <span class="text-sm font-normal text-stone-500">{{ $product->uom }}</span>
                </div>
                <div class="text-xs text-stone-400 mt-1">Seluruh varian aktif</div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-stone-200 shadow-sm">
                <div class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Status Katalog</div>
                <div class="mt-2">
                    @if($product->is_active)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                            Aktif Dijual
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-stone-100 text-stone-600">
                            Nonaktif
                        </span>
                    @endif
                </div>
                <div class="text-xs text-stone-400 mt-2 truncate">{{ $product->description ?: 'Tidak ada deskripsi' }}</div>
            </div>
        </div>

        <!-- Variants Section -->
        <div class="bg-white rounded-xl shadow-sm border border-stone-200 overflow-hidden">
            <div class="p-5 border-b border-stone-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-stone-50/50">
                <div>
                    <h3 class="font-bold text-stone-900 text-base">Daftar Varian Ukuran & Motif</h3>
                    <p class="text-xs text-stone-500">Setiap varian memiliki SKU unik, HPP, harga jual ritel, dan batas stok minimum untuk Reordering Rule.</p>
                </div>
                <button type="button" @click="showAddModal = true"
                    class="inline-flex items-center px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Varian Baru
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-stone-200 text-sm">
                    <thead class="bg-stone-50 text-stone-600 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-6 py-3.5 text-left">SKU Varian</th>
                            <th class="px-6 py-3.5 text-center">Ukuran</th>
                            <th class="px-6 py-3.5 text-left">Motif / Corak</th>
                            <th class="px-6 py-3.5 text-right">Harga Pokok (HPP)</th>
                            <th class="px-6 py-3.5 text-right">Harga Jual</th>
                            <th class="px-6 py-3.5 text-center">Stok Min (Rule)</th>
                            <th class="px-6 py-3.5 text-center">Stok Saat Ini</th>
                            <th class="px-6 py-3.5 text-center">Status</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200 bg-white">
                        @forelse($product->variants as $variant)
                            <tr class="hover:bg-amber-50/40 transition">
                                <td class="px-6 py-4 whitespace-nowrap font-mono text-xs font-bold text-stone-900">
                                    {{ $variant->sku }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded font-bold text-xs bg-stone-100 text-stone-800">
                                        {{ $variant->size }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-stone-700 font-medium">
                                    {{ $variant->pattern }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-stone-500">
                                    Rp {{ number_format($variant->cost_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-stone-900">
                                    Rp {{ number_format($variant->selling_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-semibold text-amber-700">
                                    {{ $variant->min_stock }} {{ $product->uom }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($variant->current_stock <= $variant->min_stock)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800" title="Stok kritis / mendekati batas minimum">
                                            {{ $variant->current_stock }} {{ $product->uom }} &bull; Menipis
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                            {{ $variant->current_stock }} {{ $product->uom }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($variant->is_active)
                                        <span class="text-xs text-emerald-600 font-semibold">Aktif</span>
                                    @else
                                        <span class="text-xs text-stone-400 font-semibold">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium space-x-2">
                                    <button type="button" @click="openEdit({{ $variant->toJson() }})"
                                        class="text-amber-700 hover:text-amber-900 font-semibold">
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('master.products.variants.destroy', [$product, $variant]) }}" class="inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus varian {{ $variant->sku }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-900 font-semibold ml-2">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-stone-500">
                                    Belum ada varian untuk produk ini. Klik tombol "Tambah Varian Baru" di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL: Tambah Varian Baru -->
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showAddModal" @click="showAddModal = false" class="fixed inset-0 bg-stone-900/60 transition-opacity"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showAddModal" class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form method="POST" action="{{ route('master.products.variants.store', $product) }}">
                        @csrf
                        <div class="bg-amber-800 px-6 py-4 text-white">
                            <h3 class="text-base font-bold">Tambah Varian Batik Baru</h3>
                            <p class="text-xs text-amber-200 mt-0.5">Produk: {{ $product->name }} ({{ $product->code }})</p>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-stone-700 uppercase">Ukuran <span class="text-rose-500">*</span></label>
                                    <select name="size" required class="mt-1 block w-full rounded-lg border-stone-300 text-sm focus:ring-amber-500">
                                        <option value="S">S</option>
                                        <option value="M" selected>M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                        <option value="XXL">XXL</option>
                                        <option value="All Size">All Size</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-stone-700 uppercase">SKU (Opsional)</label>
                                    <input type="text" name="sku" placeholder="Otomatis jika kosong"
                                        class="mt-1 block w-full rounded-lg border-stone-300 text-sm uppercase">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-stone-700 uppercase">Nama Motif / Corak <span class="text-rose-500">*</span></label>
                                <input type="text" name="pattern" required placeholder="Contoh: Gentongan Khas Madura"
                                    class="mt-1 block w-full rounded-lg border-stone-300 text-sm focus:ring-amber-500">
                                <p class="mt-1 text-[11px] text-stone-400">Kombinasi motif & ukuran harus unik dalam satu produk.</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-stone-700 uppercase">Harga Pokok (HPP) <span class="text-rose-500">*</span></label>
                                    <input type="number" name="cost_price" required min="0" value="150000"
                                        class="mt-1 block w-full rounded-lg border-stone-300 text-sm focus:ring-amber-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-stone-700 uppercase">Harga Jual Ritel <span class="text-rose-500">*</span></label>
                                    <input type="number" name="selling_price" required min="0" value="250000"
                                        class="mt-1 block w-full rounded-lg border-stone-300 text-sm focus:ring-amber-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-stone-700 uppercase">Stok Minimum (Reorder Rule)</label>
                                    <input type="number" name="min_stock" min="0" value="5"
                                        class="mt-1 block w-full rounded-lg border-stone-300 text-sm focus:ring-amber-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-stone-700 uppercase">Stok Awal</label>
                                    <input type="number" name="current_stock" min="0" value="0"
                                        class="mt-1 block w-full rounded-lg border-stone-300 text-sm focus:ring-amber-500">
                                </div>
                            </div>
                        </div>

                        <div class="bg-stone-50 px-6 py-3 flex items-center justify-end gap-2 border-t border-stone-200">
                            <button type="button" @click="showAddModal = false" class="px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-200 rounded-lg">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 text-xs font-semibold bg-amber-600 hover:bg-amber-700 text-white rounded-lg shadow-sm">
                                Simpan Varian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL: Edit Varian -->
        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditModal" @click="showEditModal = false" class="fixed inset-0 bg-stone-900/60 transition-opacity"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showEditModal" class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form method="POST" :action="'{{ url('/master/products/' . $product->id . '/variants') }}/' + editVariant.id">
                        @csrf
                        @method('PUT')
                        <div class="bg-stone-800 px-6 py-4 text-white">
                            <h3 class="text-base font-bold">Edit Varian: <span x-text="editVariant.sku" class="font-mono text-amber-300"></span></h3>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-stone-700 uppercase">Ukuran <span class="text-rose-500">*</span></label>
                                    <select name="size" x-model="editVariant.size" required class="mt-1 block w-full rounded-lg border-stone-300 text-sm focus:ring-amber-500">
                                        <option value="S">S</option>
                                        <option value="M">M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                        <option value="XXL">XXL</option>
                                        <option value="All Size">All Size</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-stone-700 uppercase">SKU</label>
                                    <input type="text" name="sku" x-model="editVariant.sku" required
                                        class="mt-1 block w-full rounded-lg border-stone-300 text-sm uppercase">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-stone-700 uppercase">Nama Motif / Corak <span class="text-rose-500">*</span></label>
                                <input type="text" name="pattern" x-model="editVariant.pattern" required
                                    class="mt-1 block w-full rounded-lg border-stone-300 text-sm focus:ring-amber-500">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-stone-700 uppercase">Harga Pokok (HPP) <span class="text-rose-500">*</span></label>
                                    <input type="number" name="cost_price" x-model="editVariant.cost_price" required min="0"
                                        class="mt-1 block w-full rounded-lg border-stone-300 text-sm focus:ring-amber-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-stone-700 uppercase">Harga Jual Ritel <span class="text-rose-500">*</span></label>
                                    <input type="number" name="selling_price" x-model="editVariant.selling_price" required min="0"
                                        class="mt-1 block w-full rounded-lg border-stone-300 text-sm focus:ring-amber-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-stone-700 uppercase">Stok Minimum (Reorder Rule)</label>
                                    <input type="number" name="min_stock" x-model="editVariant.min_stock" min="0"
                                        class="mt-1 block w-full rounded-lg border-stone-300 text-sm focus:ring-amber-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-stone-700 uppercase">Stok Fisik Saat Ini</label>
                                    <input type="number" name="current_stock" x-model="editVariant.current_stock" min="0"
                                        class="mt-1 block w-full rounded-lg border-stone-300 text-sm focus:ring-amber-500">
                                </div>
                            </div>
                        </div>

                        <div class="bg-stone-50 px-6 py-3 flex items-center justify-end gap-2 border-t border-stone-200">
                            <button type="button" @click="showEditModal = false" class="px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-200 rounded-lg">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 text-xs font-semibold bg-amber-600 hover:bg-amber-700 text-white rounded-lg shadow-sm">
                                Simpan Perubahan Varian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
