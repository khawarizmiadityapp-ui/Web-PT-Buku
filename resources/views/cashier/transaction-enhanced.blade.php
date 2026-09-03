@extends('layouts.app')

@section('title', 'New Transaction - StatiSync')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row g-3">
        <!-- Left Side - Product Selection -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Select Products</h5>
                        <button class="btn btn-sm btn-outline-secondary" onclick="window.location.href='{{ route('cashier.index') }}'">
                            <i class="fas fa-times"></i> Close
                        </button>
                    </div>
                    
                    <!-- Search Bar & Barcode Scanner -->
                    <div class="mb-3 position-relative">
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class="fas fa-barcode fa-lg"></i></span>
                            <input type="text" class="form-control border-start-0 ps-1 font-monospace" id="productSearch" 
                                   placeholder="Scan barcode barang atau ketik SKU / nama... (Tekan Enter)" autocomplete="off" autofocus>
                            <button class="btn btn-outline-primary d-flex align-items-center gap-1.5" type="button" onclick="openCameraScanner()" title="Scan Menggunakan Kamera HP / Webcam">
                                <i class="fas fa-camera"></i> <span class="d-none d-sm-inline small font-semibold">Scan Kamera</span>
                            </button>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-1.5 px-1">
                            <span class="text-muted" style="font-size: 11px;">
                                <i class="fas fa-bolt text-warning me-1"></i> <strong>Scanner Otomatis:</strong> Tembak barcode, barang langsung masuk ke keranjang
                            </span>
                            <span id="scanStatusBadge" class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-normal" style="font-size: 10px;">
                                <i class="fas fa-check-circle me-1"></i>Scanner Siap
                            </span>
                        </div>
                    </div>

                    <!-- Category Tabs -->
                    <div class="mb-3">
                        <div class="d-flex gap-1 overflow-auto pb-1" role="group">
                            <button type="button" class="btn btn-sm btn-primary category-btn active flex-shrink-0" data-category="all" onclick="filterCategory('all', event)">
                                All Items
                            </button>
                            @if(isset($categories) && count($categories) > 0)
                                @foreach($categories as $cat)
                                <button type="button" class="btn btn-sm btn-outline-primary category-btn flex-shrink-0" data-category="{{ $cat }}" onclick="filterCategory('{{ $cat }}', event)">
                                    {{ $cat }}
                                </button>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Product Grid -->
                    <div class="row g-2" id="productGrid" style="max-height: 450px; overflow-y: auto;">
                        @foreach($products as $product)
                        <div class="col-md-3 col-sm-4 col-6 product-item" data-category="{{ $product->category }}">
                            <div class="card product-card h-100" style="cursor: pointer;" 
                                 onclick="addToCart({{ $product->id }}, '{{ addslashes($product->product_name) }}', {{ $product->price }}, {{ $product->system_stock }}, '{{ $product->product_code }}', '{{ $product->unit ?? 'Pcs' }}')">
                                <div class="card-body p-2 text-center">
                                    <div class="product-image mb-2" style="height: 60px; background: #f0f0f0; border-radius: 5px;">
                                        <i class="fas fa-box fa-2x text-muted" style="line-height: 60px;"></i>
                                    </div>
                                    <h6 class="mb-1 small" style="font-size: 11px;">{{ Str::limit($product->product_name, 25) }}</h6>
                                    <small class="text-muted d-block" style="font-size: 9px;">{{ $product->product_code }}</small>
                                    <div class="mt-1">
                                        <strong class="text-primary d-block">Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                                        <small class="text-muted" style="font-size: 9px;">Stock: {{ $product->system_stock }} {{ $product->unit ?? 'pcs' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Cart & Payment -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Cart</h5>
                        <button class="btn btn-sm btn-outline-danger" onclick="clearCart()">
                            <i class="fas fa-trash me-1"></i> Clear Cart
                        </button>
                    </div>

                    <!-- Customer Selection with Live Autocomplete Search -->
                    <div class="mb-3 position-relative" id="customerSearchContainer">
                        <label class="form-label small font-semibold mb-1">Customer</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white text-muted"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control form-control-sm" id="customerSearchInput" 
                                   placeholder="Cari atau ketik nama customer..." 
                                   value="Walk-in Customer" autocomplete="off" 
                                   onfocus="showCustomerDropdown()" oninput="filterCustomerDropdown()">
                            <input type="hidden" id="customerId" name="customer_id" value="">
                            <input type="hidden" id="customerName" name="customer_name" value="Walk-in Customer">
                            <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addCustomerModal" title="Tambah Customer Baru">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <!-- Live Autocomplete Results -->
                        <div id="customerSearchResults" class="list-group position-absolute w-100 shadow-lg mt-1" 
                             style="z-index: 1050; max-height: 220px; overflow-y: auto; display: none; background: white; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
                        </div>
                    </div>

                    <!-- Cart Items -->
                    <div class="mb-3" style="max-height: 200px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 5px; padding: 10px; background: #f8f9fa;">
                        <div id="cartItems">
                            <div class="text-center py-4 px-2">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2 shadow-sm" 
                                     style="width: 64px; height: 64px; background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); color: #4f46e5;">
                                    <i class="fas fa-shopping-basket fa-2x"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Keranjang Masih Kosong</h6>
                                <p class="text-muted small mb-2" style="font-size: 11px; max-width: 220px; margin: 0 auto;">Pilih produk di sebelah kiri untuk menambahkan ke daftar belanja</p>
                                <div class="d-inline-flex align-items-center gap-1 px-3 py-1 rounded-pill bg-white border shadow-sm text-secondary" style="font-size: 10px;">
                                    <i class="fas fa-hand-pointer text-primary me-1"></i>
                                    <span>Klik atau cari produk</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Totals Summary -->
                    <div class="bg-light p-2 rounded mb-3">
                        <div class="d-flex justify-content-between mb-1 small">
                            <span>Subtotal:</span>
                            <strong id="subtotalDisplay">Rp 0</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1 small text-success">
                            <span>Discount:</span>
                            <strong id="discountDisplay">- Rp 0</strong>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0">Grand Total:</h5>
                            <h5 class="mb-0 text-primary" id="totalDisplay">Rp 0</h5>
                        </div>
                    </div>

                    <!-- Discount Input -->
                    <div class="mb-3">
                        <label class="form-label small">Discount</label>
                        <input type="number" class="form-control form-control-sm" id="discountAmount" 
                               value="0" min="0" onchange="calculateTotal()" placeholder="Enter discount">
                    </div>

                    <!-- Payment Methods -->
                    <div class="mb-3">
                        <label class="form-label small">Payment Method</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-primary w-100 payment-method-btn active" 
                                        onclick="selectPaymentMethod('Cash', event)" data-method="Cash">
                                    <i class="fas fa-money-bill-wave fa-2x d-block mb-1"></i>
                                    <small>Cash</small>
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-primary w-100 payment-method-btn" 
                                        onclick="selectPaymentMethod('QRIS', event)" data-method="QRIS">
                                    <i class="fas fa-qrcode fa-2x d-block mb-1"></i>
                                    <small>QRIS</small>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" id="paymentMethod" value="Cash">
                    </div>

                    <!-- Amount Input with Quick Cash Buttons & Live Kembalian -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label small font-semibold mb-0">Bayar (Received)</label>
                            <span class="text-muted small" style="font-size: 11px;">Total Tagihan: <strong class="text-dark" id="totalAmount2">Rp 0</strong></span>
                        </div>
                        
                        <!-- Quick Nominal Buttons -->
                        <div class="d-flex gap-1 mb-2">
                            <button type="button" class="btn btn-xs btn-outline-secondary flex-fill py-1 fw-medium" style="font-size: 11px;" onclick="setQuickPaid('exact')">
                                <i class="fas fa-coins text-primary me-1"></i>Uang Pas
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-secondary flex-fill py-1 fw-medium" style="font-size: 11px;" onclick="setQuickPaid(50000)">
                                50rb
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-secondary flex-fill py-1 fw-medium" style="font-size: 11px;" onclick="setQuickPaid(100000)">
                                100rb
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-secondary flex-fill py-1 fw-medium" style="font-size: 11px;" onclick="setQuickPaid(200000)">
                                200rb
                            </button>
                        </div>

                        <input type="text" class="form-control form-control-lg text-center fw-bold border-primary shadow-sm" id="paidAmount" 
                               value="0" oninput="formatPaidAmountInput(this)" onkeydown="handlePaidAmountKeyDown(event)" style="font-size: 24px; background: white;">

                        <!-- Live Kembalian Card -->
                        <div id="changeDisplayCard" class="card border-0 mt-2 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0 !important;">
                            <div class="card-body p-2.5 d-flex justify-content-between align-items-center">
                                <span class="fw-semibold small text-secondary" id="changeLabel">Kembalian:</span>
                                <strong class="fs-5 text-secondary" id="changeAmount">Rp 0</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary btn-lg" onclick="processTransaction()">
                            <i class="fas fa-check me-2"></i> Process Payment
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="holdTransaction()">
                            <i class="fas fa-pause me-2"></i> Simpan Draft
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Name *</label>
                    <input type="text" class="form-control" id="newCustomerName" required>
                </div>
                <div class="mb-3">
                    <label class="form-label font-semibold text-xs text-gray-700">No. Telepon / WhatsApp <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select class="form-select country-code-select bg-light text-secondary fw-semibold border-end-0" id="newCustomerCountryCode" style="max-width: 115px;" title="Pilih Kode Negara">
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
                               class="form-control phone-number-input" 
                               id="newCustomerPhone" 
                               placeholder="81234567890" 
                               required
                               inputmode="numeric" 
                               pattern="[0-9]*" 
                               maxlength="15">
                    </div>
                    <small class="text-muted" style="font-size: 11px;">Pilih negara & ketik nomor tanpa awalan 0</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveNewCustomer()">Save Customer</button>
            </div>
        </div>
    </div>
</div>

<!-- Success Payment Modal -->
<div class="modal fade" id="successModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <!-- Success Checkmark Animation -->
                <div class="mb-4">
                    <div class="success-checkmark">
                        <div class="check-icon">
                            <span class="icon-line line-tip"></span>
                            <span class="icon-line line-long"></span>
                            <div class="icon-circle"></div>
                            <div class="icon-fix"></div>
                        </div>
                    </div>
                </div>

                <h3 class="mb-2">Pembayaran Berhasil!</h3>
                <p class="text-muted mb-4">Transaksi telah diproses dengan sukses.</p>

                <!-- Transaction Details -->
                <div class="bg-light p-4 rounded mb-4 text-start">
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Nomor Invoice</div>
                        <div class="col-6 text-end"><strong id="successInvoiceNumber"></strong></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Customer</div>
                        <div class="col-6 text-end" id="successCustomer"></div>
                    </div>
                    <hr class="my-2">
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Total Tagihan</div>
                        <div class="col-6 text-end"><strong id="successTotalTagihan"></strong></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Jumlah Bayar</div>
                        <div class="col-6 text-end" id="successJumlahBayar"></div>
                    </div>
                    <div class="row">
                        <div class="col-6 text-muted">Kembalian</div>
                        <div class="col-6 text-end text-primary"><strong id="successKembalian"></strong></div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary btn-lg" onclick="printReceiptFromModal()">
                        <i class="fas fa-print me-2"></i> Cetak Struk
                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="newTransaction()">
                        <i class="fas fa-shopping-cart me-2"></i> Transaksi Baru
                    </button>
                </div>

                <p class="text-muted small mt-3 mb-0">
                    StatiSync Distributor Cloud POS • v2.0
                </p>
            </div>
        </div>
    </div>
</div>

<style>
.product-card {
    transition: all 0.2s;
}
.product-card:hover {
    background-color: #e7f3ff;
    border-color: #0d6efd;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.payment-method-btn {
    padding: 15px 5px;
    transition: all 0.2s;
}
.payment-method-btn.active {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}
.payment-method-btn:hover {
    transform: scale(1.05);
}
.numpad-btn {
    padding: 15px;
    font-size: 18px;
    font-weight: bold;
}
.category-btn.active {
    background-color: #0d6efd;
    color: white;
}

/* Success Checkmark Animation */
.success-checkmark {
    width: 80px;
    height: 80px;
    margin: 0 auto;
}

.success-checkmark .check-icon {
    width: 80px;
    height: 80px;
    position: relative;
    border-radius: 50%;
    box-sizing: content-box;
    border: 4px solid #4CAF50;
}

.success-checkmark .check-icon .icon-line {
    height: 5px;
    background-color: #4CAF50;
    display: block;
    border-radius: 2px;
    position: absolute;
    z-index: 10;
}

.success-checkmark .check-icon .icon-line.line-tip {
    top: 46px;
    left: 14px;
    width: 25px;
    transform: rotate(45deg);
    animation: icon-line-tip 0.75s;
}

.success-checkmark .check-icon .icon-line.line-long {
    top: 38px;
    right: 8px;
    width: 47px;
    transform: rotate(-45deg);
    animation: icon-line-long 0.75s;
}

.success-checkmark .check-icon .icon-circle {
    top: -4px;
    left: -4px;
    z-index: 10;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    position: absolute;
    box-sizing: content-box;
    border: 4px solid rgba(76, 175, 80, .5);
}

@keyframes icon-line-tip {
    0% { width: 0; left: 1px; top: 19px; }
    54% { width: 0; left: 1px; top: 19px; }
    70% { width: 50px; left: -8px; top: 37px; }
    84% { width: 17px; left: 21px; top: 48px; }
    100% { width: 25px; left: 14px; top: 45px; }
}

@keyframes icon-line-long {
    0% { width: 0; right: 46px; top: 54px; }
    65% { width: 0; right: 46px; top: 54px; }
    84% { width: 55px; right: 0px; top: 35px; }
    100% { width: 47px; right: 8px; top: 38px; }
}
</style>

<script>
let cart = [];
let subtotal = 0;
let currentCategory = 'all';

// Add product to cart
function addToCart(productId, productName, price, stock, productCode, unit) {
    const existingItem = cart.find(item => item.productId === productId);
    const baseUnit = unit || 'Pcs';
    
    if (existingItem) {
        const mult = existingItem.multiplier || 1;
        if ((existingItem.quantity + 1) * mult <= stock) {
            existingItem.quantity++;
            existingItem.subtotal = existingItem.quantity * (price * mult);
        } else {
            alert(`Stock tidak cukup! Stok tersedia: ${stock} ${baseUnit}`);
            return;
        }
    } else {
        cart.push({
            productId: productId,
            productName: productName,
            productCode: productCode,
            baseUnit: baseUnit,
            multiplier: 1,
            price: price,
            effectivePrice: price,
            quantity: 1,
            stock: stock,
            subtotal: price
        });
    }
    
    renderCart();
    calculateTotal();
}

// Update cart item unit (Pcs, Pack, Lusin, Box, Dus)
function updateCartUnit(index, multiplierStr) {
    const mult = parseInt(multiplierStr, 10);
    const item = cart[index];
    
    if (item.quantity * mult > item.stock) {
        alert(`Stok dasar (${item.stock} ${item.baseUnit || 'pcs'}) tidak mencukupi untuk ${item.quantity} unit yang dipilih!`);
        renderCart();
        return;
    }
    
    item.multiplier = mult;
    item.effectivePrice = item.price * mult;
    item.subtotal = item.quantity * item.effectivePrice;
    
    renderCart();
    calculateTotal();
}

// Render cart
function renderCart() {
    const cartItemsDiv = document.getElementById('cartItems');
    
    if (cart.length === 0) {
        cartItemsDiv.innerHTML = `
            <div class="text-center py-4 px-2">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2 shadow-sm" 
                     style="width: 64px; height: 64px; background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); color: #4f46e5;">
                    <i class="fas fa-shopping-basket fa-2x"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Keranjang Masih Kosong</h6>
                <p class="text-muted small mb-2" style="font-size: 11px; max-width: 220px; margin: 0 auto;">Pilih produk di sebelah kiri untuk menambahkan ke daftar belanja</p>
                <div class="d-inline-flex align-items-center gap-1 px-3 py-1 rounded-pill bg-white border shadow-sm text-secondary" style="font-size: 10px;">
                    <i class="fas fa-hand-pointer text-primary me-1"></i>
                    <span>Klik atau cari produk</span>
                </div>
            </div>
        `;
        return;
    }
    
    let html = '';
    cart.forEach((item, index) => {
        const mult = item.multiplier || 1;
        const effPrice = item.effectivePrice || item.price;
        
        html += `
            <div class="card border-0 shadow-sm mb-2.5 rounded-3 position-relative" style="background: #ffffff; border: 1px solid #e2e8f0 !important;">
                <div class="card-body p-2.5">
                    <!-- Top Row: Product Name & Subtotal -->
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                        <div class="flex-grow-1">
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 13px; line-height: 1.3;">${escapeHtml(item.productName)}</h6>
                            <span class="badge bg-light text-secondary border fw-normal" style="font-size: 9px; padding: 2px 6px;">${escapeHtml(item.productCode)}</span>
                        </div>
                        <div class="text-end flex-shrink-0">
                            <div class="fw-bold text-primary" style="font-size: 14px;">Rp ${formatNumber(item.subtotal)}</div>
                        </div>
                    </div>

                    <!-- Bottom Row: Satuan & Unit Price + Modern Stepper Controls -->
                    <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-light">
                        <!-- Unit & Price -->
                        <div class="d-flex align-items-center gap-1.5">
                            <div class="d-inline-flex align-items-center bg-light rounded-pill px-2 py-0.5 border" style="font-size: 10px;">
                                <span class="text-muted me-1" style="font-size: 9px;">Satuan:</span>
                                <select class="form-select form-select-sm border-0 bg-transparent p-0 fw-semibold text-primary" 
                                        style="font-size: 10px; width: auto; cursor: pointer; outline: none; box-shadow: none; display: inline-block;" 
                                        onchange="updateCartUnit(${index}, this.value)">
                                    <option value="1" ${mult === 1 ? 'selected' : ''}>Pcs (1x)</option>
                                    <option value="10" ${mult === 10 ? 'selected' : ''}>Pack (10x)</option>
                                    <option value="12" ${mult === 12 ? 'selected' : ''}>Lusin (12x)</option>
                                    <option value="24" ${mult === 24 ? 'selected' : ''}>Box (24x)</option>
                                    <option value="40" ${mult === 40 ? 'selected' : ''}>Dus (40x)</option>
                                </select>
                            </div>
                            <small class="text-muted ms-1" style="font-size: 10px;">@ Rp ${formatNumber(effPrice)}</small>
                        </div>

                        <!-- Stepper & Delete Button -->
                        <div class="d-flex align-items-center gap-1.5">
                            <div class="d-inline-flex align-items-center bg-light rounded-pill p-1 border">
                                <button type="button" class="btn btn-sm btn-white rounded-circle shadow-none p-0 d-flex align-items-center justify-content-center text-dark" 
                                        style="width: 22px; height: 22px; background: white; border: 1px solid #cbd5e1;" 
                                        onclick="updateQuantity(${index}, ${item.quantity - 1})">
                                    <i class="fas fa-minus" style="font-size: 8px;"></i>
                                </button>
                                <span class="fw-bold px-2 text-dark" style="font-size: 12px; min-width: 20px; text-align: center;">${item.quantity}</span>
                                <button type="button" class="btn btn-sm btn-white rounded-circle shadow-none p-0 d-flex align-items-center justify-content-center text-dark" 
                                        style="width: 22px; height: 22px; background: white; border: 1px solid #cbd5e1;" 
                                        onclick="updateQuantity(${index}, ${item.quantity + 1})">
                                    <i class="fas fa-plus" style="font-size: 8px;"></i>
                                </button>
                            </div>
                            <button type="button" class="btn btn-sm text-danger bg-danger bg-opacity-10 rounded-circle p-0 d-flex align-items-center justify-content-center border-0" 
                                    style="width: 26px; height: 26px;" 
                                    title="Hapus item" 
                                    onclick="removeFromCart(${index})">
                                <i class="fas fa-trash-alt" style="font-size: 10px;"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    cartItemsDiv.innerHTML = html;
}

// Remove from cart
function removeFromCart(index) {
    cart.splice(index, 1);
    renderCart();
    calculateTotal();
}

// Update quantity
function updateQuantity(index, newQty) {
    newQty = parseInt(newQty, 10);
    if (isNaN(newQty) || newQty <= 0) {
        removeFromCart(index);
        return;
    }
    
    const item = cart[index];
    const mult = item.multiplier || 1;
    if (newQty * mult > item.stock) {
        alert(`Stock tidak cukup! Stok tersedia: ${item.stock} ${item.baseUnit || 'pcs'}`);
        return;
    }
    
    item.quantity = Math.max(1, newQty);
    item.subtotal = item.quantity * (item.price * mult);
    renderCart();
    calculateTotal();
}

// Calculate totals
function calculateTotal() {
    subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
    const discount = Math.max(0, parseFloat(document.getElementById('discountAmount').value) || 0);
    const tax = 0;
    const total = Math.max(0, subtotal - discount);
    
    document.getElementById('subtotalDisplay').textContent = 'Rp ' + formatNumber(subtotal);
    if (document.getElementById('taxDisplay')) {
        document.getElementById('taxDisplay').textContent = 'Rp 0';
    }
    document.getElementById('discountDisplay').textContent = '- Rp ' + formatNumber(discount);
    document.getElementById('totalDisplay').textContent = 'Rp ' + formatNumber(total);
    document.getElementById('totalAmount2').textContent = 'Rp ' + formatNumber(total);
    
    calculateChange();
}

// Quick Paid presets
function setQuickPaid(amount) {
    const discount = parseFloat(document.getElementById('discountAmount').value) || 0;
    const total = Math.max(0, subtotal - discount);
    
    let paidVal = 0;
    if (amount === 'exact') {
        paidVal = total;
    } else {
        paidVal = parseFloat(amount);
    }
    
    document.getElementById('paidAmount').value = formatNumber(paidVal);
    calculateChange();
}

// Calculate change
function calculateChange() {
    const discount = parseFloat(document.getElementById('discountAmount').value) || 0;
    const total = Math.max(0, subtotal - discount);
    const rawPaid = document.getElementById('paidAmount').value.replace(/\./g, '');
    const paid = parseFloat(rawPaid) || 0;
    const diff = paid - total;
    
    const changeCard = document.getElementById('changeDisplayCard');
    const changeLabel = document.getElementById('changeLabel');
    const changeAmount = document.getElementById('changeAmount');
    
    if (!changeCard) return;

    if (paid === 0) {
        changeCard.style.background = '#f8fafc';
        changeCard.style.borderColor = '#e2e8f0';
        changeLabel.textContent = 'Kembalian:';
        changeLabel.className = 'fw-semibold small text-secondary';
        changeAmount.textContent = 'Rp 0';
        changeAmount.className = 'fs-5 text-secondary';
    } else if (diff >= 0) {
        changeCard.style.background = '#f0fdf4';
        changeCard.style.borderColor = '#bbf7d0';
        changeLabel.textContent = 'Kembalian:';
        changeLabel.className = 'fw-bold small text-success';
        changeAmount.textContent = 'Rp ' + formatNumber(diff);
        changeAmount.className = 'fs-5 fw-bold text-success';
    } else {
        changeCard.style.background = '#fffbeb';
        changeCard.style.borderColor = '#fef08a';
        changeLabel.textContent = 'Pembayaran Kurang:';
        changeLabel.className = 'fw-bold small text-warning';
        changeAmount.textContent = '- Rp ' + formatNumber(Math.abs(diff));
        changeAmount.className = 'fs-5 fw-bold text-warning';
    }
}

// Keyboard & Numpad functions
function formatPaidAmountInput(input) {
    let raw = input.value.replace(/[^0-9]/g, '');
    if (raw === '' || raw === '0') {
        input.value = '0';
    } else {
        input.value = formatNumber(parseInt(raw, 10));
    }
    calculateChange();
}

function handlePaidAmountKeyDown(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        processTransaction();
    }
}

function appendNumber(num) {
    let current = document.getElementById('paidAmount').value.replace(/\./g, '');
    if (current === '0') current = '';
    current += num;
    document.getElementById('paidAmount').value = formatNumber(parseInt(current, 10));
    calculateChange();
}

function clearAmount() {
    document.getElementById('paidAmount').value = '0';
    calculateChange();
}

// Select payment method
function selectPaymentMethod(method, evt) {
    document.getElementById('paymentMethod').value = method;
    document.querySelectorAll('.payment-method-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    const target = evt ? evt.currentTarget : document.querySelector(`.payment-method-btn[data-method="${method}"]`);
    if (target) {
        target.classList.add('active');
    }
    
    // If QRIS selected, auto fill total amount
    if (method === 'QRIS') {
        const discount = parseFloat(document.getElementById('discountAmount').value) || 0;
        const tax = 0;
        const total = subtotal - discount;
        if (total > 0) {
            document.getElementById('paidAmount').value = formatNumber(total);
            calculateChange();
        }
    }
}

// Filter category
function filterCategory(category, evt) {
    currentCategory = category;
    const products = document.querySelectorAll('.product-item');
    
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.classList.remove('active', 'btn-primary');
        btn.classList.add('btn-outline-primary');
    });
    
    const target = evt ? evt.currentTarget : document.querySelector(`.category-btn[data-category="${category}"]`);
    if (target) {
        target.classList.remove('btn-outline-primary');
        target.classList.add('active', 'btn-primary');
    }
    
    products.forEach(product => {
        if (category === 'all' || product.dataset.category === category) {
            product.style.display = '';
        } else {
            product.style.display = 'none';
        }
    });
}

// Process transaction
function processTransaction() {
    if (cart.length === 0) {
        alert('Cart masih kosong!');
        return;
    }
    
    const customerId = document.getElementById('customerId').value;
    const customerName = document.getElementById('customerName').value;
    const paymentMethod = document.getElementById('paymentMethod').value;
    const paidAmount = parseFloat(document.getElementById('paidAmount').value.replace(/\./g, '')) || 0;
    const discount = parseFloat(document.getElementById('discountAmount').value) || 0;
    const tax = 0;
    const total = subtotal - discount;
    
    if (!customerName) {
        alert('Masukkan nama customer!');
        return;
    }
    
    if (paidAmount <= 0) {
        alert('Masukkan jumlah pembayaran!');
        return;
    }
    
    if (paidAmount < total) {
        if (!confirm('Pembayaran kurang dari total. Lanjutkan sebagai partial payment?')) {
            return;
        }
    }
    
    const data = {
        customer_id: customerId || null,
        customer_name: customerName,
        items: cart.map(item => ({
            product_id: item.productId,
            quantity: item.quantity * (item.multiplier || 1),
            price: item.price
        })),
        discount_amount: discount,
        tax_amount: tax,
        payment_method: paymentMethod,
        paid_amount: paidAmount,
        notes: '',
        _token: '{{ csrf_token() }}'
    };
    
    fetch('{{ route("cashier.process") }}', {
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
            // Show success modal instead of alert
            showSuccessModal(result.invoice_number, customerName, total, paidAmount, result.change, result.invoice_id);
        } else {
            alert('Error: ' + result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses transaksi.');
    });
}

// Clear cart
function clearCart() {
    cart = [];
    subtotal = 0;
    renderCart();
    calculateTotal();
    document.getElementById('paidAmount').value = '0';
    document.getElementById('discountAmount').value = '0';
    document.getElementById('customerId').value = '';
    document.getElementById('customerName').value = 'Walk-in Customer';
}

// Hold transaction (save draft)
function holdTransaction() {
    if (cart.length === 0) {
        alert('Cart kosong!');
        return;
    }
    
    const draftName = prompt('Nama draft transaction:');
    if (draftName) {
        localStorage.setItem('draft_' + draftName, JSON.stringify(cart));
        alert('Draft tersimpan!');
    }
}

// Format number
function formatNumber(num) {
    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Full Products Catalog for Instant Barcode Matching
const allEnhancedProductsCatalog = [
    @foreach($products as $product)
    {
        id: {{ $product->id }},
        product_name: {!! json_encode($product->product_name) !!},
        product_code: {!! json_encode($product->product_code) !!},
        clean_code: {!! json_encode(preg_replace('/[^a-zA-Z0-9]/', '', $product->product_code)) !!},
        price: {{ (float) $product->price }},
        stock: {{ (int) $product->system_stock }},
        unit: {!! json_encode($product->unit ?? 'Pcs') !!}
    },
    @endforeach
];

// Audio Barcode Scanner Beep using Web Audio API (zero external files, instant)
function playBarcodeBeep(isSuccess = true) {
    try {
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.connect(gain);
        gain.connect(audioCtx.destination);
        
        if (isSuccess) {
            // High crisp positive beep (1600Hz for 80ms)
            osc.frequency.setValueAtTime(1600, audioCtx.currentTime);
            gain.gain.setValueAtTime(0.18, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.08);
            osc.start();
            osc.stop(audioCtx.currentTime + 0.08);
        } else {
            // Low error buzz (320Hz for 200ms)
            osc.frequency.setValueAtTime(320, audioCtx.currentTime);
            gain.gain.setValueAtTime(0.25, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.2);
            osc.start();
            osc.stop(audioCtx.currentTime + 0.2);
        }
    } catch(e) {
        console.warn('Audio Context error', e);
    }
}

// Toast notification for Barcode Scanner
function showScanToast(type, message) {
    let toast = document.getElementById('barcodeScanToast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'barcodeScanToast';
        toast.style.cssText = `
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            color: white;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            transform: translateY(-20px);
            opacity: 0;
            pointer-events: none;
        `;
        document.body.appendChild(toast);
    }
    
    if (type === 'success') {
        toast.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
        toast.innerHTML = `<i class="fas fa-check-circle me-2"></i> ${escapeHtml(message)}`;
    } else {
        toast.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
        toast.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i> ${escapeHtml(message)}`;
    }
    
    toast.style.transform = 'translateY(0)';
    toast.style.opacity = '1';
    
    clearTimeout(toast.hideTimeout);
    toast.hideTimeout = setTimeout(() => {
        toast.style.transform = 'translateY(-20px)';
        toast.style.opacity = '0';
    }, 2500);
}

// Core Barcode Scanning Handler
function handleBarcodeScan(scannedText) {
    if (!scannedText) return;
    scannedText = scannedText.trim();
    const cleanScan = scannedText.replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
    
    // Find matching product
    const matched = allEnhancedProductsCatalog.find(p => {
        const pCodeClean = (p.clean_code || '').toLowerCase();
        const pCodeRaw = (p.product_code || '').toLowerCase();
        const scanRaw = scannedText.toLowerCase();
        return pCodeClean === cleanScan || pCodeRaw === scanRaw || pCodeRaw === ('#' + scanRaw);
    });
    
    if (matched) {
        // Automatically add product to cart
        addToCart(matched.id, matched.product_name, matched.price, matched.stock, matched.product_code, matched.unit);
        
        // Play crisp cashier beep
        playBarcodeBeep(true);
        
        // Show success visual feedback
        showScanToast('success', `${matched.product_name} (${matched.product_code}) dimasukkan!`);
        
        // Flash status badge
        const badge = document.getElementById('scanStatusBadge');
        if (badge) {
            badge.className = 'badge bg-success text-white rounded-pill font-normal';
            badge.innerHTML = `<i class="fas fa-bolt me-1"></i>${matched.product_code} OK`;
            setTimeout(() => {
                badge.className = 'badge bg-success-subtle text-success border border-success-subtle rounded-pill font-normal';
                badge.innerHTML = `<i class="fas fa-check-circle me-1"></i>Scanner Siap`;
            }, 1200);
        }
        
        // Clear search input & refocus for next continuous scan
        const searchInput = document.getElementById('productSearch');
        if (searchInput) {
            searchInput.value = '';
            document.querySelectorAll('.product-item').forEach(item => item.style.display = '');
            searchInput.focus();
        }
    } else {
        // Error handling
        playBarcodeBeep(false);
        showScanToast('error', `Barcode "${scannedText}" tidak ditemukan di sistem!`);
        
        const badge = document.getElementById('scanStatusBadge');
        if (badge) {
            badge.className = 'badge bg-danger text-white rounded-pill font-normal';
            badge.innerHTML = `<i class="fas fa-times-circle me-1"></i>Tidak Ditemukan`;
            setTimeout(() => {
                badge.className = 'badge bg-success-subtle text-success border border-success-subtle rounded-pill font-normal';
                badge.innerHTML = `<i class="fas fa-check-circle me-1"></i>Scanner Siap`;
            }, 1500);
        }
    }
}

// Product search input listener (filtering)
document.getElementById('productSearch').addEventListener('input', function() {
    const search = this.value.toLowerCase().trim();
    const products = document.querySelectorAll('.product-item');
    
    products.forEach(product => {
        const text = product.textContent.toLowerCase();
        const matchCategory = currentCategory === 'all' || product.dataset.category === currentCategory;
        
        if (text.includes(search) && matchCategory) {
            product.style.display = '';
        } else {
            product.style.display = 'none';
        }
    });
});

// Barcode scanner trigger on Enter in search box
document.getElementById('productSearch').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const val = this.value.trim();
        if (val) {
            handleBarcodeScan(val);
        }
    }
});

