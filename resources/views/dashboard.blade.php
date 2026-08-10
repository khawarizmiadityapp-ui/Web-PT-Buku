@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Dashboard Overview</h1>
            <p class="text-slate-500 text-sm mt-1">Sistem Informasi Eksekutif & Manajemen Gudang</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('products.create') }}" class="flex items-center space-x-2 px-4 py-2.5 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition shadow-sm font-medium text-sm text-slate-700">
                <i class="fas fa-plus text-slate-500"></i>
                <span>Tambah Barang</span>
            </a>
            <a href="{{ route('sales.invoices.index') }}" class="flex items-center space-x-2 px-4 py-2.5 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition shadow-sm font-medium text-sm text-slate-700">
                <i class="fas fa-file-invoice text-slate-500"></i>
                <span>Buat Invoice</span>
            </a>
            <a href="{{ route('warehouse.stock-audit.start') }}" class="flex items-center space-x-2 px-4 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition shadow-md shadow-indigo-200 font-medium text-sm">
                <i class="fas fa-box"></i>
                <span>Stock Opname</span>
            </a>
        </div>
    </div>
</div>

<!-- Stats Grid Row 1 -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Barang -->
    <div class="stat-card bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">TOTAL BARANG</p>
                <h3 class="text-3xl font-bold text-gray-900 mb-1" id="totalBarang">{{ number_format($totalBarang) }}</h3>
                <p class="text-xs text-green-600 font-medium">
                    <i class="fas fa-arrow-up"></i> +2.4% vs last month
                </p>
            </div>
            <div class="bg-blue-50 p-3 rounded-lg">
                <i class="fas fa-boxes text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Supplier -->
    <div class="stat-card bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">TOTAL SUPPLIER</p>
                <h3 class="text-3xl font-bold text-gray-900 mb-1" id="totalSupplier">{{ number_format($totalSupplier) }}</h3>
                <p class="text-xs text-gray-500 font-medium">
                    <i class="fas fa-minus"></i> No change
                </p>
            </div>
            <div class="bg-blue-50 p-3 rounded-lg">
                <i class="fas fa-truck text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Customer -->
    <div class="stat-card bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">TOTAL CUSTOMER</p>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($totalCustomer) }}</h3>
                <p class="text-xs text-green-600 font-medium">
                    <i class="fas fa-arrow-up"></i> +12 this week
                </p>
            </div>
            <div class="bg-blue-50 p-3 rounded-lg">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Penjualan -->
    <div class="stat-card bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">TOTAL PENJUALAN</p>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">
                    Rp {{ number_format($totalPenjualan, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-green-600 font-medium">
                    <i class="fas fa-arrow-up"></i> +16.3% MTD
                </p>
            </div>
            <div class="bg-blue-50 p-3 rounded-lg">
                <i class="fas fa-chart-line text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Stats Grid Row 2 -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Barang Masuk -->
    <div class="stat-card bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">BARANG MASUK</p>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($barangMasuk) }}</h3>
                <p class="text-xs text-gray-600">Units this month</p>
            </div>
            <div class="bg-green-50 p-3 rounded-lg">
                <i class="fas fa-arrow-down text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Barang Keluar -->
    <div class="stat-card bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">BARANG KELUAR</p>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($barangKeluar) }}</h3>
                <p class="text-xs text-red-600 font-medium">
                    <i class="fas fa-arrow-down"></i> Stock reducing
                </p>
            </div>
            <div class="bg-blue-50 p-3 rounded-lg">
                <i class="fas fa-arrow-up text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Retur -->
    <div class="stat-card bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">TOTAL RETUR</p>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($totalRetur) }}</h3>
                <p class="text-xs text-red-600 font-medium">
                    <i class="fas fa-arrow-up"></i> +2 from last week
                </p>
            </div>
            <div class="bg-red-50 p-3 rounded-lg">
                <i class="fas fa-undo text-red-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Purchase Order -->
    <div class="stat-card bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">PURCHASE ORDER</p>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($purchaseOrderCount) }}</h3>
                <p class="text-xs text-gray-600">{{ number_format($pendingPOCount) }} pending approval</p>
            </div>
            <div class="bg-blue-50 p-3 rounded-lg">
                <i class="fas fa-file-invoice text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section Row 1 -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Sales Chart -->
    <div class="lg:col-span-2 bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900" id="chartTitle">Grafik Penjualan Bulanan</h3>
            <select class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm bg-white font-medium text-slate-700 shadow-sm focus:ring-2 focus:ring-blue-500" onchange="fetchSalesChartData(this.value)">
                <option value="perminggu">perminggu</option>
                <option value="perbulan" selected>perbulan</option>
                <option value="pertahun">pertahun</option>
            </select>
        </div>
        <canvas id="salesChart" height="100"></canvas>
    </div>

    <!-- Top Products Progress Bar -->
    <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">Barang Terlaris</h3>
            <a href="{{ route('products.index') }}" title="Lihat Semua Barang" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition inline-block">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </a>
        </div>
        <div class="space-y-4">
            @php
                $maxQty = $topProducts->max('total_qty') ?: 1;
            @endphp
            @forelse($topProducts as $prod)
                @php
                    $percentage = min(100, max(15, round(($prod->total_qty / $maxQty) * 100)));
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700 truncate max-w-[180px]">{{ $prod->product_name }}</span>
                        <span class="text-sm font-bold text-gray-900">{{ number_format($prod->total_qty) }} {{ $prod->unit ?? 'Pcs' }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full progress-bar transition-all duration-500" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-gray-500">
                    <i class="fas fa-inbox text-3xl mb-2 text-gray-300"></i>
                    <p class="text-sm">Belum ada data barang terlaris</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Product Performance Charts (Best Selling vs Least Sold) -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Chart Barang Paling Banyak Terjual -->
    <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                    Grafik Barang Paling Banyak Terjual
                </h3>
                <p class="text-xs text-gray-500 mt-1">5 Barang dengan volume penjualan tertinggi di database</p>
            </div>
            <span class="px-2.5 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-full border border-emerald-200">
                <i class="fas fa-fire me-1"></i>Top Selling
            </span>
        </div>
        <canvas id="topSoldChart" height="140"></canvas>
    </div>

    <!-- Chart Barang Kurang Diminati -->
    <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                    Grafik Barang Kurang Diminati
                </h3>
                <p class="text-xs text-gray-500 mt-1">5 Barang dengan penjualan terendah / lambat bergerak</p>
            </div>
            <span class="px-2.5 py-1 text-xs font-semibold text-amber-700 bg-amber-50 rounded-full border border-amber-200">
                <i class="fas fa-exclamation-triangle me-1"></i>Slow Moving
            </span>
        </div>
        <canvas id="leastSoldChart" height="140"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let salesChart;
    
    function fetchSalesChartData(period = 'perbulan') {
        fetch(`{{ route('dashboard.sales-chart') }}?period=${period}`)
            .then(res => res.json())
            .then(data => {
                const chartTitle = document.getElementById('chartTitle');
                if (chartTitle) {
                    if (period === 'perminggu') chartTitle.textContent = 'Grafik Penjualan Mingguan';
                    else if (period === 'pertahun') chartTitle.textContent = 'Grafik Penjualan Tahunan';
                    else chartTitle.textContent = 'Grafik Penjualan Bulanan';
                }
                
                if (salesChart) {
                    salesChart.data.labels = data.labels;
                    salesChart.data.datasets[0].data = data.data;
                    salesChart.update('active');
                } else {
                    renderChart(data.labels, data.data);
                }
            })
            .catch(err => console.error('Error fetching sales chart:', err));
    }

    function renderChart(labels, datasetData) {
        const ctx = document.getElementById('salesChart')?.getContext('2d');
        if (!ctx) return;

        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.25)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

        salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Penjualan (Juta Rp)',
                    data: datasetData,
                    borderColor: '#3B82F6',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#3B82F6',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#374151',
                        borderWidth: 1,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y + ' Juta';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + 'M';
                            },
                            color: '#9CA3AF',
                            font: { size: 11 }
                        },
                        grid: {
                            color: '#F3F4F6',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            color: '#9CA3AF',
                            font: { size: 11 }
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }

    function renderProductPerformanceCharts() {
        // Top Sold Bar Chart
        const topSoldCtx = document.getElementById('topSoldChart')?.getContext('2d');
        if (topSoldCtx) {
            new Chart(topSoldCtx, {
                type: 'bar',
                data: {
                    labels: @json($topSellingProducts->pluck('product_name')),
                    datasets: [{
                        label: 'Total Terjual (Unit)',
                        data: @json($topSellingProducts->pluck('total_sold')),
                        backgroundColor: 'rgba(16, 185, 129, 0.85)',
                        borderColor: '#059669',
                        borderWidth: 1.5,
                        borderRadius: 6,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, grid: { color: '#F3F4F6' }, ticks: { precision: 0 } },
                        y: { ticks: { color: '#374151', font: { size: 11 } }, grid: { display: false } }
                    }
                }
            });
        }

        // Least Sold Bar Chart
        const leastSoldCtx = document.getElementById('leastSoldChart')?.getContext('2d');
        if (leastSoldCtx) {
            new Chart(leastSoldCtx, {
                type: 'bar',
                data: {
                    labels: @json($leastSellingProducts->pluck('product_name')),
                    datasets: [{
                        label: 'Total Terjual (Unit)',
                        data: @json($leastSellingProducts->pluck('total_sold')),
                        backgroundColor: 'rgba(245, 158, 11, 0.85)',
                        borderColor: '#d97706',
                        borderWidth: 1.5,
                        borderRadius: 6,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, grid: { color: '#F3F4F6' }, ticks: { precision: 0 } },
                        y: { ticks: { color: '#374151', font: { size: 11 } }, grid: { display: false } }
                    }
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        fetchSalesChartData('perbulan');
        renderProductPerformanceCharts();
    });
</script>
@endpush