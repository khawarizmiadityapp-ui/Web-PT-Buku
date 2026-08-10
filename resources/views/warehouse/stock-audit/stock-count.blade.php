@extends('layouts.app')

@section('title', 'Mulai Penghitungan Stok - PT Nusantara ERP')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mulai Penghitungan Stok (Stock Opname)</h1>
            <p class="text-gray-500 mt-1">Lakukan penghitungan fisik stok barang di gudang</p>
        </div>
        <div>
            <a href="{{ route('warehouse.stock-audit.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-8 text-center max-w-3xl mx-auto mt-8">
        <div class="w-20 h-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <h2 class="text-xl font-bold text-gray-800 mb-2">Fitur Sedang Dalam Pengembangan</h2>
        <p class="text-gray-600 mb-6">
            Halaman ini nantinya akan menampilkan daftar seluruh barang yang perlu dihitung secara fisik, lengkap dengan form input atau integrasi dengan scanner barcode. Saat ini fitur sedang dalam tahap penyelesaian.
        </p>
        <button class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition" onclick="window.history.back()">
            Kembali ke Halaman Sebelumnya
        </button>
    </div>
</div>
@endsection
