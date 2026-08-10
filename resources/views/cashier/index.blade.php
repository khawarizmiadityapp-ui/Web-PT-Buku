@extends('layouts.app')

@section('title', 'Cashier Dashboard - StatiSync')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Dashboard Overview</h1>
            <p class="text-muted mb-0">Welcome back, here's what's happening at PT Nusantara today.</p>
        </div>
        <div>
            <a href="{{ route('cashier.history', ['export' => 'csv']) }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-download me-1"></i> Download Report
            </a>
            <a href="{{ route('cashier.transaction') }}" class="btn btn-primary">
                <i class="fas fa-cart-plus me-1"></i> Penjualan Baru
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="text-muted small mb-1">
                                <i class="fas fa-money-bill-wave text-primary me-1"></i>
                                Total Penjualan Hari Ini
                            </div>
                            <h3 class="mb-0">Rp {{ number_format($stats['today_revenue'], 0, ',', '.') }}</h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up"></i> +12%
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="text-muted small mb-1">
                                <i class="fas fa-receipt text-info me-1"></i>
                                Jumlah Transaksi
                            </div>
                            <h3 class="mb-0">{{ $stats['today_transactions'] }}</h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up"></i> +5.2%
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="text-muted small mb-1">
                                <i class="fas fa-wallet text-success me-1"></i>
                                Pendapatan Bersih
                            </div>
                            <h3 class="mb-0">Rp {{ number_format($stats['today_income'], 0, ',', '.') }}</h3>
                            <small class="text-danger">
                                <i class="fas fa-arrow-down"></i> -2.4%
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="text-muted small mb-1">
                                <i class="fas fa-box text-warning me-1"></i>
                                Produk Terjual
                            </div>
                            <h3 class="mb-0">{{ $stats['products_sold'] }}</h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up"></i> +18%
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">10 TRANSAKSI TERAKHIR</h5>
                        <a href="{{ route('cashier.history') }}" class="btn btn-sm btn-link">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>INVOICE NO</th>
                                    <th>TIME</th>
                                    <th>CUSTOMER</th>
                                    <th>TOTAL</th>
                                    <th>STATUS</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $transaction)
                                <tr>
                                    <td><strong>{{ $transaction->invoice_number }}</strong></td>
                                    <td>{{ $transaction->created_at->format('H:i A') }}</td>
                                    <td>{{ $transaction->customer_name }}</td>
                                    <td><strong>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong></td>
                                    <td>
                                        @if($transaction->payment_status == 'Paid')
                                            <span class="badge bg-success">SUCCESS</span>
                                        @elseif($transaction->payment_status == 'Pending')
                                            <span class="badge bg-warning">PENDING</span>
                                        @elseif($transaction->payment_status == 'Cancelled')
                                            <span class="badge bg-danger">CANCELLED</span>
                                        @else
                                            <span class="badge bg-secondary">{{ strtoupper($transaction->payment_status) }}</span>
                                        @endif
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
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-2"></i>
                                        <p>No transactions yet today</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