// Global Hardware Barcode Scanner Auto-Detection
let barcodeGunBuffer = '';
let lastBarcodeKeyTime = Date.now();

window.addEventListener('keydown', function(e) {
    const activeEl = document.activeElement;
    const activeTag = activeEl ? activeEl.tagName.toLowerCase() : '';
    const activeId = activeEl ? activeEl.id : '';
    
    if (activeTag === 'textarea' || (activeTag === 'input' && activeId !== 'productSearch')) {
        return;
    }
    
    const now = Date.now();
    if (now - lastBarcodeKeyTime > 60) {
        barcodeGunBuffer = '';
    }
    lastBarcodeKeyTime = now;
    
    if (e.key === 'Enter') {
        if (barcodeGunBuffer.length >= 2) {
            e.preventDefault();
            handleBarcodeScan(barcodeGunBuffer);
            barcodeGunBuffer = '';
        }
    } else if (e.key.length === 1) {
        barcodeGunBuffer += e.key;
    }
});

// Customer Autocomplete Search Logic
let customersData = [
    { id: '', name: 'Walk-in Customer', phone: 'Pelanggan Umum' },
    @foreach($customers as $customer)
    { id: '{{ $customer->id }}', name: {!! json_encode($customer->name) !!}, phone: {!! json_encode($customer->phone ?? '') !!} },
    @endforeach
];

