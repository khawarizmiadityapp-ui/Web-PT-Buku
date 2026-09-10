@extends('layouts.app')

@section('title', 'Mulai Penghitungan Stok - PT Nusantara ERP')

@push('styles')
<style>
    .diff-badge-surplus {
        background-color: #EFF6FF;
        color: #1D4ED8;
        border: 1px solid #BFDBFE;
    }

    .diff-badge-deficit {
        background-color: #FEF2F2;
        color: #B91C1C;
        border: 1px solid #FECACA;
    }

    .diff-badge-match {
        background-color: #ECFDF5;
        color: #047857;
        border: 1px solid #A7F3D0;
    }

    .diff-badge-uncounted {
        background-color: #F3F4F6;
        color: #4B5563;
        border: 1px solid #E5E7EB;
    }

    .stepper-btn {
        transition: all 0.15s ease;
    }
    .stepper-btn:active {
        transform: scale(0.92);
    }

    .row-highlight-scanned {
        animation: rowFlash 1.5s ease-out;
    }

    @keyframes rowFlash {
        0% { background-color: rgba(59, 130, 246, 0.15); }
        100% { background-color: transparent; }
    }

    .quick-chip {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .quick-chip:hover {
        transform: translateY(-1px);
    }

    /* Floating bottom action bar */
    .floating-bar {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 40;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
    }
</style>
@endpush

@section('content')
<div class="space-y-6 pb-20">
    <!-- Breadcrumb -->
    <div class="flex items-center text-sm text-gray-500 mb-2">
        <a href="{{ route('dashboard') }}" class="hover:text-gray-700">Warehouse</a>
        <x-icon name="chevron-right" class="mx-2 w-3.5 h-3.5" />
        <a href="{{ route('warehouse.stock-audit.index') }}" class="hover:text-gray-700">Stock Opname & Audit</a>
        <x-icon name="chevron-right" class="mx-2 w-3.5 h-3.5" />
        <span class="text-gray-900 font-medium">Sesi Penghitungan</span>
    </div>

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900">Penghitungan Stok Fisik (Stock Opname)</h1>
                <span class="px-3 py-1 bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold rounded-lg">
                    <x-icon name="barcode" class="w-4 h-4 mr-1" /> {{ $sessionCode }}
                </span>
            </div>
            <p class="text-sm text-gray-500 mt-1">Lakukan verifikasi jumlah fisik barang di gudang dengan pencatatan digital terintegrasi.</p>
        </div>

        <!-- Action Header Buttons -->
        <div class="flex items-center flex-wrap gap-2.5">
            <a href="{{ route('warehouse.stock-audit.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-sm transition shadow-sm flex items-center gap-2">
                <x-icon name="arrow-left" class="w-3.5 h-3.5" />
                <span>Kembali</span>
            </a>
            <button type="button" onclick="setAllToSystem()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-sm transition shadow-sm flex items-center gap-2">
                <x-icon name="wand-magic-sparkles" class="w-4 h-4 text-blue-600" />
                <span>Set Semua = Sistem</span>
            </button>
            <button type="button" onclick="resetAllInputs()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-sm transition shadow-sm flex items-center gap-2">
                <x-icon name="rotate-left" class="w-3.5 h-3.5" />
                <span>Reset</span>
            </button>
            <button type="button" onclick="openReviewModal()" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium text-sm transition shadow-sm flex items-center gap-2">
                <x-icon name="check-double" class="w-4 h-4" />
                <span>Selesaikan & Simpan</span>
            </button>
        </div>
    </div>

    <!-- Session Info & Progress Card -->
    <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl flex-shrink-0">
                    <x-icon name="clipboard-check" class="w-4 h-4" />
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-100 uppercase tracking-wider">Live Audit Active</span>
                        <span class="text-xs text-gray-400">• LogiBook WMS</span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mt-1">Sesi Audit: {{ $sessionCode }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Auditor: <strong class="text-gray-700">{{ Auth::user()->name }}</strong> ({{ Auth::user()->role ?? 'Staff Gudang' }}) &bull; Waktu: {{ date('d M Y') }}</p>
                </div>
            </div>

            <!-- Progress Meter -->
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 min-w-[320px]">
                <div class="flex items-center justify-between text-xs mb-2">
                    <span class="text-gray-600 font-medium">Progress Penghitungan</span>
                    <span class="font-bold text-blue-600" id="progressPercentageText">0%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                    <div id="progressBarFill" class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
                </div>
                <div class="flex items-center justify-between text-xs text-gray-500 mt-2">
                    <span><strong id="countedCountText" class="text-gray-900">0</strong> dari {{ $totalProducts }} SKU terhitung</span>
                    <span id="uncountedCountText" class="text-amber-600 font-medium">{{ $totalProducts }} belum diisi</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Live KPI Statistic Widgets (6 Cards) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- Total SKU -->
        <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total SKU</span>
                <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-600 text-xs">
                    <x-icon name="boxes-stacked" class="w-4 h-4" />
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-900">{{ $totalProducts }}</div>
            <div class="text-xs text-gray-500 mt-1">Item dalam katalog</div>
        </div>

        <!-- Terhitung -->
        <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Terhitung</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 text-xs">
                    <x-icon name="list-check" class="w-4 h-4" />
                </div>
            </div>
            <div class="text-2xl font-bold text-blue-600" id="kpiCounted">0</div>
            <div class="text-xs text-gray-500 mt-1">Barang telah dicek</div>
        </div>

        <!-- Sesuai / Match -->
        <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sesuai (Match)</span>
                <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-600 text-xs">
                    <x-icon name="circle-check" class="w-4 h-4" />
                </div>
            </div>
            <div class="text-2xl font-bold text-green-600" id="kpiMatched">0</div>
            <div class="text-xs text-gray-500 mt-1">Fisik = Sistem</div>
        </div>

        <!-- Selisih Lebih (Surplus) -->
        <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Lebih (Surplus)</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 text-xs">
                    <x-icon name="arrow-trend-up" class="w-4 h-4" />
                </div>
            </div>
            <div class="text-2xl font-bold text-blue-600" id="kpiSurplus">0</div>
            <div class="text-xs text-gray-500 mt-1">Fisik > Sistem</div>
        </div>

        <!-- Selisih Kurang (Deficit) -->
        <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kurang (Defisit)</span>
                <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-600 text-xs">
                    <x-icon name="arrow-trend-down" class="w-4 h-4" />
                </div>
            </div>
            <div class="text-2xl font-bold text-red-600" id="kpiDeficit">0</div>
            <div class="text-xs text-gray-500 mt-1">Fisik < Sistem</div>
        </div>

        <!-- Akurasi Realtime -->
        <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Akurasi Audit</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 text-xs">
                    <x-icon name="percent" class="w-4 h-4" />
                </div>
            </div>
            <div class="text-2xl font-bold text-amber-600" id="kpiAccuracy">100%</div>
            <div class="text-xs text-gray-500 mt-1">Rasio kecocokan stok</div>
        </div>
    </div>

    <!-- Quick Scanner & Filter Toolbar -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <!-- Left: Barcode / SKU Scanner -->
            <div class="flex-1">
                <div class="relative flex items-center">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                        <x-icon name="barcode" class="w-4 h-4 text-base" />
                    </span>
                    <input type="text" 
                           id="barcodeQuickInput" 
                           placeholder="Scan barcode SKU atau ketik nama produk lalu tekan ENTER..." 
                           class="w-full pl-10 pr-28 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition"
                           autocomplete="off">
                    
                    <div class="absolute right-1.5 flex items-center gap-1">
                        <button type="button" onclick="triggerBarcodeScan()" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-xs font-medium transition shadow-sm">
                            <x-icon name="plus" class="w-4 h-4 mr-1" /> Scan
                        </button>
                        <button type="button" onclick="openCameraModal()" class="p-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-md text-xs transition" title="Gunakan Kamera Scanner">
                            <x-icon name="camera" class="w-4 h-4" />
                        </button>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-2 text-xs text-gray-500">
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-1.5 cursor-pointer select-none">
                            <input type="checkbox" id="audioBeepToggle" checked class="w-3.5 h-3.5 text-blue-600 rounded">
                            <span>Audio Beep saat Scan</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer select-none">
                            <input type="radio" name="scanMode" value="increment" checked class="w-3.5 h-3.5 text-blue-600">
                            <span>Mode: Auto +1</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer select-none">
                            <input type="radio" name="scanMode" value="focus" class="w-3.5 h-3.5 text-blue-600">
                            <span>Mode: Fokus Baris</span>
                        </label>
                    </div>
                    <span id="scanFeedbackMsg" class="font-medium text-blue-600 hidden"></span>
                </div>
            </div>

            <!-- Right: Search & Category -->
            <div class="flex items-center flex-wrap gap-3">
                <select id="categoryFilter" onchange="applyFilters()" class="py-2 pl-3 pr-8 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>

                <div class="relative min-w-[200px]">
                    <x-icon name="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-3.5 h-3.5" />
                    <input type="text" 
                           id="tableSearchInput" 
                           oninput="applyFilters()" 
                           placeholder="Cari di tabel..." 
                           class="w-full pl-8 pr-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs & Filter Bar -->
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-2.5 flex flex-col sm:flex-row items-center justify-between gap-3">
        <!-- Status Tabs -->
        <div class="flex items-center flex-wrap gap-1.5">
            <button type="button" onclick="filterTab('all', this)" class="tab-btn active px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-white text-blue-600 shadow-sm border border-gray-200">
                Semua (<span id="badgeTabAll">{{ $totalProducts }}</span>)
            </button>
            <button type="button" onclick="filterTab('uncounted', this)" class="tab-btn px-3.5 py-1.5 text-xs font-semibold rounded-lg text-gray-600 hover:bg-white transition">
                Belum Dihitung (<span id="badgeTabUncounted">{{ $totalProducts }}</span>)
            </button>
            <button type="button" onclick="filterTab('discrepancy', this)" class="tab-btn px-3.5 py-1.5 text-xs font-semibold rounded-lg text-gray-600 hover:bg-white transition">
                Ada Selisih (<span id="badgeTabDiscrepancy">0</span>)
            </button>
            <button type="button" onclick="filterTab('match', this)" class="tab-btn px-3.5 py-1.5 text-xs font-semibold rounded-lg text-gray-600 hover:bg-white transition">
                Cocok (<span id="badgeTabMatch">0</span>)
            </button>
        </div>

        <!-- Quick Fill & Review Discrepancy Shortcuts -->
        <div class="flex items-center gap-2">
            <button type="button" onclick="quickFillUncounted()" class="px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 rounded-lg text-xs font-medium transition shadow-sm flex items-center gap-1.5">
                <x-icon name="check-circle" class="w-4 h-4 text-gray-500" />
                <span>Isi Sisa = Sistem</span>
            </button>
            <button type="button" onclick="quickFilterTab('discrepancy')" class="px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-200 text-red-600 rounded-lg text-xs font-medium transition shadow-sm flex items-center gap-1.5">
                <x-icon name="triangle-exclamation" class="w-4 h-4 text-red-500" />
                <span>Tinjau Selisih</span>
            </button>
        </div>
    </div>

    <!-- MAIN FORM: Counting Table -->
    <form id="stockCountForm" action="{{ route('warehouse.stock-audit.store-count') }}" method="POST">
        @csrf
        <input type="hidden" name="session_code" value="{{ $sessionCode }}">
        <input type="hidden" name="adjustment_mode" id="formAdjustmentMode" value="save_only">
        <input type="hidden" name="general_notes" id="formGeneralNotes" value="">

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="stockTable">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 text-xs font-semibold uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4 min-w-[260px]">Informasi Barang</th>
                            <th class="py-3.5 px-4 text-center w-36">Stok Sistem</th>
                            <th class="py-3.5 px-4 text-center min-w-[220px]">Stok Fisik Aktual</th>
                            <th class="py-3.5 px-4 text-center w-36">Selisih</th>
                            <th class="py-3.5 px-4 min-w-[220px]">Catatan / Keterangan</th>
                            <th class="py-3.5 px-4 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm" id="tableBody">
                        @forelse($products as $index => $product)
                            @php
                                $sysStock = (int) $product->system_stock;
                                $initPhysical = (int) ($product->physical_stock ?? $sysStock);
                                $initDiff = $initPhysical - $sysStock;
                            @endphp
                            <tr class="product-row hover:bg-gray-50/80 transition" 
                                id="row-{{ $product->id }}"
                                data-id="{{ $product->id }}"
                                data-code="{{ strtolower($product->product_code) }}"
                                data-name="{{ strtolower($product->product_name) }}"
                                data-category="{{ strtolower($product->category ?? '') }}"
                                data-system-stock="{{ $sysStock }}">
                                
                                <!-- No -->
                                <td class="py-3.5 px-4 text-center text-xs font-semibold text-gray-400 row-number">
                                    {{ $index + 1 }}
                                </td>

                                <!-- Product Info -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center flex-shrink-0 text-gray-500 overflow-hidden">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" class="w-full h-full object-cover">
                                            @else
                                                <x-icon name="box" class="w-4 h-4 text-gray-400" />
                                            @endif
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-0.5 bg-blue-50 border border-blue-100 text-blue-700 text-xs font-semibold rounded font-mono">
                                                    {{ $product->product_code }}
                                                </span>
                                                @if($product->category)
                                                    <span class="text-xs text-gray-500 font-medium">
                                                        &bull; {{ $product->category }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="font-bold text-gray-900 text-sm mt-0.5 line-clamp-1" title="{{ $product->product_name }}">
                                                {{ $product->product_name }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                Satuan: <span class="font-medium text-gray-600">{{ $product->unit ?? 'pcs' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="items[{{ $product->id }}][product_id]" value="{{ $product->id }}">
                                </td>

                                <!-- System Stock -->
                                <td class="py-3.5 px-4 text-center">
                                    <div class="inline-block px-3 py-1.5 bg-gray-100 border border-gray-200 rounded-lg text-center">
                                        <span class="text-sm font-bold text-gray-800">{{ number_format($sysStock) }}</span>
                                        <span class="text-xs text-gray-500 ml-0.5">{{ $product->unit ?? 'pcs' }}</span>
                                    </div>
                                </td>

                                <!-- Physical Stock Input Stepper -->
                                <td class="py-3.5 px-4 text-center">
                                    <div class="inline-flex flex-col items-center gap-1.5">
                                        <div class="flex items-center bg-gray-50 border border-gray-300 rounded-lg p-1 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500">
                                            <button type="button" 
                                                    onclick="stepCount({{ $product->id }}, -1)" 
                                                    class="stepper-btn w-7 h-7 rounded bg-white border border-gray-200 text-gray-700 hover:bg-gray-100 flex items-center justify-center font-bold text-xs shadow-sm">
                                                <x-icon name="minus" class="w-3.5 h-3.5" />
                                            </button>
                                            
                                            <input type="number" 
                                                   name="items[{{ $product->id }}][physical_stock]" 
                                                   id="physical-{{ $product->id }}" 
                                                   value="{{ $initPhysical }}" 
                                                   min="0"
                                                   oninput="calculateRowDiff({{ $product->id }})" 
                                                   class="physical-input w-16 text-center font-bold text-base bg-transparent border-0 focus:ring-0 text-gray-900 p-0"
                                                   data-touched="true">

                                            <button type="button" 
                                                    onclick="stepCount({{ $product->id }}, 1)" 
                                                    class="stepper-btn w-7 h-7 rounded bg-blue-600 text-white hover:bg-blue-700 flex items-center justify-center font-bold text-xs shadow-sm">
                                                <x-icon name="plus" class="w-3.5 h-3.5" />
                                            </button>
                                        </div>

                                        <!-- Quick Stepper Chips -->
                                        <div class="flex items-center gap-1">
                                            <button type="button" onclick="setPhysicalDirectly({{ $product->id }}, {{ $sysStock }})" class="quick-chip px-2 py-0.5 bg-gray-100 hover:bg-gray-200 border border-gray-200 text-gray-700 rounded text-[10px] font-medium" title="Set sama dengan stok sistem">
                                                = Sistem
                                            </button>
                                            <button type="button" onclick="setPhysicalDirectly({{ $product->id }}, 0)" class="quick-chip px-1.5 py-0.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded text-[10px] font-medium" title="Kosongkan (0)">
                                                0
                                            </button>
                                            <button type="button" onclick="stepCount({{ $product->id }}, 5)" class="quick-chip px-1.5 py-0.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded text-[10px] font-medium">
                                                +5
                                            </button>
                                            <button type="button" onclick="stepCount({{ $product->id }}, 10)" class="quick-chip px-1.5 py-0.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded text-[10px] font-medium">
                                                +10
                                            </button>
                                        </div>
                                    </div>
                                </td>

                                <!-- Real-time Difference Badge -->
                                <td class="py-3.5 px-4 text-center">
                                    <div id="diff-badge-{{ $product->id }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold">
                                        <span class="diff-val">0</span>
                                    </div>
                                </td>

                                <!-- Notes per item -->
                                <td class="py-3.5 px-4">
                                    <div class="relative">
                                        <input type="text" 
                                               name="items[{{ $product->id }}][notes]" 
                                               id="notes-{{ $product->id }}" 
                                               placeholder="Catatan kondisi / alasan..." 
                                               class="w-full text-xs px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                                    </div>
                                </td>

                                <!-- Quick Row Actions -->
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button type="button" onclick="setPhysicalDirectly({{ $product->id }}, {{ $sysStock }})" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Samakan ke Stok Sistem">
                                            <x-icon name="check" class="w-3.5 h-3.5" />
                                        </button>
                                        <button type="button" onclick="setPhysicalDirectly({{ $product->id }}, 0)" class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Reset ke 0">
                                            <x-icon name="trash-can" class="w-3.5 h-3.5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRow">
                                <td colspan="7" class="py-16 text-center text-gray-500">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400 text-2xl">
                                        <x-icon name="boxes-stacked" class="w-4 h-4" />
                                    </div>
                                    <h4 class="text-base font-bold text-gray-700">Tidak ada produk ditemukan</h4>
                                    <p class="text-xs text-gray-400 mt-1">Pastikan katalog produk master data sudah memiliki item terdaftar.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- No Results Filter Notification -->
            <div id="noFilterResults" class="hidden py-12 text-center text-gray-500 border-t border-gray-100">
                <x-icon name="filter-circle-xmark" class="w-4 h-4 text-3xl text-gray-300 mb-2" />
                <p class="text-sm font-semibold text-gray-700">Tidak ada barang yang cocok dengan filter</p>
                <button type="button" onclick="resetFilters()" class="mt-2 text-xs font-semibold text-blue-600 hover:underline">
                    Reset Filter & Pencarian
                </button>
            </div>
        </div>
    </form>

    <!-- Floating Bottom Bar (Clean Theme) -->
    <div class="floating-bar bg-white border border-gray-200 shadow-xl rounded-xl px-5 py-3 flex items-center justify-between gap-4 max-w-3xl w-[92%]">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                <x-icon name="calculator" class="w-4 h-4" />
            </div>
            <div class="text-xs">
                <div>Status: <strong class="text-gray-900" id="barCounted">0</strong> / {{ $totalProducts }} SKU Dihitung</div>
                <div class="text-gray-500">Selisih: <span id="barDiscrepancy" class="font-semibold text-amber-600">0 Item</span></div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="quickFillUncounted()" class="px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-medium transition">
                <x-icon name="wand-magic-sparkles" class="w-4 h-4 mr-1" /> Isi Sisa
            </button>
            <button type="button" onclick="openReviewModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-medium transition shadow-sm flex items-center gap-1.5">
                <x-icon name="paper-plane" class="w-4 h-4" />
                <span>Simpan Hasil Opname</span>
            </button>
        </div>
    </div>
</div>

<!-- MODAL 1: Barcode Camera Scanner -->
<div id="cameraModal" class="fixed inset-0 bg-black/50 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white text-gray-900 rounded-xl max-w-lg w-full border border-gray-200 shadow-2xl overflow-hidden">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                    <x-icon name="camera" class="w-4 h-4" />
                </div>
                <div>
                    <h4 class="font-bold text-sm text-gray-900">Scanner Kamera Barcode</h4>
                    <p class="text-xs text-gray-500">Arahkan kamera ke barcode produk di gudang</p>
                </div>
            </div>
            <button type="button" onclick="closeCameraModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                <x-icon name="times" class="w-4 h-4" />
            </button>
        </div>

        <div class="p-5">
            <!-- Camera Viewfinder -->
            <div class="relative bg-gray-900 rounded-xl overflow-hidden aspect-video border border-gray-300 flex items-center justify-center">
                <div class="absolute inset-8 border-2 border-dashed border-blue-400 rounded-lg pointer-events-none flex items-center justify-center">
                    <span class="text-xs text-white bg-black/60 px-3 py-1 rounded-full">
                        <x-icon name="expand" class="w-4 h-4 mr-1" /> Area Barcode
                    </span>
                </div>

                <div class="text-center text-gray-300 text-xs z-10 px-4">
                    <x-icon name="video" class="w-8 h-8 mb-2 text-blue-400" />
                    <p>Kamera siap mendeteksi barcode secara otomatis</p>
                </div>
            </div>

            <!-- Quick SKU test list -->
            <div class="mt-4 pt-3 border-t border-gray-100">
                <div class="text-xs font-semibold text-gray-600 mb-2">Simulasi Scan Cepat (Pilih SKU):</div>
                <div class="flex flex-wrap gap-1.5 max-h-28 overflow-y-auto pr-1">
                    @foreach($products->take(8) as $p)
                        <button type="button" onclick="handleScannedCode('{{ $p->product_code }}')" class="px-2.5 py-1 bg-gray-100 hover:bg-blue-50 hover:text-blue-700 text-gray-700 rounded-md text-xs font-mono transition border border-gray-200">
                            {{ $p->product_code }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="p-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between text-xs text-gray-600">
            <span>Status: <strong class="text-green-600">Kamera Siap</strong></span>
            <button type="button" onclick="closeCameraModal()" class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 rounded-lg font-medium transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- MODAL 2: Review Discrepancy & Final Submission -->
<div id="reviewModal" class="fixed inset-0 bg-black/50 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-2xl w-full border border-gray-200 shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
        <!-- Header -->
        <div class="p-5 border-b border-gray-200 flex items-center justify-between bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                    <x-icon name="clipboard-check" class="w-4 h-4" />
                </div>
                <div>
                    <h3 class="font-bold text-base text-gray-900">Konfirmasi Hasil Stock Opname</h3>
                    <p class="text-xs text-gray-500">Tinjau ringkasan hasil penghitungan fisik sebelum data disimpan ke sistem</p>
                </div>
            </div>
            <button type="button" onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                <x-icon name="times" class="w-4 h-4" />
            </button>
        </div>

        <!-- Body Scrollable -->
        <div class="p-6 overflow-y-auto space-y-5 flex-1">
            <!-- Review Summary Badges -->
            <div class="grid grid-cols-4 gap-3 text-center">
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <div class="text-xs text-gray-500 font-semibold uppercase">Total SKU</div>
                    <div class="text-lg font-bold text-gray-900 mt-0.5">{{ $totalProducts }}</div>
                </div>
                <div class="bg-green-50 p-3 rounded-lg border border-green-200">
                    <div class="text-xs text-green-700 font-semibold uppercase">Cocok</div>
                    <div class="text-lg font-bold text-green-700 mt-0.5" id="modalMatchedCount">0</div>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg border border-blue-200">
                    <div class="text-xs text-blue-700 font-semibold uppercase">Surplus (+)</div>
                    <div class="text-lg font-bold text-blue-700 mt-0.5" id="modalSurplusCount">0</div>
                </div>
                <div class="bg-red-50 p-3 rounded-lg border border-red-200">
                    <div class="text-xs text-red-700 font-semibold uppercase">Defisit (-)</div>
                    <div class="text-lg font-bold text-red-700 mt-0.5" id="modalDeficitCount">0</div>
                </div>
            </div>

            <!-- Discrepancy Item List Preview -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        <x-icon name="triangle-exclamation" class="w-4 h-4 text-amber-500 mr-1" /> Rincian Barang dengan Selisih (<span id="modalDiscrepancyTotal">0</span>)
                    </h4>
                </div>
                <div class="border border-gray-200 rounded-lg overflow-hidden max-h-48 overflow-y-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-semibold sticky top-0">
                            <tr>
                                <th class="p-2.5">Barang</th>
                                <th class="p-2.5 text-center">Sistem</th>
                                <th class="p-2.5 text-center">Fisik</th>
                                <th class="p-2.5 text-center">Selisih</th>
                                <th class="p-2.5">Catatan</th>
                            </tr>
                        </thead>
                        <tbody id="discrepancyListBody" class="divide-y divide-gray-100">
                            <!-- Populated dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Audit Processing Mode Selection -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                    Pilih Metode Penyelesaian Audit:
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <!-- Option A: Save Draft / Pending Review -->
                    <label class="relative flex flex-col p-4 bg-white border border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/20">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-bold text-sm text-gray-900">1. Simpan Catatan Audit Fisik</span>
                            <input type="radio" name="modal_adj_mode" value="save_only" checked class="text-blue-600 focus:ring-blue-500">
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Simpan angka fisik & buat riwayat audit (Status: <em>Pending Review</em>). Stok sistem belum diubah sampai disetujui.
                        </p>
                    </label>

                    <!-- Option B: Auto-Adjust System Stock -->
                    <label class="relative flex flex-col p-4 bg-white border border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/20">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-bold text-sm text-gray-900">2. Sesuaikan Stok Sistem Langsung</span>
                            <input type="radio" name="modal_adj_mode" value="auto_adjust" class="text-blue-600 focus:ring-blue-500">
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Otomatis sesuaikan stok sistem ke angka fisik terkini (Status: <em>Has Adjusted</em>). Disarankan jika audit sudah final.
                        </p>
                    </label>
                </div>
            </div>

            <!-- General Notes Field -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                    Catatan Umum / Berita Acara Opname:
                </label>
                <textarea id="modalGeneralNotesInput" 
                          rows="2" 
                          placeholder="Contoh: Stock Opname Rutin Bulanan Gudang Utama..." 
                          class="w-full text-xs p-3 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
            <button type="button" onclick="closeReviewModal()" class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 rounded-lg text-xs font-medium transition">
                Batal & Lanjut Hitung
            </button>
            <button type="button" onclick="submitFinalCount()" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition shadow-sm flex items-center gap-2">
                <x-icon name="check-circle" class="w-4 h-4" />
                <span>Konfirmasi & Simpan Permanen</span>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ==========================================
    // AUDIO BEEP SYNTHESIS (Web Audio API)
    // ==========================================
    let audioCtx = null;
    function playBeep(type = 'success') {
        if (!document.getElementById('audioBeepToggle')?.checked) return;
        try {
            if (!audioCtx) {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);

            if (type === 'success') {
                osc.frequency.setValueAtTime(880, audioCtx.currentTime); // A5
                gain.gain.setValueAtTime(0.15, audioCtx.currentTime);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.08);
            } else if (type === 'error') {
                osc.frequency.setValueAtTime(300, audioCtx.currentTime);
                gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.15);
            }
        } catch (e) {
            console.log('Audio error:', e);
        }
    }

    // ==========================================
    // STATE & ROW CALCULATIONS
    // ==========================================
    let currentFilterTab = 'all';

    document.addEventListener('DOMContentLoaded', function() {
        recalculateAllStats();

        const quickInput = document.getElementById('barcodeQuickInput');
        if (quickInput) {
            quickInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    triggerBarcodeScan();
                }
            });
        }
    });

    function stepCount(productId, step) {
        const input = document.getElementById(`physical-${productId}`);
        if (!input) return;
        let current = parseInt(input.value) || 0;
        current = Math.max(0, current + step);
        input.value = current;
        input.dataset.touched = "true";
        calculateRowDiff(productId);
        playBeep('success');
    }

    function setPhysicalDirectly(productId, val) {
        const input = document.getElementById(`physical-${productId}`);
        if (!input) return;
        input.value = Math.max(0, parseInt(val) || 0);
        input.dataset.touched = "true";
        calculateRowDiff(productId);
        playBeep('success');
    }

    function calculateRowDiff(productId) {
        const row = document.getElementById(`row-${productId}`);
        const input = document.getElementById(`physical-${productId}`);
        const badge = document.getElementById(`diff-badge-${productId}`);
        if (!row || !input || !badge) return;

        const sysStock = parseInt(row.dataset.systemStock) || 0;
        let physicalStock = parseInt(input.value) || 0;
        if (physicalStock < 0) {
            physicalStock = 0;
            input.value = 0;
        }
        const diff = physicalStock - sysStock;

        badge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold ';
        if (diff === 0) {
            badge.classList.add('diff-badge-match');
            badge.innerHTML = `<x-icon name="check" class="w-3.5 h-3.5" /> <span>0 Cocok</span>`;
        } else if (diff > 0) {
            badge.classList.add('diff-badge-surplus');
            badge.innerHTML = `<x-icon name="plus" class="w-3.5 h-3.5" /> <span>+${diff} Lebih</span>`;
        } else {
            badge.classList.add('diff-badge-deficit');
            badge.innerHTML = `<x-icon name="minus" class="w-3.5 h-3.5" /> <span>${diff} Kurang</span>`;
        }

        recalculateAllStats();
    }

    function recalculateAllStats() {
        const rows = document.querySelectorAll('.product-row');
        let totalCounted = 0;
        let totalMatched = 0;
        let totalSurplus = 0;
        let totalDeficit = 0;
        const total = rows.length;

        rows.forEach(row => {
            const id = row.dataset.id;
            const input = document.getElementById(`physical-${id}`);
            const sysStock = parseInt(row.dataset.systemStock) || 0;
            const physicalStock = parseInt(input.value) || 0;
            const diff = physicalStock - sysStock;

            if (diff === 0) {
                totalMatched++;
                row.dataset.status = 'match';
            } else if (diff > 0) {
                totalSurplus++;
                row.dataset.status = 'discrepancy';
            } else {
                totalDeficit++;
                row.dataset.status = 'discrepancy';
            }

            if (input.dataset.touched === "true" || input.value !== "") {
                totalCounted++;
            }
        });

        const discrepancyCount = totalSurplus + totalDeficit;
        const accuracy = total > 0 ? Math.round((totalMatched / total) * 100) : 100;
        const progressPct = total > 0 ? Math.round((totalCounted / total) * 100) : 0;

        // Update Top KPIs
        const kpiCounted = document.getElementById('kpiCounted');
        const kpiMatched = document.getElementById('kpiMatched');
        const kpiSurplus = document.getElementById('kpiSurplus');
        const kpiDeficit = document.getElementById('kpiDeficit');
        const kpiAccuracy = document.getElementById('kpiAccuracy');

        if (kpiCounted) kpiCounted.textContent = totalCounted;
        if (kpiMatched) kpiMatched.textContent = totalMatched;
        if (kpiSurplus) kpiSurplus.textContent = totalSurplus;
        if (kpiDeficit) kpiDeficit.textContent = totalDeficit;
        if (kpiAccuracy) kpiAccuracy.textContent = `${accuracy}%`;

        // Update Progress Bar
        const progressFill = document.getElementById('progressBarFill');
        const progressText = document.getElementById('progressPercentageText');
        const countedText = document.getElementById('countedCountText');
        const uncountedText = document.getElementById('uncountedCountText');

        if (progressFill) progressFill.style.width = `${progressPct}%`;
        if (progressText) progressText.textContent = `${progressPct}%`;
        if (countedText) countedText.textContent = totalCounted;
        if (uncountedText) uncountedText.textContent = `${total - totalCounted} belum diisi`;

        // Update Tab Badges
        const bAll = document.getElementById('badgeTabAll');
        const bUnc = document.getElementById('badgeTabUncounted');
        const bDisc = document.getElementById('badgeTabDiscrepancy');
        const bMatch = document.getElementById('badgeTabMatch');

        if (bAll) bAll.textContent = total;
        if (bUnc) bUnc.textContent = total - totalCounted;
        if (bDisc) bDisc.textContent = discrepancyCount;
        if (bMatch) bMatch.textContent = totalMatched;

        // Update Floating Bar
        const barCount = document.getElementById('barCounted');
        const barDisc = document.getElementById('barDiscrepancy');
        if (barCount) barCount.textContent = totalCounted;
        if (barDisc) barDisc.textContent = `${discrepancyCount} Item (${discrepancyCount > 0 ? 'Perlu review' : 'Aman'})`;

        applyFilters();
    }

    // ==========================================
    // BARCODE SCANNER LOGIC
    // ==========================================
    function triggerBarcodeScan() {
        const input = document.getElementById('barcodeQuickInput');
        const code = input ? input.value.trim() : '';
        if (!code) return;
        handleScannedCode(code);
        input.value = '';
        input.focus();
    }

    function handleScannedCode(code) {
        const query = code.toLowerCase();
        let targetRow = null;

        const rows = document.querySelectorAll('.product-row');
        for (let row of rows) {
            if (row.dataset.code === query || row.dataset.code.includes(query) || row.dataset.name.includes(query)) {
                targetRow = row;
                break;
            }
        }

        if (targetRow) {
            const id = targetRow.dataset.id;
            const scanMode = document.querySelector('input[name="scanMode"]:checked')?.value || 'increment';

            if (scanMode === 'increment') {
                stepCount(id, 1);
                showScanToast(`Berhasil: ${targetRow.querySelector('.font-bold').textContent} (+1 fisik)`);
            } else {
                const physicalInput = document.getElementById(`physical-${id}`);
                if (physicalInput) physicalInput.focus();
                showScanToast(`Fokus ke: ${targetRow.querySelector('.font-bold').textContent}`);
            }

            targetRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
            targetRow.classList.add('row-highlight-scanned');
            setTimeout(() => targetRow.classList.remove('row-highlight-scanned'), 1500);

            closeCameraModal();
        } else {
            playBeep('error');
            showScanToast(`SKU / Barang "${code}" tidak ditemukan!`, true);
        }
    }

    function showScanToast(msg, isError = false) {
        const feedback = document.getElementById('scanFeedbackMsg');
        if (!feedback) return;
        feedback.textContent = msg;
        feedback.className = isError ? 'font-semibold text-red-600 text-xs' : 'font-semibold text-green-600 text-xs';
        feedback.classList.remove('hidden');
        setTimeout(() => feedback.classList.add('hidden'), 3500);
    }

    // ==========================================
    // FILTERING & TAB SWITCHING
    // ==========================================
    function filterTab(tab, btn) {
        currentFilterTab = tab;
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('active', 'bg-white', 'text-blue-600', 'shadow-sm', 'border', 'border-gray-200');
            b.classList.add('text-gray-600');
        });
        btn.classList.add('active', 'bg-white', 'text-blue-600', 'shadow-sm', 'border', 'border-gray-200');
        btn.classList.remove('text-gray-600');
        applyFilters();
    }

    function quickFilterTab(tab) {
        const tabBtn = document.querySelector(`.tab-btn[onclick*="${tab}"]`);
        if (tabBtn) filterTab(tab, tabBtn);
    }

    function applyFilters() {
        const search = document.getElementById('tableSearchInput')?.value.toLowerCase().trim() || '';
        const category = document.getElementById('categoryFilter')?.value.toLowerCase().trim() || '';
        const rows = document.querySelectorAll('.product-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const id = row.dataset.id;
            const code = row.dataset.code;
            const name = row.dataset.name;
            const cat = row.dataset.category;
            const status = row.dataset.status;
            const input = document.getElementById(`physical-${id}`);
            const isUncounted = input.dataset.touched !== "true" && input.value === "";

            let matchTab = true;
            if (currentFilterTab === 'uncounted') matchTab = isUncounted;
            else if (currentFilterTab === 'discrepancy') matchTab = status === 'discrepancy';
            else if (currentFilterTab === 'match') matchTab = status === 'match';

            let matchCat = !category || cat.includes(category);
            let matchSearch = !search || code.includes(search) || name.includes(search) || cat.includes(search);

            if (matchTab && matchCat && matchSearch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const noResult = document.getElementById('noFilterResults');
        if (noResult) {
            noResult.classList.toggle('hidden', visibleCount > 0 || rows.length === 0);
        }
    }

    function resetFilters() {
        const searchInput = document.getElementById('tableSearchInput');
        const catFilter = document.getElementById('categoryFilter');
        if (searchInput) searchInput.value = '';
        if (catFilter) catFilter.value = '';
        const allBtn = document.querySelector('.tab-btn[onclick*="all"]');
        if (allBtn) filterTab('all', allBtn);
    }

    // ==========================================
    // MASS ACTIONS
    // ==========================================
    function setAllToSystem() {
        Swal.fire({
            title: 'Set Semua ke Stok Sistem?',
            text: 'Seluruh kolom stok fisik akan disamakan dengan stok buku sistem saat ini.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Samakan Semua',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#2563EB'
        }).then((result) => {
            if (result.isConfirmed) {
                const rows = document.querySelectorAll('.product-row');
                rows.forEach(row => {
                    const id = row.dataset.id;
                    const sysStock = parseInt(row.dataset.systemStock) || 0;
                    const input = document.getElementById(`physical-${id}`);
                    if (input) {
                        input.value = sysStock;
                        input.dataset.touched = "true";
                        calculateRowDiff(id);
                    }
                });
                playBeep('success');
                Swal.fire('Berhasil', 'Seluruh stok fisik berhasil disamakan ke stok sistem.', 'success');
            }
        });
    }

    function quickFillUncounted() {
        const rows = document.querySelectorAll('.product-row');
        let updated = 0;
        rows.forEach(row => {
            const id = row.dataset.id;
            const sysStock = parseInt(row.dataset.systemStock) || 0;
            const input = document.getElementById(`physical-${id}`);
            if (input && (input.dataset.touched !== "true" || input.value === "")) {
                input.value = sysStock;
                input.dataset.touched = "true";
                calculateRowDiff(id);
                updated++;
            }
        });
        playBeep('success');
        showScanToast(`Berhasil mengisi ${updated} item yang belum dihitung.`);
    }

    function resetAllInputs() {
        Swal.fire({
            title: 'Reset Seluruh Hitungan?',
            text: 'Semua nilai stok fisik akan dikosongkan/reset ke 0.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Reset ke 0',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#EF4444'
        }).then((result) => {
            if (result.isConfirmed) {
                const rows = document.querySelectorAll('.product-row');
                rows.forEach(row => {
                    const id = row.dataset.id;
                    const input = document.getElementById(`physical-${id}`);
                    if (input) {
                        input.value = 0;
                        input.dataset.touched = "true";
                        calculateRowDiff(id);
                    }
                });
                playBeep('error');
            }
        });
    }

    // ==========================================
    // MODAL HANDLERS
    // ==========================================
    function openCameraModal() {
        const modal = document.getElementById('cameraModal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeCameraModal() {
        const modal = document.getElementById('cameraModal');
        if (modal) modal.classList.add('hidden');
    }

    function openReviewModal() {
        const rows = document.querySelectorAll('.product-row');
        const listBody = document.getElementById('discrepancyListBody');
        if (listBody) listBody.innerHTML = '';

        let matched = 0;
        let surplus = 0;
        let deficit = 0;
        let discrepancyRows = 0;

        rows.forEach(row => {
            const id = row.dataset.id;
            const code = row.dataset.code.toUpperCase();
            const name = row.querySelector('.font-bold')?.textContent || '-';
            const sysStock = parseInt(row.dataset.systemStock) || 0;
            const input = document.getElementById(`physical-${id}`);
            const noteInput = document.getElementById(`notes-${id}`);
            const physicalStock = parseInt(input?.value) || 0;
            const diff = physicalStock - sysStock;
            const note = noteInput ? noteInput.value : '';

            if (diff === 0) {
                matched++;
            } else {
                discrepancyRows++;
                if (diff > 0) surplus++;
                else deficit++;

                if (listBody) {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-gray-50';
                    tr.innerHTML = `
                        <td class="p-2.5">
                            <span class="font-mono font-semibold text-blue-600">${code}</span>
                            <div class="font-medium text-gray-800">${name}</div>
                        </td>
                        <td class="p-2.5 text-center font-semibold text-gray-700">${sysStock}</td>
                        <td class="p-2.5 text-center font-bold text-gray-900">${physicalStock}</td>
                        <td class="p-2.5 text-center">
                            <span class="px-2 py-0.5 rounded font-semibold ${diff > 0 ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700'}">
                                ${diff > 0 ? '+' + diff : diff}
                            </span>
                        </td>
                        <td class="p-2.5 text-gray-500 italic">${note || '-'}</td>
                    `;
                    listBody.appendChild(tr);
                }
            }
        });

        if (discrepancyRows === 0 && listBody) {
            listBody.innerHTML = `
                <tr>
                    <td colspan="5" class="p-6 text-center text-green-600 font-medium">
                        <x-icon name="check-circle" class="w-5 h-5 mb-1" />
                        <div>Luar biasa! Seluruh ${rows.length} barang cocok 100% tanpa selisih.</div>
                    </td>
                </tr>
            `;
        }

        const mMatched = document.getElementById('modalMatchedCount');
        const mSurplus = document.getElementById('modalSurplusCount');
        const mDeficit = document.getElementById('modalDeficitCount');
        const mDiscrepancy = document.getElementById('modalDiscrepancyTotal');

        if (mMatched) mMatched.textContent = matched;
        if (mSurplus) mSurplus.textContent = surplus;
        if (mDeficit) mDeficit.textContent = deficit;
        if (mDiscrepancy) mDiscrepancy.textContent = discrepancyRows;

        const revModal = document.getElementById('reviewModal');
        if (revModal) revModal.classList.remove('hidden');
    }

    function closeReviewModal() {
        const revModal = document.getElementById('reviewModal');
        if (revModal) revModal.classList.add('hidden');
    }

    function submitFinalCount() {
        const selectedMode = document.querySelector('input[name="modal_adj_mode"]:checked')?.value || 'save_only';
        const generalNotes = document.getElementById('modalGeneralNotesInput')?.value || '';

        const fMode = document.getElementById('formAdjustmentMode');
        const fNotes = document.getElementById('formGeneralNotes');
        if (fMode) fMode.value = selectedMode;
        if (fNotes) fNotes.value = generalNotes;

        document.getElementById('stockCountForm')?.submit();
    }
</script>
@endpush
