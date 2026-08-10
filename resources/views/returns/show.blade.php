@extends('layouts.app')

@section('title', 'Return Detail - PT Nusantara')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Return Detail</h1>
            <p class="text-muted mb-0">{{ $return->return_id }}</p>
        </div>
        <div>
            <a href="{{ route('returns.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
            @if($return->status == 'Pending')
            <button class="btn btn-success me-1" data-bs-toggle="modal" data-bs-target="#approveModal">
                <i class="fas fa-check me-1"></i> Approve
            </button>
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                <i class="fas fa-times me-1"></i> Reject
            </button>
            @endif
        </div>
    </div>

    <div class="row g-3">
        <!-- Left Side - Return Information -->
        <div class="col-lg-8">
            <!-- Status Badge -->
            <div class="alert alert-{{ $return->status == 'Approved' ? 'success' : ($return->status == 'Pending' ? 'warning' : 'danger') }} mb-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-{{ $return->status == 'Approved' ? 'check-circle' : ($return->status == 'Pending' ? 'clock' : 'times-circle') }} fa-2x me-3"></i>
                    <div>
                        <h5 class="mb-0">Return Status: {{ $return->status }}</h5>
                        <small>
                            @if($return->status == 'Approved')
                                Return approved and refund processed
                            @elseif($return->status == 'Pending')
                                Awaiting verification from warehouse team
                            @else
                                Return request has been rejected
                            @endif
                        </small>
                    </div>
                </div>
            </div>

            <!-- Return Information Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Return Information</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Return ID</label>
                            <div><strong>{{ $return->return_id }}</strong></div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Return Date</label>
                            <div>{{ $return->date->format('d F Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Original Invoice</label>
                            <div>
                                @if($return->salesInvoice)
                                <a href="{{ route('cashier.show', $return->salesInvoice->id) }}">
                                    {{ $return->salesInvoice->invoice_number }}
                                </a>
                                @else
                                -
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Customer</label>
                            <div>
                                @if($return->salesInvoice)
                                {{ $return->salesInvoice->customer_name }}
                                @else
                                {{ $return->entity }}
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Return Type</label>
                            <div><span class="badge bg-primary">{{ $return->type }}</span></div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Reason</label>
                            <div>{{ $return->reason }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Returned Items -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Returned Items</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>PRODUCT</th>
                                    <th class="text-center">ORIGINAL QTY</th>
                                    <th class="text-center">RETURN QTY</th>
                                    <th class="text-end">UNIT PRICE</th>
                                    <th class="text-end">SUBTOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        @if($return->product)
                                        <div><strong>{{ $return->product->product_name }}</strong></div>
                                        <small class="text-muted">{{ $return->product->product_code }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($return->salesInvoice)
                                            {{ $return->salesInvoice->items->where('product_id', $return->product_id)->first()->quantity ?? '-' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center"><strong>{{ $return->items }}</strong></td>
                                    <td class="text-end">Rp {{ number_format($return->unit_price, 0, ',', '.') }}</td>
                                    <td class="text-end"><strong>Rp {{ number_format($return->total_amount, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Photo Evidence -->
            @if($return->proof_image)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Photo Evidence</h5>
                    <img src="{{ Storage::url($return->proof_image) }}" alt="Proof" class="img-fluid rounded" style="max-height: 400px;">
                </div>
            </div>
            @endif

            <!-- Notes -->
            @if($return->notes)
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Notes</h5>
                    <p class="mb-0">{{ $return->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Side - Return Summary -->
        <div class="col-lg-4">
            <!-- Refund Summary Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Return Summary</h5>
                    
                    <!-- Refund Method -->
                    <div class="mb-3">
                        <label class="text-muted small">Refund Method</label>
                        <div>
                            @if($return->refund_method == 'Cash')
                                <i class="fas fa-money-bill-wave text-success"></i>
                            @elseif($return->refund_method == 'Store Credit')
                                <i class="fas fa-ticket-alt text-primary"></i>
                            @else
                                <i class="fas fa-credit-card text-info"></i>
                            @endif
                            {{ $return->refund_method }}
                        </div>
                    </div>

                    <hr>

                    <!-- Amount Breakdown -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Items Returned:</span>
                        <strong>{{ $return->items }} items</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Return Subtotal:</span>
                        <strong>Rp {{ number_format($return->total_amount, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-danger">
                        <span>Restocking Fee:</span>
                        <strong>- Rp {{ number_format($return->restocking_fee, 0, ',', '.') }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="mb-0">Refund Amount:</h5>
                        <h5 class="mb-0 text-success">Rp {{ number_format($return->refund_amount, 0, ',', '.') }}</h5>
                    </div>

                    @if($return->status == 'Approved')
                    <div class="alert alert-success small mb-0">
                        <i class="fas fa-check-circle me-1"></i>
                        Refund has been processed and customer notified.
                    </div>
                    @endif
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Timeline</h5>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <small class="text-muted">{{ $return->created_at->format('d M Y, H:i') }}</small>
                                <p class="mb-0"><strong>Return Created</strong></p>
                                <small>Return request submitted</small>
                            </div>
                        </div>
                        
                        @if($return->status != 'Pending')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-{{ $return->status == 'Approved' ? 'success' : 'danger' }}"></div>
                            <div class="timeline-content">
                                <small class="text-muted">{{ $return->updated_at->format('d M Y, H:i') }}</small>
                                <p class="mb-0"><strong>Return {{ $return->status }}</strong></p>
                                <small>Status updated by admin</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('returns.updateStatus', $return->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="Approved">
                
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Approve Return</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to approve this return?</p>
                    <div class="mb-3">
                        <label class="form-label">Admin Notes (Optional)</label>
                        <textarea class="form-control" name="admin_notes" rows="3" placeholder="Add notes about this approval..."></textarea>
                    </div>
                    <div class="alert alert-info small mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Stock will be automatically updated upon approval.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Return</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('returns.updateStatus', $return->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="Rejected">
                
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Reject Return</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to reject this return?</p>
                    <div class="mb-3">
                        <label class="form-label">Reason for Rejection *</label>
                        <textarea class="form-control" name="admin_notes" rows="3" placeholder="Enter reason for rejection..." required></textarea>
                    </div>
                    <div class="alert alert-warning small mb-0">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Customer will be notified about the rejection.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Return</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    padding-bottom: 20px;
}
.timeline-item:before {
    content: '';
    position: absolute;
    left: -24px;
    top: 8px;
    bottom: -12px;
    width: 2px;
    background: #dee2e6;
}
.timeline-item:last-child:before {
    display: none;
}
.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
}
.timeline-content {
    padding-top: -5px;
}
</style>
@endsection
