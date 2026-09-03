@extends('layouts.app')

@section('title', 'Buat Purchase Order Baru - PT Nusantara ERP')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Buat Purchase Order (PO) Baru</h1>
            <p class="text-sm text-gray-500 mt-1">Formulir pengadaan barang dari supplier</p>
        </div>
        <a href="{{ route('purchases.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('purchases.store') }}" method="POST" id="poForm">
        @csrf

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-6">
            <!-- Information Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- PO Number -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="po_number" class="block text-sm font-medium text-gray-700">
                            No. Purchase Order <span class="text-red-500">*</span>
                        </label>
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-full">
                            <i class="fas fa-magic text-[9px]"></i> Otomatis
                        </span>
                    </div>
                    <input 
                        type="text" 
                        id="po_number"
                        name="po_number" 
                        value="{{ old('po_number', $autoPoNumber ?? \App\Models\Purchase::generatePoNumber()) }}" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm font-semibold text-blue-600"
                        required
                    >
                    @error('po_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- PO Date -->
                <div>
                    <label for="po_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal PO <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="date" 
                        id="po_date"
                        name="po_date" 
                        value="{{ old('po_date', date('Y-m-d')) }}" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                        required
                    >
                    @error('po_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Supplier -->
                <div>
                    <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Supplier Tujuan <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="supplier_id"
                        name="supplier_id" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                        required
                    >
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name ?? $supplier->company_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status Awal <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="status"
                        name="status" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                        required
                    >
                        <option value="Draft" {{ old('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                        <option value="Pending Approval" {{ old('status', 'Pending Approval') == 'Pending Approval' ? 'selected' : '' }}>Pending Approval</option>
                        <option value="Approved" {{ old('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Pengadaan / Keterangan
                    </label>
                    <input 
                        type="text"
                        id="notes"
                        name="notes" 
                        value="{{ old('notes') }}"
                        placeholder="Contoh: Estimasi pengiriman 3 hari kerja, pengiriman via kurir pabrik"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                    >
                    @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Items Table Section -->
            <div class="pt-4 border-t border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Barang Pesanan</h3>
                    <button type="button" id="addRowBtn" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold flex items-center gap-1.5 transition">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Baris Produk</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left" id="itemsTable">
                        <thead class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase">
                            <tr>
                                <th class="px-4 py-3 min-w-[240px]">Produk</th>
                                <th class="px-4 py-3 w-32">Kuantitas (Qty)</th>
                                <th class="px-4 py-3 min-w-[160px]">Harga Satuan (Rp)</th>
                                <th class="px-4 py-3 min-w-[160px]">Subtotal (Rp)</th>
                                <th class="px-4 py-3 w-16 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200" id="itemsTableBody">
                            <!-- Dynamic rows via JS -->
                        </tbody>
                        <tfoot class="bg-gray-50 font-bold">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right text-gray-700 uppercase text-xs">Total Anggaran PO:</td>
                                <td class="px-4 py-3 text-blue-600 text-base" id="grandTotalText">Rp 0</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-3">
                <a href="{{ route('purchases.index') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm font-medium">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Simpan Purchase Order
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const productsData = @json($products);
    let itemIndex = 0;

    function addRow() {
        const tbody = document.getElementById('itemsTableBody');
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50 transition item-row';
        row.dataset.index = itemIndex;

        let productOptions = '<option value="">-- Pilih Produk --</option>';
        productsData.forEach(p => {
            productOptions += `<option value="${p.id}" data-price="${p.price}">${p.product_code} - ${p.product_name}</option>`;
        });

        row.innerHTML = `
            <td class="px-4 py-3">
                <select name="items[${itemIndex}][product_id]" class="product-select w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" required>
                    ${productOptions}
                </select>
            </td>
            <td class="px-4 py-3">
                <input type="number" name="items[${itemIndex}][quantity]" value="1" min="1" class="qty-input w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" required>
            </td>
            <td class="px-4 py-3">
                <input type="number" name="items[${itemIndex}][unit_price]" value="0" min="0" step="500" class="price-input w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" required>
            </td>
            <td class="px-4 py-3 font-semibold text-gray-900 subtotal-text">
                Rp 0
            </td>
            <td class="px-4 py-3 text-center">
                <button type="button" class="remove-row-btn text-rose-500 hover:text-rose-700 text-sm">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(row);

        // Bind events
        const select = row.querySelector('.product-select');
        const qtyInput = row.querySelector('.qty-input');
        const priceInput = row.querySelector('.price-input');
        const removeBtn = row.querySelector('.remove-row-btn');

        select.addEventListener('change', function() {
            const selectedOpt = this.options[this.selectedIndex];
            const defaultPrice = selectedOpt.dataset.price || 0;
            priceInput.value = defaultPrice;
            calculateRowSubtotal(row);
        });

        qtyInput.addEventListener('input', () => calculateRowSubtotal(row));
        priceInput.addEventListener('input', () => calculateRowSubtotal(row));

        removeBtn.addEventListener('click', function() {
            if (document.querySelectorAll('.item-row').length > 1) {
                row.remove();
                calculateGrandTotal();
            } else {
                alert('Minimal harus ada 1 barang dalam pesanan PO!');
            }
        });

        itemIndex++;
        calculateGrandTotal();
    }

    function calculateRowSubtotal(row) {
        const qtyInput = row.querySelector('.qty-input');
        const priceInput = row.querySelector('.price-input');
        let qty = parseFloat(qtyInput.value) || 0;
        if (qty < 1) {
            qty = 1;
            qtyInput.value = 1;
        }
        let price = parseFloat(priceInput.value) || 0;
        if (price < 0) {
            price = 0;
            priceInput.value = 0;
        }
        const subtotal = qty * price;
        row.querySelector('.subtotal-text').textContent = 'Rp ' + formatNumber(subtotal);
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const qty = Math.max(1, parseFloat(row.querySelector('.qty-input').value) || 0);
            const price = Math.max(0, parseFloat(row.querySelector('.price-input').value) || 0);
            grandTotal += (qty * price);
        });
        document.getElementById('grandTotalText').textContent = 'Rp ' + formatNumber(grandTotal);
    }

    document.getElementById('addRowBtn').addEventListener('click', addRow);

    // Add initial row
    document.addEventListener('DOMContentLoaded', function() {
        addRow();
    });
</script>
@endpush
@endsection
