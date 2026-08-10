@extends('layouts.app')

@section('title', 'Transaction Detail - StatiSync')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Transaction Detail</h1>
            <p class="text-muted mb-0">Invoice: {{ $transaction->invoice_number }}</p>
        </div>
        <div>
            <a href="{{ route('cashier.history') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
            <button class="btn btn-outline-primary me-2" onclick="showPrintSettings()">
                <i class="fas fa-cog me-1"></i> Print Settings
            </button>
            <button class="btn btn-outline-primary me-2" onclick="downloadPDF()">
                <i class="fas fa-download me-1"></i> Download PDF
            </button>
            <a href="{{ route('cashier.print', $transaction->id) }}" class="btn btn-primary" target="_blank">
                <i class="fas fa-print me-1"></i> Print Struk
            </a>
        </div>
    </div>

    <div class="row g-3">
        <!-- Transaction Info -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Transaction Information</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Invoice Number</label>
                            <div><strong>{{ $transaction->invoice_number }}</strong></div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Date & Time</label>
                            <div>{{ $transaction->created_at->format('d F Y, H:i A') }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Customer</label>
                            <div>{{ $transaction->customer_name }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Payment Method</label>
                            <div>
                                @if($transaction->payment_method == 'Cash')
                                    <i class="fas fa-money-bill-wave text-success"></i> {{ $transaction->payment_method }}
                                @elseif($transaction->payment_method == 'QRIS')
                                    <i class="fas fa-qrcode text-primary"></i> {{ $transaction->payment_method }}
                                @elseif($transaction->payment_method == 'Card')
                                    <i class="fas fa-credit-card text-info"></i> {{ $transaction->payment_method }}
                                @else
                                    {{ $transaction->payment_method ?? '-' }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information Card (Like in Design) -->
            <div class="card border-0 shadow-sm mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="opacity-75">Payment Information</small>
                            <h6 class="mb-0 mt-1">{{ $transaction->payment_method ?? 'QRIS Dynamic' }}</h6>
                            <small class="opacity-75">ID: {{ $transaction->id }}</small>
                        </div>
                        <div class="text-end">
                            <small class="opacity-75">Cashier Details</small>
                            <h6 class="mb-0 mt-1">{{ auth()->user()->name ?? 'Admin' }}</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Items</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>PRODUCT</th>
                                    <th class="text-center">QTY</th>
                                    <th class="text-end">PRICE</th>
                                    <th class="text-end">SUBTOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->items as $item)
                                <tr>
                                    <td>
                                        <div><strong>{{ $item->product_name }}</strong></div>
                                        @if($item->product)
                                        <small class="text-muted">{{ $item->product->product_code }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-end"><strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Payment Summary</h5>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <strong>Rp {{ number_format($transaction->total_amount + $transaction->discount_amount - $transaction->tax_amount, 0, ',', '.') }}</strong>
                    </div>
                    
                    @if($transaction->discount_amount > 0)
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Discount</span>
                        <strong>- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</strong>
                    </div>
                    @endif
                    
                    @if($transaction->tax_amount > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (11%)</span>
                        <strong>Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</strong>
                    </div>
                    @endif
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="mb-0">Total</h5>
                        <h5 class="mb-0 text-primary">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</h5>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Paid Amount</span>
                        <strong>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</strong>
                    </div>
                    
                    @if($transaction->paid_amount > $transaction->total_amount)
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Change</span>
                        <strong>Rp {{ number_format($transaction->paid_amount - $transaction->total_amount, 0, ',', '.') }}</strong>
                    </div>
                    @endif
                    
                    @if($transaction->remaining_amount > 0)
                    <div class="d-flex justify-content-between mb-2 text-danger">
                        <span>Remaining</span>
                        <strong>Rp {{ number_format($transaction->remaining_amount, 0, ',', '.') }}</strong>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Status -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Status</h5>
                    <div class="text-center py-3">
                        @if($transaction->payment_status == 'Paid')
                            <div class="display-1 text-success mb-2">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h4 class="text-success">PAID</h4>
                            <p class="text-muted mb-0">Transaction completed successfully</p>
                        @elseif($transaction->payment_status == 'Partial')
                            <div class="display-1 text-warning mb-2">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <h4 class="text-warning">PARTIAL PAYMENT</h4>
                            <p class="text-muted mb-0">Remaining payment required</p>
                        @else
                            <div class="display-1 text-danger mb-2">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <h4 class="text-danger">UNPAID</h4>
                            <p class="text-muted mb-0">Payment pending</p>
                        @endif
                    </div>
                </div>
            </div>

            @if($transaction->notes)
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body">
                    <h5 class="card-title mb-2">Notes</h5>
                    <p class="text-muted mb-0">{{ $transaction->notes }}</p>
                </div>
            </div>
            @endif

            <!-- Barcode/QR for Verification -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body text-center">
                    <h6 class="mb-3">Scan for Verification</h6>
                    <div class="mb-2">
                        <svg id="barcode-{{ $transaction->id }}"></svg>
                    </div>
                    <small class="text-muted">{{ $transaction->invoice_number }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
// Generate barcode
JsBarcode("#barcode-{{ $transaction->id }}", "{{ $transaction->invoice_number }}", {
    format: "CODE128",
    width: 2,
    height: 50,
    displayValue: false
});

// Download PDF function
function downloadPDF() {
    window.open('{{ route("cashier.print", $transaction->id) }}', '_blank');
}

// Show print settings modal
function showPrintSettings() {
    const modal = new bootstrap.Modal(document.getElementById('printSettingsModal'));
    modal.show();
}

// Apply print settings and print
function printWithSettings() {
    const printer = document.getElementById('printerSelect').value;
    const copies = document.getElementById('copiesInput').value;
    const showLogo = document.getElementById('printLogo').checked;
    const showTax = document.getElementById('showTaxDetails').checked;
    const showSignature = document.getElementById('customerSignature').checked;
    const footerMessage = document.getElementById('footerMessage').value;
    
    // Save settings to localStorage
    localStorage.setItem('printSettings', JSON.stringify({
        printer, copies, showLogo, showTax, showSignature, footerMessage
    }));
    
    // Open print page with settings
    const printUrl = '{{ route("cashier.print", $transaction->id) }}' + 
                     '?copies=' + copies + 
                     '&logo=' + (showLogo ? '1' : '0') +
                     '&tax=' + (showTax ? '1' : '0') +
                     '&signature=' + (showSignature ? '1' : '0') +
                     '&footer=' + encodeURIComponent(footerMessage);
    
    window.open(printUrl, '_blank');
    
    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('printSettingsModal')).hide();
}
</script>

<!-- Print Settings Modal -->
<div class="modal fade" id="printSettingsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Print Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Select Printer -->
                <div class="mb-3">
                    <label class="form-label">Select Printer</label>
                    <select class="form-select" id="printerSelect">
                        <option value="default">Epson TM-T82III (Thermal 80mm)</option>
                        <option value="thermal58">Thermal Printer 58mm</option>
                        <option value="laser">HP LaserJet (A4)</option>
                        <option value="pdf">Save as PDF</option>
                    </select>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="printerOnlineReady" checked disabled>
                        <label class="form-check-label text-success" for="printerOnlineReady">
                            <i class="fas fa-circle me-1"></i> Printer Online & Ready
                        </label>
                    </div>
                </div>

                <!-- Number of Copies -->
                <div class="mb-3">
                    <label class="form-label">Number of Copies</label>
                    <div class="input-group">
                        <button class="btn btn-outline-secondary" type="button" onclick="changeCopies(-1)">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" class="form-control text-center" id="copiesInput" value="1" min="1" max="10">
                        <button class="btn btn-outline-secondary" type="button" onclick="changeCopies(1)">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <hr>

                <!-- Print Options -->
                <div class="mb-3">
                    <label class="form-label mb-2">Print Options</label>
                    
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="printLogo" checked>
                        <label class="form-check-label" for="printLogo">
                            Print Logo
                        </label>
                    </div>
                    
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="showTaxDetails" checked>
                        <label class="form-check-label" for="showTaxDetails">
                            Show Tax Details
                        </label>
                    </div>
                    
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="customerSignature">
                        <label class="form-check-label" for="customerSignature">
                            Customer Signature
                        </label>
                    </div>
                </div>

                <!-- Footer Custom Message -->
                <div class="mb-3">
                    <label class="form-label">Footer Custom Message</label>
                    <textarea class="form-control" id="footerMessage" rows="2" placeholder="e.g. Happy Eid Mubarak!"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="printWithSettings()">
                    <i class="fas fa-print me-2"></i> Print Now
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function changeCopies(delta) {
    const input = document.getElementById('copiesInput');
    let value = parseInt(input.value) + delta;
    if (value < 1) value = 1;
    if (value > 10) value = 10;
    input.value = value;
}
</script>

@endsection
