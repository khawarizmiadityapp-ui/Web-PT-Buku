@extends('layouts.app')

@section('title', 'Tambah Produk - PT Nusantara ERP')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Produk</h1>
        <p class="text-gray-500 mt-1">Tambahkan data produk baru ke dalam sistem</p>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 max-w-3xl">
        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Product Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="product_code" value="{{ old('product_code') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    @error('product_code') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <!-- Product Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="product_name" value="{{ old('product_name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    @error('product_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                    <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Buku Tulis" {{ old('category') == 'Buku Tulis' ? 'selected' : '' }}>Buku Tulis</option>
                        <option value="Buku Pelajaran" {{ old('category') == 'Buku Pelajaran' ? 'selected' : '' }}>Buku Pelajaran</option>
                        <option value="Novel" {{ old('category') == 'Novel' ? 'selected' : '' }}>Novel</option>
                        <option value="Komik" {{ old('category') == 'Komik' ? 'selected' : '' }}>Komik</option>
                        <option value="Buku Gambar" {{ old('category') == 'Buku Gambar' ? 'selected' : '' }}>Buku Gambar</option>
                        <option value="Alat Tulis" {{ old('category') == 'Alat Tulis' ? 'selected' : '' }}>Alat Tulis</option>
                        <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('category') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Unit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Satuan (Unit) <span class="text-red-500">*</span></label>
                    <select name="unit" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        <option value="Pcs" {{ old('unit', 'Pcs') == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                        <option value="Pack" {{ old('unit') == 'Pack' ? 'selected' : '' }}>Pack</option>
                        <option value="Lusin" {{ old('unit') == 'Lusin' ? 'selected' : '' }}>Lusin</option>
                        <option value="Box" {{ old('unit') == 'Box' ? 'selected' : '' }}>Box</option>
                        <option value="Dus" {{ old('unit') == 'Dus' ? 'selected' : '' }}>Dus</option>
                        <option value="Rim" {{ old('unit') == 'Rim' ? 'selected' : '' }}>Rim</option>
                    </select>
                    @error('unit') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- System Stock -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stok Awal <span class="text-xs text-gray-400 font-normal">(Maks. 500)</span> <span class="text-red-500">*</span></label>
                    <input type="number" name="system_stock" value="{{ old('system_stock', 0) }}" min="0" max="500" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    @error('system_stock') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" value="{{ old('price', 0) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    @error('price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('products.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">
                    <i class="fas fa-save mr-2"></i> Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
