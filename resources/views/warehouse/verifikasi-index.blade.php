@extends('layouts.app')

@section('title', 'Verifikasi Barang Masuk - LogiBook WMS')

@section('content')
<div class="container-fluid px-4 py-4 max-w-7xl mx-auto">
    <!-- Header Page & Title -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="bg-indigo-50 text-indigo-700 font-semibold px-2.5 py-0.5 rounded-full text-xs inline-flex items-center gap-1.5 border border-indigo-100">
                    <i class="fas fa-clipboard-check text-indigo-500"></i> Inbound Quality Check
                </span>
                <span class="text-slate-300">•</span>
                <span class="text-slate-500 text-xs font-medium">LogiBook WMS</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Verifikasi Barang Masuk</h1>
            <p class="text-slate-500 text-sm mt-0.5">Pemeriksaan fisik dan pencocokan kuantitas barang sebelum stok resmi ditambahkan.</p>
        </div>
        <div>
            <a href="{{ route('warehouse.incoming-goods') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-all shadow-sm shadow-indigo-500/20 active:scale-95">
                <i class="fas fa-plus-circle"></i>
                <span>Input Barang Masuk Baru</span>
            </a>
        </div>
    </div>

    <!-- Live KPI Summary Counters (4 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <!-- Card 1: Menunggu -->
        <a href="{{ route('warehouse.verifikasi.index', ['status' => 'Pending']) }}" class="block group text-decoration-none">
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md hover:border-blue-300 transition-all h-full relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">MENUNGGU VERIFIKASI</p>
                        <h3 class="text-2xl font-extrabold text-slate-900 mt-1 mb-0.5">{{ $stats['pending'] ?? 0 }}</h3>
                        <div class="text-xs text-blue-600 font-medium flex items-center gap-1 mt-1">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-600"></span>
                            </span>
                            Perlu Pemeriksaan Fisik
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <i class="fas fa-boxes-packing text-xl"></i>
                    </div>
                </div>
            </div>
        </a>

        <!-- Card 2: Direvisi -->
        <a href="{{ route('warehouse.verifikasi.index', ['status' => 'Revised']) }}" class="block group text-decoration-none">
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md hover:border-indigo-300 transition-all h-full relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">TELAH DIREVISI</p>
                        <h3 class="text-2xl font-extrabold text-slate-900 mt-1 mb-0.5">{{ $stats['revised'] ?? 0 }}</h3>
                        <div class="text-xs text-indigo-600 font-medium flex items-center gap-1 mt-1">
                            <i class="fas fa-pen-to-square"></i> Penyesuaian Qty
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <i class="fas fa-edit text-xl"></i>
                    </div>
                </div>
            </div>
        </a>

        <!-- Card 3: Terverifikasi -->
        <a href="{{ route('warehouse.verifikasi.index', ['status' => 'Verified']) }}" class="block group text-decoration-none">
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md hover:border-emerald-300 transition-all h-full relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">TERVERIFIKASI (SELESAI)</p>
                        <h3 class="text-2xl font-extrabold text-slate-900 mt-1 mb-0.5">{{ $stats['verified'] ?? 0 }}</h3>
                        <div class="text-xs text-emerald-600 font-medium flex items-center gap-1 mt-1">
                            <i class="fas fa-circle-check"></i> Stok Berhasil Masuk
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <i class="fas fa-check-double text-xl"></i>
                    </div>
                </div>
            </div>
        </a>

        <!-- Card 4: Dibatalkan -->
        <a href="{{ route('warehouse.verifikasi.index', ['status' => 'Canceled']) }}" class="block group text-decoration-none">
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md hover:border-rose-300 transition-all h-full relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">DIBATALKAN / DITOLAK</p>
                        <h3 class="text-2xl font-extrabold text-slate-900 mt-1 mb-0.5">{{ $stats['canceled'] ?? 0 }}</h3>
                        <div class="text-xs text-rose-600 font-medium flex items-center gap-1 mt-1">
                            <i class="fas fa-ban"></i> Tidak Masuk Stok
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <i class="fas fa-ban text-xl"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Filters & Main Data Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden mb-6">
        <!-- Filter Header Bar -->
        <div class="p-4 border-b border-slate-100 bg-slate-50/50">
            <form method="GET" action="{{ route('warehouse.verifikasi.index') }}" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
                
                <!-- Search Box -->
                <div class="relative flex-1 max-w-md">
                    <i class="fas fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" 
                           class="w-full pl-9 pr-4 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-xs transition" 
                           placeholder="Cari No. Penerimaan (GR) atau Supplier..." 
                           value="{{ request('search') }}">
                </div>

                <!-- Status Filter Pills Bar -->
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('warehouse.verifikasi.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}" 
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold inline-flex items-center gap-1 transition-all {{ request('status', 'Pending') === 'all' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                       Semua
                    </a>
                    
                    <a href="{{ route('warehouse.verifikasi.index', array_merge(request()->except('status', 'page'), ['status' => 'Pending'])) }}" 
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold inline-flex items-center gap-1.5 transition-all {{ request('status', 'Pending') === 'Pending' ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/20' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                       <span class="w-1.5 h-1.5 rounded-full {{ request('status', 'Pending') === 'Pending' ? 'bg-white' : 'bg-blue-500' }}"></span>
                       Menunggu ({{ $stats['pending'] ?? 0 }})
                    </a>
                    
                    <a href="{{ route('warehouse.verifikasi.index', array_merge(request()->except('status', 'page'), ['status' => 'Revised'])) }}" 
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold inline-flex items-center gap-1 transition-all {{ request('status') === 'Revised' ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-500/20' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                       <i class="fas fa-pen-to-square"></i> Direvisi ({{ $stats['revised'] ?? 0 }})
                    </a>

                    <a href="{{ route('warehouse.verifikasi.index', array_merge(request()->except('status', 'page'), ['status' => 'Verified'])) }}" 
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold inline-flex items-center gap-1 transition-all {{ request('status') === 'Verified' ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-500/20' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                       <i class="fas fa-circle-check"></i> Selesai ({{ $stats['verified'] ?? 0 }})
                    </a>

                    <a href="{{ route('warehouse.verifikasi.index', array_merge(request()->except('status', 'page'), ['status' => 'Canceled'])) }}" 
                       class="px-3.5 py-1.5 rounded-full text-xs font-semibold inline-flex items-center gap-1 transition-all {{ request('status') === 'Canceled' ? 'bg-rose-600 text-white shadow-sm shadow-rose-500/20' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                       <i class="fas fa-ban"></i> Batal ({{ $stats['canceled'] ?? 0 }})
                    </a>

                    @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('warehouse.verifikasi.index') }}" class="p-1.5 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition" title="Reset Filter">
                        <i class="fas fa-rotate-left text-xs"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50/90 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200/80">
                        <th class="px-5 py-3.5">Tanggal Penerimaan</th>
                        <th class="py-3.5">No. Penerimaan (GR)</th>
                        <th class="py-3.5">Supplier / Vendor</th>
                        <th class="py-3.5 text-center">Ringkasan Barang</th>
                        <th class="py-3.5 text-center">Status Verifikasi</th>
                        <th class="text-right px-5 py-3.5">Aksi Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($incomingGoods as $good)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <!-- Tanggal -->
                        <td class="px-5 py-4 text-slate-700">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center flex-shrink-0">
                                    <i class="far fa-calendar-alt text-xs"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-xs">{{ \Carbon\Carbon::parse($good->receive_date)->format('d M Y') }}</div>
                                    <div class="text-[11px] text-slate-400">{{ \Carbon\Carbon::parse($good->created_at)->format('H:i') }} WIB</div>
                                </div>
                            </div>
                        </td>

                        <!-- Receipt Number -->
                        <td class="py-4">
                            <span class="font-mono font-bold text-xs text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-100">
                                {{ $good->receipt_number }}
                            </span>
                        </td>

                        <!-- Supplier -->
                        <td class="py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-bold flex items-center justify-center text-xs flex-shrink-0 border border-slate-200">
                                    {{ strtoupper(substr($good->supplier->company_name ?? $good->supplier->name ?? 'S', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-xs">{{ $good->supplier->company_name ?? $good->supplier->name ?? '-' }}</div>
                                    <div class="text-[11px] text-slate-400 flex items-center gap-1">
                                        <i class="fas fa-location-dot text-[10px]"></i>
                                        {{ $good->supplier->city ?? 'Supplier Partner' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Ringkasan Items -->
                        <td class="py-4 text-center">
                            @php
                                $itemTypesCount = $good->items ? $good->items->count() : 0;
                                $totalQtySum = $good->items ? $good->items->sum('quantity') : 0;
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                <i class="fas fa-boxes-stacked text-indigo-500 text-[10px]"></i>
                                {{ $itemTypesCount }} Jenis • {{ number_format($totalQtySum) }} Unit
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="py-4 text-center">
                            @if($good->status == 'Pending')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/80">
                                    <span class="relative flex h-1.5 w-1.5">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-blue-600"></span>
                                    </span>
                                    Menunggu QC
                                </span>
                            @elseif($good->status == 'Revised')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200/80">
                                    <i class="fas fa-pen-to-square text-[10px]"></i> Telah Direvisi
                                </span>
                            @elseif($good->status == 'Verified')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                    <i class="fas fa-circle-check text-[10px]"></i> Terverifikasi
                                </span>
                            @elseif($good->status == 'Canceled')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/80">
                                    <i class="fas fa-ban text-[10px]"></i> Dibatalkan
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs bg-slate-100 text-slate-700">{{ $good->status }}</span>
                            @endif
                        </td>

                        <!-- Action -->
                        <td class="text-right px-5 py-4">
                            @if($good->status == 'Pending' || $good->status == 'Revised')
                                <a href="{{ route('warehouse.verifikasi.show', $good->id) }}" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-xs">
                                    <i class="fas fa-clipboard-check"></i>
                                    <span>Verifikasi</span>
                                </a>
                            @else
                                <a href="{{ route('warehouse.verifikasi.show', $good->id) }}" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition shadow-xs">
                                    <i class="fas fa-eye text-slate-400"></i>
                                    <span>Detail</span>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-boxes-packing text-2xl"></i>
                            </div>
                            <h3 class="font-bold text-slate-800 text-sm mb-1">Tidak Ada Data Verifikasi Barang Masuk</h3>
                            <p class="text-xs text-slate-500 max-w-sm mx-auto mb-4">Semua data penerimaan barang fisik telah diperiksa atau belum ada draf penerimaan baru.</p>
                            <a href="{{ route('warehouse.incoming-goods') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition">
                                <i class="fas fa-plus"></i>
                                <span>Buat Penerimaan Barang Masuk</span>
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Footer -->
        @include('partials.pagination', ['paginator' => $incomingGoods])
    </div>
</div>
@endsection
