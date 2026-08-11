@extends('layouts.app')

@section('title', 'Verifikasi Barang Masuk - LogiBook WMS')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Page & Title -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-warning-subtle text-warning-emphasis fw-semibold px-2.5 py-1 rounded-pill text-xs d-inline-flex align-items-center gap-1">
                    <i class="fas fa-clipboard-check"></i> Inbound Quality Check
                </span>
                <span class="text-muted text-xs">• LogiBook WMS</span>
            </div>
            <h1 class="h3 font-bold text-gray-900 mb-0">Verifikasi Barang Masuk</h1>
            <p class="text-muted text-sm mb-0">Pemeriksaan fisik dan pencocokan kuantitas barang sebelum stok ditambahkan.</p>
        </div>
        <div>
            <a href="{{ route('warehouse.incoming-goods') }}" class="btn btn-primary rounded-3 shadow-sm d-inline-flex align-items-center gap-2 px-3 py-2 text-sm font-semibold">
                <i class="fas fa-plus-circle"></i> Input Barang Masuk Baru
            </a>
        </div>
    </div>

    <!-- Live KPI Summary Counters -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Menunggu -->
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="{{ route('warehouse.verifikasi.index', ['status' => 'Pending']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-squircle p-3.5 bg-white hover:shadow-md transition-all border-start border-4 border-warning h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted text-xs font-semibold text-uppercase tracking-wider">Menunggu Verifikasi</div>
                            <div class="h2 font-bold text-gray-900 mb-0 mt-1">{{ $stats['pending'] ?? 0 }}</div>
                            <div class="text-xs text-warning font-medium mt-1 d-flex align-items-center gap-1">
                                <i class="fas fa-clock"></i> Perlu Pencocokan Fisik
                            </div>
                        </div>
                        <div class="rounded-3 bg-amber-50 text-amber-600 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fas fa-boxes-packing fa-lg"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Card 2: Direvisi -->
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="{{ route('warehouse.verifikasi.index', ['status' => 'Revised']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-squircle p-3.5 bg-white hover:shadow-md transition-all border-start border-4 border-info h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted text-xs font-semibold text-uppercase tracking-wider">Telah Direvisi</div>
                            <div class="h2 font-bold text-gray-900 mb-0 mt-1">{{ $stats['revised'] ?? 0 }}</div>
                            <div class="text-xs text-info font-medium mt-1 d-flex align-items-center gap-1">
                                <i class="fas fa-pen-to-square"></i> Penyesuaian Qty
                            </div>
                        </div>
                        <div class="rounded-3 bg-blue-50 text-blue-600 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fas fa-edit fa-lg"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Card 3: Terverifikasi -->
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="{{ route('warehouse.verifikasi.index', ['status' => 'Verified']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-squircle p-3.5 bg-white hover:shadow-md transition-all border-start border-4 border-success h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted text-xs font-semibold text-uppercase tracking-wider">Terverifikasi (Selesai)</div>
                            <div class="h2 font-bold text-gray-900 mb-0 mt-1">{{ $stats['verified'] ?? 0 }}</div>
                            <div class="text-xs text-success font-medium mt-1 d-flex align-items-center gap-1">
                                <i class="fas fa-check-circle"></i> Stok Berhasil Masuk
                            </div>
                        </div>
                        <div class="rounded-3 bg-emerald-50 text-emerald-600 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fas fa-check-double fa-lg"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Card 4: Dibatalkan -->
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="{{ route('warehouse.verifikasi.index', ['status' => 'Canceled']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-squircle p-3.5 bg-white hover:shadow-md transition-all border-start border-4 border-danger h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted text-xs font-semibold text-uppercase tracking-wider">Dibatalkan / Ditolak</div>
                            <div class="h2 font-bold text-gray-900 mb-0 mt-1">{{ $stats['canceled'] ?? 0 }}</div>
                            <div class="text-xs text-danger font-medium mt-1 d-flex align-items-center gap-1">
                                <i class="fas fa-ban"></i> Tidak Masuk Stok
                            </div>
                        </div>
                        <div class="rounded-3 bg-rose-50 text-rose-600 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fas fa-ban fa-lg"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Filters & Main Data Card -->
    <div class="card border-0 shadow-sm rounded-squircle overflow-hidden mb-4 bg-white">
        <!-- Filter Header Bar -->
        <div class="p-3.5 border-bottom bg-slate-50/50">
            <form method="GET" action="{{ route('warehouse.verifikasi.index') }}" class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center justify-content-between gap-3">
                
                <!-- Search Box -->
                <div class="position-relative flex-grow-1" style="max-width: 420px;">
                    <i class="fas fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted text-xs"></i>
                    <input type="text" name="search" class="form-control form-control-sm ps-5 pe-3 py-2 rounded-3 bg-white border shadow-xs text-xs" placeholder="Cari No. Penerimaan (GR) atau Supplier..." value="{{ request('search') }}">
                </div>

                <!-- Status Filter Pills Bar -->
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('warehouse.verifikasi.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}" 
                       class="text-decoration-none px-3 py-1.5 rounded-pill text-xs font-semibold d-inline-flex align-items-center gap-1 transition-all {{ request('status', 'Pending') === 'all' ? 'bg-dark text-white shadow-xs' : 'bg-white border text-gray-600 hover:bg-slate-100' }}">
                       Semua
                    </a>
                    
                    <a href="{{ route('warehouse.verifikasi.index', array_merge(request()->except('status', 'page'), ['status' => 'Pending'])) }}" 
                       class="text-decoration-none px-3 py-1.5 rounded-pill text-xs font-semibold d-inline-flex align-items-center gap-1 transition-all {{ request('status', 'Pending') === 'Pending' ? 'bg-warning text-dark shadow-xs' : 'bg-white border text-gray-600 hover:bg-slate-100' }}">
                       <i class="fas fa-clock"></i> Menunggu ({{ $stats['pending'] ?? 0 }})
                    </a>
                    
                    <a href="{{ route('warehouse.verifikasi.index', array_merge(request()->except('status', 'page'), ['status' => 'Revised'])) }}" 
                       class="text-decoration-none px-3 py-1.5 rounded-pill text-xs font-semibold d-inline-flex align-items-center gap-1 transition-all {{ request('status') === 'Revised' ? 'bg-info text-white shadow-xs' : 'bg-white border text-gray-600 hover:bg-slate-100' }}">
                       <i class="fas fa-pen-to-square"></i> Direvisi ({{ $stats['revised'] ?? 0 }})
                    </a>

                    <a href="{{ route('warehouse.verifikasi.index', array_merge(request()->except('status', 'page'), ['status' => 'Verified'])) }}" 
                       class="text-decoration-none px-3 py-1.5 rounded-pill text-xs font-semibold d-inline-flex align-items-center gap-1 transition-all {{ request('status') === 'Verified' ? 'bg-success text-white shadow-xs' : 'bg-white border text-gray-600 hover:bg-slate-100' }}">
                       <i class="fas fa-check-circle"></i> Selesai ({{ $stats['verified'] ?? 0 }})
                    </a>

                    <a href="{{ route('warehouse.verifikasi.index', array_merge(request()->except('status', 'page'), ['status' => 'Canceled'])) }}" 
                       class="text-decoration-none px-3 py-1.5 rounded-pill text-xs font-semibold d-inline-flex align-items-center gap-1 transition-all {{ request('status') === 'Canceled' ? 'bg-danger text-white shadow-xs' : 'bg-white border text-gray-600 hover:bg-slate-100' }}">
                       <i class="fas fa-ban"></i> Batal ({{ $stats['canceled'] ?? 0 }})
                    </a>

                    @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('warehouse.verifikasi.index') }}" class="btn btn-outline-secondary btn-sm px-2.5 py-1 rounded-pill text-xs ms-1" title="Reset Filter">
                        <i class="fas fa-rotate-left"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table View -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-slate-100 text-gray-700 text-xs text-uppercase font-semibold">
                    <tr>
                        <th class="px-4 py-3.5">Tanggal Penerimaan</th>
                        <th class="py-3.5">No. Penerimaan (GR)</th>
                        <th class="py-3.5">Supplier / Vendor</th>
                        <th class="py-3.5 text-center">Ringkasan Barang</th>
                        <th class="py-3.5 text-center">Status Verifikasi</th>
                        <th class="text-end px-4 py-3.5">Aksi Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($incomingGoods as $good)
                    <tr class="hover:bg-slate-50 transition-all">
                        <!-- Tanggal -->
                        <td class="px-4 text-gray-700 font-medium">
                            <div class="d-flex align-items-center gap-2">
                                <div class="p-2 bg-slate-100 text-muted rounded-2 d-inline-flex">
                                    <i class="far fa-calendar-alt"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold text-gray-900">{{ \Carbon\Carbon::parse($good->receive_date)->format('d M Y') }}</div>
                                    <div class="text-xs text-muted">{{ \Carbon\Carbon::parse($good->created_at)->format('H:i') }} WIB</div>
                                </div>
                            </div>
                        </td>

                        <!-- Receipt Number -->
                        <td>
                            <span class="badge bg-primary-subtle text-primary font-mono fw-bold px-2.5 py-1.5 rounded-2 border border-primary-subtle">
                                <i class="fas fa-barcode me-1 text-primary"></i> {{ $good->receipt_number }}
                            </span>
                        </td>

                        <!-- Supplier -->
                        <td>
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle bg-slate-200 text-slate-700 fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px; font-size: 13px;">
                                    {{ strtoupper(substr($good->supplier->company_name ?? $good->supplier->name ?? 'S', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold text-gray-900">{{ $good->supplier->company_name ?? $good->supplier->name ?? '-' }}</div>
                                    <div class="text-xs text-muted"><i class="fas fa-city me-1"></i>{{ $good->supplier->city ?? 'Supplier Partner' }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Ringkasan Items -->
                        <td class="text-center">
                            @php
                                $itemTypesCount = $good->items ? $good->items->count() : 0;
                                $totalQtySum = $good->items ? $good->items->sum('quantity') : 0;
                            @endphp
                            <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill text-xs">
                                <i class="fas fa-box text-info me-1"></i> {{ $itemTypesCount }} Jenis • {{ $totalQtySum }} Unit
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="text-center">
                            @if($good->status == 'Pending')
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1.5 rounded-pill font-semibold text-xs d-inline-flex align-items-center gap-1.5">
                                    <span class="spinner-grow spinner-grow-sm text-warning" role="status" style="width: 0.5rem; height: 0.5rem;"></span>
                                    Menunggu Verifikasi
                                </span>
                            @elseif($good->status == 'Revised')
                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-3 py-1.5 rounded-pill font-semibold text-xs d-inline-flex align-items-center gap-1.5">
                                    <i class="fas fa-pen-to-square"></i> Telah Direvisi
                                </span>
                            @elseif($good->status == 'Verified')
                                <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle px-3 py-1.5 rounded-pill font-semibold text-xs d-inline-flex align-items-center gap-1.5">
                                    <i class="fas fa-check-circle"></i> Terverifikasi (Stok Aktif)
                                </span>
                            @elseif($good->status == 'Canceled')
                                <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle px-3 py-1.5 rounded-pill font-semibold text-xs d-inline-flex align-items-center gap-1.5">
                                    <i class="fas fa-ban"></i> Dibatalkan / Ditolak
                                </span>
                            @else
                                <span class="badge bg-secondary text-white px-2.5 py-1 rounded-pill text-xs">{{ $good->status }}</span>
                            @endif
                        </td>

                        <!-- Action -->
                        <td class="text-end px-4">
                            @if($good->status == 'Pending' || $good->status == 'Revised')
                                <a href="{{ route('warehouse.verifikasi.show', $good->id) }}" class="btn btn-primary btn-sm rounded-3 font-medium px-3 py-1.5 d-inline-flex align-items-center gap-1.5 shadow-xs">
                                    <i class="fas fa-clipboard-check"></i> Proses Verifikasi
                                </a>
                            @else
                                <a href="{{ route('warehouse.verifikasi.show', $good->id) }}" class="btn btn-outline-secondary btn-sm rounded-3 font-medium px-3 py-1.5 d-inline-flex align-items-center gap-1.5">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="py-4">
                                <div class="mb-3">
                                    <div class="p-3 bg-slate-100 text-slate-400 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                                        <i class="fas fa-boxes-packing fa-2xl"></i>
                                    </div>
                                </div>
                                <h6 class="fw-bold text-gray-800 mb-1">Tidak Ada Data Verifikasi Barang Masuk</h6>
                                <p class="text-muted text-xs mb-3">Semua data penerimaan barang fisik telah diperiksa atau belum ada draf penerimaan baru.</p>
                                <a href="{{ route('warehouse.incoming-goods') }}" class="btn btn-outline-primary btn-sm rounded-3 px-3">
                                    <i class="fas fa-plus me-1"></i> Buat Penerimaan Barang Masuk
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($incomingGoods->hasPages())
        <div class="p-3.5 border-top bg-slate-50/50 d-flex justify-content-between align-items-center">
            <div class="text-xs text-muted">
                Menampilkan {{ $incomingGoods->firstItem() }} - {{ $incomingGoods->lastItem() }} dari total {{ $incomingGoods->total() }} transaksi penerimaan
            </div>
            <div>
                {{ $incomingGoods->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
