@extends('layouts.app')

@section('title', 'New Transaction - StatiSync')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row g-3">
        <!-- Left Side - Product Selection -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Select Products</h5>
                    
                    <!-- Search Bar -->
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="productSearch" 
                                   placeholder="Search product by name or code..." autocomplete="off">
                        </div>
                        <div id="searchResults" class="list-group position-absolute w-100 mt-1" style="z-index: 1000; display: none;"></div>
                    </div>

                    <!-- Product Grid -->
                    <div class="row g-2" id="productGrid">
                        @foreach($products as $product)
                        <div class="col-md-4 col-sm-6">
                            <div class="card product-card h-100" style="cursor: pointer;" 
                                 onclick="addToCart({{ $product->id }}, '{{ $product->product_name }}', {{ $product->price }}, {{ $product->system_stock }})">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $product->product_name }}</h6>
                                            <small class="text-muted">{{ $product->product_code }}</small>
                                            <div class="mt-2">
                                                <strong class="text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                                                <br>
                                                <small class="text-muted">Stock: {{ $product->system_stock }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Cart -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Cart</h5>

                    <!-- Customer Selection with Live Autocomplete Search -->
                    <div class="mb-3 position-relative" id="customerSearchContainer">
                        <label class="form-label font-semibold mb-1">Customer</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="customerSearchInput" 
                                   placeholder="Cari atau ketik nama customer..." 
                                   value="Walk-in Customer" autocomplete="off" 
                                   onfocus="showCustomerDropdown()" oninput="filterCustomerDropdown()">
                            <input type="hidden" id="customerId" name="customer_id" value="">
                            <input type="hidden" id="customerName" name="customer_name" value="Walk-in Customer">
                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#addCustomerModal" title="Tambah Customer Baru">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <!-- Live Autocomplete Results -->
                        <div id="customerSearchResults" class="list-group position-absolute w-100 shadow-lg mt-1" 
                             style="z-index: 1050; max-height: 220px; overflow-y: auto; display: none; background: white; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
                        </div>
                    </div>

                    <!-- Cart Items -->
                    <div class="mb-3" style="max-height: 250px; overflow-y: auto;">
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

                    <hr>

                    <!-- Discount -->
                    <div class="mb-2">
                        <label class="form-label small">Discount</label>
                        <input type="number" class="form-control form-control-sm" id="discountAmount" 
                               value="0" min="0" onchange="calculateTotal()">
                        <input type="hidden" id="taxAmount" value="0">
                    </div>

                    <!-- Totals -->
                    <div class="bg-light p-3 rounded mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Subtotal:</span>
                            <strong id="subtotalDisplay">Rp 0</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Discount:</span>
                            <strong id="discountDisplay">Rp 0</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <strong id="taxDisplay">Rp 0</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0">Total:</h5>
                            <h5 class="mb-0 text-primary" id="totalDisplay">Rp 0</h5>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" id="paymentMethod" required>
                            <option value="Cash">Cash</option>
                            <option value="QRIS">QRIS</option>
                        </select>
                    </div>

                    <!-- Paid Amount & Live Kembalian -->
                    <div class="mb-3">
                        <label class="form-label font-semibold">Bayar (Received)</label>
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
                        <input type="number" class="form-control form-control-lg fw-bold border-primary shadow-sm text-center" id="paidAmount" 
                               placeholder="Masukkan jumlah bayar" min="0" oninput="calculateChange()" style="font-size: 20px;">
                        
                        <!-- Live Kembalian Card -->
                        <div id="changeDisplayCard" class="card border-0 mt-2 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0 !important;">
                            <div class="card-body p-2.5 d-flex justify-content-between align-items-center">
                                <span class="fw-semibold small text-secondary" id="changeLabel">Kembalian:</span>
                                <strong class="fs-5 text-secondary" id="changeAmount">Rp 0</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" rows="2" placeholder="Add notes..."></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary btn-lg" onclick="processTransaction()">
                            <i class="fas fa-check me-2"></i> Process Payment
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearCart()">
                            <i class="fas fa-times me-2"></i> Clear Cart
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
                    <label class="form-label">Phone *</label>
                    <input type="text" class="form-control" id="newCustomerPhone" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveNewCustomer()">Save Customer</button>
            </div>
        </div>
    </div>
</div>

<style>
.product-card:hover {
    background-color: #f8f9fa;
    border-color: #0d6efd;
}
</style>

<script>
let cart = [];
let subtotal = 0;

// Add product to cart
function addToCart(productId, productName, price, stock) {
    // Check if product already in cart
    const existingItem = cart.find(item => item.productId === productId);
    
    if (existingItem) {
        if (existingItem.quantity < stock) {
            existingItem.quantity++;
            existingItem.subtotal = existingItem.quantity * existingItem.price;
        } else {
            alert('Insufficient stock!');
            return;
        }
    } else {
        cart.push({
            productId: productId,
            productName: productName,
            price: price,
            quantity: 1,
            stock: stock,
            subtotal: price
        });
    }
    
    renderCart();
    calculateTotal();
}

// Remove from cart
function removeFromCart(index) {
    cart.splice(index, 1);
    renderCart();
    calculateTotal();
}

// Update quantity
function updateQuantity(index, newQty) {
    if (newQty <= 0) {
        removeFromCart(index);
        return;
    }
    
    if (newQty > cart[index].stock) {
        alert('Insufficient stock!');
        return;
    }
    
    cart[index].quantity = parseInt(newQty);
    cart[index].subtotal = cart[index].quantity * cart[index].price;
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
        html += `
            <div class="card border-0 shadow-sm mb-2.5 rounded-3 position-relative" style="background: #ffffff; border: 1px solid #e2e8f0 !important;">
                <div class="card-body p-2.5">
                    <!-- Top Row: Product Name & Subtotal -->
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                        <div class="flex-grow-1">
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 13px; line-height: 1.3;">${escapeHtml(item.productName)}</h6>
                            <small class="text-muted" style="font-size: 10px;">@ Rp ${formatNumber(item.price)}</small>
                        </div>
                        <div class="text-end flex-shrink-0">
                            <div class="fw-bold text-primary" style="font-size: 14px;">Rp ${formatNumber(item.subtotal)}</div>
                        </div>
                    </div>

                    <!-- Bottom Row: Stepper Controls & Delete Button -->
                    <div class="d-flex justify-content-end align-items-center mt-2 pt-2 border-top border-light gap-1.5">
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
        `;
    });
    
    cartItemsDiv.innerHTML = html;
}

