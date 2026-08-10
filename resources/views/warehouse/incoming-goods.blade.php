@extends('layouts.app')

@section('title', 'Input Barang Masuk - LogiBook WMS')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gray-900">Input Barang Masuk</h1>
        <p class="text-gray-500 mt-1">Warehouse Inbound Management</p>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form id="incomingGoodsForm">
                @csrf
                <!-- Receipt Information -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label small text-muted font-bold text-primary"><i class="fas fa-file-invoice me-1"></i> Referensi PO Detail (Opsional)</label>
                        <select class="form-select border-primary" id="poSelect" onchange="loadPOData(this)">
                            <option value="">-- Pilih Referensi PO (Opsional) --</option>
                            @foreach($purchaseOrders as $po)
                                <option value="{{ $po->id }}" data-po="{{ json_encode($po) }}">
                                    {{ $po->po_number }} - {{ $po->supplier->name ?? ($po->supplier->company_name ?? 'Supplier') }} ({{ $po->status }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Nomor Penerimaan</label>
                        <input type="text" class="form-control" name="receipt_number" value="GR-{{ date('Ymd-His') }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">Tanggal Penerimaan</label>
                        <input type="date" class="form-control" name="receive_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Supplier</label>
                        <select class="form-select" name="supplier_id" id="supplierSelect" required>
                            <option value="">Pilih Supplier</option>
                            @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name ?? $supplier->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Daftar Barang Masuk</h5>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addItem()">
                            <i class="fas fa-plus me-1"></i> Tambah Item
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%" class="text-center">NO</th>
                                    <th width="30%">NAMA BARANG</th>
                                    <th width="20%">KODE BARANG</th>
                                    <th width="10%" class="text-center">QTY</th>
                                    <th width="15%" class="text-end">HARGA BELI (Rp)</th>
                                    <th width="15%" class="text-end">SUBTOTAL (Rp)</th>
                                    <th width="5%" class="text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTable">
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>
                                        <select class="form-select form-select-sm product-select" name="items[0][product_id]" onchange="updateProductInfo(this)" required>
                                            <option value="">Pilih Produk</option>
                                            @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-code="{{ $product->product_code }}" data-price="{{ $product->price }}">
                                                {{ $product->product_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm code-input" placeholder="Kode Barang">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm text-center qty-input" name="items[0][quantity]" value="1" min="1" onchange="calculateSubtotal(this)" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm text-end price-input" name="items[0][price]" value="0" min="0" onchange="calculateSubtotal(this)" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm text-end subtotal-input" value="0" readonly>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-end"><strong>Grand Total:</strong></td>
                                    <td class="text-end"><strong id="grandTotal">Rp 0</strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <p class="text-muted small mt-2 mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Anda dapat memilih <strong>Referensi PO</strong> untuk mengisi data barang secara otomatis, atau mengisinya secara manual dengan menekan <strong>+ Tambah Item</strong>.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Simpan Penerimaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let itemCount = 1;

function addItem() {
    itemCount++;
    const tbody = document.getElementById('itemsTable');
    let productOptions = '<option value="">Pilih Produk</option>';
    @foreach($products as $product)
        productOptions += `<option value="{{ $product->id }}" data-code="{{ $product->product_code }}" data-price="{{ $product->price }}">{{ addslashes($product->product_name) }}</option>`;
    @endforeach

    const row = document.createElement('tr');
    row.innerHTML = `
        <td class="text-center"></td>
        <td>
            <select class="form-select form-select-sm product-select" name="items[${itemCount}][product_id]" onchange="updateProductInfo(this)" required>
                ${productOptions}
            </select>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm code-input" placeholder="Kode Barang">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm text-center qty-input" name="items[${itemCount}][quantity]" value="1" min="1" onchange="calculateSubtotal(this)" required>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm text-end price-input" name="items[${itemCount}][price]" value="0" min="0" onchange="calculateSubtotal(this)" required>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm text-end subtotal-input" value="0" readonly>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
    renumberRows();
}

function removeItem(btn) {
    const row = btn.closest('tr');
    if (row) {
        row.remove();
        updateGrandTotal();
        renumberRows();
    }
}

function renumberRows() {
    const rows = document.querySelectorAll('#itemsTable tr');
    rows.forEach((row, index) => {
        const numberCell = row.querySelector('td:first-child');
        if (numberCell) {
            numberCell.textContent = index + 1;
        }
    });
}

function updateProductInfo(elem) {
    const row = elem.closest('tr');
    if (!row) return;
    const select = row.querySelector('.product-select');
    const option = select.options[select.selectedIndex];
    if (option && option.value) {
        const codeInput = row.querySelector('.code-input');
        const priceInput = row.querySelector('.price-input');
        if (codeInput) codeInput.value = option.dataset.code || '';
        if (priceInput) priceInput.value = option.dataset.price || 0;
        calculateSubtotal(elem);
    }
}

function calculateSubtotal(elem) {
    const row = elem.closest('tr');
    if (!row) return;
    const qtyInput = row.querySelector('.qty-input');
    const priceInput = row.querySelector('.price-input');
    const subtotalInput = row.querySelector('.subtotal-input');
    
    const qty = parseFloat(qtyInput?.value) || 0;
    const price = parseFloat(priceInput?.value) || 0;
    const subtotal = qty * price;
    
    if (subtotalInput) subtotalInput.value = formatNumber(subtotal);
    updateGrandTotal();
}

function loadPOData(selectElem) {
    const selectedOpt = selectElem.options[selectElem.selectedIndex];
    if (!selectedOpt || !selectedOpt.value || !selectedOpt.dataset.po) return;
    
    const po = JSON.parse(selectedOpt.dataset.po);
    if (!po) return;
    
    // Set supplier
    if (po.supplier_id) {
        document.getElementById('supplierSelect').value = po.supplier_id;
    }
    
    // Fill items
    if (po.items && po.items.length > 0) {
        const tbody = document.getElementById('itemsTable');
        tbody.innerHTML = '';
        
        po.items.forEach((item, i) => {
            let productOptions = '<option value="">Pilih Produk</option>';
            @foreach($products as $product)
                const isSel = {{ $product->id }} == item.product_id ? 'selected' : '';
                productOptions += `<option value="{{ $product->id }}" data-code="{{ $product->product_code }}" data-price="{{ $product->price }}" ${isSel}>{{ addslashes($product->product_name) }}</option>`;
            @endforeach
            
            const itemPrice = item.price || item.unit_price || 0;
            const subtotal = item.quantity * itemPrice;
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="text-center">${i + 1}</td>
                <td>
                    <select class="form-select form-select-sm product-select" name="items[${i}][product_id]" onchange="updateProductInfo(this)" required>
                        ${productOptions}
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm code-input" value="${item.product ? item.product.product_code : ''}" placeholder="Kode Barang">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm text-center qty-input" name="items[${i}][quantity]" value="${item.quantity}" min="1" onchange="calculateSubtotal(this)" required>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm text-end price-input" name="items[${i}][price]" value="${itemPrice}" min="0" onchange="calculateSubtotal(this)" required>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm text-end subtotal-input" value="${formatNumber(subtotal)}" readonly>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
        renumberRows();
        updateGrandTotal();
    }
}

function updateGrandTotal() {
    let total = 0;
    document.querySelectorAll('.subtotal-input').forEach(input => {
        const value = parseFloat(input.value.replace(/\./g, '')) || 0;
        total += value;
    });
    document.getElementById('grandTotal').textContent = 'Rp ' + formatNumber(total);
}

function formatNumber(num) {
    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Handle form submission
document.getElementById('incomingGoodsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        receipt_number: formData.get('receipt_number'),
        receive_date: formData.get('receive_date'),
        supplier_id: formData.get('supplier_id'),
        items: []
    };
    
    // Collect items
    const rows = document.querySelectorAll('#itemsTable tr');
    rows.forEach((row) => {
        const productSelect = row.querySelector('.product-select');
        const qtyInput = row.querySelector('.qty-input');
        const priceInput = row.querySelector('.price-input');
        
        if (productSelect && productSelect.value) {
            data.items.push({
                product_id: productSelect.value,
                quantity: parseInt(qtyInput ? qtyInput.value : 1),
                price: parseFloat(priceInput ? priceInput.value : 0)
            });
        }
    });

    if (data.items.length === 0) {
        alert('Silakan tambahkan minimal 1 item barang.');
        return;
    }
    
    fetch('{{ route("warehouse.incoming-goods.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert(result.message);
            window.location.href = '{{ route("warehouse.verifikasi.index") }}';
        } else {
            alert('Error: ' + result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data.');
    });
});
</script>
@endsection