function showCustomerDropdown() {
    filterCustomerDropdown();
}

function filterCustomerDropdown() {
    const input = document.getElementById('customerSearchInput');
    const results = document.getElementById('customerSearchResults');
    if (!input || !results) return;
    
    const query = input.value.toLowerCase().trim();
    
    // Sync customerName value with input
    document.getElementById('customerName').value = input.value || 'Walk-in Customer';
    
    let html = '';
    const filtered = customersData.filter(c => 
        c.name.toLowerCase().includes(query) || (c.phone && c.phone.toLowerCase().includes(query))
    );
    
    if (filtered.length === 0) {
        html = `
            <div class="list-group-item text-muted py-2 px-3 small">
                <i class="fas fa-info-circle me-1 text-primary"></i> Customer "<strong>${escapeHtml(input.value)}</strong>" belum terdaftar. (Transaksi tetap bisa diproses dengan nama ini).
            </div>
        `;
    } else {
        filtered.forEach(c => {
            const isWalkIn = c.id === '';
            const icon = isWalkIn ? '<i class="fas fa-user-tag text-primary me-2"></i>' : '<i class="fas fa-user text-secondary me-2"></i>';
            const phoneText = c.phone && c.phone !== 'Pelanggan Umum' 
                ? `<small class="text-muted d-block" style="font-size: 10px;"><i class="fas fa-phone me-1"></i>${escapeHtml(c.phone)}</small>` 
                : (isWalkIn ? '<small class="text-muted d-block" style="font-size: 10px;">Pelanggan Umum / Tanpa Akun</small>' : '');
            
            const nameEscaped = c.name.replace(/'/g, "\\'");
            html += `
                <button type="button" class="list-group-item list-group-item-action py-2 px-3 border-bottom text-start"
                        onclick="selectCustomer('${c.id}', '${nameEscaped}')">
                    <div class="fw-bold text-dark small">${icon}${escapeHtml(c.name)}</div>
                    ${phoneText}
                </button>
            `;
        });
    }
    
    results.innerHTML = html;
    results.style.display = 'block';
}

function selectCustomer(id, name) {
    document.getElementById('customerId').value = id;
    document.getElementById('customerName').value = name;
    document.getElementById('customerSearchInput').value = name;
    const results = document.getElementById('customerSearchResults');
    if (results) results.style.display = 'none';
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

document.addEventListener('click', function(e) {
    const container = document.getElementById('customerSearchContainer');
    const results = document.getElementById('customerSearchResults');
    if (container && !container.contains(e.target) && results) {
        results.style.display = 'none';
    }
});

// Save new customer
function saveNewCustomer() {
    const name = document.getElementById('newCustomerName').value.trim();
    const countryCode = document.getElementById('newCustomerCountryCode')?.value || '+62';
    const dialDigits = countryCode.replace(/\D/g, '');
    let cleanDigits = phoneInput.replace(/\D/g, '');
    if (cleanDigits.startsWith(dialDigits)) cleanDigits = cleanDigits.substring(dialDigits.length);
    if (cleanDigits.startsWith('0')) cleanDigits = cleanDigits.substring(1);

    if (!name || !cleanDigits) {
        alert('Mohon isi nama dan nomor telepon dengan benar!');
        return;
    }
    const phone = countryCode + cleanDigits;
    
    fetch('{{ route("customers.quickStore") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ name, phone })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            customersData.push({
                id: result.customer.id.toString(),
                name: result.customer.name,
                phone: result.customer.phone || ''
            });
            
            selectCustomer(result.customer.id.toString(), result.customer.name);
            
            bootstrap.Modal.getInstance(document.getElementById('addCustomerModal')).hide();
            
            document.getElementById('newCustomerName').value = '';
            document.getElementById('newCustomerPhone').value = '';
            
            alert('Customer berhasil ditambahkan!');
        } else {
            alert('Error menambahkan customer!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan.');
    });
}