// Calculate totals
function calculateTotal() {
    subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
    const discount = parseFloat(document.getElementById('discountAmount').value) || 0;
    const tax = 0;
    const total = subtotal - discount;
    
    document.getElementById('subtotalDisplay').textContent = 'Rp ' + formatNumber(subtotal);
    document.getElementById('discountDisplay').textContent = 'Rp ' + formatNumber(discount);
    if (document.getElementById('taxDisplay')) {
        document.getElementById('taxDisplay').textContent = 'Rp 0';
    }
    document.getElementById('totalDisplay').textContent = 'Rp ' + formatNumber(total);
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
    
    document.getElementById('paidAmount').value = paidVal;
    calculateChange();
}

// Calculate change
function calculateChange() {
    const discount = parseFloat(document.getElementById('discountAmount').value) || 0;
    const total = Math.max(0, subtotal - discount);
    const paid = parseFloat(document.getElementById('paidAmount').value) || 0;
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

// Process transaction
function processTransaction() {
    if (cart.length === 0) {
        alert('Cart is empty!');
        return;
    }
    
    const customerId = document.getElementById('customerId').value;
    const customerName = document.getElementById('customerName').value;
    const paymentMethod = document.getElementById('paymentMethod').value;
    const paidAmount = parseFloat(document.getElementById('paidAmount').value) || 0;
    const discount = parseFloat(document.getElementById('discountAmount').value) || 0;
    const tax = parseFloat(document.getElementById('taxAmount').value) || 0;
    const notes = document.getElementById('notes').value;
    const total = subtotal - discount + tax;
    
    if (!customerName) {
        alert('Please enter customer name!');
        return;
    }
    
    if (paidAmount <= 0) {
        alert('Please enter paid amount!');
        return;
    }
    
    if (paidAmount < total) {
        if (!confirm('Paid amount is less than total. Continue as partial payment?')) {
            return;
        }
    }
    
    const data = {
        customer_id: customerId || null,
        customer_name: customerName,
        items: cart.map(item => ({
            product_id: item.productId,
            quantity: item.quantity,
            price: item.price
        })),
        discount_amount: discount,
        tax_amount: tax,
        payment_method: paymentMethod,
        paid_amount: paidAmount,
        notes: notes,
        _token: '{{ csrf_token() }}'
    };
    
    // Send to server
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
            alert('Transaction completed successfully!\nInvoice: ' + result.invoice_number + '\nChange: Rp ' + formatNumber(result.change));
            
            // Ask if want to print
            if (confirm('Print receipt?')) {
                window.open('/cashier/print/' + result.invoice_id, '_blank');
            }
            
            // Clear cart and redirect
            clearCart();
            window.location.href = '{{ route("cashier.index") }}';
        } else {
            alert('Error: ' + result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing the transaction.');
    });
}

// Clear cart
function clearCart() {
    cart = [];
    subtotal = 0;
    renderCart();
    calculateTotal();
    document.getElementById('paidAmount').value = '';
    document.getElementById('discountAmount').value = '0';
    document.getElementById('taxAmount').value = '0';
    document.getElementById('notes').value = '';
    document.getElementById('customerId').value = '';
    document.getElementById('customerName').value = 'Walk-in Customer';
}

// Format number
function formatNumber(num) {
    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Product search
document.getElementById('productSearch').addEventListener('input', function() {
    const search = this.value.toLowerCase();
    const products = document.querySelectorAll('.product-card');
    
    products.forEach(product => {
        const text = product.textContent.toLowerCase();
        if (text.includes(search)) {
            product.parentElement.style.display = '';
        } else {
            product.parentElement.style.display = 'none';
        }
    });
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
    const name = document.getElementById('newCustomerName').value;
    const phone = document.getElementById('newCustomerPhone').value;
    
    if (!name || !phone) {
        alert('Please fill all required fields!');
        return;
    }
    
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
            
            alert('Customer added successfully!');
        } else {
            alert('Error adding customer!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding customer.');
    });
}
</script>
@endsection
