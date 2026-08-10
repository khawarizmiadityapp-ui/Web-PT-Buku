@extends('layouts.app')

@section('title', 'Buat Invoice Penjualan - PT Nusantara ERP')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Buat Sales Invoice Baru</h1>
            <p class="text-sm text-gray-500">Buat tagihan/faktur penjualan baru untuk pelanggan</p>
        </div>
        <a href="{{ route('sales.invoices.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <form action="{{ route('sales.invoices.store') }}" method="POST" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 max-w-5xl">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Invoice Number -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">No. Invoice <span class="text-red-500">*</span></label>
                <input type="text" name="invoice_number" value="{{ old('invoice_number', $invoiceNumber) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-blue-600" required>
                @error('invoice_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Customer -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pelanggan <span class="text-red-500">*</span></label>
                <input type="text" name="customer_name" id="customer_name_input" value="{{ old('customer_name') }}" list="customer_list" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Ketik atau pilih pelanggan..." required>
                <datalist id="customer_list">
                    @foreach($customers as $c)
                        <option value="{{ $c->name }}"></option>
                    @endforeach
                </datalist>
                @error('customer_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Invoice Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Invoice <span class="text-red-500">*</span></label>
                <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" required>
                @error('date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Due Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jatuh Tempo <span class="text-red-500">*</span></label>
                <input type="date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+7 days'))) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" required>
                @error('due_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Payment Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Pembayaran <span class="text-red-500">*</span></label>
                <select name="payment_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" required>
                    <option value="Unpaid" {{ old('payment_status') == 'Unpaid' ? 'selected' : '' }}>Unpaid (Belum Lunas)</option>
                    <option value="Paid" {{ old('payment_status') == 'Paid' ? 'selected' : '' }}>Paid (Lunas)</option>
                    <option value="Overdue" {{ old('payment_status') == 'Overdue' ? 'selected' : '' }}>Overdue (Jatuh Tempo)</option>
                </select>
                @error('payment_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Total Amount -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Total Amount (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="total_amount" id="total_amount_input" value="{{ old('total_amount', 0) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm font-semibold" required>
                @error('total_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Dynamic Items Table -->
        <div class="mb-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-3">Item Barang / Produk</h3>
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <table class="w-full text-sm text-left" id="itemsTable">
                    <thead class="bg-gray-50 border-b border-gray-200 font-semibold text-gray-600">
                        <tr>
                            <th class="p-3">Nama Produk</th>
                            <th class="p-3 w-28">Jumlah</th>
                            <th class="p-3 w-40">Harga (Rp)</th>
                            <th class="p-3 w-40">Subtotal (Rp)</th>
                            <th class="p-3 w-16 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" id="itemsBody">
                        <tr class="item-row">
                            <td class="p-2">
                                <input type="text" name="items[0][product_name]" class="w-full p-2 border border-gray-300 rounded item-name" placeholder="Nama barang/produk" required>
                            </td>
                            <td class="p-2">
                                <input type="number" name="items[0][quantity]" value="1" min="1" class="w-full p-2 border border-gray-300 rounded item-qty" oninput="calculateSubtotal(this)" required>
                            </td>
                            <td class="p-2">
                                <input type="number" name="items[0][price]" value="0" min="0" class="w-full p-2 border border-gray-300 rounded item-price" oninput="calculateSubtotal(this)" required>
                            </td>
                            <td class="p-2">
                                <input type="number" readonly class="w-full p-2 bg-gray-50 border border-gray-200 rounded item-subtotal font-semibold" value="0">
                            </td>
                            <td class="p-2 text-center">
                                <button type="button" onclick="removeRow(this)" class="text-red-500 hover:text-red-700 p-2"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button type="button" onclick="addRow()" class="mt-3 text-xs font-semibold text-blue-600 hover:text-blue-800 inline-flex items-center">
                <i class="fas fa-plus mr-1"></i> Tambah Item Baris
            </button>
        </div>

        <div class="flex justify-end gap-3 border-t border-gray-100 pt-6">
            <a href="{{ route('sales.invoices.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium text-sm">Batal</a>
            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm">
                <i class="fas fa-save mr-2"></i> Simpan Invoice
            </button>
        </div>
    </form>
</div>

<script>
let rowIndex = 1;

function calculateSubtotal(element) {
    const row = element.closest('.item-row');
    const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
    const price = parseFloat(row.querySelector('.item-price').value) || 0;
    const subtotal = qty * price;
    row.querySelector('.item-subtotal').value = subtotal;
    
    updateTotalAmount();
}

function updateTotalAmount() {
    let total = 0;
    document.querySelectorAll('.item-subtotal').forEach(input => {
        total += parseFloat(input.value) || 0;
    });
    document.getElementById('total_amount_input').value = total;
}

function addRow() {
    const tbody = document.getElementById('itemsBody');
    const tr = document.createElement('tr');
    tr.className = 'item-row';
    tr.innerHTML = `
        <td class="p-2">
            <input type="text" name="items[${rowIndex}][product_name]" class="w-full p-2 border border-gray-300 rounded item-name" placeholder="Nama barang/produk" required>
        </td>
        <td class="p-2">
            <input type="number" name="items[${rowIndex}][quantity]" value="1" min="1" class="w-full p-2 border border-gray-300 rounded item-qty" oninput="calculateSubtotal(this)" required>
        </td>
        <td class="p-2">
            <input type="number" name="items[${rowIndex}][price]" value="0" min="0" class="w-full p-2 border border-gray-300 rounded item-price" oninput="calculateSubtotal(this)" required>
        </td>
        <td class="p-2">
            <input type="number" readonly class="w-full p-2 bg-gray-50 border border-gray-200 rounded item-subtotal font-semibold" value="0">
        </td>
        <td class="p-2 text-center">
            <button type="button" onclick="removeRow(this)" class="text-red-500 hover:text-red-700 p-2"><i class="fas fa-trash"></i></button>
        </td>
    `;
    tbody.appendChild(tr);
    rowIndex++;
}

function removeRow(button) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) {
        button.closest('.item-row').remove();
        updateTotalAmount();
    }
}
</script>
@endsection
