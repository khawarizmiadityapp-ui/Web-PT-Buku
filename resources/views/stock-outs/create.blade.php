@extends('layouts.app')

@section('title', 'Tambah Barang Keluar - PT Nusantara ERP')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Barang Keluar (Stock Out)</h1>
            <p class="text-sm text-gray-500 mt-1">Catat transaksi barang keluar / pengiriman ke pelanggan baru</p>
        </div>
        <a href="{{ route('stock-outs.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <form action="{{ route('stock-outs.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Transaction ID -->
                <div>
                    <label for="transaction_id" class="block text-sm font-medium text-gray-700 mb-2">
                        ID Transaksi <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="transaction_id"
                        name="transaction_id" 
                        value="{{ old('transaction_id', 'SO-' . date('YmdHis')) }}" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm @error('transaction_id') border-red-500 @enderror"
                        required
                    >
                    @error('transaction_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="date" 
                        id="date"
                        name="date" 
                        value="{{ old('date', date('Y-m-d')) }}" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm @error('date') border-red-500 @enderror"
                        required
                    >
                    @error('date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Customer Name -->
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Pelanggan <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="customer_name"
                        name="customer_name" 
                        value="{{ old('customer_name') }}" 
                        placeholder="Contoh: PT Jaya Abadi"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm @error('customer_name') border-red-500 @enderror"
                        required
                    >
                    @error('customer_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Total Items -->
                <div>
                    <label for="total_items" class="block text-sm font-medium text-gray-700 mb-2">
                        Total Jumlah Barang (Qty) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        id="total_items"
                        name="total_items" 
                        value="{{ old('total_items', 1) }}" 
                        min="1"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm @error('total_items') border-red-500 @enderror"
                        required
                    >
                    @error('total_items')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Recipient Name -->
                <div>
                    <label for="recipient_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Penerima / Kurir
                    </label>
                    <input 
                        type="text" 
                        id="recipient_name"
                        name="recipient_name" 
                        value="{{ old('recipient_name') }}" 
                        placeholder="Contoh: Budi Santoso (Supir Expedisi)"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm @error('recipient_name') border-red-500 @enderror"
                    >
                    @error('recipient_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="status"
                        name="status" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm @error('status') border-red-500 @enderror"
                        required
                    >
                        <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="In-Progress" {{ old('status', 'In-Progress') == 'In-Progress' ? 'selected' : '' }}>In-Progress</option>
                        <option value="Canceled" {{ old('status') == 'Canceled' ? 'selected' : '' }}>Canceled</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan / keterangan tambahan
                    </label>
                    <textarea 
                        id="notes"
                        name="notes" 
                        rows="3"
                        placeholder="Tambahkan catatan khusus pengiriman atau keterangan barang..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm @error('notes') border-red-500 @enderror"
                    >{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 pt-4 border-t border-gray-200 flex items-center justify-end gap-3">
                <a href="{{ route('stock-outs.index') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm font-medium">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
