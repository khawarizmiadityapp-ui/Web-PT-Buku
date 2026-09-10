@extends('layouts.app')

@section('title', 'Input Barang Masuk - LogiBook WMS')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Page & Title -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1 rounded-pill small">
                    <x-icon name="boxes-packing" class="w-4 h-4 me-1" /> Warehouse Inbound
                </span>
                <span class="text-muted small">• LogiBook WMS</span>
            </div>
            <h1 class="h3 font-bold text-gray-900 mb-0">Input Barang Masuk</h1>
            <p class="text-muted small mb-0">Pencatatan fisik barang yang diterima di gudang dari supplier.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('warehouse.verifikasi.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">
                <x-icon name="list-check" class="w-4 h-4 me-1.5" /> Riwayat & Verifikasi
            </a>
        </div>
    </div>

    <!-- Quick Educational Banner / Explanation -->
    <div class="card border-0 shadow-sm mb-4 bg-gradient-to-r from-blue-50/80 via-indigo-50/40 to-white rounded-squircle">
        <div class="card-body p-3.5 d-flex align-items-start gap-3">
            <div class="p-3 bg-primary text-white rounded-3 shadow-sm d-none d-sm-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <x-icon name="truck-ramp-box" class="w-4 h-4 fa-lg" />
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
                        <x-icon name="boxes-stacked" class="w-4 h-4 fa-xl" />
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
                        <x-icon name="layer-group" class="w-4 h-4 fa-xl" />
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
                        <x-icon name="wallet" class="w-4 h-4 fa-xl" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="card border-0 shadow-sm rounded-squircle mb-4">
        <div class="card-header bg-white py-3.5 px-4 border-bottom d-flex align-items-center justify-content-between">
            <h5 class="fw-bold text-gray-900 mb-0 d-flex align-items-center gap-2">
                <x-icon name="file-invoice-dollar" class="w-4 h-4 text-primary" /> Form Penerimaan Barang Fisik
            </h5>
            <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill text-xs">
                Status Data: <strong class="text-warning"><x-icon name="clock" class="w-4 h-4 me-1" />Draft Pending</strong>
            </span>
        </div>
        <div class="card-body p-4">
            <form id="incomingGoodsForm">
                @csrf
                
                <!-- Section Header 1: Document Header Info -->
                <div class="bg-slate-50/80 p-3.5 rounded-3 border mb-4 position-relative">
                    <div class="row g-3">
                        <!-- PO Reference Search Combobox -->
                        <div class="col-md-4">
                            <label class="form-label font-bold text-xs text-uppercase text-gray-700">
                                <x-icon name="file-contract" class="w-4 h-4 text-primary me-1" /> Referensi PO / Surat Jalan (Opsional)
                            </label>
                            <div class="position-relative" id="poComboboxWrapper">
                                <div class="input-group input-group-sm shadow-xs">
                                    <span class="input-group-text bg-white text-muted border-end-0"><x-icon name="search" class="w-4 h-4" /></span>
                                    <input type="text" class="form-control border-start-0 ps-0 text-xs font-semibold" id="poSearchInput" placeholder="Cari No. PO atau ketik Surat Jalan..." autocomplete="off" onfocus="renderPODropdown(this.value)" oninput="renderPODropdown(this.value)">
                                    <button class="btn btn-outline-secondary border-start-0 d-none" type="button" id="btnClearPO" onclick="clearPOSelection()" title="Reset PO"><x-icon name="xmark" class="w-4 h-4" /></button>
                                </div>
                                <input type="hidden" id="poSelect" name="purchase_order_id" value="">
                                <input type="hidden" id="manualReference" name="manual_reference" value="">
                                <div id="poSearchResults" class="dropdown-menu shadow-lg w-100 p-1 mt-1 border-0 rounded-3 overflow-auto" style="max-height: 250px; display: none; position: absolute; z-index: 1050;"></div>
                            </div>
                            <div class="mt-1.5" id="poSelectedBadge">
                                <span class="text-xs text-muted"><x-icon name="info-circle" class="w-4 h-4 me-1" />Ketik untuk cari PO atau nomor Surat Jalan manual.</span>
                            </div>
                        </div>

                        <!-- Goods Receipt Number -->
                        <div class="col-md-3">
                            <label class="form-label font-bold text-xs text-uppercase text-gray-700">
                                <x-icon name="hashtag" class="w-4 h-4 text-muted me-1" /> Nomor Penerimaan (GR)
                            </label>
                            <input type="text" class="form-control bg-light font-mono" name="receipt_number" value="GR-{{ date('Ymd-His') }}" required readonly title="Nomor penerimaan di-generate otomatis oleh sistem">
                        </div>

                        <!-- Date -->
                        <div class="col-md-2">
                            <label class="form-label font-bold text-xs text-uppercase text-gray-700">
                                <x-icon name="calendar-day" class="w-4 h-4 text-muted me-1" /> Tgl Penerimaan
                            </label>
                            <input type="date" class="form-control" name="receive_date" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <!-- Supplier Search Combobox -->
                        <div class="col-md-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label font-bold text-xs text-uppercase text-gray-700 mb-0">
                                    <x-icon name="building" class="w-4 h-4 text-muted me-1" /> Supplier <span class="text-danger">*</span>
                                </label>
                                <button type="button" class="btn btn-link p-0 text-primary text-xs font-semibold text-decoration-none hover:underline" data-bs-toggle="modal" data-bs-target="#quickAddSupplierModal">
                                    <x-icon name="plus-circle" class="w-4 h-4 me-0.5" /> + Detail Supplier
                                </button>
                            </div>
                            <div class="position-relative" id="supplierComboboxWrapper">
                                <div class="input-group input-group-sm shadow-xs">
                                    <span class="input-group-text bg-white text-muted border-end-0"><x-icon name="search" class="w-4 h-4 text-primary" /></span>
                                    <input type="text" class="form-control border-start-0 ps-0 text-xs font-semibold" id="supplierSearchInput" placeholder="Cari / ketik nama supplier..." autocomplete="off" onfocus="renderSupplierDropdown(this.value)" oninput="renderSupplierDropdown(this.value)" onclick="renderSupplierDropdown(this.value)">
                                    <button class="btn btn-outline-secondary border-start-0 d-none" type="button" id="btnClearSupplier" onclick="clearSupplierSelection()" title="Reset Supplier"><x-icon name="xmark" class="w-4 h-4" /></button>
                                </div>
                                <input type="hidden" name="supplier_id" id="supplierIdInput" value="">
                                <input type="hidden" name="new_supplier_name" id="newSupplierNameInput" value="">
                                <div id="supplierSearchResults" class="dropdown-menu shadow-lg w-100 p-1 mt-1 border-0 rounded-3 overflow-auto" style="max-height: 260px; display: none; position: absolute; top: 100%; left: 0; right: 0; z-index: 99999 !important;"></div>
                            </div>
                            <div class="mt-1.5" id="supplierSelectedBadge">
                                <span class="text-xs text-muted"><x-icon name="info-circle" class="w-4 h-4 me-1" />Ketik nama supplier untuk mencari/membuat baru.</span>
                            </div>
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
                            <x-icon name="plus" class="w-4 h-4 me-1.5" /> Tambah Baris Barang
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
                                                <span class="text-muted text-xs stock-badge"><x-icon name="cubes" class="w-4 h-4 text-info me-1" />Stok: 0</span>
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
                                            <x-icon name="trash-can" class="w-4 h-4" />
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
                            <x-icon name="circle-info" class="w-4 h-4 text-primary" />
                            <span>Anda dapat memilih produk dari dropdown atau mengetik/scan <strong>Kode Barang (SKU)</strong> secara langsung.</span>
                        </div>
                    </div>
                </div>

                <!-- Form Action Buttons -->
                <div class="d-flex justify-content-end align-items-center gap-2 pt-3 border-top">
                    <button type="button" class="btn btn-light px-4 border" onclick="window.history.back()">
                        <x-icon name="arrow-left" class="w-4 h-4 me-1" /> Batal
                    </button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" id="btnSubmit">
                        <x-icon name="floppy-disk" class="w-4 h-4 me-1.5" /> Simpan Penerimaan Barang
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
                    <span class="text-muted text-xs stock-badge"><x-icon name="cubes" class="w-4 h-4 text-info me-1" />Stok: 0</span>
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
                <x-icon name="trash-can" class="w-4 h-4" />
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
        
        if (stockBadge) stockBadge.innerHTML = `<x-icon name="cubes" class="w-4 h-4 text-info me-1" />Stok: ${stock}`;
        if (unitLabel) unitLabel.textContent = unit;
        if (codeInput && updateCodeInput) codeInput.value = code;
        if (priceInput && (parseFloat(priceInput.value) === 0 || !priceInput.dataset.manual)) {
            priceInput.value = price;
        }
        calculateSubtotal(elem);
    } else {
        if (stockBadge) stockBadge.innerHTML = `<x-icon name="cubes" class="w-4 h-4 text-info me-1" />Stok: 0`;
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
    
    let qty = parseFloat(qtyInput?.value) || 0;
    if (qty < 1) {
        qty = 1;
        if (qtyInput) qtyInput.value = 1;
    }
    let price = parseFloat(priceInput?.value) || 0;
    if (price < 0) {
        price = 0;
        if (priceInput) priceInput.value = 0;
    }
    const subtotal = qty * price;
    
    if (subtotalInput) subtotalInput.value = formatNumber(subtotal);
    updateGrandTotal();
}

