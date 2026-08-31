@extends('layouts.app')

@section('content')
<div class="space-y-6">
                <!-- Page Header -->
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 mb-1">Daftar Supplier</h1>
                    <p class="text-sm text-gray-500">Kelola data vendor dan pemasok barang</p>
                </div>

                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-3"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-green-600 hover:text-green-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                <!-- Filters & Actions -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                    <form method="GET" action="{{ route('suppliers.index') }}" class="flex items-center justify-between gap-4">
                        <!-- Search -->
                        <div class="flex-1 flex items-center gap-3">
                            <div class="relative flex-1 max-w-md">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="{{ request('search') }}"
                                    placeholder="Cari supplier..." 
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                >
                            </div>

                            <!-- Status Filter -->
                            <select name="status" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="">All Statuses</option>
                                <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Non-aktif" {{ request('status') == 'Non-aktif' ? 'selected' : '' }}>Non-aktif</option>
                            </select>

                            <button type="submit" class="px-6 py-2.5 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition text-sm font-medium">
                                <i class="fas fa-filter mr-2"></i>Filter
                            </button>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-3">
                            <a href="{{ route('suppliers.export', request()->query()) }}" class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm font-medium">
                                <i class="fas fa-download text-gray-600"></i>
                                <span class="text-gray-700">Export CSV</span>
                            </a>
                            <a href="{{ route('suppliers.create') }}" class="flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                                <i class="fas fa-plus"></i>
                                <span>Tambah Supplier</span>
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="bg-white border border-gray-200 rounded-lg overflow-visible">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID Supplier</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Perusahaan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact Person</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Telepon</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($suppliers as $supplier)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-blue-600">{{ $supplier->supplier_code }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $supplier->company_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $supplier->city }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $supplier->contact_person ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $supplier->phone }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $supplier->email ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($supplier->status == 'Aktif')
                                            <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Non-aktif</span>
                                        @endif
                                    </td>
                                     <td class="px-6 py-4 whitespace-nowrap text-sm">
                                         <div class="relative inline-block text-left action-menu-container">
                                             <button type="button" onclick="toggleActionMenu(this, event)" class="p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition border border-gray-200 shadow-sm" title="Aksi">
                                                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                                 </svg>
                                             </button>
                                              <div class="action-menu-popup absolute right-0 top-full mt-1 z-50 bg-white border border-gray-200 rounded-xl shadow-xl p-1.5 items-center gap-1 min-w-max" style="display: none;">
                                                 <a href="{{ route('suppliers.edit', $supplier) }}" title="Edit Supplier" class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition">
                                                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                     </svg>
                                                 </a>
                                                 <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus supplier ini?')">
                                                     @csrf
                                                     @method('DELETE')
                                                     <button type="submit" title="Hapus Supplier" class="p-2 text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-lg transition">
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
                                        <p class="text-sm">Tidak ada data supplier</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination Footer -->
                    @include('partials.pagination', ['paginator' => $suppliers])
                </div>
</div>
@endsection
