@extends('layouts.app')

@section('title', 'Detail Sales Invoice - PT Nusantara ERP')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Action Header -->
    <div class="mb-6 flex items-center justify-between no-print">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                Invoice {{ $invoice->invoice_number }}
                @if($invoice->payment_status == 'Paid')
                    <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Paid</span>
                @elseif($invoice->payment_status == 'Unpaid')
                    <span class="px-3 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">Unpaid</span>
                @elseif($invoice->payment_status == 'Overdue')
                    <span class="px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Overdue</span>
                @else
                    <span class="px-3 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">Partial</span>
                @endif
            </h1>
            <p class="text-sm text-gray-500 mt-1">Dibuat pada {{ $invoice->date ? $invoice->date->format('d F Y') : '-' }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('sales.invoices.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                <i class="fas fa-print mr-2"></i> Cetak Invoice
            </button>
        </div>
    </div>

    <!-- Invoice Content Paper -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 max-w-4xl mx-auto printable-area">
        <!-- Brand & Title -->
        <div class="flex justify-between items-start border-b border-gray-200 pb-6 mb-6">
            <div>
                <img src="{{ asset('images/logo PT buku.png') }}" alt="Logo" class="h-12 w-auto mb-2">
                <h2 class="text-lg font-bold text-gray-800">PT Distribusi Buku dan Alat Tulis Nusantara</h2>
                <p class="text-xs text-gray-500">Jl. Jend. Sudirman No. 123, Jakarta Pusat</p>
                <p class="text-xs text-gray-500">Email: info@ptbuku.co.id | Telp: (021) 555-1234</p>
            </div>
            <div class="text-right">
                <h2 class="text-3xl font-black text-gray-900 tracking-wider uppercase mb-1">INVOICE</h2>
                <p class="text-sm font-semibold text-blue-600">{{ $invoice->invoice_number }}</p>
                <p class="text-xs text-gray-500 mt-2">Tanggal: <strong>{{ $invoice->date ? $invoice->date->format('d/m/Y') : '-' }}</strong></p>
                <p class="text-xs text-gray-500">Jatuh Tempo: <strong>{{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : '-' }}</strong></p>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="mb-8">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Ditujukan Kepada:</h3>
            <p class="text-base font-bold text-gray-900">{{ $invoice->customer_name }}</p>
            @if($invoice->customer)
                <p class="text-xs text-gray-600">{{ $invoice->customer->address ?? '' }}</p>
                <p class="text-xs text-gray-600">Telp: {{ $invoice->customer->phone ?? '-' }}</p>
            @endif
        </div>

        <!-- Items Table -->
        <div class="border border-gray-200 rounded-lg overflow-hidden mb-6">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b border-gray-200 font-semibold text-gray-700">
                    <tr>
                        <th class="p-3">No</th>
                        <th class="p-3">Deskripsi Barang / Produk</th>
                        <th class="p-3 text-center">Qty</th>
                        <th class="p-3 text-right">Harga Satuan</th>
                        <th class="p-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($invoice->items as $index => $item)
                        <tr>
                            <td class="p-3 text-gray-500">{{ $index + 1 }}</td>
                            <td class="p-3 font-medium text-gray-900">{{ $item->product_name }}</td>
                            <td class="p-3 text-center">{{ $item->quantity }}</td>
                            <td class="p-3 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="p-3 text-right font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">Detail barang tidak dilampirkan secara spesifik.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Summary Totals -->
        <div class="flex justify-end mb-8">
            <div class="w-72 space-y-2 text-sm">
                <div class="flex justify-between py-1 border-b border-gray-100 text-gray-600">
                    <span>Total Tagihan:</span>
                    <span class="font-bold text-gray-900">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-gray-100 text-gray-600">
                    <span>Sudah Dibayar:</span>
                    <span class="font-bold text-green-600">Rp {{ number_format($invoice->paid_amount ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2 border-t-2 border-gray-900 text-base font-bold text-gray-900">
                    <span>Sisa Pembayaran:</span>
                    <span class="text-red-600">Rp {{ number_format($invoice->total_amount - ($invoice->paid_amount ?? 0), 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Status Update Form (No Print) -->
        <div class="no-print mt-8 p-4 bg-gray-50 border border-gray-200 rounded-xl">
            <h4 class="text-sm font-bold text-gray-800 mb-3"><i class="fas fa-edit mr-2 text-blue-600"></i>Update Status Pembayaran</h4>
            <form action="{{ route('sales.invoices.updatePayment', $invoice->id) }}" method="POST" class="flex items-center gap-4">
                @csrf
                @method('PATCH')
                
                <div class="flex-1">
                    <label class="block text-xs text-gray-500 mb-1">Status Pembayaran</label>
                    <select name="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="Paid" {{ $invoice->payment_status == 'Paid' ? 'selected' : '' }}>Paid (Lunas)</option>
                        <option value="Unpaid" {{ $invoice->payment_status == 'Unpaid' ? 'selected' : '' }}>Unpaid (Belum Lunas)</option>
                        <option value="Overdue" {{ $invoice->payment_status == 'Overdue' ? 'selected' : '' }}>Overdue (Terlambat)</option>
                        <option value="Partial" {{ $invoice->payment_status == 'Partial' ? 'selected' : '' }}>Partial (Cicil)</option>
                    </select>
                </div>

                <div class="flex-1">
                    <label class="block text-xs text-gray-500 mb-1">Jumlah Dibayar (Rp)</label>
                    <input type="number" name="paid_amount" value="{{ $invoice->paid_amount ?? 0 }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>

                <div class="self-end">
                    <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@media print {
    /* Hide layout chrome elements */
    aside,
    header,
    nav,
    .no-print,
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
        padding: 10mm !important;
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
