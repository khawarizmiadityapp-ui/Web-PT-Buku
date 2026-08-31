@extends('layouts.app')

@section('title', 'Purchase Orders - PT Nusantara ERP')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Purchase Order (Pengadaan Barang)</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola dokumen pesanan pembelian ke supplier dan persetujuan pengadaan</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('purchases.export', request()->query()) }}" class="px-4 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg shadow-sm transition flex items-center gap-2 text-sm">
                <i class="fas fa-download text-gray-600"></i>
                <span>Export CSV</span>
            </a>
            <a href="{{ route('purchases.create') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition flex items-center gap-2 text-sm">
                <i class="fas fa-plus"></i>
                <span>Buat Purchase Order</span>
            </a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total PO -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total PO</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_po']) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                    <i class="fas fa-file-invoice"></i>
                </div>
            </div>
        </div>

        <!-- Pending Approval -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pending Approval</p>
                    <h3 class="text-2xl font-bold text-amber-600 mt-1">{{ number_format($stats['pending_approval']) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <!-- Approved -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Approved PO</p>
                    <h3 class="text-2xl font-bold text-emerald-600 mt-1">{{ number_format($stats['approved']) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <!-- Total Budget -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Anggaran PO</p>
                    <h3 class="text-xl font-bold text-gray-900 mt-1">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search (Auto Filter without button) -->
    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
        <form method="GET" action="{{ route('purchases.index') }}" class="flex flex-col lg:flex-row items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto flex-1">
                <!-- Search -->
                <div class="relative flex-1 min-w-[220px]">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari No. PO atau Supplier..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                        onchange="this.form.submit()"
                    >
                </div>

                <!-- Date Range -->
                <input 
                    type="date" 
                    name="date_from" 
                    value="{{ request('date_from') }}"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm"
                    title="Dari Tanggal"
                    onchange="this.form.submit()"
                >
                <span class="text-gray-400 text-sm">-</span>
                <input 
                    type="date" 
                    name="date_to" 
                    value="{{ request('date_to') }}"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm"
                    title="Sampai Tanggal"
                    onchange="this.form.submit()"
                >

                <!-- Status Filter -->
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                    <option value="">Semua Status</option>
                    <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                    <option value="Pending Approval" {{ request('status') == 'Pending Approval' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Received" {{ request('status') == 'Received' ? 'selected' : '' }}>Received</option>
                    <option value="Canceled" {{ request('status') == 'Canceled' ? 'selected' : '' }}>Canceled</option>
                </select>

                @if(request()->hasAny(['search', 'date_from', 'date_to', 'status']))
                    <a href="{{ route('purchases.index') }}" class="px-3 py-2 text-xs font-semibold text-gray-500 hover:text-gray-800 transition flex items-center gap-1">
                        <i class="fas fa-times-circle"></i> Reset Filter
                    </a>
                @endif
            </div>
        </form>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl overflow-visible shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-600 uppercase tracking-wider">No. PO</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal PO</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-600 uppercase tracking-wider">Supplier</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah Item</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Nilai</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    @forelse($purchases as $purchase)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('purchases.show', $purchase) }}" class="font-bold text-blue-600 hover:underline">
                                    {{ $purchase->po_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                {{ $purchase->po_date ? $purchase->po_date->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $purchase->supplier->name ?? ($purchase->supplier->company_name ?? '-') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                {{ $purchase->items->count() }} Item ({{ number_format($purchase->items->sum('quantity')) }} Pcs)
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">
                                Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($purchase->status == 'Draft')
                                    <span class="px-3 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-full">Draft</span>
                                @elseif($purchase->status == 'Pending Approval')
                                    <span class="px-3 py-1 text-xs font-semibold text-amber-700 bg-amber-100 rounded-full">Pending Approval</span>
                                @elseif($purchase->status == 'Approved')
                                    <span class="px-3 py-1 text-xs font-semibold text-emerald-700 bg-emerald-100 rounded-full">Approved</span>
                                @elseif($purchase->status == 'Received')
                                    <span class="px-3 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">Received</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold text-rose-700 bg-rose-100 rounded-full">Canceled</span>
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
                                        <a href="{{ route('purchases.show', $purchase) }}" title="Detail PO" class="p-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        @if(!in_array($purchase->status, ['Received', 'Canceled']))
                                        <a href="{{ route('purchases.edit', $purchase) }}" title="Edit PO" class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        @endif
                                        <form id="delete-po-{{ $purchase->id }}" action="{{ route('purchases.destroy', $purchase) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" title="Hapus PO" onclick="confirmDelete('delete-po-{{ $purchase->id }}', 'Hapus Purchase Order ini?')" class="p-2 text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-lg transition">
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
                                <i class="fas fa-file-invoice text-4xl text-gray-300 mb-3"></i>
                                <p class="text-sm font-medium">Belum ada data Purchase Order</p>
                                <a href="{{ route('purchases.create') }}" class="inline-block mt-3 text-xs text-blue-600 font-semibold hover:underline">
                                    + Buat Purchase Order Pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination Footer -->
            @include('partials.pagination', ['paginator' => $purchases])
        </div>
    </div>
</div>
@endsection
