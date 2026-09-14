@extends('layouts.app')

@section('title', 'Pesanan Masuk (Online Orders) - StatiSync POS')

@push('styles')
<style>
    /* Prevent any horizontal overflow */
    .no-horizontal-scroll {
        overflow-x: hidden !important;
        max-width: 100%;
    }
    
    /* Fixed layout table to prevent stretching */
    .table-fixed-layout {
        table-layout: fixed !important;
        width: 100% !important;
    }

    /* Custom elegant tabs */
    .order-nav-pill {
        border-radius: 10px;
        padding: 6px 14px;
        font-weight: 600;
        font-size: 12.5px;
        color: #475569;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        transition: all 0.15s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .order-nav-pill:hover {
        background-color: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }
    .order-nav-pill.active {
        background: #2563eb !important;
        border-color: #2563eb !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
    }
    .order-nav-pill.active .badge-pill-counter {
        background-color: #ffffff;
        color: #2563eb;
    }

    /* Table row hover and styling */
    .order-table-row {
        transition: background-color 0.15s ease;
    }
    .order-table-row:hover {
        background-color: #f8fafc !important;
    }
    .order-table-row.row-unpaid {
        background-color: #fffbf5;
    }
    .order-table-row.row-unpaid:hover {
        background-color: #fef3c7 !important;
    }

    /* Modern mini barcode styling */
    .barcode-chip {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 2px 6px;
        font-family: 'Courier New', Courier, monospace;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #0f172a;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .barcode-chip:hover {
        background: #e2e8f0;
        border-color: #94a3b8;
    }

    /* Action popup specific style */
    .action-menu-popup {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
    }

    /* Metric card accent effects */
    .stat-card-modern {
        border-radius: 16px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        background: #ffffff;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.08);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4 py-3 no-horizontal-scroll">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h1 class="h4 mb-0 fw-extrabold text-slate-800 tracking-tight">Pesanan Masuk</h1>
                <span class="badge bg-blue-50 text-blue-700 border border-blue-200 px-2 py-0.5 rounded-pill small fw-semibold" style="font-size: 11px;">
                    <x-icon name="globe" class="w-3 h-3 inline mr-1" /> Online Store
                </span>
            </div>
            <p class="text-muted mb-0 small mt-0.5" style="font-size: 12px;">Kelola verifikasi pembayaran pesanan web pelanggan dan sinkronisasi ke tim gudang.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('cashier.orders') }}" class="btn btn-sm btn-white bg-white border border-slate-200 shadow-2xs text-slate-700 hover:bg-slate-50 d-flex align-items-center gap-1.5 px-3">
                <x-icon name="sync" class="w-3.5 h-3.5 text-slate-500" />
                <span class="small fw-semibold">Refresh</span>
            </a>
            <a href="{{ route('cashier.history') }}" class="btn btn-sm btn-white bg-white border border-slate-200 shadow-2xs text-slate-700 hover:bg-slate-50 d-flex align-items-center gap-1.5 px-3">
                <x-icon name="history" class="w-3.5 h-3.5 text-slate-500" />
                <span class="small fw-semibold">Riwayat POS</span>
            </a>
        </div>
    </div>

    <!-- Metric Summary Cards -->
    <div class="row g-2.5 mb-3">
        <!-- 1. Perlu Verifikasi (Unpaid) -->
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100 {{ $stats['unpaid_count'] > 0 ? 'border-amber-300 bg-amber-50/20' : '' }}">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-slate-500 fw-semibold text-uppercase" style="font-size: 11px;">Perlu Verifikasi</span>
                        <h4 class="fw-bold my-0.5 {{ $stats['unpaid_count'] > 0 ? 'text-amber-600' : 'text-slate-800' }}">
                            {{ $stats['unpaid_count'] }}
                        </h4>
                        <div class="text-slate-400" style="font-size: 10.5px;">Rp {{ number_format($stats['unpaid_total'], 0, ',', '.') }} tertunda</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 d-flex align-items-center justify-content-center flex-shrink-0">
                        <x-icon name="clock" class="w-5 h-5" />
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Siap Gudang (Confirmed) -->
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-slate-500 fw-semibold text-uppercase" style="font-size: 11px;">Siap Gudang</span>
                        <h4 class="fw-bold my-0.5 text-blue-600">{{ $stats['confirmed_count'] }}</h4>
                        <div class="text-slate-400" style="font-size: 10.5px;">Dikonfirmasi (Lunas)</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 d-flex align-items-center justify-content-center flex-shrink-0">
                        <x-icon name="box" class="w-5 h-5" />
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Sedang Diproses Gudang -->
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-slate-500 fw-semibold text-uppercase" style="font-size: 11px;">Diproses Gudang</span>
                        <h4 class="fw-bold my-0.5 text-indigo-600">{{ $stats['processing_count'] }}</h4>
                        <div class="text-slate-400" style="font-size: 10.5px;">Picking & Packing aktif</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 d-flex align-items-center justify-content-center flex-shrink-0">
                        <x-icon name="dolly" class="w-5 h-5" />
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Total Masuk Hari Ini -->
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-slate-500 fw-semibold text-uppercase" style="font-size: 11px;">Order Hari Ini</span>
                        <h4 class="fw-bold my-0.5 text-emerald-600">{{ $stats['today_orders'] }}</h4>
                        <div class="text-slate-400" style="font-size: 10.5px;">Total {{ $stats['total_incoming'] }} pesanan</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 d-flex align-items-center justify-content-center flex-shrink-0">
                        <x-icon name="shopping-bag" class="w-5 h-5" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Container Card -->
    <div class="card border-0 shadow-sm rounded-2xl overflow-hidden bg-white">
        <!-- Navigation Pills Header -->
        <div class="p-2.5 bg-slate-50/80 border-b border-slate-200">
            <div class="d-flex items-center gap-1.5 overflow-x-auto pb-1 pb-md-0 scrollbar-none">
                <a href="{{ route('cashier.orders', array_merge(request()->except('tab', 'page'), ['tab' => 'all'])) }}" 
                   class="order-nav-pill {{ $tab === 'all' ? 'active' : '' }}">
                    <span>Semua Pesanan</span>
                    <span class="badge-pill-counter bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded-full text-xs font-bold">{{ $stats['total_incoming'] }}</span>
                </a>

                <a href="{{ route('cashier.orders', array_merge(request()->except('tab', 'page'), ['tab' => 'unpaid'])) }}" 
                   class="order-nav-pill {{ $tab === 'unpaid' ? 'active' : '' }}">
                    <span>Belum Lunas</span>
                    @if($stats['unpaid_count'] > 0)
                        <span class="badge-pill-counter bg-rose-500 text-white px-1.5 py-0.5 rounded-full text-xs font-bold">{{ $stats['unpaid_count'] }}</span>
                    @endif
                </a>

                <a href="{{ route('cashier.orders', array_merge(request()->except('tab', 'page'), ['tab' => 'confirmed'])) }}" 
                   class="order-nav-pill {{ $tab === 'confirmed' ? 'active' : '' }}">
                    <span>Dikonfirmasi</span>
                    <span class="badge-pill-counter bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded-full text-xs font-bold">{{ $stats['confirmed_count'] }}</span>
                </a>

                <a href="{{ route('cashier.orders', array_merge(request()->except('tab', 'page'), ['tab' => 'processing'])) }}" 
                   class="order-nav-pill {{ $tab === 'processing' ? 'active' : '' }}">
                    <span>Diproses Gudang</span>
                    <span class="badge-pill-counter bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded-full text-xs font-bold">{{ $stats['processing_count'] }}</span>
                </a>

                <a href="{{ route('cashier.orders', array_merge(request()->except('tab', 'page'), ['tab' => 'completed'])) }}" 
                   class="order-nav-pill {{ $tab === 'completed' ? 'active' : '' }}">
                    <span>Selesai</span>
                </a>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="p-3 bg-white border-b border-slate-100">
            <form method="GET" action="{{ route('cashier.orders') }}" id="filterForm">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="row g-2 align-items-center">
                    <!-- Search Input -->
                    <div class="col-12 col-md-4 col-lg-5">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-slate-50 border-slate-200 text-slate-400">
                                <x-icon name="search" class="w-3.5 h-3.5" />
                            </span>
                            <input type="text" name="search" class="form-control border-slate-200 bg-slate-50/50 focus:bg-white text-xs" 
                                   placeholder="Cari Kode Order, SKU, Invoice, Customer..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Payment Method Filter -->
                    <div class="col-6 col-md-3 col-lg-2">
                        <select name="payment_method" class="form-select form-select-sm border-slate-200 bg-slate-50/50 text-xs" onchange="this.form.submit()">
                            <option value="">Semua Metode</option>
                            <option value="QRIS" {{ request('payment_method') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                            <option value="Bank Transfer" {{ request('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="Cash on Delivery" {{ request('payment_method') == 'Cash on Delivery' ? 'selected' : '' }}>COD (Bayar di Tempat)</option>
                        </select>
                    </div>

                    <!-- Payment Status Filter -->
                    <div class="col-6 col-md-3 col-lg-2">
                        <select name="payment_status" class="form-select form-select-sm border-slate-200 bg-slate-50/50 text-xs" onchange="this.form.submit()">
                            <option value="">Semua Pembayaran</option>
                            <option value="Paid" {{ request('payment_status') == 'Paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                            <option value="Unpaid" {{ request('payment_status') == 'Unpaid' ? 'selected' : '' }}>Belum Lunas (Unpaid)</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-12 col-md-2 col-lg-3 d-flex justify-content-end gap-1.5">
                        <button type="submit" class="btn btn-sm btn-primary px-3 text-xs fw-semibold">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'payment_method', 'payment_status', 'start_date', 'end_date']))
                        <a href="{{ route('cashier.orders', ['tab' => $tab]) }}" class="btn btn-sm btn-light border text-xs text-slate-600" title="Reset">
                            Reset
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Orders Table — NO HORIZONTAL SCROLL (Fixed Table Layout Fitting 100% Screen) -->
        <div class="w-100 no-horizontal-scroll">
            <table class="table table-hover align-middle mb-0 table-fixed-layout" style="width: 100%; min-width: 100%;">
                <thead class="bg-slate-50/90 text-slate-500 border-b border-slate-200" style="font-size: 11px;">
                    <tr>
                        <!-- 1. Order & Barcode (21%) -->
                        <th class="ps-3 py-2.5 fw-semibold" style="width: 21%;">PESANAN & BARCODE</th>
                        <!-- 2. Customer & Address (23%) -->
                        <th class="py-2.5 fw-semibold" style="width: 23%;">PELANGGAN & TUJUAN</th>
                        <!-- 3. Items & SKU (24%) -->
                        <th class="py-2.5 fw-semibold" style="width: 24%;">PRODUK & KODE BARANG</th>
                        <!-- 4. Total & Payment (14%) -->
                        <th class="py-2.5 fw-semibold text-end" style="width: 14%;">TOTAL TAGIHAN</th>
                        <!-- 5. Statuses (11%) -->
                        <th class="py-2.5 fw-semibold text-center" style="width: 11%;">STATUS</th>
                        <!-- 6. Actions (7%) -->
                        <th class="pe-3 py-2.5 fw-semibold text-center" style="width: 7%;">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                    <tr class="order-table-row {{ $order->payment_status !== 'Paid' ? 'row-unpaid' : '' }}">
                        <!-- 1. PESANAN & BARCODE -->
                        <td class="ps-3 py-2.5">
                            <div class="d-flex flex-column gap-1">
                                <!-- Order Code with Mini Barcode Trigger -->
                                <div class="d-flex align-items-center gap-1.5">
                                    <span class="barcode-chip" onclick="showOrderBarcodeModal('{{ $order->order_code }}', '{{ $order->invoice_number }}', '{{ addslashes($order->customer_name) }}')" title="Klik untuk cetak label barcode">
                                        <x-icon name="barcode" class="w-3.5 h-3.5 text-blue-600" />
                                        <span>{{ $order->order_code }}</span>
                                    </span>
                                </div>
                                <!-- Invoice Number -->
                                <div class="d-flex align-items-center gap-1 text-slate-600" style="font-size: 11px;">
                                    <a href="{{ route('cashier.show', $order->id) }}" class="text-slate-700 hover:text-blue-600 fw-semibold text-decoration-none">
                                        {{ $order->invoice_number }}
                                    </a>
                                </div>
                                <!-- Time -->
                                <div class="text-slate-400" style="font-size: 10.5px;">
                                    {{ $order->created_at->format('d M, H:i') }} • {{ $order->created_at->diffForHumans(null, true) }}
                                </div>
                            </div>
                        </td>

                        <!-- 2. PELANGGAN & TUJUAN -->
                        <td class="py-2.5">
                            <div class="d-flex flex-column">
                                <!-- Customer Name -->
                                <div class="fw-bold text-slate-800 text-truncate" style="font-size: 12.5px;" title="{{ $order->customer_name }}">
                                    {{ $order->customer_name }}
                                </div>
                                <!-- WhatsApp Phone -->
                                <div class="d-flex align-items-center gap-1 text-slate-500 mt-0.5" style="font-size: 11px;">
                                    <span>{{ $order->customer_phone }}</span>
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $order->customer_phone);
                                        if(str_starts_with($cleanPhone, '0')) $cleanPhone = '62' . substr($cleanPhone, 1);
                                    @endphp
                                    <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="text-emerald-600 hover:text-emerald-700" title="Chat WhatsApp">
                                        <x-icon name="comments" class="w-3 h-3 inline" />
                                    </a>
                                </div>
                                <!-- Shipping Address -->
                                @if($order->shipping_address)
                                <div class="text-slate-400 text-truncate mt-0.5" style="font-size: 10.5px;" title="{{ $order->shipping_address }}">
                                    📍 {{ $order->shipping_address }}
                                </div>
                                @endif
                            </div>
                        </td>

                        <!-- 3. PRODUK & KODE BARANG (SKU) -->
                        <td class="py-2.5">
                            <div class="d-flex flex-column gap-1">
                                <div class="small fw-semibold text-slate-700 d-flex align-items-center justify-content-between">
                                    <span>{{ $order->items->sum('quantity') }} Item</span>
                                    <span class="text-slate-400" style="font-size: 10px;">{{ $order->items->count() }} jenis</span>
                                </div>
                                <!-- First Item with Barcode/SKU -->
                                @php
                                    $firstItem = $order->items->first();
                                    $firstProductCode = $firstItem && $firstItem->product ? $firstItem->product->product_code : null;
                                @endphp
                                @if($firstItem)
                                <div class="p-1.5 rounded bg-slate-50 border border-slate-100">
                                    <div class="text-truncate fw-medium text-slate-800" style="font-size: 11px;" title="{{ $firstItem->product_name }}">
                                        {{ $firstItem->product_name }}
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mt-0.5">
                                        @if($firstProductCode)
                                        <span class="badge bg-white text-slate-600 border px-1.5 py-0.5 font-mono" style="font-size: 9.5px;" title="Kode SKU / Barcode">
                                            <x-icon name="barcode" class="w-2.5 h-2.5 inline mr-0.5 text-slate-400" />{{ $firstProductCode }}
                                        </span>
                                        @endif
                                        <span class="text-slate-500 font-semibold" style="font-size: 10px;">× {{ $firstItem->quantity }}</span>
                                    </div>
                                </div>
                                @endif
                                <!-- If more items -->
                                @if($order->items->count() > 1)
                                <span class="text-blue-600 fw-semibold text-truncate" style="font-size: 10.5px;" title="Total {{ $order->items->count() }} produk">
                                    +{{ $order->items->count() - 1 }} produk lainnya
                                </span>
                                @endif
                            </div>
                        </td>

                        <!-- 4. TOTAL TAGIHAN & METODE -->
                        <td class="py-2.5 text-end">
                            <div class="d-flex flex-column align-items-end">
                                <div class="fw-bold text-slate-900" style="font-size: 13px;">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </div>
                                <span class="badge bg-slate-100 text-slate-600 border px-1.5 py-0.5 mt-1" style="font-size: 10px;">
                                    {{ $order->payment_method ?? 'Cash' }}
                                </span>
                            </div>
                        </td>

                        <!-- 5. STATUS BAYAR & GUDANG -->
                        <td class="py-2.5 text-center">
                            <div class="d-flex flex-column align-items-center gap-1">
                                <!-- Status Pembayaran -->
                                @if($order->payment_status === 'Paid')
                                    <span class="badge bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-0.5 w-100 text-center fw-semibold" style="font-size: 10px;">
                                        Lunas
                                    </span>
                                @elseif($order->payment_status === 'Partial')
                                    <span class="badge bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.5 w-100 text-center fw-semibold" style="font-size: 10px;">
                                        Sebagian
                                    </span>
                                @else
                                    <span class="badge bg-rose-50 text-rose-700 border border-rose-200 px-2 py-0.5 w-100 text-center fw-semibold" style="font-size: 10px;">
                                        Belum Lunas
                                    </span>
                                @endif

                                <!-- Status Gudang -->
                                <span class="badge bg-slate-100 text-slate-700 border px-1.5 py-0.5 w-100 text-center text-truncate" style="font-size: 9.5px;" title="{{ $order->order_status_label }}">
                                    {{ $order->order_status_label }}
                                </span>
                            </div>
                        </td>

                        <!-- 6. AKSI (Button Titik Tiga ⋮) -->
                        <td class="pe-3 py-2.5 text-center">
                            <div class="position-relative d-inline-block action-menu-container">
                                <button type="button" onclick="toggleOrderActionMenu(this, event)" class="btn btn-sm btn-light border-0 rounded-circle text-slate-500 hover:text-slate-900 hover:bg-slate-200 transition d-flex align-items-center justify-content-center mx-auto" style="width: 32px; height: 32px; padding: 0;" title="Aksi">
                                    <svg class="bi" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>
                                <div class="action-menu-popup position-absolute end-0 top-100 mt-1 bg-white border border-slate-200 rounded-2xl shadow-xl p-1.5 flex-column gap-1 min-w-max text-start" style="display: none; min-width: 190px; z-index: 1050;">
                                    @if($order->payment_status !== 'Paid')
                                    <form action="{{ route('cashier.confirmPayment', $order->id) }}" method="POST" class="w-100 m-0" onsubmit="return confirm('Konfirmasi bahwa pesanan {{ $order->order_code ?? $order->invoice_number }} telah lunas dibayar?')">
                                        @csrf
                                        <button type="submit" class="w-100 text-start border-0 bg-transparent px-2.5 py-1.5 rounded-xl text-xs font-semibold text-emerald-700 hover:bg-emerald-50 d-flex align-items-center gap-2 transition">
                                            <x-icon name="check-circle" class="w-4 h-4 text-emerald-600 flex-shrink-0" />
                                            <span>Konfirmasi Lunas</span>
                                        </button>
                                    </form>
                                    <hr class="my-1 border-slate-100">
                                    @endif

                                    <a href="{{ route('cashier.show', $order->id) }}" class="text-decoration-none px-2.5 py-1.5 rounded-xl text-xs text-slate-700 hover:bg-slate-50 d-flex align-items-center gap-2 transition">
                                        <x-icon name="eye" class="w-4 h-4 text-blue-600 flex-shrink-0" />
                                        <span>Detail Pesanan</span>
                                    </a>

                                    <button type="button" onclick="showOrderBarcodeModal('{{ $order->order_code }}', '{{ $order->invoice_number }}', '{{ addslashes($order->customer_name) }}')" class="w-100 text-start border-0 bg-transparent px-2.5 py-1.5 rounded-xl text-xs text-slate-700 hover:bg-slate-50 d-flex align-items-center gap-2 transition">
                                        <x-icon name="barcode" class="w-4 h-4 text-indigo-600 flex-shrink-0" />
                                        <span>Cetak Label Barcode</span>
                                    </button>

                                    <a href="{{ route('cashier.print', $order->id) }}" target="_blank" class="text-decoration-none px-2.5 py-1.5 rounded-xl text-xs text-slate-700 hover:bg-slate-50 d-flex align-items-center gap-2 transition">
                                        <x-icon name="print" class="w-4 h-4 text-slate-500 flex-shrink-0" />
                                        <span>Cetak Struk Transaksi</span>
                                    </a>

                                    @if(!empty($order->customer_phone))
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $order->customer_phone);
                                        if(str_starts_with($cleanPhone, '0')) $cleanPhone = '62' . substr($cleanPhone, 1);
                                    @endphp
                                    <hr class="my-1 border-slate-100">
                                    <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="text-decoration-none px-2.5 py-1.5 rounded-xl text-xs text-emerald-700 hover:bg-emerald-50 d-flex align-items-center gap-2 transition">
                                        <x-icon name="comments" class="w-4 h-4 text-emerald-600 flex-shrink-0" />
                                        <span>Chat WhatsApp</span>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="py-4">
                                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 d-inline-flex align-items-center justify-content-center mb-2">
                                    <x-icon name="inbox" class="w-6 h-6" />
                                </div>
                                <h6 class="fw-bold text-slate-700 mb-1">Tidak ada pesanan masuk</h6>
                                <p class="text-slate-400 small mb-0" style="font-size: 11.5px;">Belum ada pesanan online pada kategori atau filter ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if($orders->hasPages())
        <div class="p-3 bg-slate-50 border-t border-slate-200">
            @include('partials.pagination', ['paginator' => $orders])
        </div>
        @endif
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL BARCODE PESANAN / PENGIRIMAN                                        -->
<!-- ========================================================================= -->
<div class="modal fade" id="orderBarcodeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-2xl overflow-hidden">
            <div class="modal-header bg-slate-50 border-bottom py-2.5 px-3">
                <div class="d-flex align-items-center gap-1.5">
                    <x-icon name="barcode" class="w-4 h-4 text-blue-600" />
                    <h6 class="modal-title fw-bold text-slate-800 small mb-0">Label Barcode Pesanan</h6>
                </div>
                <button type="button" class="btn-close text-xs" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center" id="printableBarcodeArea">
                <div class="text-xs fw-bold text-slate-500 text-uppercase tracking-wider mb-1">PT BUKU NUSANTARA</div>
                <div id="modalCustomerName" class="fw-bold text-slate-800 text-sm mb-2"></div>
                <div class="p-2 bg-white rounded-xl border border-slate-200 d-inline-block shadow-2xs mb-2">
                    <svg id="modalOrderBarcodeSvg" class="mx-auto" style="max-height: 55px; max-width: 100%;"></svg>
                </div>
                <div id="modalOrderCode" class="font-mono fw-bold text-slate-800 text-sm"></div>
                <div id="modalInvoiceNumber" class="text-slate-400 font-mono" style="font-size: 11px;"></div>
            </div>
            <div class="modal-footer bg-slate-50 border-top py-2 px-3 d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-light border text-xs" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-sm btn-primary text-xs d-flex align-items-center gap-1" onclick="printBarcodeLabel()">
                    <x-icon name="print" class="w-3.5 h-3.5" />
                    <span>Cetak Label</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
let barcodeModalInstance = null;

function showOrderBarcodeModal(orderCode, invoiceNumber, customerName) {
    document.getElementById('modalOrderCode').innerText = orderCode;
    document.getElementById('modalInvoiceNumber').innerText = invoiceNumber;
    document.getElementById('modalCustomerName').innerText = customerName || 'Pelanggan Online';

    // Generate real 1D barcode on SVG
    try {
        JsBarcode("#modalOrderBarcodeSvg", orderCode, {
            format: "CODE128",
            width: 1.8,
            height: 48,
            displayValue: false,
            margin: 0
        });
    } catch(e) {
        console.warn('JsBarcode error:', e);
    }

    if (!barcodeModalInstance) {
        barcodeModalInstance = new bootstrap.Modal(document.getElementById('orderBarcodeModal'));
    }
    barcodeModalInstance.show();
}

function printBarcodeLabel() {
    const printContent = document.getElementById('printableBarcodeArea').innerHTML;
    const printWindow = window.open('', '_blank', 'width=400,height=400');
    printWindow.document.write(`
        <html>
            <head>
                <title>Print Barcode Label</title>
                <style>
                    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; text-align: center; padding: 20px; }
                    .font-mono { font-family: monospace; }
                </style>
            </head>
            <body onload="window.print(); window.close();">
                ${printContent}
            </body>
        </html>
    `);
    printWindow.document.close();
}

function toggleOrderActionMenu(button, event) {
    if (event) event.stopPropagation();
    const container = button.closest('.action-menu-container');
    const popup = container.querySelector('.action-menu-popup');
    if (!popup) return;

    const isCurrentlyVisible = popup.style.display === 'flex';

    // Close all open action popups & reset row z-indices
    document.querySelectorAll('.action-menu-popup').forEach(el => {
        el.style.display = 'none';
        const row = el.closest('tr');
        if (row) {
            row.style.position = '';
            row.style.zIndex = '';
        }
    });

    if (!isCurrentlyVisible) {
        popup.style.display = 'flex';
        const parentRow = button.closest('tr');
        if (parentRow) {
            parentRow.style.position = 'relative';
            parentRow.style.zIndex = '999';
        }
    }
}

// Close popups when clicking anywhere outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.action-menu-container')) {
        document.querySelectorAll('.action-menu-popup').forEach(el => {
            el.style.display = 'none';
            const row = el.closest('tr');
            if (row) {
                row.style.position = '';
                row.style.zIndex = '';
            }
        });
    }
});
</script>
@endpush