// Show success modal
function showSuccessModal(invoiceNumber, customerName, total, paidAmount, change, invoiceId) {
    document.getElementById('successInvoiceNumber').textContent = invoiceNumber;
    document.getElementById('successCustomer').textContent = customerName;
    document.getElementById('successTotalTagihan').textContent = 'Rp ' + formatNumber(total);
    document.getElementById('successJumlahBayar').textContent = 'Rp ' + formatNumber(paidAmount);
    document.getElementById('successKembalian').textContent = 'Rp ' + formatNumber(change);
    
    window.currentInvoiceId = invoiceId;
    
    const modal = new bootstrap.Modal(document.getElementById('successModal'));
    modal.show();
}

function printReceiptFromModal() {
    window.open('/cashier/print/' + window.currentInvoiceId, '_blank');
}

function newTransaction() {
    bootstrap.Modal.getInstance(document.getElementById('successModal')).hide();
    clearCart();
    location.reload();
}

// Camera Barcode Scanner Logic
let html5QrCodeScanner = null;

function openCameraScanner() {
    const modalEl = document.getElementById('cameraScannerModal');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
    
    setTimeout(() => {
        if (typeof Html5Qrcode === 'undefined') {
            alert('Modul scanner kamera sedang dimuat, coba lagi dalam 2 detik.');
            return;
        }
        
        if (!html5QrCodeScanner) {
            html5QrCodeScanner = new Html5Qrcode("cameraScannerReader");
        }
        
        html5QrCodeScanner.start(
            { facingMode: "environment" },
            {
                fps: 10,
                qrbox: { width: 280, height: 160 }
            },
            (decodedText) => {
                handleBarcodeScan(decodedText);
                closeCameraScanner();
            },
            () => {}
        ).catch(err => {
            console.warn('Camera error', err);
            alert('Tidak dapat mengakses kamera: Pastikan izin kamera aktif.');
        });
    }, 400);
}

function closeCameraScanner() {
    if (html5QrCodeScanner && html5QrCodeScanner.isScanning) {
        html5QrCodeScanner.stop().then(() => {
            const modalEl = document.getElementById('cameraScannerModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        }).catch(() => {
            const modalEl = document.getElementById('cameraScannerModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        });
    } else {
        const modalEl = document.getElementById('cameraScannerModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
    }
}
</script>

<!-- Modal Camera Barcode Scanner -->
<div class="modal fade" id="cameraScannerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title font-semibold text-dark d-flex align-items-center gap-2">
                    <i class="fas fa-camera text-primary me-2"></i> Scan Barcode Kamera
                </h5>
                <button type="button" class="btn-close" onclick="closeCameraScanner()" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3">
                <div id="cameraScannerReader" style="width: 100%; min-height: 250px; background: #000; border-radius: 12px; overflow: hidden;"></div>
                <p class="text-muted small mt-2 mb-0">Arahkan barcode barang tepat ke area kamera</p>
            </div>
            <div class="modal-footer border-top justify-content-between">
                <span class="small text-muted"><i class="fas fa-barcode text-primary me-1"></i>Deteksi otomatis</span>
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeCameraScanner()">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
@endsection
