@extends('layouts.app')

@section('content')
<div class="space-y-6">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Monthly Performance Review</h1>
                        <p class="text-sm text-gray-500">Real-time financial analytics for the current fiscal period</p>
                    </div>
                    <a href="{{ route('reports.financial', array_merge(request()->query(), ['export' => 'csv'])) }}" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium inline-flex items-center">
                        <i class="fas fa-download mr-2"></i>Export Report
                    </a>
                </div>

                <div class="grid grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-xl p-6 border border-gray-200">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="bg-blue-50 p-3 rounded-lg"><i class="fas fa-chart-line text-blue-600 text-xl"></i></div>
                            <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded">+{{ number_format($profitGrowth, 1) }}%</span>
                        </div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Net Profit</p>
                        <h3 class="text-3xl font-bold text-gray-900">Rp {{ number_format($netProfit, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-white rounded-xl p-6 border border-gray-200">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="bg-red-50 p-3 rounded-lg"><i class="fas fa-wallet text-red-600 text-xl"></i></div>
                            <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded">{{ number_format($expenseGrowth, 1) }}%</span>
                        </div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Total Expenses</p>
                        <h3 class="text-3xl font-bold text-gray-900">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-white rounded-xl p-6 border border-gray-200">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="bg-purple-50 p-3 rounded-lg"><i class="fas fa-hand-holding-usd text-purple-600 text-xl"></i></div>
                            <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded">-9.8%</span>
                        </div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Accounts Receivables</p>
                        <h3 class="text-3xl font-bold text-gray-900">Rp {{ number_format($accountsReceivable, 0, ',', '.') }}</h3>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-6 mb-8">
                    <div class="col-span-2 bg-white rounded-xl p-6 border border-gray-200">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-900">Cash Flow Trends</h3>
                            <div class="flex items-center gap-4 text-xs">
                                <div class="flex items-center gap-2"><span class="w-3 h-3 bg-blue-600 rounded-full"></span><span>Inflow</span></div>
                                <div class="flex items-center gap-2"><span class="w-3 h-3 bg-gray-300 rounded-full"></span><span>Outflow</span></div>
                            </div>
                        </div>
                        <canvas id="cashFlowChart" height="80"></canvas>
                    </div>
                    <div class="bg-white rounded-xl p-6 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Expenses Distribution</h3>
                        <div class="space-y-4">
                            @foreach($expenseDistribution as $expense)
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-700">{{ $expense['category'] }}</span>
                                        <span class="text-sm font-bold text-gray-900">{{ $expense['percentage'] }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $expense['percentage'] }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a href="#" class="block text-center text-sm text-blue-600 font-medium mt-6 hover:underline">View Detailed Ledger</a>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold">Monthly Financial Summary</h3>
                        <div class="flex gap-2">
                            <button class="p-2 hover:bg-gray-100 rounded"><i class="fas fa-filter text-gray-600"></i></button>
                            <button class="p-2 hover:bg-gray-100 rounded"><i class="fas fa-search text-gray-600"></i></button>
                        </div>
                    </div>
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Month(s)</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Revenue</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Expenses</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Net Profit</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Margin</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($monthlySummary as $summary)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $summary->month }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp {{ number_format($summary->revenue, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">Rp {{ number_format($summary->expenses, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp {{ number_format($summary->net_profit, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $summary->margin }}%</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">HEALTHY</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('cashFlowChart')?.getContext('2d');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json(array_column($cashFlowData, 'month')),
                    datasets: [{
                        label: 'Cash Flow',
                        data: @json(array_column($cashFlowData, 'value')),
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + Number(v).toLocaleString('id-ID') } }
                    }
                }
            });
        }
    </script>
@endpush
