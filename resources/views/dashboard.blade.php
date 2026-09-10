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
                <x-icon name="plus" class="w-4 h-4 text-slate-500" />
                <span>Tambah Barang</span>
            </a>
            <a href="{{ route('sales.invoices.index') }}" class="flex items-center space-x-2 px-4 py-2.5 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition shadow-sm font-medium text-sm text-slate-700">
                <x-icon name="file-invoice" class="w-4 h-4 text-slate-500" />
                <span>Buat Invoice</span>
            </a>
            <a href="{{ route('warehouse.stock-audit.start') }}" class="flex items-center space-x-2 px-4 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition shadow-md shadow-indigo-200 font-medium text-sm">
                <x-icon name="box" class="w-4 h-4" />
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
                    <x-icon name="arrow-up" class="w-4 h-4" /> +2.4% vs last month
                </p>
            </div>
            <div class="bg-blue-50 p-3 rounded-lg">
                <x-icon name="boxes" class="text-blue-600 w-6 h-6" />
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
                    <x-icon name="minus" class="w-4 h-4" /> No change
                </p>
            </div>
            <div class="bg-blue-50 p-3 rounded-lg">
                <x-icon name="truck" class="text-blue-600 w-6 h-6" />
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
                    <x-icon name="arrow-up" class="w-4 h-4" /> +12 this week
                </p>
            </div>
            <div class="bg-blue-50 p-3 rounded-lg">
                <x-icon name="users" class="text-blue-600 w-6 h-6" />
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
                    <x-icon name="arrow-up" class="w-4 h-4" /> +16.3% MTD
                </p>
            </div>
            <div class="bg-blue-50 p-3 rounded-lg">
                <x-icon name="chart-line" class="text-blue-600 w-6 h-6" />
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
                <x-icon name="arrow-down" class="text-green-600 w-6 h-6" />
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
                    <x-icon name="arrow-down" class="w-4 h-4" /> Stock reducing
                </p>
            </div>
            <div class="bg-blue-50 p-3 rounded-lg">
                <x-icon name="arrow-up" class="text-blue-600 w-6 h-6" />
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
                    <x-icon name="arrow-up" class="w-4 h-4" /> +2 from last week
                </p>
            </div>
            <div class="bg-red-50 p-3 rounded-lg">
                <x-icon name="undo" class="text-red-600 w-6 h-6" />
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
                <x-icon name="file-invoice" class="text-blue-600 w-6 h-6" />
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

    <!-- Category Distribution Doughnut Chart -->
    <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-indigo-500 inline-block"></span>
                    Distribusi Kategori Produk
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Proporsi stok fisik per kategori barang</p>
            </div>
            <a href="{{ route('products.index') }}" title="Kelola Master Produk" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition inline-block">
                <x-icon name="layer-group" class="w-4 h-4" />
            </a>
        </div>
        
        <div class="relative flex items-center justify-center my-auto py-2" style="height: 190px;">
            <canvas id="categoryChart"></canvas>
        </div>

        <div class="mt-3 pt-3 border-t border-gray-100 grid grid-cols-2 gap-x-4 gap-y-1.5 text-xs">
            @php
                $colorDots = ['bg-blue-500', 'bg-emerald-500', 'bg-amber-500', 'bg-purple-500', 'bg-pink-500', 'bg-cyan-500'];
            @endphp
            @forelse($categoryDistribution->take(4) as $idx => $cat)
                <div class="flex items-center justify-between">
                    <span class="flex items-center gap-1.5 text-gray-600 truncate max-w-[100px]">
                        <span class="w-2 h-2 rounded-full {{ $colorDots[$idx % count($colorDots)] }} flex-shrink-0"></span>
                        <span class="truncate">{{ $cat->category }}</span>
                    </span>
                    <span class="font-bold text-gray-900 ml-1">{{ number_format($cat->total_stock) }}</span>
                </div>
            @empty
                <div class="col-span-2 text-center text-gray-400 py-2">Belum ada kategori produk</div>
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
                <x-icon name="fire" class="w-4 h-4 me-1" />Top Selling
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
                <x-icon name="exclamation-triangle" class="w-4 h-4 me-1" />Slow Moving
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

    function renderCategoryChart() {
        const categoryCtx = document.getElementById('categoryChart')?.getContext('2d');
        if (!categoryCtx) return;

        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: @json($categoryDistribution->pluck('category')),
                datasets: [{
                    data: @json($categoryDistribution->pluck('total_stock')),
                    backgroundColor: [
                        '#3B82F6', // Blue
                        '#10B981', // Emerald
                        '#F59E0B', // Amber
                        '#8B5CF6', // Purple
                        '#EC4899', // Pink
                        '#06B6D4', // Cyan
                        '#64748B', // Slate
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        padding: 10,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderWidth: 1,
                        borderColor: '#374151',
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed || 0;
                                return ` ${context.label}: ${value.toLocaleString()} Pcs`;
                            }
                        }
                    }
                },
                cutout: '68%'
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
        renderCategoryChart();
        renderProductPerformanceCharts();
    });
</script>
@endpush