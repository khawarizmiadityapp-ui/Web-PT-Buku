@extends('layouts.app')

@section('title', 'Edit Produk - PT Nusantara ERP')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gray-900">Edit Produk</h1>
        <p class="text-gray-500 mt-1">Ubah data produk yang sudah ada di sistem</p>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 max-w-3xl">
        <form action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Product Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Barcode Produk (SKU) <span class="text-red-500">*</span></label>
                    <input type="text" name="product_code" id="productCodeInput" value="{{ old('product_code', $product->product_code) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none font-semibold text-blue-600" required>
                    @error('product_code') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    
                    <!-- Visual Barcode Preview -->
                    <div class="mt-2.5 p-2 bg-gray-50 border border-dashed border-gray-300 rounded-lg flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-barcode text-gray-400 text-lg"></i>
                            <div>
                                <div class="text-[10px] text-gray-500 font-medium">Barcode Fisik Siap Scan:</div>
                                <svg id="barcodeLivePreview" style="max-height: 38px;"></svg>
                            </div>
                        </div>
                        <span class="text-[10px] text-gray-400 font-mono">Code-128</span>
                    </div>
                </div>
                
                <!-- Product Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="product_name" value="{{ old('product_name', $product->product_name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    @error('product_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                    <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Buku Tulis" {{ old('category', $product->category) == 'Buku Tulis' ? 'selected' : '' }}>Buku Tulis</option>
                        <option value="Buku Pelajaran" {{ old('category', $product->category) == 'Buku Pelajaran' ? 'selected' : '' }}>Buku Pelajaran</option>
                        <option value="Novel" {{ old('category', $product->category) == 'Novel' ? 'selected' : '' }}>Novel</option>
                        <option value="Komik" {{ old('category', $product->category) == 'Komik' ? 'selected' : '' }}>Komik</option>
                        <option value="Buku Gambar" {{ old('category', $product->category) == 'Buku Gambar' ? 'selected' : '' }}>Buku Gambar</option>
                        <option value="Alat Tulis" {{ old('category', $product->category) == 'Alat Tulis' ? 'selected' : '' }}>Alat Tulis</option>
                        <option value="Lainnya" {{ old('category', $product->category) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('category') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Unit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Satuan (Unit) <span class="text-red-500">*</span></label>
                    <select name="unit" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        <option value="Pcs" {{ old('unit', $product->unit) == 'Pcs' || old('unit', $product->unit) == 'pcs' ? 'selected' : '' }}>Pcs</option>
                        <option value="Pack" {{ old('unit', $product->unit) == 'Pack' || old('unit', $product->unit) == 'pack' ? 'selected' : '' }}>Pack</option>
                        <option value="Lusin" {{ old('unit', $product->unit) == 'Lusin' || old('unit', $product->unit) == 'lusin' ? 'selected' : '' }}>Lusin</option>
                        <option value="Box" {{ old('unit', $product->unit) == 'Box' || old('unit', $product->unit) == 'box' ? 'selected' : '' }}>Box</option>
                        <option value="Dus" {{ old('unit', $product->unit) == 'Dus' || old('unit', $product->unit) == 'dus' ? 'selected' : '' }}>Dus</option>
                        <option value="Rim" {{ old('unit', $product->unit) == 'Rim' || old('unit', $product->unit) == 'ream' ? 'selected' : '' }}>Rim</option>
                    </select>
                    @error('unit') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- System Stock -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stok Sistem <span class="text-xs text-gray-400 font-normal">(Maks. 500)</span> <span class="text-red-500">*</span></label>
                    <input type="number" name="system_stock" value="{{ old('system_stock', $product->system_stock) }}" min="0" max="500" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    @error('system_stock') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    @error('price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        <option value="Active" {{ old('status', $product->status) == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status', $product->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('products.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">
                    <i class="fas fa-save mr-2"></i> Perbarui Produk
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
function updateBarcodePreview() {
    const input = document.getElementById('productCodeInput');
    const val = input ? input.value.trim().replace(/^#/, '') : '';
    if (val) {
        try {
            JsBarcode("#barcodeLivePreview", val, {
                format: "CODE128",
                lineColor: "#1e293b",
                width: 1.4,
                height: 32,
                displayValue: true,
                fontSize: 11,
                margin: 0
            });
        } catch (e) {
            console.warn('Barcode preview error', e);
        }
    }
}
document.addEventListener('DOMContentLoaded', updateBarcodePreview);
document.getElementById('productCodeInput')?.addEventListener('input', updateBarcodePreview);
</script>
@endpush
@endsection
