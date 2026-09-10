@extends('layouts.app')

@section('title', 'Process Sales Return - PT Nusantara')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Process Sales Return</h1>
            <p class="text-muted mb-0">Search for a transaction to initiate a product return</p>
        </div>
        <a href="{{ route('returns.create') }}" class="btn btn-primary">
            <x-icon name="plus" class="w-4 h-4 me-2" /> New Return
        </a>
    </div>

    <!-- Search Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('returns.index') }}">
                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text"><x-icon name="search" class="w-4 h-4" /></span>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Enter invoice number, barcode, or Customer Name..." 
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary px-4">
                                <x-icon name="search" class="w-4 h-4 me-1" /> Find Transaction
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="date_filter" class="form-select" onchange="this.form.submit()">
                            <option value="last_7" {{ request('date_filter') == 'last_7' ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="last_30" {{ request('date_filter') == 'last_30' || !request('date_filter') ? 'selected' : '' }}>Last 30 Days</option>
                            <option value="all">All Time</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Recent Returns (if no search) -->
    @if(!request('search') && $recentReturns->count() > 0)
    <div class="mb-4">
        <h6 class="text-muted mb-2">RECENT RETURNS</h6>
        <div class="d-flex flex-wrap gap-2">
            @foreach($recentReturns as $recent)
            <a href="{{ route('returns.show', $recent->id) }}" class="btn btn-sm btn-outline-primary">
                {{ $recent->return_id }}
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Returns Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($returns->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>RETURN ID</th>
                            <th>DATE</th>
                            <th>INVOICE</th>
                            <th>CUSTOMER</th>
                            <th>PRODUCT</th>
                            <th>QTY</th>
                            <th>REFUND AMOUNT</th>
                            <th>METHOD</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($returns as $return)
                        <tr>
                            <td><strong>{{ $return->return_id }}</strong></td>
                            <td>{{ $return->date->format('d M Y') }}</td>
                            <td>
                                @if($return->salesInvoice)
                                <a href="{{ route('cashier.show', $return->salesInvoice->id) }}">
                                    {{ $return->salesInvoice->invoice_number }}
                                </a>
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                @if($return->salesInvoice)
                                {{ $return->salesInvoice->customer_name }}
                                @else
                                {{ $return->entity }}
                                @endif
                            </td>
                            <td>
                                @if($return->product)
                                {{ $return->product->product_name }}
                                <br><small class="text-muted">{{ $return->product->product_code }}</small>
                                @else
                                -
                                @endif
                            </td>
                            <td>{{ $return->items }}</td>
                            <td><strong>Rp {{ number_format($return->refund_amount, 0, ',', '.') }}</strong></td>
                            <td>
                                @if($return->refund_method == 'Cash')
                                    <x-icon name="money-bill-wave" class="w-4 h-4 text-success" /> {{ $return->refund_method }}
                                @elseif($return->refund_method == 'Store Credit')
                                    <x-icon name="ticket-alt" class="w-4 h-4 text-primary" /> {{ $return->refund_method }}
                                @else
                                    {{ $return->refund_method }}
                                @endif
                            </td>
                            <td>
                                @if($return->status == 'Approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($return->status == 'Pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>
                                <div class="relative inline-block text-start action-menu-container">
                                    <button type="button" onclick="toggleActionMenu(this, event)" class="btn btn-sm btn-light border-0 rounded-circle" style="width: 32px; height: 32px; padding: 0;" title="Aksi">
                                        <svg class="bi mx-auto" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                    <div class="action-menu-popup position-absolute end-0 top-100 mt-1 z-3 bg-white border rounded-3 shadow p-1 align-items-center gap-1 min-w-max" style="display: none;">
                                        <a href="{{ route('returns.show', $return->id) }}" class="btn btn-sm btn-light text-primary p-2" title="Detail Return">
                                            <svg class="bi" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            @include('partials.pagination', ['paginator' => $returns])
            @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <x-icon name="search" class="w-16 h-16 text-muted mb-3" />
                <h5 class="text-muted">No returns found</h5>
                <p class="text-muted mb-3">Try adjusting your search or filters</p>
                <a href="{{ route('returns.create') }}" class="btn btn-primary">
                    <x-icon name="plus" class="w-4 h-4 me-2" /> Create New Return
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mt-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Total Returns</small>
                            <h4 class="mb-0">{{ $stats['total_returns'] }}</h4>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <x-icon name="undo" class="w-8 h-8 text-primary" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Pending Approval</small>
                            <h4 class="mb-0">{{ $stats['pending_approvals'] }}</h4>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <x-icon name="clock" class="w-8 h-8 text-warning" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Approved Today</small>
                            <h4 class="mb-0">{{ $stats['approved_today'] }}</h4>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <x-icon name="check-circle" class="w-8 h-8 text-success" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Total Refunds</small>
                            <h4 class="mb-0">Rp {{ number_format($stats['total_refund_amount'], 0, ',', '.') }}</h4>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <x-icon name="money-bill-wave" class="w-8 h-8 text-info" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
