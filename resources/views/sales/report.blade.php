@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Sales Performance Report</h1>
            <p class="text-sm text-gray-500">Track and analyze all distribution activities and revenue data</p>
        </div>

        <div class="flex items-center gap-3 flex-wrap relative">
            <!-- Preset Dropdown -->
            <div class="relative inline-block text-left" id="presetDropdownContainer">
                <button type="button" id="presetDropdownBtn" onclick="togglePresetDropdown(event)" class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium text-gray-700 flex items-center gap-2 shadow-sm transition">
                    <x-icon name="calendar" class="w-4 h-4 text-gray-400" />
                    <span>{{ $currentPeriodLabel ?? 'Last 30 Days' }}</span>
                    <x-icon name="chevron-down" class="w-3.5 h-3.5 text-gray-400" />
                </button>
                <div id="presetDropdownMenu" class="hidden absolute right-0 mt-2 w-60 bg-white border border-gray-200 rounded-xl shadow-xl z-50 py-2">
                    <div class="px-3 py-1.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Pilih Rentang Cepat</div>
                    <a href="{{ route('sales.report', ['period' => 'last_30_days']) }}" class="flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition {{ $period === 'last_30_days' ? 'font-bold text-blue-600 bg-blue-50/60' : '' }}">
                        <span>30 Hari Terakhir</span>
                        @if($period === 'last_30_days') <x-icon name="check" class="w-3.5 h-3.5 text-blue-600" /> @endif
                    </a>
                    <a href="{{ route('sales.report', ['period' => 'last_7_days']) }}" class="flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition {{ $period === 'last_7_days' ? 'font-bold text-blue-600 bg-blue-50/60' : '' }}">
                        <span>7 Hari Terakhir</span>
                        @if($period === 'last_7_days') <x-icon name="check" class="w-3.5 h-3.5 text-blue-600" /> @endif
                    </a>
                    <a href="{{ route('sales.report', ['period' => 'today']) }}" class="flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition {{ $period === 'today' ? 'font-bold text-blue-600 bg-blue-50/60' : '' }}">
                        <span>Hari Ini</span>
                        @if($period === 'today') <x-icon name="check" class="w-3.5 h-3.5 text-blue-600" /> @endif
                    </a>
                    <a href="{{ route('sales.report', ['period' => 'this_month']) }}" class="flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition {{ $period === 'this_month' ? 'font-bold text-blue-600 bg-blue-50/60' : '' }}">
                        <span>Bulan Ini</span>
                        @if($period === 'this_month') <x-icon name="check" class="w-3.5 h-3.5 text-blue-600" /> @endif
                    </a>
                    <a href="{{ route('sales.report', ['period' => 'last_month']) }}" class="flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition {{ $period === 'last_month' ? 'font-bold text-blue-600 bg-blue-50/60' : '' }}">
                        <span>Bulan Lalu</span>
                        @if($period === 'last_month') <x-icon name="check" class="w-3.5 h-3.5 text-blue-600" /> @endif
                    </a>
                    <a href="{{ route('sales.report', ['period' => 'this_year']) }}" class="flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition {{ $period === 'this_year' ? 'font-bold text-blue-600 bg-blue-50/60' : '' }}">
                        <span>Tahun Ini ({{ now()->year }})</span>
                        @if($period === 'this_year') <x-icon name="check" class="w-3.5 h-3.5 text-blue-600" /> @endif
                    </a>
                    <div class="border-t border-gray-100 my-1"></div>
                    <a href="{{ route('sales.report', ['period' => 'all']) }}" class="flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition {{ $period === 'all' ? 'font-bold text-blue-600 bg-blue-50/60' : '' }}">
                        <span>Semua Waktu (All Time)</span>
                        @if($period === 'all') <x-icon name="check" class="w-3.5 h-3.5 text-blue-600" /> @endif
                    </a>
                </div>
            </div>

            <!-- Custom Range Button & Popover -->
            <div class="relative inline-block text-left" id="customRangeContainer">
                <button type="button" id="customRangeBtn" onclick="toggleCustomRangePopover(event)" class="px-4 py-2 {{ $period === 'custom' ? 'bg-blue-50 border-blue-500 text-blue-700 font-semibold ring-2 ring-blue-500/20 shadow-sm' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50 hover:border-gray-400' }} border rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition duration-150">
                    <x-icon name="sliders-h" class="w-4 h-4 {{ $period === 'custom' ? 'text-blue-600' : 'text-gray-400' }}" />
                    <span>{{ $period === 'custom' ? 'Custom: ' . \Carbon\Carbon::parse($startDate)->format('d M') . ' - ' . \Carbon\Carbon::parse($endDate)->format('d M') : 'Custom Range' }}</span>
                    <x-icon name="chevron-down" class="w-3.5 h-3.5 {{ $period === 'custom' ? 'text-blue-500' : 'text-gray-400' }}" />
                </button>

                <!-- Custom Range Popover Card -->
                <div id="customRangePopover" class="hidden absolute right-0 mt-3 w-96 bg-white border border-gray-200/90 rounded-2xl shadow-2xl z-50 p-5 backdrop-blur-sm transition-all duration-200">
                    <!-- Popover Header -->
                    <div class="flex items-center justify-between pb-3.5 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center shadow-md shadow-blue-500/20 text-sm">
                                <x-icon name="calendar-alt" class="w-4 h-4" />
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 tracking-tight">Rentang Tanggal Khusus</h4>
                                <p class="text-[11px] text-gray-500">Pilih periode awal dan akhir penjualan</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeCustomRangePopover()" class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 flex items-center justify-center transition" title="Tutup">
                            <x-icon name="times" class="w-3.5 h-3.5" />
                        </button>
                    </div>

                    <form method="GET" action="{{ route('sales.report') }}" id="customRangeForm" class="space-y-4 pt-3.5">
                        <input type="hidden" name="period" value="custom">

                        <!-- Side by Side Date Inputs -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1.5 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span> Dari Tanggal:
                                </label>
                                <input type="date" name="start_date" id="customStartDate" onchange="updateRangeSummary()" value="{{ $startDate }}" class="w-full px-3 py-2 bg-gray-50/80 hover:bg-gray-50 focus:bg-white border border-gray-200 focus:border-blue-500 rounded-xl text-xs font-semibold text-gray-900 focus:ring-4 focus:ring-blue-500/15 transition shadow-sm" required>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1.5 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span> Sampai Tanggal:
                                </label>
                                <input type="date" name="end_date" id="customEndDate" onchange="updateRangeSummary()" value="{{ $endDate }}" class="w-full px-3 py-2 bg-gray-50/80 hover:bg-gray-50 focus:bg-white border border-gray-200 focus:border-blue-500 rounded-xl text-xs font-semibold text-gray-900 focus:ring-4 focus:ring-blue-500/15 transition shadow-sm" required>
                            </div>
                        </div>

                        <!-- Live Duration Summary Banner -->
                        <div class="flex items-center justify-between px-3.5 py-2.5 bg-gradient-to-r from-blue-50/90 to-indigo-50/70 border border-blue-100/90 rounded-xl">
                            <div class="flex items-center gap-2">
                                <x-icon name="clock-rotate-left" class="text-blue-600 w-3.5 h-3.5" />
                                <span class="text-xs font-medium text-gray-600">Durasi Periode:</span>
                            </div>
                            <span id="rangeDaysCount" class="text-xs font-bold px-2.5 py-0.5 bg-blue-600 text-white rounded-full shadow-sm">30 Hari</span>
                        </div>

                        <!-- Quick Shortcuts Section -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Shortcut Cepat</span>
                                <span class="text-[10px] text-gray-400">Klik untuk isi otomatis</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" onclick="setQuickRange('this_month')" class="px-3 py-2 text-xs font-semibold rounded-xl border border-gray-200 bg-white hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 text-gray-700 transition duration-150 flex items-center gap-2 shadow-sm">
                                    <x-icon name="calendar" class="text-blue-500 w-3.5 h-3.5" />
                                    <span>Bulan Ini</span>
                                </button>
                                <button type="button" onclick="setQuickRange('last_30')" class="px-3 py-2 text-xs font-semibold rounded-xl border border-gray-200 bg-white hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 text-gray-700 transition duration-150 flex items-center gap-2 shadow-sm">
                                    <x-icon name="history" class="text-indigo-500 w-3.5 h-3.5" />
                                    <span>30 Hari Terakhir</span>
                                </button>
                                <button type="button" onclick="setQuickRange('oct_2026')" class="px-3 py-2 text-xs font-semibold rounded-xl border border-indigo-200 bg-indigo-50/50 hover:bg-indigo-100 hover:border-indigo-300 text-indigo-700 transition duration-150 flex items-center gap-2 shadow-sm">
                                    <x-icon name="database" class="text-indigo-600 w-3.5 h-3.5" />
                                    <span>Oktober 2026</span>
                                </button>
                                <button type="button" onclick="setQuickRange('this_year')" class="px-3 py-2 text-xs font-semibold rounded-xl border border-gray-200 bg-white hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 text-gray-700 transition duration-150 flex items-center gap-2 shadow-sm">
                                    <x-icon name="calendar-check" class="text-emerald-500 w-3.5 h-3.5" />
                                    <span>Tahun {{ now()->year }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- Footer Action Buttons -->
                        <div class="flex items-center gap-2.5 pt-3 border-t border-gray-100">
                            <button type="button" onclick="closeCustomRangePopover()" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition duration-150">
                                Batal
                            </button>
                            <button type="submit" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-500/25 hover:shadow-blue-500/40 transition duration-150 flex items-center justify-center gap-2">
                                <x-icon name="check" class="w-4 h-4" />
                                <span>Terapkan Filter</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Export Report Link -->
            <a href="{{ route('sales.report', array_merge(request()->query(), ['export' => 'csv'])) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium inline-flex items-center shadow-sm transition">
                <x-icon name="download" class="w-4 h-4 mr-2" />Export Report
            </a>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Sales -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-blue-50 p-3 rounded-lg">
                    <x-icon name="chart-line" class="text-blue-600 w-8 h-8" />
                </div>
                @if($growthPercentage > 0)
                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full flex items-center gap-1">
                        <x-icon name="arrow-trend-up" class="w-3.5 h-3.5" /> +{{ number_format($growthPercentage, 1) }}%
                    </span>
                @elseif($growthPercentage < 0)
                    <span class="text-xs font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2.5 py-1 rounded-full flex items-center gap-1">
                        <x-icon name="arrow-trend-down" class="w-3.5 h-3.5" /> {{ number_format($growthPercentage, 1) }}%
                    </span>
                @else
                    <span class="text-xs font-semibold text-gray-600 bg-gray-100 border border-gray-200 px-2.5 py-1 rounded-full">
                        0.0%
                    </span>
                @endif
            </div>
            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Total Sales</p>
            <h3 class="text-3xl font-bold text-gray-900">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
            <p class="text-xs text-gray-600 mt-1">Total revenue in this period</p>
        </div>

        <!-- Top Category -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-indigo-50 p-3 rounded-lg">
                    <x-icon name="book" class="text-indigo-600 w-8 h-8" />
                </div>
                <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Category Lead</span>
            </div>
            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Top Performing Category</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $topCategory['name'] }}</h3>
            <p class="text-xs text-gray-600 mt-1">{{ $topCategory['percentage'] }}% of volume in period</p>
        </div>

        <!-- Avg Margin -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-green-50 p-3 rounded-lg">
                    <x-icon name="receipt" class="text-green-600 w-8 h-8" />
                </div>
                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">
                    Avg Order
                </span>
            </div>
            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Total Orders</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $dailySales->sum('total_orders') }}</h3>
            <p class="text-xs text-gray-600 mt-1">Total invoice orders this period</p>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Monthly Sales Distribution</h3>
                <p class="text-xs text-gray-500 mt-0.5">Revenue trends across months (Fiscal Year {{ $fiscalYear ?? now()->year }})</p>
            </div>
            <div class="flex items-center gap-4 text-xs">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-blue-600 rounded-full"></span>
                    <span class="text-gray-600">Revenue (Rp)</span>
                </div>
            </div>
        </div>
        <canvas id="salesChart" height="80"></canvas>
    </div>

    <!-- Daily Sales Log Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-visible">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900">DAILY SALES LOG</h3>
            <button class="text-gray-400 hover:text-gray-600">
                <x-icon name="ellipsis-h" class="w-4 h-4" />
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
                            <x-icon name="inbox" class="w-4 h-4 text-4xl mb-3 text-gray-300" />
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
        // Preset Dropdown Handler
        function togglePresetDropdown(e) {
            if (e) e.stopPropagation();
            const menu = document.getElementById('presetDropdownMenu');
            const customPopover = document.getElementById('customRangePopover');
            if (customPopover) customPopover.classList.add('hidden');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }

        // Live Range Duration Calculator
        function updateRangeSummary() {
            const startInput = document.getElementById('customStartDate');
            const endInput = document.getElementById('customEndDate');
            const badge = document.getElementById('rangeDaysCount');
            if (!badge || !startInput || !endInput || !startInput.value || !endInput.value) return;

            const s = new Date(startInput.value);
            const e = new Date(endInput.value);
            if (isNaN(s.getTime()) || isNaN(e.getTime())) return;

            const diffTime = Math.abs(e - s);
            const diffDays = Math.round(diffTime / (1000 * 60 * 60 * 24)) + 1;
            badge.innerText = diffDays + ' Hari';
        }

        // Custom Range Popover Handler
        function toggleCustomRangePopover(e) {
            if (e) e.stopPropagation();
            const popover = document.getElementById('customRangePopover');
            const presetMenu = document.getElementById('presetDropdownMenu');
            if (presetMenu) presetMenu.classList.add('hidden');
            if (popover) {
                popover.classList.toggle('hidden');
                if (!popover.classList.contains('hidden')) {
                    updateRangeSummary();
                }
            }
        }

        function closeCustomRangePopover() {
            const popover = document.getElementById('customRangePopover');
            if (popover) popover.classList.add('hidden');
        }

        // Quick range helper for custom range form
        function setQuickRange(type) {
            const today = new Date();
            const formatDate = (d) => {
                const year = d.getFullYear();
                const month = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };

            let start = new Date();
            let end = new Date();

            if (type === 'this_month') {
                start = new Date(today.getFullYear(), today.getMonth(), 1);
                end = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            } else if (type === 'last_30') {
                start = new Date();
                start.setDate(today.getDate() - 29);
                end = new Date();
            } else if (type === 'oct_2026') {
                start = new Date('2026-10-01');
                end = new Date('2026-10-31');
            } else if (type === 'this_year') {
                start = new Date(today.getFullYear(), 0, 1);
                end = new Date(today.getFullYear(), 11, 31);
            }

            const startInput = document.getElementById('customStartDate');
            const endInput = document.getElementById('customEndDate');
            if (startInput) startInput.value = formatDate(start);
            if (endInput) endInput.value = formatDate(end);
            updateRangeSummary();
        }

        document.addEventListener('DOMContentLoaded', updateRangeSummary);

        // Global click listener to close popovers on click outside
        window.addEventListener('click', function(e) {
            const presetContainer = document.getElementById('presetDropdownContainer');
            const presetMenu = document.getElementById('presetDropdownMenu');
            if (presetContainer && !presetContainer.contains(e.target) && presetMenu) {
                presetMenu.classList.add('hidden');
            }

            const customContainer = document.getElementById('customRangeContainer');
            const customPopover = document.getElementById('customRangePopover');
            if (customContainer && !customContainer.contains(e.target) && customPopover) {
                customPopover.classList.add('hidden');
            }

            // Close action popups
            document.querySelectorAll('.action-menu-container').forEach(function(c) {
                if (!c.contains(e.target)) {
                    const p = c.querySelector('.action-menu-popup');
                    if (p) p.style.display = 'none';
                }
            });
        });

        // Action Menu Toggle
        function toggleActionMenu(button, event) {
            if (event) event.stopPropagation();
            const container = button.closest('.action-menu-container');
            if (!container) return;
            const popup = container.querySelector('.action-menu-popup');
            if (!popup) return;

            // Close all others
            document.querySelectorAll('.action-menu-popup').forEach(function(p) {
                if (p !== popup) p.style.display = 'none';
            });

            popup.style.display = popup.style.display === 'none' || !popup.style.display ? 'flex' : 'none';
        }

        // Initialize Sales Chart
        const ctx = document.getElementById('salesChart')?.getContext('2d');
        if (ctx) {
            const chartData = @json($monthlyChartData ?? []);
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [
                        {
                            label: 'Revenue {{ $fiscalYear ?? now()->year }}',
                            data: chartData,
                            borderColor: '#2563EB',
                            backgroundColor: 'rgba(37, 99, 235, 0.08)',
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#2563EB',
                            pointBorderColor: '#FFFFFF',
                            pointHoverRadius: 6,
                            pointRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Revenue: Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return (value / 1000000).toFixed(0) + ' Jt';
                                    }
                                    return value;
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
@endpush
