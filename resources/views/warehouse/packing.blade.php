@extends('layouts.app')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-500 mb-2">
            <a href="{{ route('warehouse.index') }}" class="hover:text-blue-600">Processes</a>
            <x-icon name="chevron-right" class="mx-2 w-3.5 h-3.5" />
            <span class="text-blue-600 font-medium">Packing Process</span>
        </div>
        
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <span class="px-3 py-1 text-xs font-semibold bg-blue-600 text-white rounded">IN PROGRESS</span>
                    <span class="text-sm text-gray-600">Task ID: <span class="font-semibold">#PICK-2026-0045</span></span>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Packing Station #B4</h1>
            </div>
            
            <div class="flex items-center space-x-3">
                <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center space-x-2">
                    <x-icon name="book-open" class="w-4 h-4" />
                    <span>Manual Guide</span>
                </button>
                <button class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 flex items-center space-x-2">
                    <x-icon name="flag" class="w-4 h-4" />
                    <span>Report Issue</span>
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Items List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <!-- Header -->
                <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">ITEMS FROM PICKING TASK</h3>
                    <span class="text-sm text-blue-600 font-medium">4 items total</span>
                </div>

                <!-- Items List -->
                <div class="divide-y divide-gray-100">
                    @forelse($activeProducts as $index => $prod)
                        @php
                            $qty = min(25, max(3, round($prod->system_stock / 8)));
                            if ($qty == 0) $qty = 10;
                            $packCode = 'Pack #' . chr(65 + ($index % 5)) . '-' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                        @endphp
                        <div class="p-6 hover:bg-gray-50 flex items-start space-x-4">
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <x-icon name="box" class="text-blue-600 w-6 h-6" />
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-gray-800 mb-1">{{ $prod->product_name }}</div>
                                <div class="text-xs text-gray-500">SKU: {{ $prod->product_code }} • {{ $packCode }}</div>
                                <div class="mt-2 flex items-center space-x-4">
                                    <span class="text-sm"><span class="font-semibold text-blue-600">Qty: {{ $qty }}</span></span>
                                    <span class="text-xs text-gray-400">• {{ $prod->unit ?? 'Pcs' }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-block px-3 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full">✓ Packed</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500">Belum ada produk di database</div>
                    @endforelse
                </div>

                <!-- Packing Details Section -->
                <div class="p-6 border-t border-gray-200 bg-gray-50">
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Left: Packing Details -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-4">Packing Details</h4>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="text-xs text-gray-500 mb-1 block">Berat Paket (kg)</label>
                                    <input type="number" value="0.0" step="0.1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                
                                <div>
                                    <label class="text-xs text-gray-500 mb-1 block">Jumlah Pakeli (Colt)</label>
                                    <div class="flex items-center space-x-2">
                                        <input type="number" value="1" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <span class="text-sm font-medium text-gray-600">BOX</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Operational Notes -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-4">Operational Notes</h4>
                            
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Catatan</label>
                                <textarea rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="e.g. Gunakan bubble wrap ekstra untuk barang pecah-belah..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Shipping Label & Actions -->
        <div class="space-y-6">
            <!-- Shipping Label Preview -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase">Preview Shipping Label</h3>
                    <button class="text-sm text-blue-600 hover:text-blue-700">
                        <x-icon name="eye" class="w-4 h-4" />
                    </button>
                </div>

                <!-- Label Preview Box -->
                <div class="border-2 border-gray-200 rounded-lg p-4 bg-white">
                    <div class="text-center mb-3">
                        <div class="font-bold text-lg">LogiBook Express</div>
                        <div class="text-xs text-gray-500">STD-SAFEXP</div>
                        <div class="text-xs text-gray-500">25.12.2026</div>
                    </div>
                    
                    <div class="text-xs space-y-2 mb-3">
                        <div>
                            <div class="font-semibold">TUJUAN & KONTAK</div>
                            <div class="text-gray-600">TOKO BUKU SENTRAL</div>
                            <div class="text-gray-600">Jl Sudirman No 123, Blok A2</div>
                            <div class="text-gray-600">Kebayoran, Jakarta Selatan, 12170</div>
                        </div>
                        
                        <div>
                            <div class="font-semibold">PENGIRIM</div>
                            <div class="text-gray-600">PT Distribusi Maju Jaya</div>
                            <div class="text-gray-600">Gudirman No 123, Blok A1</div>
                            <div class="text-gray-600">Kebayoran, Jakarta Selatan, 12170</div>
                        </div>
                    </div>

                    <!-- Barcode -->
                    <div class="text-center border-t border-gray-200 pt-3">
                        <svg class="mx-auto" width="180" height="50">
                            <rect width="2" height="50" x="10" fill="#000"/>
                            <rect width="1" height="50" x="14" fill="#000"/>
                            <rect width="3" height="50" x="18" fill="#000"/>
                            <rect width="1" height="50" x="24" fill="#000"/>
                            <rect width="2" height="50" x="28" fill="#000"/>
                            <rect width="1" height="50" x="32" fill="#000"/>
                            <rect width="3" height="50" x="36" fill="#000"/>
                            <rect width="2" height="50" x="42" fill="#000"/>
                            <rect width="1" height="50" x="46" fill="#000"/>
                            <rect width="2" height="50" x="50" fill="#000"/>
                            <rect width="3" height="50" x="56" fill="#000"/>
                            <rect width="1" height="50" x="62" fill="#000"/>
                            <rect width="2" height="50" x="66" fill="#000"/>
                            <rect width="1" height="50" x="70" fill="#000"/>
                            <rect width="3" height="50" x="74" fill="#000"/>
                            <rect width="2" height="50" x="80" fill="#000"/>
                            <rect width="1" height="50" x="86" fill="#000"/>
                            <rect width="2" height="50" x="90" fill="#000"/>
                            <rect width="3" height="50" x="96" fill="#000"/>
                            <rect width="1" height="50" x="102" fill="#000"/>
                            <rect width="2" height="50" x="106" fill="#000"/>
                            <rect width="1" height="50" x="112" fill="#000"/>
                            <rect width="3" height="50" x="116" fill="#000"/>
                            <rect width="2" height="50" x="122" fill="#000"/>
                            <rect width="1" height="50" x="128" fill="#000"/>
                            <rect width="2" height="50" x="132" fill="#000"/>
                            <rect width="3" height="50" x="138" fill="#000"/>
                            <rect width="1" height="50" x="144" fill="#000"/>
                            <rect width="2" height="50" x="148" fill="#000"/>
                            <rect width="1" height="50" x="154" fill="#000"/>
                            <rect width="3" height="50" x="158" fill="#000"/>
                            <rect width="2" height="50" x="164" fill="#000"/>
                            <rect width="1" height="50" x="170" fill="#000"/>
                        </svg>
                        <div class="text-xs font-mono font-semibold mt-1">LB-2026-0045-0BRT2</div>
                    </div>
                    
                    <div class="text-xs text-gray-400 text-center mt-2">
                        Label akan dicetak menggunakan Printer Zebra<br>ZD410 (Review #492)
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <button class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center justify-center space-x-2 font-semibold">
                    <x-icon name="print" class="w-4 h-4" />
                    <span>Print Label Pengiriman</span>
                </button>
                
                <button class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center justify-center space-x-2 font-semibold">
                    <x-icon name="check-circle" class="w-4 h-4" />
                    <span>Packing Selesai</span>
                </button>
                
                <button class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 flex items-center justify-center space-x-2 text-sm">
                    <x-icon name="save" class="w-4 h-4" />
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
