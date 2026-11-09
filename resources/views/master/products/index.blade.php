<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-xl text-stone-800 leading-tight">
                    Master Data Produk Batik & Varian
                </h2>
                <p class="text-xs text-stone-500 mt-1">
                    Katalog batik tulis, batik cap, kain, dan pakaian jadi lengkap dengan varian motif & ukuran (S-XL).
                </p>
            </div>
            <a href="{{ route('master.products.create') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Search & Filter Card -->
        <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-4 mb-6">
            <form method="GET" action="{{ route('master.products.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama batik, kode, SKU varian, atau motif..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-stone-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-stone-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="w-full sm:w-56">
                    <select name="category" class="w-full py-2 px-3 rounded-lg border border-stone-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                        <option value="">Semua Kategori Batik</option>
                        <option value="batik_tulis" {{ $category === 'batik_tulis' ? 'selected' : '' }}>Batik Tulis</option>
                        <option value="batik_cap" {{ $category === 'batik_cap' ? 'selected' : '' }}>Batik Cap</option>
                        <option value="kain" {{ $category === 'kain' ? 'selected' : '' }}>Kain Batik Belum Jadi</option>
                        <option value="pakaian" {{ $category === 'pakaian' ? 'selected' : '' }}>Pakaian Jadi (Kemeja/Gamis)</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-stone-800 text-white text-sm font-medium rounded-lg hover:bg-stone-700 transition">
                        Filter
                    </button>
                    @if($search || $category)
                        <a href="{{ route('master.products.index') }}" class="px-4 py-2 bg-stone-100 text-stone-600 text-sm font-medium rounded-lg hover:bg-stone-200 transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow-sm border border-stone-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-stone-200 text-sm">
                    <thead class="bg-stone-50 text-stone-600 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-6 py-3.5 text-left">Kode & Nama Produk</th>
                            <th class="px-6 py-3.5 text-center">Kategori</th>
                            <th class="px-6 py-3.5 text-center">Jumlah Varian</th>
                            <th class="px-6 py-3.5 text-left">Rentang Harga Jual</th>
                            <th class="px-6 py-3.5 text-center">Total Stok</th>
                            <th class="px-6 py-3.5 text-center">Status</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200 bg-white">
                        @forelse($products as $product)
                            <tr class="hover:bg-amber-50/40 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('master.products.show', $product) }}" class="font-bold text-stone-900 hover:text-amber-800 transition">
                                        {{ $product->name }}
                                    </a>
                                    <div class="mt-0.5 flex items-center gap-2">
                                        <span class="text-xs font-mono font-medium text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                            {{ $product->code }}
                                        </span>
                                        <span class="text-xs text-stone-400">Satuan: {{ $product->uom }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $badgeStyles = [
                                            'batik_tulis' => 'bg-amber-100 text-amber-900 border-amber-300',
                                            'batik_cap' => 'bg-teal-100 text-teal-900 border-teal-300',
                                            'kain' => 'bg-purple-100 text-purple-900 border-purple-300',
                                            'pakaian' => 'bg-sky-100 text-sky-900 border-sky-300',
                                        ];
                                        $badgeLabels = [
                                            'batik_tulis' => 'Batik Tulis',
                                            'batik_cap' => 'Batik Cap',
                                            'kain' => 'Kain Batik',
                                            'pakaian' => 'Pakaian Jadi',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeStyles[$product->category] ?? 'bg-stone-100 text-stone-800' }}">
                                        {{ $badgeLabels[$product->category] ?? ucfirst($product->category) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded bg-stone-100 text-stone-700 font-semibold text-xs">
                                        {{ $product->variants->count() }} Varian
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-stone-800 font-medium">
                                    @if($product->variants->count() > 0)
                                        Rp {{ number_format($product->variants->min('selling_price'), 0, ',', '.') }}
                                        @if($product->variants->min('selling_price') != $product->variants->max('selling_price'))
                                            &ndash; Rp {{ number_format($product->variants->max('selling_price'), 0, ',', '.') }}
                                        @endif
                                    @else
                                        <span class="text-xs text-stone-400 italic">Belum ada varian</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="font-bold text-stone-900">{{ $product->variants->sum('current_stock') }}</span>
                                    <span class="text-xs text-stone-500">{{ $product->uom }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($product->is_active)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-stone-100 text-stone-600">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium space-x-2">
                                    <a href="{{ route('master.products.show', $product) }}" class="text-amber-700 hover:text-amber-900 font-semibold">
                                        Detail & Varian
                                    </a>
                                    <a href="{{ route('master.products.edit', $product) }}" class="text-stone-600 hover:text-stone-900 ml-2">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('master.products.destroy', $product) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini beserta seluruh variannya?');">
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
                                <td colspan="7" class="px-6 py-8 text-center text-stone-500">
                                    Tidak ada data produk ditemukan. Silakan tambah produk batik baru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
                <div class="p-4 border-t border-stone-200">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
