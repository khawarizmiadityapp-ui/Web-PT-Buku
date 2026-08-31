@extends('layouts.app')

@section('title', 'Barang Keluar (Stock Out) - PT Nusantara ERP')

@section('content')
<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Barang Keluar (Stock Out)</h1>
        <p class="text-sm text-gray-500">Manage and track outbound shipments to customers.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('stock-outs.export', request()->query()) }}" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm font-medium">
            <i class="fas fa-download text-gray-600"></i>
            <span class="text-gray-700">Export CSV</span>
        </a>
        <a href="{{ route('stock-outs.create') }}" class="flex items-center gap-2 px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium shadow-sm">
            <i class="fas fa-plus"></i>
            <span>Input Barang Keluar</span>
        </a>
    </div>
</div>

<!-- Filters & Search (Auto Filter without button) -->
<div class="bg-white border border-gray-200 rounded-xl p-4 mb-6 shadow-sm">
    <form method="GET" action="{{ route('stock-outs.index') }}" class="flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex-1 flex flex-wrap items-center gap-3 w-full md:w-auto">
            <div class="relative flex-1 min-w-[200px] max-w-xs">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search TRX ID or Customer..." 
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                    onchange="this.form.submit()"
                >
            </div>

            <!-- Date Range -->
            <input 
                type="date" 
                name="date_from" 
                value="{{ request('date_from') }}"
                title="Dari Tanggal"
                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm"
                onchange="this.form.submit()"
            >
            <span class="text-gray-400 text-sm">-</span>
            <input 
                type="date" 
                name="date_to" 
                value="{{ request('date_to') }}"
                title="Sampai Tanggal"
                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm"
                onchange="this.form.submit()"
            >

            <!-- Status Filter -->
            <select name="status" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">All Statuses</option>
                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                <option value="In-Progress" {{ request('status') == 'In-Progress' ? 'selected' : '' }}>In-Progress</option>
                <option value="Canceled" {{ request('status') == 'Canceled' ? 'selected' : '' }}>Canceled</option>
            </select>

            @if(request()->hasAny(['search', 'date_from', 'date_to', 'status']))
                <a href="{{ route('stock-outs.index') }}" class="px-3 py-2 text-xs font-semibold text-gray-500 hover:text-gray-800 transition flex items-center gap-1">
                    <i class="fas fa-times-circle"></i> Reset Filter
                </a>
            @endif
        </div>
    </form>
</div>
<div class="bg-white border border-gray-200 rounded-xl overflow-visible shadow-sm">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Transaction ID</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer Name</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Items</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Recipient Name</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($stockOuts as $stockOut)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('stock-outs.show', $stockOut) }}" class="text-sm font-semibold text-blue-600 hover:underline">
                            {{ $stockOut->transaction_id }}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ $stockOut->date ? $stockOut->date->format('M d, Y') : '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        {{ $stockOut->customer_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ number_format($stockOut->total_items) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ $stockOut->recipient_name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($stockOut->status == 'Completed')
                            <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Completed</span>
                        @elseif($stockOut->status == 'In-Progress')
                            <span class="px-3 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">In-Progress</span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Canceled</span>
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
                                <a href="{{ route('stock-outs.show', $stockOut) }}" title="Detail Barang Keluar" class="p-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('stock-outs.edit', $stockOut) }}" title="Edit Data" class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form id="delete-form-{{ $stockOut->id }}" action="{{ route('stock-outs.destroy', $stockOut) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" title="Hapus Data" onclick="confirmDelete('delete-form-{{ $stockOut->id }}', 'Apakah Anda yakin ingin menghapus data barang keluar ini?')" class="p-2 text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-lg transition">
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
                        <p class="text-sm font-medium">Tidak ada data barang keluar</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination Footer -->
    @include('partials.pagination', ['paginator' => $stockOuts])
</div>
@endsection

