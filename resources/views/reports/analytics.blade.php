@extends('layouts.app')

@section('title', 'Data Science & Advanced Analytics - PT Nusantara ERP')

@section('content')
<div class="space-y-6 pb-12">
    <!-- Header & Navigation Tabs -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-sm shadow-sm">
                    <x-icon name="brain" class="w-4 h-4" />
                </span>
                <h1 class="text-2xl font-bold text-gray-900">Data Science & Predictive Analytics</h1>
                <span class="px-2.5 py-0.5 text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-full">
                    AI Insights
                </span>
            </div>
            <p class="text-sm text-gray-500">Pemodelan statistik prediktif, peramalan omset, segmentasi RFM pelanggan, dan analisis ABC Pareto 80/20.</p>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium text-gray-700 flex items-center gap-2 shadow-sm">
                <x-icon name="print" class="w-4 h-4 text-gray-500" />
                <span>Cetak Laporan</span>
            </button>
        </div>
    </div>

    <!-- Navigation Tab Switcher -->
    <div class="flex items-center border-b border-gray-200 gap-6">
        <a href="{{ route('reports.financial') }}" class="pb-3 text-sm font-semibold text-gray-500 hover:text-gray-700 border-b-2 border-transparent transition flex items-center gap-2">
            <x-icon name="coins" class="w-4 h-4" />
            <span>Ringkasan Finansial</span>
        </a>
        <a href="{{ route('reports.analytics') }}" class="pb-3 text-sm font-bold text-indigo-600 border-b-2 border-indigo-600 transition flex items-center gap-2">
            <x-icon name="chart-network" class="w-4 h-4" />
            <span>Data Science & Prediktif</span>
            <span class="bg-indigo-100 text-indigo-700 text-[10px] font-extrabold px-1.5 py-0.5 rounded-full">PRO</span>
        </a>
    </div>

    <!-- Top Statistical KPI Indicators -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Next Month Forecast -->
        <div class="bg-white rounded-2xl p-5 border border-indigo-100 shadow-sm relative overflow-hidden bg-gradient-to-br from-white to-indigo-50/30">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Prediksi Omset Depan</span>
                <span class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm">
                    <x-icon name="wand-magic-sparkles" class="w-4 h-4" />
                </span>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-900">
                Rp {{ number_format($nextMonthPredicted, 0, ',', '.') }}
            </h3>
            <div class="flex items-center gap-1.5 mt-2">
                @if($forecastGrowthPct >= 0)
                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full flex items-center gap-1">
                        <x-icon name="arrow-trend-up" class="w-3.5 h-3.5" /> +{{ $forecastGrowthPct }}%
                    </span>
                @else
                    <span class="text-xs font-bold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-full flex items-center gap-1">
                        <x-icon name="arrow-trend-down" class="w-3.5 h-3.5" /> {{ $forecastGrowthPct }}%
                    </span>
                @endif
                <span class="text-[11px] text-gray-500">Estimasi linear regression OLS</span>
            </div>
        </div>

        <!-- Average Order Value (AOV) -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Rata-Rata Order (AOV)</span>
                <span class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                    <x-icon name="receipt" class="w-4 h-4" />
                </span>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-900">
                Rp {{ number_format($aov, 0, ',', '.') }}
            </h3>
            <p class="text-[11px] text-gray-500 mt-2">
                Nilai Median: <strong class="text-gray-700 font-semibold">Rp {{ number_format($median, 0, ',', '.') }}</strong>
            </p>
        </div>

        <!-- Demand Volatility (CV) -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Stabilitas Permintaan</span>
                <span class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                    <x-icon name="gauge-high" class="w-4 h-4" />
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <h3 class="text-2xl font-extrabold text-gray-900">CV {{ $cv }}</h3>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full border {{ $volatilityBadge }}">
                    {{ $volatilityStatus }}
                </span>
            </div>
            <p class="text-[11px] text-gray-500 mt-2">
                Std. Deviasi: <strong class="text-gray-700">Rp {{ number_format($stdDev, 0, ',', '.') }}</strong>
            </p>
        </div>

        <!-- Class A Core Products -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Produk Inti (Kelas A)</span>
                <span class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                    <x-icon name="star" class="w-4 h-4" />
                </span>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-900">
                {{ $classACount }} <span class="text-sm font-semibold text-gray-500">SKU</span>
            </h3>
            <p class="text-[11px] text-gray-500 mt-2">
                Menghasilkan <strong class="text-indigo-600">Rp {{ number_format($classARev, 0, ',', '.') }}</strong> (80% omset)
            </p>
        </div>
    </div>

    <!-- Section 1: Predictive Sales Forecasting (OLS Linear Regression Model) -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <x-icon name="chart-line" class="w-4 h-4 text-indigo-600" />
                    Peramalan Penjualan & Proyeksi Tren (Predictive Sales Forecasting)
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Model regresi linier OLS (*Ordinary Least Squares*) memproyeksikan penjualan 3 bulan ke depan beserta rentang estimasi 95% *Confidence Interval*.</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center gap-1.5 text-xs text-gray-600 bg-gray-50 border px-2.5 py-1 rounded-lg">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span> Realisasi Aktual
                </span>
                <span class="inline-flex items-center gap-1.5 text-xs text-indigo-700 bg-indigo-50 border border-indigo-200 px-2.5 py-1 rounded-lg">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span> Proyeksi Garis Tren
                </span>
                <span class="inline-flex items-center gap-1.5 text-xs text-purple-700 bg-purple-50 border border-purple-200 px-2.5 py-1 rounded-lg">
                    <span class="w-2.5 h-2.5 rounded-full bg-purple-400"></span> 95% Confidence Band
                </span>
            </div>
        </div>

        <div class="relative" style="height: 330px;">
            <canvas id="forecastChart"></canvas>
        </div>

        <!-- AI Narrative Insight Banner -->
        <div class="mt-5 p-4 rounded-xl bg-gradient-to-r from-indigo-50 via-blue-50 to-white border border-indigo-100 flex items-start gap-3.5">
            <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex-shrink-0 flex items-center justify-center text-sm mt-0.5">
                <x-icon name="robot" class="w-4 h-4" />
            </div>
            <div class="text-xs space-y-1">
                <div class="font-bold text-gray-900">Insight Analitik Cerdas:</div>
                <p class="text-gray-600 leading-relaxed">
                    Tren penjualan menunjukkan koefisien kemiringan (*slope*) sebesar <strong class="text-indigo-700">{{ $slope >= 0 ? '+' : '' }}Rp {{ number_format($slope, 0, ',', '.') }}/bulan</strong>. 
                    Bulan depan diperkirakan mencapai <strong class="text-gray-900">Rp {{ number_format($nextMonthPredicted, 0, ',', '.') }}</strong> dengan batas optimis hingga <strong class="text-indigo-600">Rp {{ number_format(end($forecastUpperSeries), 0, ',', '.') }}</strong>. Disarankan mempersiapkan persediaan stok 10-15% lebih awal untuk produk kategori favorit.
                </p>
            </div>
        </div>
    </div>

    <!-- Section 2: ABC Pareto Analysis (80/20 Rule) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Pareto Dual-Axis Chart -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                <div>
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <x-icon name="filter-circle-dollar" class="w-4 h-4 text-emerald-600" />
                        Klasifikasi Persediaan ABC (Prinsip Pareto 80/20)
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Memisahkan produk vital (80% omset) dari produk pelengkap untuk optimasi gudang.</p>
                </div>
            </div>

            <div class="relative" style="height: 290px;">
                <canvas id="paretoChart"></canvas>
            </div>
        </div>

        <!-- Pareto Summary & Recommendations -->
        <div class="lg:col-span-4 bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex flex-col justify-between">
            <div>
                <h4 class="text-sm font-bold text-gray-900 mb-3 flex items-center justify-between">
                    <span>Distribusi Kontribusi SKU</span>
                    <x-icon name="chart-pie" class="w-4 h-4 text-gray-400" />
                </h4>

                <div class="space-y-3">
                    <!-- Kelas A -->
                    <div class="p-3.5 rounded-xl border border-blue-200 bg-blue-50/50">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-bold text-blue-800 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span> Kelas A (Vital - 80% Omset)
                            </span>
                            <span class="text-xs font-extrabold text-blue-700">{{ $classACount }} SKU</span>
                        </div>
                        <div class="text-sm font-bold text-gray-900">Rp {{ number_format($classARev, 0, ',', '.') }}</div>
                        <p class="text-[10px] text-gray-600 mt-1">Wajib dipantau setiap hari, jangan sampai *out-of-stock*.</p>
                    </div>

                    <!-- Kelas B -->
                    <div class="p-3.5 rounded-xl border border-amber-200 bg-amber-50/50">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-bold text-amber-800 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Kelas B (Moderat - 15% Omset)
                            </span>
                            <span class="text-xs font-extrabold text-amber-700">{{ $classBCount }} SKU</span>
                        </div>
                        <div class="text-sm font-bold text-gray-900">Rp {{ number_format($classBRev, 0, ',', '.') }}</div>
                        <p class="text-[10px] text-gray-600 mt-1">Review berkala mingguan, stok *buffer* standar.</p>
                    </div>

                    <!-- Kelas C -->
                    <div class="p-3.5 rounded-xl border border-gray-200 bg-gray-50/70">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-bold text-gray-700 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span> Kelas C (Slow Moving - 5% Omset)
                            </span>
                            <span class="text-xs font-extrabold text-gray-600">{{ $classCCount }} SKU</span>
                        </div>
                        <div class="text-sm font-bold text-gray-900">Rp {{ number_format($classCRev, 0, ',', '.') }}</div>
                        <p class="text-[10px] text-gray-600 mt-1">Evaluasi bundle/diskon cuci gudang agar modal tidak macet.</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t text-[11px] text-gray-500 flex items-center justify-between">
                <span>Total Evaluasi: <strong>{{ count($productRevenues) }} SKU</strong></span>
                <span class="font-semibold text-indigo-600">Efisiensi Stok 80/20</span>
            </div>
        </div>
    </div>

    <!-- Section 3: Customer RFM Segmentation & Behavioral Matrix -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- RFM Customer Segmentation -->
        <div class="lg:col-span-5 bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <x-icon name="users-viewfinder" class="w-4 h-4 text-blue-600" />
                        Segmentasi Pelanggan RFM
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Recency, Frequency, & Monetary Clustering</p>
                </div>
            </div>

            <div class="relative" style="height: 220px;">
                <canvas id="rfmChart"></canvas>
            </div>

            <!-- RFM Legend & Counts -->
            <div class="grid grid-cols-2 gap-2 mt-4 pt-3 border-t">
                @foreach($rfmClusters as $segName => $info)
                <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50 border text-xs">
                    <span class="flex items-center gap-1.5 font-medium text-gray-700">
                        <span class="w-2 h-2 rounded-full" style="background-color: {{ $info['color'] }}"></span>
                        {{ $segName }}
                    </span>
                    <span class="font-bold text-gray-900">{{ $info['count'] }} org</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Heatmap: Waktu Transaksi Puncak -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex flex-col justify-between">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <x-icon name="calendar-week" class="w-4 h-4 text-amber-600" />
                            Matriks Waktu Puncak (Purchasing Heatmap)
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Kepadatan volume transaksi berdasarkan hari & rentang jam operasional.</p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200">
                        Puncak: <strong>{{ $peakDay }}</strong> - {{ $peakSlot }}
                    </span>
                </div>

                <!-- Matrix Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-center border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 border-b">
                                <th class="py-2.5 px-3 text-left font-semibold">Hari</th>
                                @foreach($timeSlots as $slot)
                                    <th class="py-2.5 px-2 font-semibold">{{ $slot }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($heatmapMatrix as $dayData)
                            <tr class="hover:bg-gray-50/80">
                                <td class="py-2 px-3 text-left font-semibold text-gray-800">{{ $dayData['name'] }}</td>
                                @foreach($dayData['slots'] as $count)
                                    @php
                                        if ($count >= 3) {
                                            $bg = 'bg-indigo-600 text-white font-bold';
                                        } elseif ($count == 2) {
                                            $bg = 'bg-indigo-400 text-white font-semibold';
                                        } elseif ($count == 1) {
                                            $bg = 'bg-indigo-100 text-indigo-800 font-semibold';
                                        } else {
                                            $bg = 'bg-gray-50 text-gray-300';
                                        }
                                    @endphp
                                    <td class="py-2 px-2">
                                        <span class="inline-block w-8 py-1 rounded {{ $bg }}">
                                            {{ $count }}
                                        </span>
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3 text-[11px] text-gray-500 flex items-center justify-between">
                <span><x-icon name="circle" class="w-4 h-4 text-indigo-600 text-[9px] me-1" />Warna pekat menandakan jam pesanan paling sibuk.</span>
                <span>Optimasi shift kasir & staf gudang</span>
            </div>
        </div>
    </div>

    <!-- Section 4: Prescriptive Business Action Table (AI Targeted Customer Interventions) -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <x-icon name="bullseye-pointer" class="w-4 h-4 text-indigo-600" />
                    Rekomendasi Tindakan Bisnis Nyata (Prescriptive Targeted Actions)
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Saran aksi terarah untuk pelanggan prioritas (*Champions*) dan pelanggan berisiko lepas (*At-Risk*).</p>
            </div>
            <span class="text-xs bg-gray-100 text-gray-600 px-3 py-1 rounded-full font-medium">
                Total {{ count($customerDetails) }} Pelanggan Terklaster
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-600 uppercase font-semibold border-b">
                    <tr>
                        <th class="px-6 py-3.5">Pelanggan</th>
                        <th class="px-6 py-3.5">Kota</th>
                        <th class="px-6 py-3.5">Segmen RFM</th>
                        <th class="px-6 py-3.5 text-center">Terakhir Transaksi</th>
                        <th class="px-6 py-3.5 text-right">Total Belanja</th>
                        <th class="px-6 py-3.5">Rekomendasi Aksi AI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse(array_slice($customerDetails, 0, 8) as $cust)
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="px-6 py-3.5 font-bold text-gray-900 flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-blue-50 text-blue-600 font-bold flex items-center justify-center text-xs">
                                {{ strtoupper(substr($cust['name'], 0, 1)) }}
                            </span>
                            <div>
                                <div>{{ $cust['name'] }}</div>
                                <span class="text-[10px] font-normal text-gray-400">{{ $cust['phone'] }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3.5 text-gray-600 font-medium">{{ $cust['city'] }}</td>
                        <td class="px-6 py-3.5">
                            @php
                                $badgeClass = match($cust['segment']) {
                                    'Champions' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'Loyal' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'Potential' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'At-Risk' => 'bg-rose-100 text-rose-700 border-rose-200 font-bold',
                                    default => 'bg-gray-100 text-gray-600 border-gray-200',
                                };
                            @endphp
                            <span class="px-2.5 py-1 rounded-full border text-[11px] {{ $badgeClass }}">
                                {{ $cust['segment'] }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-center font-medium {{ $cust['recency'] > 45 ? 'text-rose-600 font-bold' : 'text-gray-700' }}">
                            {{ $cust['recency'] }} hari lalu
                        </td>
                        <td class="px-6 py-3.5 text-right font-bold text-gray-900">
                            Rp {{ number_format($cust['monetary'], 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-700 font-medium">
                                <x-icon name="lightbulb" class="text-amber-500 w-3.5 h-3.5" />
                                {{ $cust['action'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-400">Belum ada data pelanggan untuk dianalisis.</td>
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
    // 1. Predictive Forecasting Line Chart
    const forecastCtx = document.getElementById('forecastChart')?.getContext('2d');
    if (forecastCtx) {
        new Chart(forecastCtx, {
            type: 'line',
            data: {
                labels: @json($forecastChartLabels),
                datasets: [
                    {
                        label: 'Penjualan Aktual (Rp)',
                        data: @json($actualSeries),
                        borderColor: '#2563EB',
                        backgroundColor: '#2563EB',
                        borderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        tension: 0.2
                    },
                    {
                        label: 'Garis Tren / Prediksi (OLS)',
                        data: @json($trendlineSeries),
                        borderColor: '#6366F1',
                        borderWidth: 2.5,
                        borderDash: [5, 5],
                        pointRadius: 4,
                        fill: false,
                        tension: 0.2
                    },
                    {
                        label: '95% Confidence Upper Band',
                        data: @json($forecastUpperSeries),
                        borderColor: 'rgba(168, 85, 247, 0.3)',
                        backgroundColor: 'rgba(168, 85, 247, 0.08)',
                        borderWidth: 1,
                        pointRadius: 0,
                        fill: '+1',
                        tension: 0.2
                    },
                    {
                        label: '95% Confidence Lower Band',
                        data: @json($forecastLowerSeries),
                        borderColor: 'rgba(168, 85, 247, 0.3)',
                        borderWidth: 1,
                        pointRadius: 0,
                        fill: false,
                        tension: 0.2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.parsed.y === null) return null;
                                let val = 'Rp ' + Number(context.parsed.y).toLocaleString('id-ID');
                                return `${context.dataset.label}: ${val}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F3F4F6' },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + ' Jt';
                                return 'Rp ' + Number(value).toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 2. ABC Pareto Dual-Axis Chart
    const paretoCtx = document.getElementById('paretoChart')?.getContext('2d');
    if (paretoCtx) {
        new Chart(paretoCtx, {
            type: 'bar',
            data: {
                labels: @json(array_map(fn($n) => \Illuminate\Support\Str::limit($n, 16), $paretoLabels)),
                datasets: [
                    {
                        type: 'line',
                        label: 'Persentase Kumulatif (%)',
                        data: @json($paretoCumPct),
                        borderColor: '#DC2626',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#DC2626',
                        pointRadius: 4,
                        yAxisID: 'y1',
                        tension: 0.25
                    },
                    {
                        type: 'bar',
                        label: 'Kontribusi Nilai (Rp)',
                        data: @json($paretoRevenues),
                        backgroundColor: '#3B82F6',
                        borderRadius: 6,
                        yAxisID: 'y'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: true, position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.dataset.yAxisID === 'y1') {
                                    return `Kumulatif: ${context.parsed.y}%`;
                                }
                                return `Kontribusi: Rp ${Number(context.parsed.y).toLocaleString('id-ID')}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        grid: { color: '#F3F4F6' },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(0) + ' Jt';
                                return 'Rp ' + Number(value).toLocaleString('id-ID');
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        min: 0,
                        max: 100,
                        grid: { drawOnChartArea: false },
                        ticks: { callback: value => value + '%' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 3. Customer RFM Segmentation Doughnut Chart
    const rfmCtx = document.getElementById('rfmChart')?.getContext('2d');
    if (rfmCtx) {
        new Chart(rfmCtx, {
            type: 'doughnut',
            data: {
                labels: @json(array_keys($rfmClusters)),
                datasets: [{
                    data: @json(array_column($rfmClusters, 'count')),
                    backgroundColor: @json(array_column($rfmClusters, 'color')),
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
});
</script>
@endpush
