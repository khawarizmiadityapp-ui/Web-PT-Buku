@extends('layouts.app')

@section('title', 'Detail Purchase Order #' . $purchase->po_number . ' - PT Nusantara ERP')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header & Action Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
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
                <a href="{{ route('purchases.edit', $purchase) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition flex items-center gap-1.5">
                    <i class="fas fa-edit"></i> Edit PO
                </a>
            @endif

            <button onclick="window.print()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition flex items-center gap-1.5">
                <i class="fas fa-print"></i> Cetak PO
            </button>

            <a href="{{ route('purchases.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition flex items-center gap-1.5">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Quick Status Approval Bar -->
    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-tasks"></i>
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
                        <i class="fas fa-check-circle"></i> Setujui (Approve)
                    </button>
                </form>
            @endif

            @if($purchase->status == 'Approved')
                <form action="{{ route('purchases.updateStatus', $purchase) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="Received">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition flex items-center gap-1">
                        <i class="fas fa-box-check"></i> Tandai Sudah Diterima
                    </button>
                </form>
            @endif

            @if($purchase->status != 'Canceled' && $purchase->status != 'Received')
                <form action="{{ route('purchases.updateStatus', $purchase) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan PO ini?')">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="Canceled">
                    <button type="submit" class="px-4 py-2 bg-rose-100 hover:bg-rose-200 text-rose-700 rounded-lg text-xs font-bold transition flex items-center gap-1">
                        <i class="fas fa-times-circle"></i> Batalkan PO
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- PO Details Card -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <!-- Top Info Grid -->
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50/50 border-b border-gray-200">
            <!-- Supplier Info -->
            <div class="space-y-2">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Informasi Supplier</h3>
                <p class="text-base font-bold text-gray-900">{{ $purchase->supplier->name ?? ($purchase->supplier->company_name ?? '-') }}</p>
                @if(isset($purchase->supplier->email))
                    <p class="text-xs text-gray-600"><i class="fas fa-envelope mr-1 text-gray-400"></i>{{ $purchase->supplier->email }}</p>
                @endif
                @if(isset($purchase->supplier->phone))
                    <p class="text-xs text-gray-600"><i class="fas fa-phone mr-1 text-gray-400"></i>{{ $purchase->supplier->phone }}</p>
                @endif
                @if(isset($purchase->supplier->address))
                    <p class="text-xs text-gray-600"><i class="fas fa-map-marker-alt mr-1 text-gray-400"></i>{{ $purchase->supplier->address }}</p>
                @endif
            </div>

            <!-- Meta Info -->
            <div class="space-y-2 md:text-right">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Detail Dokumen</h3>
                <p class="text-sm font-semibold text-gray-900">Nomor PO: <span class="text-blue-600">{{ $purchase->po_number }}</span></p>
                <p class="text-xs text-gray-600">Tanggal PO: <span class="font-medium text-gray-900">{{ $purchase->po_date ? $purchase->po_date->format('d F Y') : '-' }}</span></p>
                <p class="text-xs text-gray-600">Catatan: <span class="font-medium text-gray-900">{{ $purchase->notes ?? 'Tidak ada catatan.' }}</span></p>
            </div>
        </div>

        <!-- Items Table -->
        <div class="p-6">
            <h3 class="text-base font-bold text-gray-900 mb-4">Rincian Barang yang Dipesan</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Kode / Produk</th>
                            <th class="px-4 py-3 text-right">Kuantitas (Qty)</th>
                            <th class="px-4 py-3 text-right">Harga Satuan</th>
                            <th class="px-4 py-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm">
                        @foreach($purchase->items as $index => $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3.5 text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-3.5">
                                    <p class="font-semibold text-gray-900">{{ $item->product->product_name ?? 'Produk #' . $item->product_id }}</p>
                                    <p class="text-xs text-gray-500">SKU: {{ $item->product->product_code ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-3.5 text-right font-medium text-gray-900">
                                    {{ number_format($item->quantity) }} {{ $item->product->unit ?? 'Pcs' }}
                                </td>
                                <td class="px-4 py-3.5 text-right text-gray-700">
                                    Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3.5 text-right font-semibold text-gray-900">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 font-bold border-t border-gray-200">
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-right text-gray-700 uppercase text-xs">Total Anggaran Pembelian:</td>
                            <td class="px-4 py-4 text-right text-blue-600 text-lg">
                                Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
