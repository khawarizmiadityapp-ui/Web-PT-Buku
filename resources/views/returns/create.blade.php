@extends('layouts.app')

@section('title', 'Sales Return Process - PT Nusantara')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row g-3">
        <!-- Left Side - Return Form -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="mb-1">Sales Return Process</h4>
                            <p class="text-muted mb-0 small">Initiate a return for Order #INV-2026-0542</p>
                        </div>
                        <a href="{{ route('returns.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <form action="{{ route('returns.store') }}" method="POST" enctype="multipart/form-data" id="returnForm">
                        @csrf
                        
                        <!-- Return Details Card -->
                        <div class="card bg-light border-0 mb-3">
                            <div class="card-body">
                                <h6 class="card-title mb-3">
                                    <i class="fas fa-clipboard-list text-primary me-2"></i> Return Details
                                </h6>
                                
                                <!-- Reason for Return -->
                                <div class="mb-3">
                                    <label class="form-label">Reason for return *</label>
                                    <select class="form-select @error('reason') is-invalid @enderror" 
                                            name="reason" required>
                                        <option value="">Select a reason</option>
                                        <option value="Defective">Defective</option>
                                        <option value="Wrong Item">Wrong Item</option>
                                        <option value="Damaged">Damaged</option>
                                        <option value="Not as Described">Not as Described</option>
                                        <option value="Customer Changed Mind">Customer Changed Mind</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Select a Product -->
                                <div class="mb-3">
                                    <label class="form-label">Select a product *</label>
                                    <select class="form-select @error('product_id') is-invalid @enderror" 
                                            name="product_id" id="productSelect" required onchange="updateProductInfo()">
                                        <option value="">Choose product</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}" 
                                                data-price="{{ $product->price }}"
                                                data-code="{{ $product->product_code }}">
                                            {{ $product->product_name }} ({{ $product->product_code }})
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Quantity & Price -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Quantity *</label>
                                        <input type="number" class="form-control @error('items') is-invalid @enderror" 
                                               name="items" id="quantityInput" min="1" value="1" 
                                               required onchange="calculateRefund()">
                                        @error('items')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Unit Price *</label>
                                        <input type="number" class="form-control @error('unit_price') is-invalid @enderror" 
                                               name="unit_price" id="unitPriceInput" min="0" step="0.01" 
                                               required readonly>
                                        @error('unit_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Photo Evidence (Optional) -->
                        <div class="card bg-light border-0 mb-3">
                            <div class="card-body">
                                <h6 class="card-title mb-3">
                                    <i class="fas fa-camera text-primary me-2"></i> Photo Evidence (Optional)
                                </h6>
                                
                                <div class="text-center py-4" id="uploadArea" style="border: 2px dashed #dee2e6; border-radius: 8px; cursor: pointer;"
                                     onclick="document.getElementById('proofImage').click()">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                                    <p class="mb-1">Click to upload or drag-and-drop</p>
                                    <small class="text-muted">PNG, JPG, JPEG, WEBP - Maks. 2MB</small>
                                    <input type="file" id="proofImage" name="proof_image" class="d-none" 
                                           accept="image/png,image/jpeg,image/jpg,image/webp" onchange="previewImage(this)">
                                </div>
                                @error('proof_image')
                                    <div class="text-danger small mt-2"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                                
                                <div id="imagePreview" class="mt-3" style="display: none;">
                                    <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                                    <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeImage()">
                                        <i class="fas fa-times me-1"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Internal Notes -->
                        <div class="mb-3">
                            <label class="form-label">Internal Notes</label>
                            <textarea class="form-control" name="notes" rows="3" 
                                      placeholder="Enter detailed description of the issue for warehouse audit..."></textarea>
                            <small class="text-muted">This note is for internal use only</small>
                        </div>

                        <!-- Hidden fields -->
                        <input type="hidden" name="date" value="{{ now()->toDateString() }}">
                        <input type="hidden" name="entity" id="entityInput" value="Customer">
                        <input type="hidden" name="type" value="SALES">
                        <input type="hidden" name="sales_invoice_id" value="{{ $invoice->id ?? '' }}">
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Side - Return Summary -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Return Summary</h5>
                    
                    <!-- Refund Method -->
                    <div class="mb-3">
                        <label class="form-label">Refund Method *</label>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary refund-btn active" 
                                    onclick="selectRefund('Cash')" data-method="Cash">
                                <i class="fas fa-money-bill-wave me-2"></i> Cash
                            </button>
                            <button type="button" class="btn btn-outline-primary refund-btn" 
                                    onclick="selectRefund('QRIS')" data-method="QRIS">
                                <i class="fas fa-qrcode me-2"></i> QRIS
                            </button>
                        </div>
                        <input type="hidden" name="refund_method" id="refundMethodInput" form="returnForm" value="Cash">
                    </div>

                    <!-- Amounts Summary -->
                    <div class="bg-light p-3 rounded mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Items Returned:</span>
                            <strong id="totalItemsDisplay">0 items</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Return Subtotal:</span>
                            <strong id="subtotalDisplay">Rp 0</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Restocking Fee (10%):</span>
                            <strong class="text-danger" id="restockingDisplay">- Rp 0</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0">Refund Amount:</h5>
                            <h5 class="mb-0 text-success" id="refundDisplay">Rp 0</h5>
                        </div>
                    </div>
                    
                    <input type="hidden" name="restocking_fee" id="restockingFeeInput" form="returnForm" value="0">

                    <!-- Actions -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger btn-lg" form="returnForm">
                            <i class="fas fa-check me-2"></i> Process Return
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">
                            Cancel
                        </button>
                    </div>

                    <!-- Info Alert -->
                    <div class="alert alert-info mt-3 small mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Returns will be processed within 24 hours after protocol examination at the warehouse center.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.refund-btn.active {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}
#uploadArea:hover {
    background-color: #f8f9fa;
}
</style>

<script>
let restockingFeeRate = 0.10; // 10%

function updateProductInfo() {
    const select = document.getElementById('productSelect');
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        const price = parseFloat(option.dataset.price);
        document.getElementById('unitPriceInput').value = price;
        document.getElementById('entityInput').value = option.text.split('(')[0].trim();
        calculateRefund();
    }
}

function calculateRefund() {
    const quantity = parseInt(document.getElementById('quantityInput').value) || 0;
    const unitPrice = parseFloat(document.getElementById('unitPriceInput').value) || 0;
    
    const subtotal = quantity * unitPrice;
    const restockingFee = subtotal * restockingFeeRate;
    const refundAmount = subtotal - restockingFee;
    
    document.getElementById('totalItemsDisplay').textContent = quantity + ' items';
    document.getElementById('subtotalDisplay').textContent = 'Rp ' + formatNumber(subtotal);
    document.getElementById('restockingDisplay').textContent = '- Rp ' + formatNumber(restockingFee);
    document.getElementById('refundDisplay').textContent = 'Rp ' + formatNumber(refundAmount);
    document.getElementById('restockingFeeInput').value = restockingFee;
}

function selectRefund(method) {
    document.getElementById('refundMethodInput').value = method;
    document.querySelectorAll('.refund-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.closest('.refund-btn').classList.add('active');
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
            document.getElementById('uploadArea').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage() {
    document.getElementById('proofImage').value = '';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('uploadArea').style.display = 'block';
}

function formatNumber(num) {
    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    selectRefund('Cash');
});
</script>
@endsection
