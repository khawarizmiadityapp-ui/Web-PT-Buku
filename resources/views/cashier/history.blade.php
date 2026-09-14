@extends('layouts.app')

@section('title', 'Sales History - StatiSync POS')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Sales History</h1>
            <p class="text-muted mb-0">Manage and review all retail transactions across your branches.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="exportCSV()">
                <x-icon name="download" class="w-4 h-4 me-2" /> Export CSV
            </button>
            <a href="{{ route('cashier.transaction.enhanced') }}" class="btn btn-primary">
                <x-icon name="plus" class="w-4 h-4 me-2" /> New Sale
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('cashier.history') }}" id="filterForm">
                <div class="row g-3 align-items-end">
                    <!-- Date Range -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">DATE RANGE</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><x-icon name="calendar" class="w-4 h-4" /></span>
                            <input type="date" name="start_date" class="form-control" 
                                   value="{{ request('start_date', now()->subDays(7)->format('Y-m-d')) }}" 
                                   placeholder="Start Date">
                            <span class="input-group-text bg-white">-</span>
                            <input type="date" name="end_date" class="form-control" 
                                   value="{{ request('end_date', now()->format('Y-m-d')) }}" 
                                   placeholder="End Date">
                            <button type="button" class="btn btn-outline-secondary" onclick="clearDateRange()">
                                <x-icon name="sliders-h" class="w-4 h-4" />
                            </button>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="col-md-2">
                        <label class="form-label small text-muted">PAYMENT METHOD</label>
                        <select name="payment_method" class="form-select">
                            <option value="">All Methods</option>
                            <option value="Cash" {{ request('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="QRIS" {{ request('payment_method') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                            <option value="Card" {{ request('payment_method') == 'Card' ? 'selected' : '' }}>Card</option>
                            <option value="Credit Card" {{ request('payment_method') == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="Bank Transfer" {{ request('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="col-md-2">
                        <label class="form-label small text-muted">STATUS</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Success</option>
                            <option value="Partial" {{ request('status') == 'Partial' ? 'selected' : '' }}>Partial</option>
                            <option value="Unpaid" {{ request('status') == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="Refunded" {{ request('status') == 'Refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>

                    <!-- Search -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">SEARCH</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search invoices, customers..." 
                               value="{{ request('search') }}">
                    </div>

                    <!-- Filter Button -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <x-icon name="search" class="w-4 h-4 me-1" /> Apply Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-muted small">
                            <th class="fw-normal">INVOICE NO</th>
                            <th class="fw-normal">DATE/TIME</th>
                            <th class="fw-normal">CUSTOMER</th>
                            <th class="fw-normal text-end">TOTAL AMOUNT</th>
                            <th class="fw-normal">STATUS</th>
                            <th class="fw-normal">PAYMENT</th>
                            <th class="fw-normal text-center">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr>
                            <td>
                                <a href="{{ route('cashier.show', $transaction->id) }}" class="text-primary text-decoration-none fw-semibold">
                                    {{ $transaction->invoice_number }}
                                </a>
                                @if($transaction->order_code)
                                    <div class="small text-muted" style="font-size: 11px;">{{ $transaction->order_code }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="small">{{ $transaction->created_at->format('d M, H:i') }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @php
                                        $initials = strtoupper(substr($transaction->customer_name, 0, 2));
                                        $colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DFE6E9', '#74B9FF'];
                                        $colorIndex = ord($initials[0]) % count($colors);
                                        $bgColor = $colors[$colorIndex];
                                    @endphp
                                    <div class="avatar-circle me-2" style="background-color: {{ $bgColor }};">
                                        {{ $initials }}
                                    </div>
                                    <span>{{ $transaction->customer_name }}</span>
                                </div>
                            </td>
                            <td class="text-end">
                                <strong>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <div>
                                        @if($transaction->payment_status == 'Paid')
                                            <span class="badge badge-success">Paid</span>
                                        @elseif($transaction->payment_status == 'Partial')
                                            <span class="badge badge-warning">Partial</span>
                                        @elseif($transaction->payment_status == 'Refunded')
                                            <span class="badge badge-refunded">Refunded</span>
                                        @else
                                            <span class="badge badge-danger">Unpaid</span>
                                        @endif
                                    </div>
                                    @if($transaction->order_status)
                                        <div>
                                            <span class="badge bg-light text-secondary border" style="font-size: 10px; font-weight: 500;">
                                                📦 {{ $transaction->order_status_label }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="text-muted small">{{ $transaction->payment_method ?? 'Cash' }}</span>
                            </td>
                            <td class="text-center">
                                <div class="relative inline-block text-start action-menu-container">
                                    <button type="button" onclick="toggleActionMenu(this, event)" class="btn btn-sm btn-light border-0 rounded-circle" style="width: 32px; height: 32px; padding: 0;" title="Aksi">
                                        <svg class="bi mx-auto" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                    <div class="action-menu-popup position-absolute end-0 top-100 mt-1 z-3 bg-white border rounded-3 shadow p-1 align-items-center gap-1 min-w-max" style="display: none;">
                                        <a href="{{ route('cashier.show', $transaction->id) }}" title="Detail Transaksi" class="btn btn-sm btn-light text-primary p-2">
                                            <svg class="bi" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        @if($transaction->payment_status !== 'Paid')
                                        <form action="{{ route('cashier.confirmPayment', $transaction->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Konfirmasi pembayaran lunas untuk faktur {{ $transaction->invoice_number }}?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-light text-success p-2" title="Konfirmasi Lunas">
                                                <svg class="bi" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </form>
                                        @endif
                                        <a href="{{ route('cashier.print', $transaction->id) }}" title="Cetak Struk" class="btn btn-sm btn-light text-secondary p-2" target="_blank">
                                            <svg class="bi" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <x-icon name="receipt" class="w-12 h-12 text-muted mb-3 d-block" />
                                <p class="text-muted mb-0">No transactions found</p>
                                <small class="text-muted">Try adjusting your filters</small>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            @include('partials.pagination', ['paginator' => $transactions])
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm stat-card-blue">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                            <x-icon name="dollar-sign" class="w-4 h-4" />
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted small mb-1">DAILY REVENUE</div>
                            <h4 class="mb-0">Rp {{ number_format($dailyRevenue, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm stat-card-green">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                            <x-icon name="check-circle" class="w-4 h-4" />
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted small mb-1">SUCCESSFUL SALES</div>
                            <h4 class="mb-0">{{ number_format($successfulSales, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm stat-card-orange">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning me-3">
                            <x-icon name="undo" class="w-4 h-4" />
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted small mb-1">PENDING RETURNS</div>
                            <h4 class="mb-0">{{ $pendingReturns }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 11px;
}

.badge-success {
    background-color: #d4edda;
    color: #155724;
    padding: 6px 12px;
    border-radius: 4px;
    font-weight: 500;
}

.badge-warning {
    background-color: #fff3cd;
    color: #856404;
    padding: 6px 12px;
    border-radius: 4px;
    font-weight: 500;
}

.badge-refunded {
    background-color: #fff3cd;
    color: #856404;
    padding: 6px 12px;
    border-radius: 4px;
    font-weight: 500;
}

.badge-danger {
    background-color: #f8d7da;
    color: #721c24;
    padding: 6px 12px;
    border-radius: 4px;
    font-weight: 500;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.stat-card-blue {
    border-left: 4px solid #0d6efd;
}

.stat-card-green {
    border-left: 4px solid #198754;
}

.stat-card-orange {
    border-left: 4px solid #ffc107;
}

.pagination .page-link {
    border: none;
    color: #6c757d;
    padding: 6px 12px;
}

.pagination .page-item.active .page-link {
    background-color: #0d6efd;
    color: white;
    border-radius: 4px;
}

.pagination .page-link:hover {
    background-color: #e9ecef;
    border-radius: 4px;
}
</style>

<script>
function exportCSV() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'csv');
    window.location.href = '{{ route("cashier.history") }}?' + params.toString();
}

function clearDateRange() {
    document.querySelector('input[name="start_date"]').value = '';
    document.querySelector('input[name="end_date"]').value = '';
}
</script>
@endsection
