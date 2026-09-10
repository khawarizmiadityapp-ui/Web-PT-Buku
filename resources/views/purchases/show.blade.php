@extends('layouts.app')

@section('title', 'Detail Purchase Order #' . $purchase->po_number . ' - PT Nusantara ERP')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header & Action Toolbar (No Print) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 no-print">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900">Purchase Order #{{ $purchase->po_number }}</h1>
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
            </div>
            <p class="text-sm text-gray-500 mt-1">Dibuat pada {{ $purchase->created_at->format('d F Y, H:i') }} WIB</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @if(!in_array($purchase->status, ['Received', 'Canceled']))
                <a href="{{ route('purchases.edit', $purchase) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition flex items-center gap-1.5 shadow-sm">
                    <x-icon name="edit" class="w-4 h-4" /> Edit PO
                </a>
            @endif

            <button onclick="window.print()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition flex items-center gap-1.5 shadow-sm">
                <x-icon name="print" class="w-4 h-4" /> Cetak PO
            </button>

            <a href="{{ route('purchases.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition flex items-center gap-1.5">
                <x-icon name="arrow-left" class="w-4 h-4" /> Kembali
            </a>
        </div>
    </div>

    <!-- Quick Status Approval Bar (No Print) -->
    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4 no-print">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
                <x-icon name="tasks" class="w-4 h-4" />
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Ubah Status Alur Kerja PO</p>
                <p class="text-sm font-medium text-gray-900">Status Saat Ini: <span class="font-bold text-blue-600">{{ $purchase->status }}</span></p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @if($purchase->status == 'Draft' || $purchase->status == 'Pending Approval')
                <form action="{{ route('purchases.updateStatus', $purchase) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="Approved">
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition flex items-center gap-1">
                        <x-icon name="check-circle" class="w-4 h-4" /> Setujui (Approve)
                    </button>
                </form>
            @endif

            @if($purchase->status == 'Approved')
                <form action="{{ route('purchases.updateStatus', $purchase) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="Received">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition flex items-center gap-1">
                        <x-icon name="box-check" class="w-4 h-4" /> Tandai Sudah Diterima
                    </button>
                </form>
            @endif

            @if($purchase->status != 'Canceled' && $purchase->status != 'Received')
                <form action="{{ route('purchases.updateStatus', $purchase) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan PO ini?')">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="Canceled">
                    <button type="submit" class="px-4 py-2 bg-rose-100 hover:bg-rose-200 text-rose-700 rounded-lg text-xs font-bold transition flex items-center gap-1">
                        <x-icon name="times-circle" class="w-4 h-4" /> Batalkan PO
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- PO Printable Document Paper -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 md:p-10 printable-area">
        <!-- Letterhead / Company Header -->
        <div class="flex justify-between items-start border-b border-gray-200 pb-6 mb-6">
            <div>
                <img src="{{ asset('images/logo PT buku.png') }}" alt="Logo" class="h-12 w-auto mb-2">
                <h2 class="text-lg font-bold text-gray-800">PT Distribusi Buku dan Alat Tulis Nusantara</h2>
                <p class="text-xs text-gray-500">Jl. Jend. Sudirman No. 123, Jakarta Pusat</p>
                <p class="text-xs text-gray-500">Email: purchasing@ptbuku.co.id | Telp: (021) 555-1234</p>
            </div>
            <div class="text-right">
                <h2 class="text-2xl md:text-3xl font-black text-gray-900 tracking-wider uppercase mb-1">PURCHASE ORDER</h2>
                <p class="text-sm font-semibold text-blue-600">#{{ $purchase->po_number }}</p>
                <p class="text-xs text-gray-500 mt-2">Tanggal PO: <strong>{{ $purchase->po_date ? $purchase->po_date->format('d/m/Y') : '-' }}</strong></p>
                <p class="text-xs text-gray-500">Status: <strong class="text-gray-800 uppercase">{{ $purchase->status }}</strong></p>
            </div>
        </div>

        <!-- Supplier & Document Meta Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
            <!-- Supplier Info -->
            <div class="space-y-1">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Vendor / Supplier:</h3>
                <p class="text-base font-bold text-gray-900">{{ $purchase->supplier->name ?? ($purchase->supplier->company_name ?? '-') }}</p>
                @if(isset($purchase->supplier->email))
                    <p class="text-xs text-gray-600"><x-icon name="envelope" class="w-4 h-4 mr-1 text-gray-400" />{{ $purchase->supplier->email }}</p>
                @endif
                @if(isset($purchase->supplier->phone))
                    <p class="text-xs text-gray-600"><x-icon name="phone" class="w-4 h-4 mr-1 text-gray-400" />{{ $purchase->supplier->phone }}</p>
                @endif
                @if(isset($purchase->supplier->address))
                    <p class="text-xs text-gray-600"><x-icon name="map-marker-alt" class="w-4 h-4 mr-1 text-gray-400" />{{ $purchase->supplier->address }}</p>
                @endif
            </div>

            <!-- Meta Info -->
            <div class="space-y-1 md:text-right">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Informasi Pengiriman & Catatan:</h3>
                <p class="text-xs text-gray-600">Alamat Kirim: <strong>Gudang Utama PT Nusantara</strong></p>
                <p class="text-xs text-gray-600">Catatan Order: <strong>{{ $purchase->notes ?? 'Tidak ada catatan.' }}</strong></p>
            </div>
        </div>

        <!-- Items Table -->
        <div class="border border-gray-200 rounded-lg overflow-hidden mb-6">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b border-gray-200 font-semibold text-gray-700">
                    <tr>
                        <th class="p-3 w-12 text-center">No</th>
                        <th class="p-3">Kode / Deskripsi Barang</th>
                        <th class="p-3 text-center">Kuantitas</th>
                        <th class="p-3 text-right">Harga Satuan</th>
                        <th class="p-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($purchase->items as $index => $item)
                        <tr>
                            <td class="p-3 text-center text-gray-500">{{ $index + 1 }}</td>
                            <td class="p-3">
                                <p class="font-semibold text-gray-900">{{ $item->product->product_name ?? 'Produk #' . $item->product_id }}</p>
                                <p class="text-xs text-gray-500">Kode/SKU: {{ $item->product->product_code ?? '-' }}</p>
                            </td>
                            <td class="p-3 text-center font-medium text-gray-900">
                                {{ number_format($item->quantity) }} {{ $item->product->unit ?? 'Pcs' }}
                            </td>
                            <td class="p-3 text-right text-gray-700">
                                Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                            </td>
                            <td class="p-3 text-right font-semibold text-gray-900">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">Tidak ada rincian item barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Summary Totals -->
        <div class="flex justify-end mb-8">
            <div class="w-80 space-y-2 text-sm">
                <div class="flex justify-between py-2 border-t-2 border-b-2 border-gray-900 text-base font-bold text-gray-900">
                    <span>Total Nilai Pembelian:</span>
                    <span class="text-blue-600">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Signatures / Authorization Section -->
        <div class="grid grid-cols-3 gap-6 pt-6 text-center text-xs text-gray-600 border-t border-gray-200">
            <div>
                <p class="font-medium text-gray-700 mb-16">Dibuat Oleh,</p>
                <div class="border-t border-gray-400 w-32 mx-auto pt-1">
                    <p class="font-bold text-gray-900">{{ Auth::user()->name ?? 'Purchasing Staff' }}</p>
                    <p class="text-[10px] text-gray-500">Bagian Pengadaan</p>
                </div>
            </div>
            <div>
                <p class="font-medium text-gray-700 mb-16">Disetujui Oleh,</p>
                <div class="border-t border-gray-400 w-32 mx-auto pt-1">
                    <p class="font-bold text-gray-900">Manager Operasional</p>
                    <p class="text-[10px] text-gray-500">Authorized Signature</p>
                </div>
            </div>
            <div>
                <p class="font-medium text-gray-700 mb-16">Konfirmasi Supplier,</p>
                <div class="border-t border-gray-400 w-32 mx-auto pt-1">
                    <p class="font-bold text-gray-900">{{ $purchase->supplier->name ?? 'Pihak Vendor' }}</p>
                    <p class="text-[10px] text-gray-500">Tanda Tangan & Cap</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    /* Hide layout chrome and buttons */
    aside,
    aside *,
    header,
    header *,
    nav,
    nav *,
    .no-print,
    .no-print *,
    button,
    .btn,
    form {
        display: none !important;
    }

    body, html, main, .container-fluid, .flex-1 {
        background: #ffffff !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        height: auto !important;
        overflow: visible !important;
        box-shadow: none !important;
    }

    .printable-area {
        border: none !important;
        box-shadow: none !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    table {
        width: 100% !important;
        border-collapse: collapse !important;
    }

    th, td {
        border-bottom: 1px solid #e5e7eb !important;
    }

    @page {
        size: A4 portrait;
        margin: 10mm;
    }
}
</style>
@endsection
