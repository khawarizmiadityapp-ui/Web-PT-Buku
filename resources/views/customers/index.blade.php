@extends('layouts.app')

@section('title', 'Customer Management - PT Nusantara')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="h3 mb-1">Customer Management</h1>
        <p class="text-muted mb-0">Manage and organize your distributor's customer base with ease.</p>
    </div>

    <!-- Search & Add Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="input-group" style="max-width: 400px;">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" placeholder="Search customer name or ID..." id="searchCustomer">
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('customers.export', request()->query()) }}" class="btn btn-outline-secondary">
                <i class="fas fa-download me-1"></i> Export CSV
            </a>
            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Tambah Customer
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">TOTAL CUSTOMERS</p>
                            <h2 class="mb-0">{{ $customers->total() }}</h2>
                            <small class="text-success">
                                <i class="fas fa-arrow-up"></i> 12%
                            </small>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">DATA PEMBELI TERCATAT</p>
                            <h2 class="mb-0">{{ $customers->total() }}</h2>
                            <small class="text-muted">Siap Digunakan di Kasir</small>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-address-book fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase font-weight-bold">GROWTH TREND</p>
                            <div class="d-flex align-items-baseline gap-2">
                                <h3 class="mb-0 fw-bold">+18.5%</h3>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5 text-xs">
                                    <i class="fas fa-arrow-up me-1"></i>Positif
                                </span>
                            </div>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-2.5 rounded-3">
                            <i class="fas fa-chart-line text-primary fs-5"></i>
                        </div>
                    </div>
                    <div style="height: 48px;" class="w-100 mt-1">
                        <svg class="w-100 h-100" viewBox="0 0 200 45" preserveAspectRatio="none" style="overflow: visible;">
                            <defs>
                                <linearGradient id="customerGrowthGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#2563EB" stop-opacity="0.35"/>
                                    <stop offset="100%" stop-color="#2563EB" stop-opacity="0.0"/>
                                </linearGradient>
                            </defs>
                            <path d="M 0 32 Q 25 15, 50 28 T 100 18 T 150 26 T 200 6 L 200 45 L 0 45 Z" fill="url(#customerGrowthGrad)" />
                            <path d="M 0 32 Q 25 15, 50 28 T 100 18 T 150 26 T 200 6" fill="none" stroke="#2563EB" stroke-width="3" stroke-linecap="round" />
                            <circle cx="200" cy="6" r="4.5" fill="#2563EB" stroke="#ffffff" stroke-width="2" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>CUSTOMER NAME</th>
                            <th>PHONE & EMAIL</th>
                            <th>CITY</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary text-white me-3">
                                        {{ strtoupper(substr($customer->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div><strong>{{ $customer->name }}</strong></div>
                                        <small class="text-muted">ID: {{ $customer->customer_code }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>{{ $customer->phone }}</div>
                                @if($customer->email)
                                <small class="text-muted">{{ $customer->email }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="d-inline-flex align-items-center px-2.5 py-1 rounded-pill bg-light border text-dark fw-medium" style="font-size: 13px;" @if($customer->address) title="{{ $customer->address }}" @endif>
                                    <i class="fas fa-map-marker-alt text-danger me-1.5" style="font-size: 11px;"></i>
                                    {{ $customer->city ?: '-' }}
                                </span>
                            </td>
                            <td>
                                <div class="relative inline-block text-start action-menu-container">
                                    <button type="button" onclick="toggleActionMenu(this, event)" class="btn btn-sm btn-light border-0 rounded-circle" style="width: 32px; height: 32px; padding: 0;" title="Aksi">
                                        <svg class="bi mx-auto" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                    <div class="action-menu-popup position-absolute end-0 top-100 mt-1 z-3 bg-white border rounded-3 shadow p-1 align-items-center gap-1 min-w-max" style="display: none;">
                                        <a href="{{ route('customers.show', $customer) }}" title="Detail Customer" class="btn btn-sm btn-light text-primary p-2">
                                            <svg class="bi" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer) }}" title="Edit Customer" class="btn btn-sm btn-light text-warning p-2">
                                            <svg class="bi" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <button type="button" title="Hapus Customer" class="btn btn-sm btn-light text-danger p-2" onclick="confirmDelete('{{ $customer->id }}', '{{ $customer->name }}')">
                                            <svg class="bi" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No customers found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            @include('partials.pagination', ['paginator' => $customers])
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}
</style>

<script>
// Search customer
document.getElementById('searchCustomer').addEventListener('input', function() {
    const search = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(search) ? '' : 'none';
    });
});

// Confirm delete
function confirmDelete(id, name) {
    if(confirm('Are you sure you want to delete customer: ' + name + '?')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/customers/' + id;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
