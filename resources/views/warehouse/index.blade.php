@extends('layouts.app')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    <!-- Header Section -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Operasional</h1>
        <p class="text-sm text-gray-500 mt-1">Selamat datang kembali, Warehouse Manager. Berikut status gudang hari ini.</p>
    </div>

    <!-- Quick Action Buttons -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <a href="{{ route('warehouse.incoming-goods') }}" class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl p-4 flex items-center justify-between transition shadow-lg">
            <div class="flex items-center space-x-3">
                <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                    <x-icon name="plus" class="w-6 h-6" />
                </div>
                <div>
                    <div class="text-sm font-medium">Barang</div>
                    <div class="text-lg font-bold">Masuk</div>
                </div>
            </div>
            <x-icon name="arrow-right" class="w-6 h-6" />
        </a>

        <a href="{{ route('warehouse.picking') }}" class="bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-xl p-4 flex items-center justify-between transition">
            <div class="flex items-center space-x-3">
                <div class="bg-purple-500 bg-opacity-20 p-2 rounded-lg">
                    <x-icon name="hand-paper" class="w-6 h-6" />
                </div>
                <div>
                    <div class="text-sm font-medium">Picking</div>
                    <div class="text-lg font-bold">Baru</div>
                </div>
            </div>
            <x-icon name="arrow-right" class="w-6 h-6" />
        </a>

        <a href="{{ route('warehouse.packing') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl p-4 flex items-center justify-between transition">
            <div class="flex items-center space-x-3">
                <div class="bg-gray-500 bg-opacity-20 p-2 rounded-lg">
                    <x-icon name="box" class="w-6 h-6" />
                </div>
                <div>
                    <div class="text-sm font-medium">Packing</div>
                    <div class="text-lg font-bold">Baru</div>
                </div>
            </div>
            <x-icon name="arrow-right" class="w-6 h-6" />
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Barang Masuk Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="text-gray-500 text-sm font-medium">BARANG MASUK<br>HARI INI</div>
                <div class="bg-green-50 p-2 rounded-lg">
                    <x-icon name="inbox" class="text-green-600 w-6 h-6" />
                </div>
            </div>
            <div class="flex items-end justify-between">
                <div class="text-4xl font-bold text-gray-800">{{ $stats['barang_masuk']['value'] }}</div>
                <div class="text-sm font-semibold text-green-600 flex items-center">
                    <x-icon name="arrow-up" class="w-4 h-4 mr-1" />{{ $stats['barang_masuk']['trend'] }}
                </div>
            </div>
            <div class="mt-3 bg-green-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-green-500 h-full w-3/4"></div>
            </div>
        </div>

        <!-- Barang Keluar Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="text-gray-500 text-sm font-medium">BARANG KELUAR<br>HARI INI</div>
                <div class="bg-blue-50 p-2 rounded-lg">
                    <x-icon name="sign-out-alt" class="text-blue-600 w-6 h-6" />
                </div>
            </div>
            <div class="flex items-end justify-between">
                <div class="text-4xl font-bold text-gray-800">{{ $stats['barang_keluar']['value'] }}</div>
                <div class="text-sm font-semibold text-blue-600 flex items-center">
                    <x-icon name="arrow-up" class="w-4 h-4 mr-1" />{{ $stats['barang_keluar']['trend'] }}
                </div>
            </div>
            <div class="mt-3 bg-blue-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-blue-500 h-full w-2/3"></div>
            </div>
        </div>

        <!-- Picking Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="text-gray-500 text-sm font-medium">PICKING HARI INI</div>
                <div class="bg-orange-50 p-2 rounded-lg">
                    <x-icon name="hand-paper" class="text-orange-600 w-6 h-6" />
                </div>
            </div>
            <div class="flex items-end justify-between">
                <div class="text-4xl font-bold text-gray-800">{{ $stats['picking']['value'] }}</div>
                <div class="text-xs font-medium text-orange-600 bg-orange-50 px-2 py-1 rounded">
                    {{ $stats['picking']['pending'] }} Pending
                </div>
            </div>
            <div class="mt-3 bg-orange-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-orange-400 h-full w-4/5"></div>
            </div>
        </div>

        <!-- Packing Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="text-gray-500 text-sm font-medium">PACKING HARI INI</div>
                <div class="bg-purple-50 p-2 rounded-lg">
                    <x-icon name="box-open" class="text-purple-600 w-6 h-6" />
                </div>
            </div>
            <div class="flex items-end justify-between">
                <div class="text-4xl font-bold text-gray-800">{{ $stats['packing']['value'] }}</div>
                <div class="text-xs font-medium text-purple-600 bg-purple-50 px-2 py-1 rounded">
                    {{ $stats['packing']['efficiency'] }}% Efisiensi
                </div>
            </div>
            <div class="mt-3 bg-purple-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-purple-500 h-full" style="width: {{ $stats['packing']['efficiency'] }}%"></div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Chart & Shipments -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Activity Chart -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Aktivitas Gudang 7 Hari Terakhir</h2>
                        <p class="text-sm text-gray-500">Volume keluar-masuk barang secara real-time</p>
                    </div>
                    <button class="px-4 py-2 text-sm text-gray-600 bg-gray-50 rounded-lg hover:bg-gray-100">
                        Minggu Ini
                    </button>
                </div>

                <!-- Chart -->
                <div class="relative" style="height: 300px;">
                    <canvas id="activityChart"></canvas>
                </div>

                <!-- Legend -->
                <div class="flex items-center justify-center space-x-6 mt-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">Barang Keluar</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-blue-200 rounded-full"></div>
                        <span class="text-sm text-gray-600">Barang Masuk</span>
                    </div>
                </div>
            </div>

            <!-- Status Pengiriman Terakhir -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-800">Status Pengiriman Terakhir</h2>
                    <a href="{{ route('warehouse.stock', ['export' => 'csv']) }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Export Report ↓</a>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left text-xs font-semibold text-gray-500 uppercase pb-3">NO. ORDER</th>
                                <th class="text-left text-xs font-semibold text-gray-500 uppercase pb-3">TUJUAN</th>
                                <th class="text-left text-xs font-semibold text-gray-500 uppercase pb-3">METODE</th>
                                <th class="text-left text-xs font-semibold text-gray-500 uppercase pb-3">WAKTU KELUAR</th>
                                <th class="text-left text-xs font-semibold text-gray-500 uppercase pb-3">STATUS</th>
                                <th class="text-left text-xs font-semibold text-gray-500 uppercase pb-3">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentShipments as $shipment)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-4 text-sm font-medium text-gray-800">{{ $shipment['order_number'] }}</td>
                                <td class="py-4 text-sm text-gray-600">{{ $shipment['destination'] }}</td>
                                <td class="py-4 text-sm text-gray-600">{{ $shipment['method'] }}</td>
                                <td class="py-4 text-sm text-gray-600">{{ $shipment['time'] }}</td>
                                <td class="py-4">
                                    @if($shipment['status_color'] === 'green')
                                        <span class="inline-block px-3 py-1 text-xs font-medium bg-green-50 text-green-700 rounded-full">{{ $shipment['status'] }}</span>
                                    @elseif($shipment['status_color'] === 'blue')
                                        <span class="inline-block px-3 py-1 text-xs font-medium bg-blue-50 text-blue-700 rounded-full">{{ $shipment['status'] }}</span>
                                    @else
                                        <span class="inline-block px-3 py-1 text-xs font-medium bg-yellow-50 text-yellow-700 rounded-full">{{ $shipment['status'] }}</span>
                                    @endif
                                </td>
                                <td class="py-4">
                                    <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                        @if($shipment['status_color'] === 'green')
                                            Track
                                        @else
                                            Detail
                                        @endif
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Alerts & Activities -->
        <div class="space-y-6">
            <!-- Stok Hampir Habis -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-800">STOK HAMPIR HABIS</h2>
                    <span class="bg-red-50 text-red-600 text-xs font-bold px-2 py-1 rounded">{{ count($lowStockItems) }} ALERT</span>
                </div>

                <div class="space-y-3">
                    @foreach($lowStockItems as $item)
                    <div class="border-l-4 {{ $item['quantity'] < 5 ? 'border-red-500' : 'border-orange-500' }} pl-4 py-2">
                        <div class="font-semibold text-sm text-gray-800">{{ $item['name'] }}</div>
                        <div class="text-xs text-gray-500 mt-1">SKU: {{ $item['sku'] }}</div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-lg font-bold {{ $item['quantity'] < 5 ? 'text-red-600' : 'text-orange-600' }}">{{ $item['quantity'] }} {{ $item['unit'] }}</span>
                            <span class="text-xs text-gray-500">Min: {{ $item['min_stock'] }} {{ $item['unit'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <a href="{{ route('warehouse.stock') }}" class="block mt-4 text-center text-sm text-blue-600 hover:text-blue-700 font-medium">
                    Lihat Semua Stok →
                </a>
            </div>

            <!-- Aktivitas Terbaru -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold text-gray-800 mb-4">AKTIVITAS TERBARU</h2>

                <div class="space-y-4">
                    @foreach($recentActivities as $activity)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            @if($activity['color'] === 'purple')
                                <div class="w-2 h-2 bg-purple-500 rounded-full mt-1.5"></div>
                            @elseif($activity['color'] === 'blue')
                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5"></div>
                            @elseif($activity['color'] === 'green')
                                <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5"></div>
                            @else
                                <div class="w-2 h-2 bg-gray-400 rounded-full mt-1.5"></div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-semibold text-gray-800">{{ $activity['title'] }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $activity['description'] }}</div>
                            <div class="text-xs text-gray-400 mt-1">{{ $activity['time'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('activityChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [
                {
                    label: 'Barang Keluar',
                    data: {!! json_encode($chartData['barang_keluar']) !!},
                    backgroundColor: '#3B82F6',
                    borderRadius: 6,
                    barThickness: 24,
                },
                {
                    label: 'Barang Masuk',
                    data: {!! json_encode($chartData['barang_masuk']) !!},
                    backgroundColor: '#BFDBFE',
                    borderRadius: 6,
                    barThickness: 24,
                }
            ]
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
                    padding: 12,
                    borderRadius: 8,
                    titleColor: '#F3F4F6',
                    bodyColor: '#F3F4F6',
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        },
                        color: '#6B7280'
                    }
                },
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 25,
                        font: {
                            size: 12
                        },
                        color: '#6B7280'
                    },
                    grid: {
                        color: '#F3F4F6'
                    }
                }
            }
        }
    });
});
</script>
@endsection
