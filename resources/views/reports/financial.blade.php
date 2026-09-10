@extends('layouts.app')

@section('title', 'Laporan Kinerja Keuangan - PT Nusantara ERP')

@section('content')
<div class="space-y-6 pb-12">
    <!-- Header & Action Controls -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                Monthly Performance Review
                <span class="px-2.5 py-0.5 text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 rounded-full">
                    Real-time
                </span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">Analisis performa finansial, arus kas, dan profitabilitas terintegrasi secara otomatis.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('reports.financial', array_merge(request()->query(), ['export' => 'csv'])) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition shadow-sm flex items-center gap-2">
                <x-icon name="file-csv" class="w-4 h-4" />
                <span>Export Report (CSV)</span>
            </a>
        </div>
    </div>

    <!-- Navigation Tab Switcher -->
    <div class="flex items-center border-b border-gray-200 gap-6">
        <a href="{{ route('reports.financial') }}" class="pb-3 text-sm font-bold text-blue-600 border-b-2 border-blue-600 transition flex items-center gap-2">
            <x-icon name="coins" class="w-4 h-4" />
            <span>Ringkasan Finansial</span>
        </a>
        <a href="{{ route('reports.analytics') }}" class="pb-3 text-sm font-semibold text-gray-500 hover:text-gray-700 border-b-2 border-transparent transition flex items-center gap-2">
            <x-icon name="brain" class="w-4 h-4" />
            <span>Data Science & Prediktif</span>
            <span class="bg-indigo-100 text-indigo-700 text-[10px] font-extrabold px-1.5 py-0.5 rounded-full">PRO</span>
        </a>
    </div>

    <!-- Period Filter Toolbar -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
        <form method="GET" action="{{ route('reports.financial') }}" class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <!-- Quick Preset Pills -->
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-xs font-semibold text-gray-500 uppercase mr-1">Filter Periode:</span>
                <a href="{{ route('reports.financial', ['period' => 'all']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $period == 'all' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Semua Waktu
                </a>
                <a href="{{ route('reports.financial', ['period' => 'this_month']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $period == 'this_month' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Bulan Ini
                </a>
                <a href="{{ route('reports.financial', ['period' => 'last_month']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $period == 'last_month' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Bulan Lalu
                </a>
                <a href="{{ route('reports.financial', ['period' => 'this_year']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $period == 'this_year' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Tahun Ini
                </a>
            </div>

            <!-- Custom Date Range Form -->
            <div class="flex items-center gap-2 flex-wrap">
                <input type="hidden" name="period" value="custom">
                <div class="flex items-center gap-1.5 bg-gray-50 border border-gray-300 rounded-lg px-2.5 py-1 text-xs">
                    <span class="text-gray-500">Dari:</span>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="bg-transparent border-none text-xs focus:ring-0 p-0 text-gray-800">
                </div>
                <div class="flex items-center gap-1.5 bg-gray-50 border border-gray-300 rounded-lg px-2.5 py-1 text-xs">
                    <span class="text-gray-500">Sampai:</span>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="bg-transparent border-none text-xs focus:ring-0 p-0 text-gray-800">
                </div>
                <button type="submit" class="px-3 py-1.5 bg-gray-800 hover:bg-gray-900 text-white rounded-lg text-xs font-medium transition shadow-sm">
                    <x-icon name="filter" class="w-4 h-4 mr-1" /> Terapkan
                </button>
            </div>
        </form>
    </div>

    <!-- 3 Stat KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Net Profit -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                    <x-icon name="chart-line" class="w-4 h-4" />
                </div>
                @if($profitGrowth >= 0)
                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full flex items-center gap-1">
                        <x-icon name="arrow-trend-up" class="w-3.5 h-3.5" /> +{{ number_format($profitGrowth, 1) }}% MoM
                    </span>
                @else
                    <span class="text-xs font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2.5 py-1 rounded-full flex items-center gap-1">
                        <x-icon name="arrow-trend-down" class="w-3.5 h-3.5" /> {{ number_format($profitGrowth, 1) }}% MoM
                    </span>
                @endif
            </div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Net Profit (Laba Bersih)</p>
            <h3 class="text-3xl font-bold {{ $netProfit >= 0 ? 'text-gray-900' : 'text-rose-600' }}">
                Rp {{ number_format($netProfit, 0, ',', '.') }}
            </h3>
            <p class="text-xs text-gray-500 mt-2">
                Total Omzet: <strong class="text-gray-700">Rp {{ number_format($revenue, 0, ',', '.') }}</strong>
            </p>
        </div>

        <!-- Total Expenses -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl">
                    <x-icon name="wallet" class="w-4 h-4" />
                </div>
                @if($expenseGrowth <= 0)
                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full flex items-center gap-1">
                        <x-icon name="arrow-trend-down" class="w-3.5 h-3.5" /> {{ number_format($expenseGrowth, 1) }}% MoM
                    </span>
                @else
                    <span class="text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-full flex items-center gap-1">
                        <x-icon name="arrow-trend-up" class="w-3.5 h-3.5" /> +{{ number_format($expenseGrowth, 1) }}% MoM
                    </span>
                @endif
            </div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Expenses (Pengadaan PO)</p>
            <h3 class="text-3xl font-bold text-gray-900">
                Rp {{ number_format($totalExpenses, 0, ',', '.') }}
            </h3>
            <p class="text-xs text-gray-500 mt-2">
                Beban pengadaan barang dan biaya operasional
            </p>
        </div>

        <!-- Accounts Receivables -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                    <x-icon name="hand-holding-dollar" class="w-4 h-4" />
                </div>
                <span class="text-xs font-bold text-purple-700 bg-purple-50 border border-purple-200 px-2.5 py-1 rounded-full">
                    {{ number_format($arGrowth, 1) }}% dari Omzet
                </span>
            </div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Accounts Receivables (Piutang)</p>
            <h3 class="text-3xl font-bold text-gray-900">
                Rp {{ number_format($accountsReceivable, 0, ',', '.') }}
            </h3>
            <p class="text-xs text-gray-500 mt-2">
                Faktur penjualan berstatus Unpaid/Overdue
            </p>
        </div>
    </div>

    <!-- Charts & Category Distribution Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Cash Flow Trends (2 Columns) -->
        <div class="lg:col-span-2 bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Cash Flow Trends</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Perbandingan arus kas masuk (Penjualan) vs arus kas keluar (Pembelian) 6 bulan terakhir</p>
                </div>
                <div class="flex items-center gap-4 text-xs font-medium">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-blue-600 rounded-full inline-block"></span>
                        <span class="text-gray-700">Inflow (Penjualan)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-rose-500 rounded-full inline-block"></span>
                        <span class="text-gray-700">Outflow (Pembelian)</span>
                    </div>
                </div>
            </div>
            <div class="relative w-full" style="height: 280px;">
                <canvas id="cashFlowChart"></canvas>
            </div>
        </div>

        <!-- Expenses Distribution (1 Column) -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Expenses Distribution</h3>
                <p class="text-xs text-gray-500 mb-6">Alokasi anggaran berdasarkan kategori pengadaan</p>

                <div class="space-y-4">
                    @forelse($expenseDistribution as $expense)
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full {{ $expense['color'] ?? 'bg-blue-600' }}"></span>
                                    {{ $expense['category'] }}
                                </span>
                                <span class="text-sm font-bold text-gray-900">{{ $expense['percentage'] }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                <div class="{{ $expense['color'] ?? 'bg-blue-600' }} h-2 rounded-full transition-all duration-500" style="width: {{ $expense['percentage'] }}%"></div>
                            </div>
                            <p class="text-[11px] text-gray-400 mt-1 text-right">Rp {{ number_format($expense['amount'], 0, ',', '.') }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">Belum ada data distribusi pengeluaran.</p>
                    @endforelse
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 mt-6 text-center">
                <a href="{{ route('sales.invoices.index') }}" class="inline-flex items-center gap-1.5 text-sm text-blue-600 font-semibold hover:text-blue-800 transition">
                    <span>Lihat Rincian Faktur & Jurnal</span>
                    <x-icon name="arrow-right" class="w-3.5 h-3.5" />
                </a>
            </div>
        </div>
    </div>

    <!-- Monthly Financial Summary Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50/50">
            <div>
                <h3 class="text-base font-bold text-gray-900">Monthly Financial Summary</h3>
                <p class="text-xs text-gray-500">Histori pembukuan bulanan (Revenue, Expenses, Net Profit, & Margin)</p>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <x-icon name="search" class="w-3.5 h-3.5" />
                    </span>
                    <input type="text" id="tableSearchInput" onkeyup="filterSummaryTable()" placeholder="Cari bulan..." class="pl-8 pr-3 py-1.5 bg-white border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <select id="tableStatusFilter" onchange="filterSummaryTable()" class="px-3 py-1.5 bg-white border border-gray-300 rounded-lg text-xs font-medium text-gray-700 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="ALL">Semua Status</option>
                    <option value="HEALTHY">HEALTHY</option>
                    <option value="MODERATE">MODERATE</option>
                    <option value="DEFICIT">DEFICIT</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left" id="financialSummaryTable">
                <thead class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase">
                    <tr>
                        <th class="px-6 py-3.5">Bulan (Period)</th>
                        <th class="px-6 py-3.5 text-right">Pendapatan (Revenue)</th>
                        <th class="px-6 py-3.5 text-right">Pengeluaran (Expenses)</th>
                        <th class="px-6 py-3.5 text-right">Laba Bersih (Net Profit)</th>
                        <th class="px-6 py-3.5 text-center">Margin Laba (%)</th>
                        <th class="px-6 py-3.5 text-center">Status Kesehatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($monthlySummary as $summary)
                        <tr class="hover:bg-gray-50 transition summary-row" data-month="{{ strtolower($summary->month) }}" data-status="{{ $summary->status }}">
                            <td class="px-6 py-4 font-bold text-gray-900">
                                <div class="flex items-center gap-2">
                                    <x-icon name="calendar-alt" class="text-gray-400 w-3.5 h-3.5" />
                                    <span>{{ $summary->month }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                Rp {{ number_format($summary->revenue, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right text-gray-700">
                                Rp {{ number_format($summary->expenses, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold {{ $summary->net_profit >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                Rp {{ number_format($summary->net_profit, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center font-semibold text-gray-700">
                                {{ $summary->margin }}%
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 text-xs font-bold rounded-full border {{ $summary->status_badge }}">
                                    {{ $summary->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyTableRow">
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <x-icon name="chart-pie" class="w-4 h-4 text-gray-300 text-3xl mb-2" />
                                <p class="text-sm font-medium">Belum ada riwayat transaksi keuangan pada periode ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('cashFlowChart')?.getContext('2d');
        if (ctx) {
            const labels = @json(array_column($cashFlowData, 'month'));
            const inflowData = @json(array_column($cashFlowData, 'inflow'));
            const outflowData = @json(array_column($cashFlowData, 'outflow'));

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Inflow (Penjualan)',
                            data: inflowData,
                            borderColor: '#2563EB',
                            backgroundColor: 'rgba(37, 99, 235, 0.12)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#2563EB',
                            pointRadius: 4,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Outflow (Pembelian)',
                            data: outflowData,
                            borderColor: '#F43F5E',
                            backgroundColor: 'rgba(244, 63, 94, 0.08)',
                            borderWidth: 2.5,
                            borderDash: [4, 4],
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#F43F5E',
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += 'Rp ' + Number(context.parsed.y).toLocaleString('id-ID');
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#F1F5F9'
                            },
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000).toFixed(0) + ' Jt';
                                    }
                                    return 'Rp ' + Number(value).toLocaleString('id-ID');
                                },
                                font: { size: 11 }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        }
                    }
                }
            });
        }
    });

    // Client-side instant filter for Monthly Financial Summary Table
    function filterSummaryTable() {
        const query = (document.getElementById('tableSearchInput')?.value || '').toLowerCase().trim();
        const statusFilter = document.getElementById('tableStatusFilter')?.value || 'ALL';
        const rows = document.querySelectorAll('.summary-row');

        let visibleCount = 0;
        rows.forEach(row => {
            const monthText = row.getAttribute('data-month') || '';
            const rowStatus = row.getAttribute('data-status') || '';

            const matchQuery = monthText.includes(query);
            const matchStatus = (statusFilter === 'ALL' || rowStatus === statusFilter);

            if (matchQuery && matchStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endpush

