@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Sales Performance Report</h1>
            <p class="text-sm text-gray-500">Track and analyze all distribution activities and revenue data</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">
                Last 30 Days
            </button>
            <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">
                Custom Range
            </button>
            <a href="{{ route('sales.report', array_merge(request()->query(), ['export' => 'csv'])) }}" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium inline-flex items-center">
                <i class="fas fa-download mr-2"></i>Export Report
            </a>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="grid grid-cols-3 gap-6 mb-8">
        <!-- Total Sales -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-blue-50 p-3 rounded-lg">
                    <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
                </div>
                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded">
                    +{{ number_format($growthPercentage, 1) }}%
                </span>
            </div>
            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Total Sales</p>
            <h3 class="text-3xl font-bold text-gray-900">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
            <p class="text-xs text-gray-600 mt-1">Total revenue this period</p>
        </div>

        <!-- Top Category -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-indigo-50 p-3 rounded-lg">
                    <i class="fas fa-book text-indigo-600 text-2xl"></i>
                </div>
                <a href="#" class="text-xs font-semibold text-blue-600 hover:underline">View Trends</a>
            </div>
            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Top Performing Category</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $topCategory['name'] }}</h3>
            <p class="text-xs text-gray-600 mt-1">{{ $topCategory['percentage'] }}% of total volume shares</p>
        </div>

        <!-- Avg Margin -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-green-50 p-3 rounded-lg">
                    <i class="fas fa-percentage text-green-600 text-2xl"></i>
                </div>
                <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded">
                    -2.3%
                </span>
            </div>
            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Avg Profit Margin</p>
            <h3 class="text-3xl font-bold text-gray-900">8.2%</h3>
            <p class="text-xs text-gray-600 mt-1">Down by 2.3% vs last 30 days</p>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">Monthly Sales Distribution</h3>
            <div class="flex items-center gap-4 text-xs">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-blue-600 rounded-full"></span>
                    <span class="text-gray-600">Revenue</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-gray-300 rounded-full"></span>
                    <span class="text-gray-600">Volume</span>
                </div>
            </div>
        </div>
        <p class="text-sm text-gray-500 mb-6">Revenue and Order Volume compared across months (Fiscal Year 2026)</p>
        <canvas id="salesChart" height="80"></canvas>
    </div>

    <!-- Daily Sales Log Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-visible">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900">DAILY SALES LOG</h3>
            <button class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-ellipsis-h"></i>
            </button>
        </div>
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Total Orders</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Gross Revenue</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Average Order Value</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($dailySales as $sale)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($sale->sale_date)->format('M d, Y') }}
                            <span class="text-xs text-gray-500 block">
                                ({{ \Carbon\Carbon::parse($sale->sale_date)->format('l') }})
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $sale->total_orders }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                            Rp {{ number_format($sale->gross_revenue, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            Rp {{ number_format($sale->avg_order_value, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusClass = 'bg-green-100 text-green-700';
                                if($sale->status_label == 'Unpaid') $statusClass = 'bg-yellow-100 text-yellow-700';
                                if($sale->status_label == 'Overdue') $statusClass = 'bg-red-100 text-red-700';
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold {{ $statusClass }} rounded-full">
                                {{ $sale->status_label ?? 'Completed' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="relative inline-block text-left action-menu-container">
                                <button type="button" onclick="toggleActionMenu(this, event)" class="p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition border border-gray-200 shadow-sm" title="Aksi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>
                                <div class="action-menu-popup absolute right-0 top-full mt-1 z-50 bg-white border border-gray-200 rounded-xl shadow-xl p-1.5 items-center gap-1 min-w-max" style="display: none;">
                                    <a href="{{ route('sales.invoices.index') }}" title="Detail Penjualan" class="p-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                            <p>No sales data available</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart')?.getContext('2d');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [
                        {
                            label: 'Revenue 2026',
                            data: [42000000, 51000000, 48000000, 63000000, 59000000, 72000000, 68000000, 85000000, 78000000, 92000000, 88000000, 105000000],
                            borderColor: '#2563EB',
                            backgroundColor: 'rgba(37, 99, 235, 0.05)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
    </script>
@endpush
