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
                            <x-icon name="file-excel" class="w-4 h-4 text-emerald-600 mr-2" />Export CSV/Excel
                        </a>
                        <button onclick="window.print()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium text-gray-700 flex items-center">
                            <x-icon name="print" class="w-4 h-4 text-gray-600 mr-2" />Print
                        </button>
                        <a href="{{ route('products.create') }}" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium inline-flex items-center">
                            <x-icon name="plus" class="w-4 h-4 mr-2" />Tambah Barang
                        </a>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                    <form method="GET" class="flex items-center gap-4">
                        <div class="relative flex-1 max-w-md">
                            <x-icon name="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
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
                            <x-icon name="filter" class="w-4 h-4 mr-2" />Filter
                        </button>
                    </form>
                </div>

                <!-- Table -->
                <div class="bg-white border border-gray-200 rounded-lg overflow-visible">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase"><x-icon name="barcode" class="w-4 h-4 mr-1.5 text-blue-600" />Barcode / Kode</th>
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
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col items-start gap-1">
                                            <svg class="product-barcode-svg" data-code="{{ preg_replace('/^#/', '', $product->product_code) }}" style="height: 32px; max-width: 140px;"></svg>
                                            <div class="flex items-center gap-1.5">
                                                <span class="text-xs font-mono font-bold text-gray-800 bg-gray-100 px-2 py-0.5 rounded border border-gray-200">
                                                    {{ $product->product_code }}
                                                </span>
                                                <button type="button" onclick="printSingleBarcode('{{ $product->product_code }}', '{{ addslashes($product->product_name) }}', '{{ $product->price }}')" 
                                                        class="text-gray-400 hover:text-blue-600 text-xs p-1" title="Cetak Barcode">
                                                    <x-icon name="print" class="w-4 h-4" />
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center">
                                                <x-icon name="box" class="w-4 h-4 text-gray-400" />
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
                                                 <button type="button" onclick="printSingleBarcode('{{ $product->product_code }}', '{{ addslashes($product->product_name) }}', '{{ $product->price }}')" 
                                                         title="Cetak Barcode Label" class="p-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition">
                                                     <x-icon name="barcode" class="w-4 h-4" />
                                                 </button>
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
                                        <x-icon name="inbox" class="w-4 h-4 text-4xl mb-3 text-gray-300" />
                                        <p>Tidak ada data barang</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination Footer -->
                    @include('partials.pagination', ['paginator' => $products])
                </div>
</div>

<!-- Modal Cetak Barcode Label -->
<div id="barcodePrintModal" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl m-4">
        <div class="flex items-center justify-between border-b pb-3 mb-4">
            <h5 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <x-icon name="barcode" class="w-4 h-4 text-blue-600" />
                <span id="barcodeModalTitle">Cetak Label Barcode</span>
            </h5>
            <button type="button" onclick="closeBarcodeModal()" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>
        
        <div id="barcodePrintArea" class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center mb-4">
            <h6 id="printProductName" class="font-bold text-gray-900 text-sm mb-1">Nama Produk</h6>
            <div class="text-xs text-blue-600 font-semibold mb-2" id="printProductPrice">Rp 0</div>
            <div class="bg-white p-3 rounded-lg border inline-block shadow-sm">
                <svg id="modalBarcodeSvg" style="max-height: 65px;"></svg>
            </div>
            <div class="text-xs font-mono font-semibold text-gray-600 mt-2" id="printProductCode">CODE</div>
        </div>

        <div class="flex items-center justify-between gap-3">
            <span class="text-xs text-gray-500"><x-icon name="info-circle" class="w-4 h-4 me-1" />Siap dicetak pada kertas stiker</span>
            <div class="flex gap-2">
                <button type="button" onclick="closeBarcodeModal()" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Tutup</button>
                <button type="button" onclick="executePrintBarcode()" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 flex items-center gap-2">
                    <x-icon name="print" class="w-4 h-4" /> Cetak Label
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    renderAllTableBarcodes();
});

function renderAllTableBarcodes() {
    document.querySelectorAll('.product-barcode-svg').forEach(svg => {
        const code = svg.getAttribute('data-code');
        if (!code) return;
        try {
            JsBarcode(svg, code, {
                format: "CODE128",
                lineColor: "#1e293b",
                width: 1.3,
                height: 28,
                displayValue: false,
                margin: 0
            });
        } catch (e) {
            console.warn('Gagal render barcode untuk:', code, e);
        }
    });
}

function printSingleBarcode(code, name, price) {
    const cleanCode = code.replace(/^#/, '');
    document.getElementById('barcodeModalTitle').textContent = 'Label Barcode: ' + code;
    document.getElementById('printProductName').textContent = name;
    document.getElementById('printProductPrice').textContent = 'Rp ' + Number(price).toLocaleString('id-ID');
    document.getElementById('printProductCode').textContent = code;
    
    try {
        JsBarcode("#modalBarcodeSvg", cleanCode, {
            format: "CODE128",
            lineColor: "#000000",
            width: 2,
            height: 60,
            displayValue: true,
            fontSize: 14,
            margin: 5
        });
    } catch(e) {
        console.warn('JsBarcode modal error', e);
    }
    
    document.getElementById('barcodePrintModal').classList.remove('hidden');
}

function closeBarcodeModal() {
    document.getElementById('barcodePrintModal').classList.add('hidden');
}

function executePrintBarcode() {
    const printContent = document.getElementById('barcodePrintArea').innerHTML;
    const printWindow = window.open('', '_blank', 'width=450,height=400');
    printWindow.document.write(`
        <html>
            <head>
                <title>Cetak Label Barcode</title>
                <style>
                    body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; text-align: center; padding: 20px; }
                    .barcode-label { border: 1px dashed #999; padding: 15px; display: inline-block; border-radius: 8px; }
                </style>
            </head>
            <body onload="window.print(); window.close();">
                <div class="barcode-label">
                    ${printContent}
                </div>
            </body>
        </html>
    `);
    printWindow.document.close();
}
</script>
@endpush
@endsection
