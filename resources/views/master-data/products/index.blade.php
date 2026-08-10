@extends('layouts.app')

@section('content')
<div class="space-y-6">
                <!-- Header -->
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-1">Daftar Barang</h1>
                        <p class="text-sm text-gray-500">Kelola data barang dan stok gudang</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('products.export', request()->query()) }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium text-gray-700 flex items-center">
                            <i class="fas fa-file-excel text-emerald-600 mr-2"></i>Export CSV/Excel
                        </a>
                        <button onclick="window.print()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium text-gray-700 flex items-center">
                            <i class="fas fa-print text-gray-600 mr-2"></i>Print
                        </button>
                        <a href="{{ route('products.create') }}" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i>Tambah Barang
                        </a>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                    <form method="GET" class="flex items-center gap-4">
                        <div class="relative flex-1 max-w-md">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama barang..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                        </div>
                        <select name="category" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        <select name="status" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm">
                            <option value="">Semua Status</option>
                            <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <button type="submit" class="px-6 py-2.5 bg-gray-700 text-white rounded-lg hover:bg-gray-800 text-sm font-medium">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                    </form>
                </div>

                <!-- Table -->
                <div class="bg-white border border-gray-200 rounded-lg overflow-visible">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kode Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nama Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Satuan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Stok</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Harga Jual</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-semibold text-blue-600">{{ $product->product_code }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center">
                                                <i class="fas fa-box text-gray-400"></i>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">{{ $product->product_name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">{{ $product->category }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $product->unit }}</td>
                                    <td class="px-6 py-4">
                                        @if($product->system_stock < 100)
                                            <span class="text-sm font-semibold text-red-600">{{ number_format($product->system_stock) }}</span>
                                        @else
                                            <span class="text-sm font-semibold text-gray-900">{{ number_format($product->system_stock) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                     <td class="px-6 py-4">
                                         <div class="relative inline-block text-left action-menu-container">
                                             <button type="button" onclick="toggleActionMenu(this, event)" class="p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition border border-gray-200 shadow-sm" title="Aksi">
                                                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                                 </svg>
                                             </button>
                                              <div class="action-menu-popup absolute right-0 top-full mt-1 z-50 bg-white border border-gray-200 rounded-xl shadow-xl p-1.5 items-center gap-1 min-w-max" style="display: none;">
                                                 <a href="{{ route('products.edit', $product->id) }}" title="Edit Produk" class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition">
                                                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                     </svg>
                                                 </a>
                                                 <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                                     @csrf
                                                     @method('DELETE')
                                                     <button type="submit" title="Hapus Produk" class="p-2 text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-lg transition">
                                                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                         </svg>
                                                     </button>
                                                 </form>
                                             </div>
                                         </div>
                                     </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                        <p>Tidak ada data barang</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($products->hasPages())
                    <div class="mt-6 flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Menampilkan {{ $products->firstItem() }} dari {{ $products->total() }} barang
                        </div>
                        <div>{{ $products->links() }}</div>
                    </div>
                @endif
</div>
@endsection
