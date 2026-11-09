<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-xl text-stone-800 leading-tight">
                    Master Data Pelanggan
                </h2>
                <p class="text-xs text-stone-500 mt-1">
                    Kelola data pelanggan ritel umum dan reseller/grosir batik tulis & cap.
                </p>
            </div>
            <a href="{{ route('master.customers.create') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Pelanggan
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Search & Filter Card -->
        <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-4 mb-6">
            <form method="GET" action="{{ route('master.customers.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari kode, nama pelanggan, atau nomor telepon..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-stone-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-stone-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="w-full sm:w-48">
                    <select name="type" class="w-full py-2 px-3 rounded-lg border border-stone-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                        <option value="">Semua Tipe Pelanggan</option>
                        <option value="umum" {{ $type === 'umum' ? 'selected' : '' }}>Umum (Ritel)</option>
                        <option value="reseller" {{ $type === 'reseller' ? 'selected' : '' }}>Reseller (Grosir)</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-stone-800 text-white text-sm font-medium rounded-lg hover:bg-stone-700 transition">
                        Filter
                    </button>
                    @if($search || $type)
                        <a href="{{ route('master.customers.index') }}" class="px-4 py-2 bg-stone-100 text-stone-600 text-sm font-medium rounded-lg hover:bg-stone-200 transition">
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
                            <th class="px-6 py-3.5 text-left">Kode & Nama Pelanggan</th>
                            <th class="px-6 py-3.5 text-center">Tipe</th>
                            <th class="px-6 py-3.5 text-left">Kontak & Telepon</th>
                            <th class="px-6 py-3.5 text-left">Kota & Alamat</th>
                            <th class="px-6 py-3.5 text-right">Diskon Khusus</th>
                            <th class="px-6 py-3.5 text-center">Status</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200 bg-white">
                        @forelse($customers as $customer)
                            <tr class="hover:bg-amber-50/40 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-stone-900">{{ $customer->name }}</div>
                                    <span class="inline-block mt-0.5 text-xs font-mono font-medium text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                        {{ $customer->code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($customer->customer_type === 'reseller')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">
                                            Reseller
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-stone-100 text-stone-700">
                                            Umum
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-stone-600">
                                    <div class="font-medium text-stone-800">{{ $customer->contact_name ?? '-' }}</div>
                                    <div class="text-xs text-stone-500">{{ $customer->phone ?? '-' }}</div>
                                    @if($customer->email)
                                        <div class="text-xs text-stone-400">{{ $customer->email }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-stone-600 max-w-xs truncate">
                                    {{ $customer->city ? $customer->city . ' — ' : '' }}{{ $customer->address ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-semibold text-stone-700">
                                    {{ number_format($customer->special_discount, 1) }}%
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($customer->is_active)
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
                                    <a href="{{ route('master.customers.edit', $customer) }}" class="text-amber-700 hover:text-amber-900 font-semibold">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('master.customers.destroy', $customer) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?');">
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
                                    Tidak ada data pelanggan ditemukan. Silakan tambah pelanggan baru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($customers->hasPages())
                <div class="p-4 border-t border-stone-200">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
