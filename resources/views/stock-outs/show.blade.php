@extends('layouts.app')

@section('title', 'Detail Barang Keluar - PT Nusantara ERP')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6 flex items-center justify-between no-print">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Barang Keluar (Stock Out)</h1>
            <p class="text-sm text-gray-500 mt-1">Informasi detail transaksi #{{ $stockOut->transaction_id }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('stock-outs.edit', $stockOut) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium flex items-center gap-2">
                <i class="fas fa-edit"></i>
                Edit Transaksi
            </a>
            <a href="{{ route('stock-outs.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Detail Card -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Transaction ID</span>
                <h2 class="text-xl font-bold text-blue-600">{{ $stockOut->transaction_id }}</h2>
            </div>
            <div>
                @if($stockOut->status == 'Completed')
                    <span class="px-4 py-1.5 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Completed</span>
                @elseif($stockOut->status == 'In-Progress')
                    <span class="px-4 py-1.5 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">In-Progress</span>
                @else
                    <span class="px-4 py-1.5 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Canceled</span>
                @endif
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</dt>
                <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $stockOut->date ? $stockOut->date->format('d F Y') : '-' }}</dd>
            </div>

            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pelanggan</dt>
                <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $stockOut->customer_name }}</dd>
            </div>

            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Items (Qty)</dt>
                <dd class="mt-1 text-sm font-semibold text-gray-900">{{ number_format($stockOut->total_items) }}</dd>
            </div>

            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Penerima / Kurir</dt>
                <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $stockOut->recipient_name ?? '-' }}</dd>
            </div>

            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Pada</dt>
                <dd class="mt-1 text-sm text-gray-700">{{ $stockOut->created_at ? $stockOut->created_at->format('d M Y H:i') : '-' }}</dd>
            </div>

            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Diubah</dt>
                <dd class="mt-1 text-sm text-gray-700">{{ $stockOut->updated_at ? $stockOut->updated_at->format('d M Y H:i') : '-' }}</dd>
            </div>

            <div class="md:col-span-2 pt-4 border-t border-gray-100">
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Catatan</dt>
                <dd class="text-sm text-gray-700 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    {{ $stockOut->notes ?? 'Tidak ada catatan untuk transaksi ini.' }}
                </dd>
            </div>
        </div>
    </div>
</div>
@endsection