function loadPODataFromObj(po) {
    if (!po) return;
    
    // Fill items
    if (po.items && po.items.length > 0) {
        const tbody = document.getElementById('itemsTable');
        tbody.innerHTML = '';
        
        po.items.forEach((item, i) => {
            let productOptions = '<option value="">-- Pilih Produk --</option>';
            @foreach($products as $product)
                productOptions += `<option value="{{ $product->id }}" 
                    data-code="{{ addslashes($product->product_code) }}" 
                    data-price="{{ $product->price }}"
                    data-unit="{{ addslashes($product->unit ?? 'Pcs') }}"
                    data-stock="{{ $product->physical_stock ?? $product->system_stock ?? 0 }}" ${ {{ $product->id }} == item.product_id ? 'selected' : '' }>
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
                            <span class="text-muted text-xs stock-badge"><x-icon name="cubes" class="w-4 h-4 text-info me-1" />Stok: ${itemStock}</span>
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
                        <x-icon name="trash-can" class="w-4 h-4" />
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

// --- Live Search Combobox Engine ---
let rawSuppliersData = @json($suppliers);
let rawPurchaseOrdersData = @json($purchaseOrders);

function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// Supplier Combobox Logic
function renderSupplierDropdown(query = '') {
    const dropdown = document.getElementById('supplierSearchResults');
    if (!dropdown) return;
    const q = (query || '').trim().toLowerCase();
    
    let matches = rawSuppliersData.filter(s => {
        const name = (s.name || '').toLowerCase();
        const company = (s.company_name || '').toLowerCase();
        const code = (s.supplier_code || '').toLowerCase();
        return name.includes(q) || company.includes(q) || code.includes(q);
    });

    let html = '';

    if (q.length > 0) {
        const exactMatch = rawSuppliersData.some(s => 
            (s.name || '').toLowerCase() === q || (s.company_name || '').toLowerCase() === q
        );
        if (!exactMatch) {
            html += `
                <div class="dropdown-item py-2 px-3 text-primary font-semibold border-bottom cursor-pointer rounded-2 bg-blue-50/80 mb-1" onclick="selectNewSupplierFromSearch('${escapeHtml(query)}')">
                    <x-icon name="plus-circle" class="w-4 h-4 me-1.5 text-primary" /> + Buat Supplier Baru: <strong class="text-indigo-700">"${escapeHtml(query)}"</strong>
                </div>
            `;
        }
    }

    if (matches.length > 0) {
        matches.forEach(s => {
            const displayName = s.name || s.company_name;
            const companySub = (s.company_name && s.company_name !== s.name) ? s.company_name : (s.city ? s.city : 'Supplier');
            const codeStr = s.supplier_code ? s.supplier_code : '';
            html += `
                <div class="dropdown-item py-2.5 px-3 cursor-pointer rounded-2 mb-0.5 d-flex justify-content-between align-items-center hover:bg-slate-100" onclick="selectExistingSupplier(${s.id}, '${escapeHtml(displayName)}')">
                    <div>
                        <div class="fw-semibold text-gray-900 text-xs d-flex align-items-center gap-1.5">
                            <x-icon name="building" class="w-4 h-4 text-primary small" />
                            <span>${escapeHtml(displayName)}</span>
                        </div>
                        <div class="text-muted text-xs ms-3.5" style="font-size: 11px;">${escapeHtml(companySub)} ${s.phone ? '• ' + escapeHtml(s.phone) : ''}</div>
                    </div>
                    <span class="badge bg-light text-secondary border font-mono text-xs">${escapeHtml(codeStr)}</span>
                </div>
            `;
        });
    } else if (q.length === 0) {
        html += `<div class="px-3 py-2 text-muted text-xs"><x-icon name="search" class="w-4 h-4 me-1" />Pilih supplier dari daftar atau ketik nama supplier baru...</div>`;
    } else {
        html += `<div class="px-3 py-2 text-muted text-xs">Tidak ada supplier terdaftar dengan nama <strong>"${escapeHtml(query)}"</strong>. Klik opsi di atas untuk membuatnya sebagai supplier baru.</div>`;
    }

    dropdown.innerHTML = html;
    dropdown.classList.add('show');
    dropdown.style.setProperty('display', 'block', 'important');
    dropdown.style.setProperty('z-index', '99999', 'important');
}

function selectExistingSupplier(id, name) {
    document.getElementById('supplierIdInput').value = id;
    document.getElementById('newSupplierNameInput').value = '';
    document.getElementById('supplierSearchInput').value = name;
    
    const dropdown = document.getElementById('supplierSearchResults');
    if (dropdown) {
        dropdown.classList.remove('show');
        dropdown.style.display = 'none';
    }
    document.getElementById('btnClearSupplier').classList.remove('d-none');
    
    document.getElementById('supplierSelectedBadge').innerHTML = `
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill text-xs">
            <x-icon name="building" class="w-4 h-4 me-1" /> ${escapeHtml(name)} (Supplier Terdaftar)
        </span>
    `;
}

function selectNewSupplierFromSearch(name) {
    document.getElementById('supplierIdInput').value = 'NEW';
    document.getElementById('newSupplierNameInput').value = name;
    document.getElementById('supplierSearchInput').value = name;
    
    const dropdown = document.getElementById('supplierSearchResults');
    if (dropdown) {
        dropdown.classList.remove('show');
        dropdown.style.display = 'none';
    }
    document.getElementById('btnClearSupplier').classList.remove('d-none');
    
    document.getElementById('supplierSelectedBadge').innerHTML = `
        <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill text-xs">
            <x-icon name="plus" class="w-4 h-4 me-1" /> Baru: ${escapeHtml(name)} (Didaftarkan saat simpan)
        </span>
    `;
}

function clearSupplierSelection() {
    document.getElementById('supplierIdInput').value = '';
    document.getElementById('newSupplierNameInput').value = '';
    document.getElementById('supplierSearchInput').value = '';
    
    const dropdown = document.getElementById('supplierSearchResults');
    if (dropdown) {
        dropdown.classList.remove('show');
        dropdown.style.display = 'none';
    }
    document.getElementById('btnClearSupplier').classList.add('d-none');
    document.getElementById('supplierSelectedBadge').innerHTML = '<span class="text-xs text-muted"><x-icon name="info-circle" class="w-4 h-4 me-1" />Ketik nama supplier untuk mencari/membuat baru.</span>';
}

// PO Combobox Logic
function renderPODropdown(query = '') {
    const dropdown = document.getElementById('poSearchResults');
    if (!dropdown) return;
    const q = (query || '').trim().toLowerCase();

    let matches = rawPurchaseOrdersData.filter(po => {
        const poNum = (po.po_number || '').toLowerCase();
        const suppName = po.supplier ? ((po.supplier.name || '') + ' ' + (po.supplier.company_name || '')).toLowerCase() : '';
        const status = (po.status || '').toLowerCase();
        return poNum.includes(q) || suppName.includes(q) || status.includes(q);
    });

    let html = '';

    if (q.length > 0) {
        html += `
            <div class="dropdown-item py-2 px-3 text-indigo-700 font-semibold border-bottom cursor-pointer rounded-2 bg-indigo-50/80 mb-1" onclick="selectManualPORef('${escapeHtml(query)}')">
                <x-icon name="file-signature" class="w-4 h-4 me-1.5 text-indigo" /> Gunakan Ref/Surat Jalan Manual: <strong class="text-indigo-900">"${escapeHtml(query)}"</strong>
            </div>
        `;
    } else {
        html += `
            <div class="dropdown-item py-2 px-3 text-muted font-normal border-bottom cursor-pointer rounded-2 mb-1" onclick="clearPOSelection()">
                <x-icon name="minus-circle" class="w-4 h-4 me-1.5" /> -- Tanpa PO (Input Manual) --
            </div>
        `;
    }

    if (matches.length > 0) {
        matches.forEach(po => {
            const suppName = po.supplier ? (po.supplier.name || po.supplier.company_name) : 'Supplier';
            html += `
                <div class="dropdown-item py-2.5 px-3 cursor-pointer rounded-2 mb-0.5 d-flex justify-content-between align-items-center hover:bg-slate-100" onclick="selectPOItem(${po.id})">
                    <div>
                        <div class="fw-semibold text-gray-900 text-xs d-flex align-items-center gap-1.5">
                            <x-icon name="file-contract" class="w-4 h-4 text-primary small" />
                            <span>${escapeHtml(po.po_number)}</span>
                        </div>
                        <div class="text-muted text-xs ms-3.5" style="font-size: 11px;">Supplier: ${escapeHtml(suppName)}</div>
                    </div>
                    <span class="badge bg-primary-subtle text-primary text-xs">${escapeHtml(po.status)}</span>
                </div>
            `;
        });
    } else if (q.length > 0) {
        html += `<div class="px-3 py-2 text-muted text-xs">Tidak ada PO terdaftar yang cocok. Klik opsi di atas untuk menjadikannya Ref/Surat Jalan Manual.</div>`;
    }

    dropdown.innerHTML = html;
    dropdown.classList.add('show');
    dropdown.style.setProperty('display', 'block', 'important');
    dropdown.style.setProperty('z-index', '99999', 'important');
}

function selectPOItem(poId) {
    const po = rawPurchaseOrdersData.find(p => p.id == poId);
    if (!po) return;
    
    document.getElementById('poSelect').value = po.id;
    document.getElementById('manualReference').value = '';
    document.getElementById('poSearchInput').value = po.po_number;
    
    const dropdown = document.getElementById('poSearchResults');
    if (dropdown) {
        dropdown.classList.remove('show');
        dropdown.style.display = 'none';
    }
    document.getElementById('btnClearPO').classList.remove('d-none');
    
    const suppName = po.supplier ? (po.supplier.name || po.supplier.company_name) : 'Supplier';
    document.getElementById('poSelectedBadge').innerHTML = `
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill text-xs">
            <x-icon name="file-contract" class="w-4 h-4 me-1" /> ${escapeHtml(po.po_number)} (${escapeHtml(suppName)})
        </span>
    `;

    // Auto select supplier if available in PO
    if (po.supplier_id) {
        selectExistingSupplier(po.supplier_id, suppName);
    }
    
    // Fill items from PO
    loadPODataFromObj(po);
}

function selectManualPORef(refText) {
    document.getElementById('poSelect').value = '';
    document.getElementById('manualReference').value = refText;
    document.getElementById('poSearchInput').value = refText;
    
    const dropdown = document.getElementById('poSearchResults');
    if (dropdown) {
        dropdown.classList.remove('show');
        dropdown.style.display = 'none';
    }
    document.getElementById('btnClearPO').classList.remove('d-none');

    document.getElementById('poSelectedBadge').innerHTML = `
        <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 rounded-pill text-xs">
            <x-icon name="file-invoice" class="w-4 h-4 me-1" /> Ref Manual: ${escapeHtml(refText)}
        </span>
    `;
}

function clearPOSelection() {
    document.getElementById('poSelect').value = '';
    document.getElementById('manualReference').value = '';
    document.getElementById('poSearchInput').value = '';
    
    const dropdown = document.getElementById('poSearchResults');
    if (dropdown) {
        dropdown.classList.remove('show');
        dropdown.style.display = 'none';
    }
    document.getElementById('btnClearPO').classList.add('d-none');
    document.getElementById('poSelectedBadge').innerHTML = '<span class="text-xs text-muted">Tanpa PO (Penerimaan Manual)</span>';
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    const suppWrapper = document.getElementById('supplierComboboxWrapper');
    if (suppWrapper && !suppWrapper.contains(e.target)) {
        const suppResults = document.getElementById('supplierSearchResults');
        if (suppResults) {
            suppResults.classList.remove('show');
            suppResults.style.display = 'none';
        }
    }

    const poWrapper = document.getElementById('poComboboxWrapper');
    if (poWrapper && !poWrapper.contains(e.target)) {
        const poResults = document.getElementById('poSearchResults');
        if (poResults) {
            poResults.classList.remove('show');
            poResults.style.display = 'none';
        }
    }
});

// Handle Form Submit via AJAX & SweetAlert2
document.getElementById('incomingGoodsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    let supplierId = document.getElementById('supplierIdInput') ? document.getElementById('supplierIdInput').value : '';
    let newSupplierName = document.getElementById('newSupplierNameInput') ? document.getElementById('newSupplierNameInput').value.trim() : '';
    const typedSupplierSearch = document.getElementById('supplierSearchInput') ? document.getElementById('supplierSearchInput').value.trim() : '';

    // Auto detect typed supplier name if not explicitly selected from dropdown
    if (!supplierId && typedSupplierSearch) {
        const match = rawSuppliersData.find(s => 
            (s.name || '').toLowerCase() === typedSupplierSearch.toLowerCase() ||
            (s.company_name || '').toLowerCase() === typedSupplierSearch.toLowerCase()
        );

        if (match) {
            supplierId = match.id;
        } else {
            supplierId = 'NEW';
            newSupplierName = typedSupplierSearch;
        }
    }

    if (!supplierId && !newSupplierName) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'warning', title: 'Supplier Belum Dipilih', text: 'Silakan ketik nama supplier pada kolom pencarian.', confirmButtonColor: '#4f46e5' });
        } else {
            alert('Silakan pilih atau ketik supplier penerimaan barang.');
        }
        return;
    }

    const data = {
        receipt_number: formData.get('receipt_number'),
        receive_date: formData.get('receive_date'),
        supplier_id: supplierId || 'NEW',
        new_supplier_name: newSupplierName,
        manual_reference: document.getElementById('manualReference') ? document.getElementById('manualReference').value : '',
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
    submitBtn.innerHTML = '<x-icon name="spinner" class="w-4 h-4 animate-spin me-1.5" /> Menyimpan...';
    
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

// Quick Add Supplier AJAX
document.getElementById('quickSupplierForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSaveQuickSupplier');
    const origHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<x-icon name="spinner" class="w-4 h-4 animate-spin me-1" /> Menyimpan...';

    const countryCode = document.getElementById('quickSupplierCountryCode')?.value || '+62';
    const dialDigits = countryCode.replace(/\D/g, '');
    let supplierPhoneDigits = (document.getElementById('quickSupplierPhone').value || '').replace(/\D/g, '');
    if (supplierPhoneDigits.startsWith(dialDigits)) supplierPhoneDigits = supplierPhoneDigits.substring(dialDigits.length);
    if (supplierPhoneDigits.startsWith('0')) supplierPhoneDigits = supplierPhoneDigits.substring(1);
    const supplierPhone = supplierPhoneDigits ? countryCode + supplierPhoneDigits : '';

    const data = {
        name: document.getElementById('quickSupplierName').value,
        company_name: document.getElementById('quickSupplierCompany').value,
        phone: supplierPhone,
        email: document.getElementById('quickSupplierEmail').value,
        address: document.getElementById('quickSupplierAddress').value,
    };

    fetch('{{ route("suppliers.quickStore") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = origHtml;
        if (res.success && res.supplier) {
            // Update rawSuppliersData array and select it!
            rawSuppliersData.unshift(res.supplier);
            selectExistingSupplier(res.supplier.id, res.supplier.name || res.supplier.company_name);

            // Hide modal & reset form
            const modalEl = document.getElementById('quickAddSupplierModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            } else {
                const closeBtn = modalEl.querySelector('[data-bs-dismiss="modal"]');
                if (closeBtn) closeBtn.click();
            }
            document.getElementById('quickSupplierForm').reset();

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Supplier Ditambahkan',
                    text: `Supplier "${res.supplier.name}" berhasil ditambahkan dan dipilih.`,
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Gagal menyimpan supplier.' });
            } else {
                alert(res.message || 'Gagal menyimpan supplier.');
            }
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = origHtml;
        console.error(err);
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan', text: 'Gagal mengontak server.' });
        } else {
            alert('Terjadi kesalahan jaringan.');
        }
    });
});
</script>

<!-- Quick Add Supplier Modal -->
<div class="modal fade" id="quickAddSupplierModal" tabindex="-1" aria-labelledby="quickAddSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-squircle overflow-hidden">
            <div class="modal-header bg-primary text-white py-3 px-4">
                <h6 class="modal-title font-bold d-flex align-items-center gap-2" id="quickAddSupplierModalLabel">
                    <x-icon name="truck-field" class="w-4 h-4" /> Tambah Detail Supplier Baru
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickSupplierForm">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label font-semibold text-xs text-gray-700">Nama Supplier / Kontak <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="name" id="quickSupplierName" required placeholder="Contoh: PT Penerbit Harapan / Budi Santoso">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-semibold text-xs text-gray-700">Nama Perusahaan / Toko</label>
                        <input type="text" class="form-control form-control-sm" name="company_name" id="quickSupplierCompany" placeholder="Contoh: CV Media Kita (Kosongkan jika sama)">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label font-semibold text-xs text-gray-700">No. HP / Telepon</label>
                            <div class="input-group input-group-sm">
                                <select class="form-select form-select-sm country-code-select bg-light text-secondary fw-semibold border-end-0" id="quickSupplierCountryCode" style="max-width: 110px;" title="Pilih Kode Negara">
                                    <option value="+62" selected>🇮🇩 +62</option>
                                    <option value="+60">🇲🇾 +60</option>
                                    <option value="+65">🇸🇬 +65</option>
                                    <option value="+63">🇵🇭 +63</option>
                                    <option value="+66">🇹🇭 +66</option>
                                    <option value="+84">🇻🇳 +84</option>
                                    <option value="+1">🇺🇸 +1</option>
                                    <option value="+44">🇬🇧 +44</option>
                                    <option value="+61">🇦🇺 +61</option>
                                    <option value="+81">🇯🇵 +81</option>
                                    <option value="+82">🇰🇷 +82</option>
                                    <option value="+86">🇨🇳 +86</option>
                                    <option value="+966">🇸🇦 +966</option>
                                    <option value="+971">🇦🇪 +971</option>
                                    <option value="+49">🇩🇪 +49</option>
                                    <option value="+31">🇳🇱 +31</option>
                                </select>
                                <input type="tel" 
                                       class="form-control form-control-sm phone-number-input" 
                                       name="phone" 
                                       id="quickSupplierPhone" 
                                       placeholder="81234567890"
                                       inputmode="numeric" 
                                       pattern="[0-9]*" 
                                       maxlength="15">
                            </div>
                            <small class="text-muted" style="font-size: 11px;">Hanya angka tanpa awalan 0</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-semibold text-xs text-gray-700">Email</label>
                            <input type="email" class="form-control form-control-sm" name="email" id="quickSupplierEmail" placeholder="supplier@example.com">
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label font-semibold text-xs text-gray-700">Alamat Lengkap</label>
                        <textarea class="form-control form-control-sm" name="address" id="quickSupplierAddress" rows="2" placeholder="Jl. Merdeka No. 123..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-slate-50 py-2.5 px-4 border-top">
                    <button type="button" class="btn btn-light btn-sm px-3 border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4" id="btnSaveQuickSupplier">
                        <x-icon name="check" class="w-4 h-4 me-1" /> Simpan Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

