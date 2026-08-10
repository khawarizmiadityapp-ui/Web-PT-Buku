@extends('layouts.app')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-500 mb-2">
            <a href="{{ route('warehouse.index') }}" class="hover:text-blue-600">Picking</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <span class="text-gray-700">Active Task</span>
        </div>
        
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-3">
                    <h1 class="text-2xl font-bold text-gray-800">TASK: PICK-2026-0045</h1>
                    <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">ORDER #850 9821</span>
                </div>
                <div class="flex items-center space-x-4 mt-2 text-sm">
                    <span class="flex items-center">
                        Priority: <span class="ml-1 font-semibold text-red-600">HIGH RUSH</span>
                    </span>
                    <span class="text-gray-400">•</span>
                    <span>Assigned to Station 04</span>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center space-x-2">
                    <i class="fas fa-print"></i>
                    <span>Print Label</span>
                </button>
                <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center space-x-2">
                    <i class="fas fa-check-circle"></i>
                    <span>Konfirmasi Picking</span>
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Scan & Progress -->
        <div class="space-y-6">
            <!-- Scan Barcode Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">SCAN BARCODE</h3>
                
                <!-- Scanner Preview -->
                <div class="bg-gray-900 rounded-lg overflow-hidden mb-4" style="height: 200px;">
                    <img src="https://via.placeholder.com/400x200/1a1a1a/ffffff?text=Scanner+Camera+View" alt="Scanner" class="w-full h-full object-cover">
                </div>
                
                <!-- Manual Entry -->
                <div class="mb-4">
                    <label class="text-xs text-gray-500 mb-2 block">Manual SKU Entry</label>
                    <div class="flex space-x-2">
                        <input type="text" 
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="Enter SKU manually...">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Picking Progress -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">PICKING PROGRESS</h3>
                
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-3xl font-bold text-blue-600">60%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full" style="width: 60%"></div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-xs text-gray-500 mb-1">ITEMS PICKED</div>
                        <div class="text-2xl font-bold text-gray-800">10</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 mb-1">PENDING</div>
                        <div class="text-2xl font-bold text-gray-800">6</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Items Table -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <!-- Table Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">Items to Pick</h3>
                        <span class="text-sm text-gray-500">Tip: Use shortcut <kbd class="px-2 py-1 bg-gray-100 rounded">Space</kbd> to confirm entry</span>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <input type="checkbox" class="rounded border-gray-300">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">NAMA BARANG / SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">LOKASI RAK</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">REQUIRED</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">PICKED</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($activeProducts as $index => $prod)
                                @php
                                    $isPicked = $index < 2;
                                    $requiredQty = min(20, max(2, round($prod->system_stock / 10)));
                                    if ($requiredQty == 0) $requiredQty = 5;
                                    $pickedQty = $isPicked ? $requiredQty : 0;
                                    $rackLoc = chr(65 + ($index % 4)) . '-' . str_pad($index + 3, 2, '0', STR_PAD_LEFT) . '-01';
                                @endphp
                                <tr class="{{ $isPicked ? 'bg-green-50' : 'hover:bg-gray-50' }}">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" {{ $isPicked ? 'checked' : '' }} class="rounded border-gray-300">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-800">{{ $prod->product_name }}</div>
                                        <div class="text-xs text-gray-500">SKU: {{ $prod->product_code }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded">{{ $rackLoc }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-sm font-medium">{{ $requiredQty }} {{ $prod->unit ?? 'Pcs' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($isPicked)
                                            <span class="text-lg font-bold text-green-600">{{ $pickedQty }}</span>
                                        @else
                                            <input type="number" value="{{ $pickedQty }}" class="w-16 px-2 py-1 border border-gray-300 rounded text-center">
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada produk aktif di database.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Warning Banner -->
                <div class="p-4 bg-red-50 border-t border-red-100">
                    <div class="flex items-center text-sm text-red-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span class="font-medium">Flag Discrepancy:</span>
                        <span class="ml-2">1 item needs attention</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Next Item Notification -->
    <div class="fixed bottom-6 right-6 bg-blue-600 text-white rounded-lg shadow-lg p-4 flex items-center space-x-3 max-w-md">
        <div class="bg-blue-500 p-2 rounded-lg">
            <i class="fas fa-info-circle text-xl"></i>
        </div>
        <div class="flex-1">
            <div class="font-semibold">Next Item is in Row C</div>
            <div class="text-sm text-blue-100">Distance: 45 meters from current station.</div>
        </div>
        <button class="hover:bg-blue-700 p-2 rounded">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<style>
kbd {
    box-shadow: 0 2px 0 rgba(0,0,0,0.1);
}
</style>
@endsection
