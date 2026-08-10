@extends('layouts.app')

@section('title', 'Input Barang Masuk - LogiBook WMS')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Page & Title -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1 rounded-pill small">
                    <i class="fas fa-boxes-packing me-1"></i> Warehouse Inbound
                </span>
                <span class="text-muted small">• LogiBook WMS</span>
            </div>
            <h1 class="h3 font-bold text-gray-900 mb-0">Input Barang Masuk</h1>
            <p class="text-muted small mb-0">Pencatatan fisik barang yang diterima di gudang dari supplier.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('warehouse.verifikasi.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">
                <i class="fas fa-list-check me-1.5"></i> Riwayat & Verifikasi
            </a>
        </div>
    </div>

    <!-- Quick Educational Banner / Explanation -->
    <div class="card border-0 shadow-sm mb-4 bg-gradient-to-r from-blue-50/80 via-indigo-50/40 to-white rounded-squircle">
        <div class="card-body p-3.5 d-flex align-items-start gap-3">
            <div class="p-3 bg-primary text-white rounded-3 shadow-sm d-none d-sm-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <i class="fas fa-truck-ramp-box fa-lg"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="fw-bold text-gray-900 mb-1 d-flex align-items-center gap-2">
                    <span>Cara Kerja Penerimaan Barang</span>
                    <span class="badge bg-info-subtle text-info rounded-pill font-normal small px-2">Petunjuk Penggunaan</span>
                </h6>
                <p class="text-muted small mb-0 leading-relaxed">
                    Halaman ini digunakan untuk menginput data penerimaan barang fisik. Anda bisa memilih <strong>Referensi PO</strong> untuk menarik daftar pesanan secara otomatis, atau mengisinya secara <strong>Manual</strong>. Setelah disimpan, data akan masuk ke daftar <em>Verifikasi Barang Masuk</em> untuk dicek fisik oleh petugas gudang.
                </p>
            </div>
        </div>
    </div>

    <!-- Workflow Visual Stepper -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 rounded-3 border-start border-4 border-primary">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary text-white font-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">1</div>
                    <div>
                        <div class="fw-semibold text-gray-900 small">Tentukan Metode & Supplier</div>
                        <div class="text-muted text-xs">Pilih PO (jika ada) atau Supplier & Tanggal</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 rounded-3 border-start border-4 border-info">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-info text-white font-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">2</div>
                    <div>
                        <div class="fw-semibold text-gray-900 small">Input Detail Produk & Qty</div>
                        <div class="text-muted text-xs">Pilih produk, masukkan Qty & harga beli</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 rounded-3 border-start border-4 border-success">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success text-white font-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">3</div>
                    <div>
                        <div class="fw-semibold text-gray-900 small">Simpan Penerimaan</div>
                        <div class="text-muted text-xs">Data diteruskan ke tahap Verifikasi Gudang</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live KPI Summary Counters -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm stat-card bg-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted text-xs fw-semibold text-uppercase">Total Jenis Barang</div>
                        <div class="h3 font-bold text-gray-900 mb-0 mt-1" id="kpiItemTypes">1 Item</div>
                    </div>
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-3">
                        <i class="fas fa-boxes-stacked fa-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm stat-card bg-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted text-xs fw-semibold text-uppercase">Total Kuantitas Unit</div>
                        <div class="h3 font-bold text-indigo-600 mb-0 mt-1" id="kpiTotalQty">1 Pcs</div>
                    </div>
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-3">
                        <i class="fas fa-layer-group fa-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm stat-card bg-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted text-xs fw-semibold text-uppercase">Estimasi Nilai Barang</div>
                        <div class="h3 font-bold text-emerald-600 mb-0 mt-1" id="kpiGrandTotal">Rp 0</div>
                    </div>
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-3">
                        <i class="fas fa-wallet fa-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="card border-0 shadow-sm rounded-squircle overflow-hidden mb-4">
        <div class="card-header bg-white py-3.5 px-4 border-bottom d-flex align-items-center justify-content-between">
            <h5 class="fw-bold text-gray-900 mb-0 d-flex align-items-center gap-2">
                <i class="fas fa-file-invoice-dollar text-primary"></i> Form Penerimaan Barang Fisik
            </h5>
            <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill text-xs">
                Status Data: <strong class="text-warning"><i class="fas fa-clock me-1"></i>Draft Pending</strong>
            </span>
        </div>
        <div class="card-body p-4">
            <form id="incomingGoodsForm">
                @csrf
                
                <!-- Section Header 1: Document Header Info -->
                <div class="bg-slate-50/80 p-3.5 rounded-3 border mb-4">
                    <div class="row g-3">
                        <!-- PO Reference Select -->
                        <div class="col-md-4">
                            <label class="form-label font-bold text-xs text-uppercase text-gray-700">
                                <i class="fas fa-file-contract text-primary me-1"></i> Referensi PO (Opsional)
                            </label>
                            <select class="form-select border-primary-subtle shadow-sm" id="poSelect" onchange="loadPOData(this)">
                                <option value="">-- Tanpa PO (Input Manual) --</option>
                                @foreach($purchaseOrders as $po)
                                    <option value="{{ $po->id }}" data-po="{{ json_encode($po) }}">
                                        {{ $po->po_number }} - {{ $po->supplier->name ?? ($po->supplier->company_name ?? 'Supplier') }} [{{ $po->status }}]
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text text-xs text-muted">Pilih PO untuk mengisi supplier & item otomatis.</div>
                        </div>

                        <!-- Goods Receipt Number -->
                        <div class="col-md-3">
                            <label class="form-label font-bold text-xs text-uppercase text-gray-700">
                                <i class="fas fa-hashtag text-muted me-1"></i> Nomor Penerimaan (GR)
                            </label>
                            <input type="text" class="form-control bg-light font-mono" name="receipt_number" value="GR-{{ date('Ymd-His') }}" required readonly title="Nomor penerimaan di-generate otomatis oleh sistem">
                        </div>

                        <!-- Date -->
                        <div class="col-md-2">
                            <label class="form-label font-bold text-xs text-uppercase text-gray-700">
                                <i class="fas fa-calendar-day text-muted me-1"></i> Tgl Penerimaan
                            </label>
                            <input type="date" class="form-control" name="receive_date" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <!-- Supplier -->
                        <div class="col-md-3">
                            <label class="form-label font-bold text-xs text-uppercase text-gray-700">
                                <i class="fas fa-building text-muted me-1"></i> Supplier <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" name="supplier_id" id="supplierSelect" required>
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name ?? $supplier->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section Header 2: Items Table -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold text-gray-900 mb-0">Rincian Barang Diterima</h5>
                            <p class="text-muted text-xs mb-0">Pastikan kuantitas fisik dan harga beli sudah sesuai dokumen pengiriman.</p>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm px-3 rounded-3 shadow-sm hover:transform hover:-translate-y-0.5 transition-all" onclick="addItem()">
                            <i class="fas fa-plus me-1.5"></i> Tambah Baris Barang
                        </button>
                    </div>

                    <div class="table-responsive border rounded-3 overflow-hidden shadow-xs">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-slate-100 text-gray-700 text-xs text-uppercase font-semibold">
                                <tr>
                                    <th width="4%" class="text-center py-3">#</th>
                                    <th width="30%" class="py-3">Produk / Barang</th>
                                    <th width="20%" class="py-3">Kode Barang (SKU)</th>
                                    <th width="12%" class="text-center py-3">Qty Diterima</th>
                                    <th width="15%" class="text-end py-3">Harga Beli (Rp)</th>
                                    <th width="14%" class="text-end py-3">Subtotal (Rp)</th>
                                    <th width="5%" class="text-center py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTable" class="divide-y divide-gray-100">
                                <tr>
                                    <td class="text-center font-bold text-muted text-xs row-number">1</td>
                                    <td>
                                        <select class="form-select form-select-sm product-select" name="items[0][product_id]" onchange="updateProductInfo(this)" required>
                                            <option value="">-- Pilih Produk --</option>
                                            @foreach($products as $product)
                                            <option value="{{ $product->id }}" 
                                                    data-code="{{ $product->product_code }}" 
                                                    data-price="{{ $product->price }}"
                                                    data-unit="{{ $product->unit ?? 'Pcs' }}"
                                                    data-stock="{{ $product->physical_stock ?? $product->system_stock ?? 0 }}">
                                                {{ $product->product_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <input type="text" class="form-control form-control-sm code-input font-mono" placeholder="Kode Barang / SKU" oninput="findProductByCode(this)">
                                            <div class="d-flex align-items-center justify-content-between text-xs px-0.5">
                                                <span class="text-muted text-xs stock-badge"><i class="fas fa-cubes text-info me-1"></i>Stok: 0</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control text-center qty-input font-semibold" name="items[0][quantity]" value="1" min="1" onchange="calculateSubtotal(this)" onkeyup="calculateSubtotal(this)" required>
                                            <span class="input-group-text text-xs text-muted unit-label">Pcs</span>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm text-end price-input font-mono" name="items[0][price]" value="0" min="0" step="100" onchange="calculateSubtotal(this)" onkeyup="calculateSubtotal(this)" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm text-end bg-light font-bold text-gray-900 subtotal-input font-mono" value="0" readonly>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" onclick="removeItem(this)" title="Hapus Baris">
                                            <i class="fas fa-trash-can"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-slate-50/70 border-top font-semibold">
                                <tr>
                                    <td colspan="5" class="text-end py-3 text-gray-700">Grand Total Keseluruhan:</td>
                                    <td class="text-end py-3"><strong class="h6 font-bold text-primary mb-0" id="grandTotal">Rp 0</strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mt-3 text-muted text-xs">
                        <div class="d-flex align-items-center gap-1.5">
                            <i class="fas fa-circle-info text-primary"></i>
                            <span>Anda dapat memilih produk dari dropdown atau mengetik/scan <strong>Kode Barang (SKU)</strong> secara langsung.</span>
                        </div>
                    </div>
                </div>

                <!-- Form Action Buttons -->
                <div class="d-flex justify-content-end align-items-center gap-2 pt-3 border-top">
                    <button type="button" class="btn btn-light px-4 border" onclick="window.history.back()">
                        <i class="fas fa-arrow-left me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" id="btnSubmit">
                        <i class="fas fa-floppy-disk me-1.5"></i> Simpan Penerimaan Barang
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
    
    // Find master product select from first row or template to clone
    const firstSelect = document.querySelector('.product-select');
    let selectHtml = '';
    
    if (firstSelect) {
        // Clone outerHTML and update name index
        selectHtml = firstSelect.outerHTML.replace(/name="items\[\d+\]\[product_id\]"/g, `name="items[${itemCount}][product_id]"`);
    } else {
        selectHtml = `<select class="form-select form-select-sm product-select" name="items[${itemCount}][product_id]" onchange="updateProductInfo(this)" required><option value="">-- Pilih Produk --</option></select>`;
    }

    const row = document.createElement('tr');
    row.innerHTML = `
        <td class="text-center font-bold text-muted text-xs row-number"></td>
        <td>
            ${selectHtml}
        </td>
        <td>
            <div class="d-flex flex-column gap-1">
                <input type="text" class="form-control form-control-sm code-input font-mono" placeholder="Kode Barang / SKU" oninput="findProductByCode(this)">
                <div class="d-flex align-items-center justify-content-between text-xs px-0.5">
                    <span class="text-muted text-xs stock-badge"><i class="fas fa-cubes text-info me-1"></i>Stok: 0</span>
                </div>
            </div>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <input type="number" class="form-control text-center qty-input font-semibold" name="items[${itemCount}][quantity]" value="1" min="1" onchange="calculateSubtotal(this)" onkeyup="calculateSubtotal(this)" required>
                <span class="input-group-text text-xs text-muted unit-label">Pcs</span>
            </div>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm text-end price-input font-mono" name="items[${itemCount}][price]" value="0" min="0" step="100" onchange="calculateSubtotal(this)" onkeyup="calculateSubtotal(this)" required>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm text-end bg-light font-bold text-gray-900 subtotal-input font-mono" value="0" readonly>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" onclick="removeItem(this)" title="Hapus Baris">
                <i class="fas fa-trash-can"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
    
    // Ensure newly added select starts at index 0 (unselected)
    const newRowSelect = row.querySelector('.product-select');
    if (newRowSelect) {
        newRowSelect.selectedIndex = 0;
    }

    renumberRows();
    updateGrandTotal();
}

function removeItem(btn) {
    const rows = document.querySelectorAll('#itemsTable tr');
    if (rows.length <= 1) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Minimal 1 Item',
                text: 'Tabel penerimaan harus memiliki minimal 1 item barang.',
                confirmButtonColor: '#4f46e5'
            });
        } else {
            alert('Minimal harus ada 1 item barang.');
        }
        return;
    }
    
    const row = btn.closest('tr');
    if (row) {
        row.remove();
        renumberRows();
        updateGrandTotal();
    }
}

function renumberRows() {
    const rows = document.querySelectorAll('#itemsTable tr');
    rows.forEach((row, index) => {
        const numberCell = row.querySelector('.row-number');
        if (numberCell) {
            numberCell.textContent = index + 1;
        }
    });
}

function findProductByCode(inputElem) {
    const row = inputElem.closest('tr');
    if (!row) return;
    const searchCode = inputElem.value.trim().toLowerCase();
    const select = row.querySelector('.product-select');
    if (!select) return;
    
    if (!searchCode) {
        select.value = "";
        updateProductInfo(select, false);
        return;
    }
    
    let matched = false;
    for (let i = 0; i < select.options.length; i++) {
        const opt = select.options[i];
        const code = (opt.dataset.code || '').toLowerCase();
        if (code === searchCode) {
            select.selectedIndex = i;
            updateProductInfo(select, false);
            matched = true;
            break;
        }
    }
    
    if (!matched) {
        for (let i = 0; i < select.options.length; i++) {
            const opt = select.options[i];
            const code = (opt.dataset.code || '').toLowerCase();
            if (code && code.includes(searchCode)) {
                select.selectedIndex = i;
                updateProductInfo(select, false);
                break;
            }
        }
    }
}

function updateProductInfo(elem, updateCodeInput = true) {
    const row = elem.closest('tr');
    if (!row) return;
    const select = row.querySelector('.product-select');
    const option = select.options[select.selectedIndex];
    
    const stockBadge = row.querySelector('.stock-badge');
    const unitLabel = row.querySelector('.unit-label');
    const codeInput = row.querySelector('.code-input');
    const priceInput = row.querySelector('.price-input');
    
    if (option && option.value) {
        const code = option.dataset.code || '';
        const price = option.dataset.price || 0;
        const unit = option.dataset.unit || 'Pcs';
        const stock = option.dataset.stock || 0;
        
        if (stockBadge) stockBadge.innerHTML = `<i class="fas fa-cubes text-info me-1"></i>Stok: ${stock}`;
        if (unitLabel) unitLabel.textContent = unit;
        if (codeInput && updateCodeInput) codeInput.value = code;
        if (priceInput && (parseFloat(priceInput.value) === 0 || !priceInput.dataset.manual)) {
            priceInput.value = price;
        }
        calculateSubtotal(elem);
    } else {
        if (stockBadge) stockBadge.innerHTML = `<i class="fas fa-cubes text-info me-1"></i>Stok: 0`;
        if (unitLabel) unitLabel.textContent = 'Pcs';
        if (codeInput && updateCodeInput) codeInput.value = '';
        if (priceInput) priceInput.value = 0;
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
    
    try {
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
                let productOptions = '<option value="">-- Pilih Produk --</option>';
                @foreach($products as $product)
                    const isSel = {{ $product->id }} == item.product_id ? 'selected' : '';
                    productOptions += `<option value="{{ $product->id }}" 
                        data-code="{{ addslashes($product->product_code) }}" 
                        data-price="{{ $product->price }}"
                        data-unit="{{ addslashes($product->unit ?? 'Pcs') }}"
                        data-stock="{{ $product->physical_stock ?? $product->system_stock ?? 0 }}" ${isSel}>
                        {{ addslashes($product->product_name) }}
                    </option>`;
                @endforeach
                
                const itemPrice = item.price || item.unit_price || (item.product ? item.product.price : 0);
                const itemCode = item.product ? item.product.product_code : '';
                const itemUnit = item.product ? (item.product.unit || 'Pcs') : 'Pcs';
                const itemStock = item.product ? (item.product.physical_stock ?? item.product.system_stock ?? 0) : 0;
                const subtotal = item.quantity * itemPrice;
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="text-center font-bold text-muted text-xs row-number">${i + 1}</td>
                    <td>
                        <select class="form-select form-select-sm product-select" name="items[${i}][product_id]" onchange="updateProductInfo(this)" required>
                            ${productOptions}
                        </select>
                    </td>
                    <td>
                        <div class="d-flex flex-column gap-1">
                            <input type="text" class="form-control form-control-sm code-input font-mono" value="${itemCode}" placeholder="Kode Barang / SKU" oninput="findProductByCode(this)">
                            <div class="d-flex align-items-center justify-content-between text-xs px-0.5">
                                <span class="text-muted text-xs stock-badge"><i class="fas fa-cubes text-info me-1"></i>Stok: ${itemStock}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control text-center qty-input font-semibold" name="items[${i}][quantity]" value="${item.quantity}" min="1" onchange="calculateSubtotal(this)" onkeyup="calculateSubtotal(this)" required>
                            <span class="input-group-text text-xs text-muted unit-label">${itemUnit}</span>
                        </div>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm text-end price-input font-mono" name="items[${i}][price]" value="${itemPrice}" min="0" step="100" onchange="calculateSubtotal(this)" onkeyup="calculateSubtotal(this)" required>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm text-end bg-light font-bold text-gray-900 subtotal-input font-mono" value="${formatNumber(subtotal)}" readonly>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" onclick="removeItem(this)" title="Hapus Baris">
                            <i class="fas fa-trash-can"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
            renumberRows();
            updateGrandTotal();
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Item PO Berhasil Dimuat',
                    text: `Impor ${po.items.length} barang dari PO ${po.po_number}`,
                    showConfirmButton: false,
                    timer: 2500
                });
            }
        }
    } catch (err) {
        console.error('Error parsing PO data:', err);
    }
}

function updateGrandTotal() {
    let grandTotal = 0;
    let totalQty = 0;
    let itemCount = 0;

    document.querySelectorAll('#itemsTable tr').forEach(row => {
        const productSelect = row.querySelector('.product-select');
        const qtyInput = row.querySelector('.qty-input');
        const subtotalInput = row.querySelector('.subtotal-input');
        
        if (productSelect && productSelect.value) {
            itemCount++;
            const qty = parseInt(qtyInput?.value) || 0;
            totalQty += qty;
            
            const valStr = subtotalInput?.value ? subtotalInput.value.replace(/\./g, '') : '0';
            grandTotal += parseFloat(valStr) || 0;
        }
    });

    document.getElementById('grandTotal').textContent = 'Rp ' + formatNumber(grandTotal);
    
    // Update top KPI cards
    document.getElementById('kpiItemTypes').textContent = itemCount + ' Jenis';
    document.getElementById('kpiTotalQty').textContent = totalQty + ' Unit';
    document.getElementById('kpiGrandTotal').textContent = 'Rp ' + formatNumber(grandTotal);
}

function formatNumber(num) {
    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Handle Form Submit via AJAX & SweetAlert2
document.getElementById('incomingGoodsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        receipt_number: formData.get('receipt_number'),
        receive_date: formData.get('receive_date'),
        supplier_id: formData.get('supplier_id'),
        items: []
    };

    if (!data.supplier_id) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'warning', title: 'Supplier Belum Dipilih', text: 'Silakan pilih supplier penerimaan barang.', confirmButtonColor: '#4f46e5' });
        } else {
            alert('Silakan pilih supplier penerimaan barang.');
        }
        return;
    }
    
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
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'warning', title: 'Barang Kosong', text: 'Silakan tambahkan minimal 1 item barang yang valid.', confirmButtonColor: '#4f46e5' });
        } else {
            alert('Silakan tambahkan minimal 1 item barang.');
        }
        return;
    }

    const submitBtn = document.getElementById('btnSubmit');
    const origHtml = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1.5"></i> Menyimpan...';
    
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
        submitBtn.disabled = false;
        submitBtn.innerHTML = origHtml;

        if (result.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Penerimaan Berhasil Disimpan!',
                    text: result.message || 'Data penerimaan telah dibuat dan masuk ke tahap verifikasi gudang.',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Lihat Daftar Verifikasi',
                    cancelButtonText: 'Input Barang Lain'
                }).then((res) => {
                    if (res.isConfirmed) {
                        window.location.href = '{{ route("warehouse.verifikasi.index") }}';
                    } else {
                        window.location.reload();
                    }
                });
            } else {
                alert(result.message);
                window.location.href = '{{ route("warehouse.verifikasi.index") }}';
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Gagal Menyimpan', text: result.message, confirmButtonColor: '#4f46e5' });
            } else {
                alert('Error: ' + result.message);
            }
        }
    })
    .catch(error => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = origHtml;
        console.error('Error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan', text: 'Gagal menghubungi server saat menyimpan data.', confirmButtonColor: '#4f46e5' });
        } else {
            alert('Terjadi kesalahan saat menyimpan data.');
        }
    });
});
</script>
@endsection

