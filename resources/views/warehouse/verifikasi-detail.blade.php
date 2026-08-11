@extends('layouts.app')

@section('title', 'Detail & Quality Control Verifikasi Barang Masuk - LogiBook WMS')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Navigation & Actions -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('warehouse.verifikasi.index') }}" class="text-decoration-none text-muted text-xs font-semibold hover:text-primary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Verifikasi
                </a>
                <span class="text-muted text-xs">• QC Physical Check</span>
            </div>
            <h1 class="h3 font-bold text-gray-900 mb-0 d-flex align-items-center gap-2">
                Verifikasi Penerimaan: <span class="text-primary font-mono">{{ $incomingGood->receipt_number }}</span>
            </h1>
            <p class="text-muted text-sm mb-0">Formulir pemeriksaan fisik barang, pencocokan kuantitas, dan penyesuaian stok gudang.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if($incomingGood->status == 'Pending')
                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2 rounded-pill font-semibold text-xs d-inline-flex align-items-center gap-1.5">
                    <span class="spinner-grow spinner-grow-sm text-warning" role="status" style="width: 0.5rem; height: 0.5rem;"></span>
                    Menunggu Pencocokan Fisik
                </span>
            @elseif($incomingGood->status == 'Revised')
                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-3 py-2 rounded-pill font-semibold text-xs d-inline-flex align-items-center gap-1.5">
                    <i class="fas fa-pen-to-square"></i> Telah Direvisi
                </span>
            @elseif($incomingGood->status == 'Verified')
                <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle px-3 py-2 rounded-pill font-semibold text-xs d-inline-flex align-items-center gap-1.5">
                    <i class="fas fa-circle-check"></i> Terverifikasi (Stok Bertambah)
                </span>
            @elseif($incomingGood->status == 'Canceled')
                <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle px-3 py-2 rounded-pill font-semibold text-xs d-inline-flex align-items-center gap-1.5">
                    <i class="fas fa-ban"></i> Penerimaan Dibatalkan
                </span>
            @endif
        </div>
    </div>

    <!-- Info Overview Banner -->
    <div class="card border-0 shadow-sm rounded-squircle bg-white mb-4 overflow-hidden">
        <div class="card-body p-4">
            <div class="row g-4 align-items-center">
                <!-- Info 1: Supplier -->
                <div class="col-md-4 border-end-md">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 bg-primary-subtle text-primary rounded-3 flex-shrink-0" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-building fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-xs text-muted text-uppercase fw-semibold">Supplier / Vendor</div>
                            <div class="h6 font-bold text-gray-900 mb-0 mt-0.5">{{ $incomingGood->supplier->company_name ?? $incomingGood->supplier->name ?? 'Supplier Umum' }}</div>
                            <div class="text-xs text-muted"><i class="fas fa-location-dot me-1"></i>{{ $incomingGood->supplier->city ?? 'Lokasi Vendor' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Info 2: Date & Receipt Number -->
                <div class="col-md-4 border-end-md">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 bg-indigo-subtle text-indigo rounded-3 flex-shrink-0" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-calendar-day fa-lg text-indigo-600"></i>
                        </div>
                        <div>
                            <div class="text-xs text-muted text-uppercase fw-semibold">Tanggal Penerimaan</div>
                            <div class="h6 font-bold text-gray-900 mb-0 mt-0.5">{{ \Carbon\Carbon::parse($incomingGood->receive_date)->format('d F Y') }}</div>
                            <div class="text-xs text-muted"><i class="fas fa-clock me-1"></i>Dibuat: {{ \Carbon\Carbon::parse($incomingGood->created_at)->format('H:i') }} WIB</div>
                        </div>
                    </div>
                </div>

                <!-- Info 3: Total Items Summary -->
                <div class="col-md-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 bg-emerald-subtle text-emerald rounded-3 flex-shrink-0" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-boxes-stacked fa-lg text-emerald-600"></i>
                        </div>
                        <div>
                            <div class="text-xs text-muted text-uppercase fw-semibold">Ringkasan Fisik</div>
                            <div class="h6 font-bold text-gray-900 mb-0 mt-0.5">
                                {{ $incomingGood->items->count() }} Jenis Item Barang
                            </div>
                            <div class="text-xs text-muted">
                                Total Qty: <strong class="text-gray-900">{{ $incomingGood->items->sum('quantity') }} Unit</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instruction Helper Banner -->
    @if($incomingGood->status == 'Pending' || $incomingGood->status == 'Revised')
    <div class="card border-0 shadow-sm mb-4 bg-gradient-to-r from-amber-50/80 via-blue-50/40 to-white rounded-squircle">
        <div class="card-body p-3.5 d-flex align-items-start gap-3">
            <div class="p-2.5 bg-amber-500 text-white rounded-3 shadow-xs flex-shrink-0">
                <i class="fas fa-circle-info fa-lg"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="fw-bold text-gray-900 mb-1 d-flex align-items-center gap-2">
                    <span>Petunjuk Verifikasi Fisik</span>
                    <span class="badge bg-warning text-dark rounded-pill font-normal text-xs">Penting</span>
                </h6>
                <p class="text-muted text-xs mb-0 leading-relaxed">
                    Periksa barang secara fisik di area penerimaan gudang. Uji sampel dan hitung jumlah aktual. 
                    Jika jumlah sesuai, tekan <strong>Verifikasi (Lengkap & Sesuai)</strong>. 
                    Jika ada selisih/kerusakan, ubah angka pada kolom <strong>QTY FISIK AKTUAL</strong> lalu klik <strong>Revisi</strong>. 
                    Jika seluruh pengiriman ditolak/batal, pilih <strong>Batalkan / Tolak Penerimaan</strong>.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Table Inspection Card -->
    <div class="card border-0 shadow-sm rounded-squircle overflow-hidden mb-4">
        <div class="card-header bg-white py-3.5 px-4 border-bottom d-flex align-items-center justify-content-between">
            <h5 class="fw-bold text-gray-900 mb-0 d-flex align-items-center gap-2 text-base">
                <i class="fas fa-table-list text-primary"></i> Tabel Inspection & Match Quantity
            </h5>
            <span class="text-xs text-muted">
                Status Data: <strong class="text-gray-900 font-mono">{{ $incomingGood->status }}</strong>
            </span>
        </div>
        <div class="card-body p-0">
            <form id="verifikasiForm">
                @csrf
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-slate-100 text-gray-700 text-xs text-uppercase font-semibold">
                            <tr>
                                <th width="5%" class="text-center py-3.5">#</th>
                                <th width="35%" class="py-3.5">Produk / Barang</th>
                                <th width="20%" class="py-3.5">Kode Barang (SKU)</th>
                                <th width="15%" class="text-center py-3.5 bg-slate-200/60">QTY Sistem (Nota)</th>
                                <th width="25%" class="text-center py-3.5 bg-amber-50">QTY Fisik Aktual <span class="text-danger">*</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($incomingGood->items as $index => $item)
                            <tr class="hover:bg-slate-50/80 transition-all">
                                <td class="text-center font-bold text-muted text-xs py-3.5">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="p-2 bg-blue-50 text-blue-600 rounded-2 flex-shrink-0">
                                            <i class="fas fa-box"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-gray-900">{{ $item->product->product_name ?? 'Produk' }}</div>
                                            <div class="text-xs text-muted">
                                                Stok Gudang Terkini: <strong class="text-info">{{ $item->product->system_stock ?? 0 }} {{ $item->product->unit ?? 'Pcs' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-slate-100 text-slate-700 font-mono px-2.5 py-1 rounded border">
                                        {{ $item->product->product_code ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-center bg-slate-50 font-bold text-gray-900 text-base">
                                    {{ $item->quantity }} <span class="text-xs font-normal text-muted">{{ $item->product->unit ?? 'Pcs' }}</span>
                                </td>
                                <td class="bg-amber-50/50 p-2.5">
                                    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                    @if($incomingGood->status == 'Pending' || $incomingGood->status == 'Revised')
                                    <div class="input-group input-group-sm max-w-xs mx-auto shadow-xs">
                                        <button type="button" class="btn btn-outline-secondary px-2.5" onclick="adjustQty(this, -1)">-</button>
                                        <input type="number" class="form-control text-center font-bold text-primary physical-qty" 
                                               name="items[{{ $index }}][quantity]" 
                                               value="{{ $item->quantity }}" 
                                               data-original="{{ $item->quantity }}"
                                               min="0" required onchange="checkQtyMismatch(this)" onkeyup="checkQtyMismatch(this)" style="font-size: 15px;">
                                        <button type="button" class="btn btn-outline-secondary px-2.5" onclick="adjustQty(this, 1)">+</button>
                                    </div>
                                    <div class="text-center text-xs mt-1 mismatch-label text-muted">
                                        <span>Status Qty: <strong class="text-success"><i class="fas fa-check-circle me-1"></i>Sesuai</strong></span>
                                    </div>
                                    @else
                                    <div class="text-center font-bold text-emerald-600 text-base">
                                        {{ $item->quantity }} <span class="text-xs font-normal text-muted">{{ $item->product->unit ?? 'Pcs' }}</span>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Footer Action Controls -->
                @if($incomingGood->status == 'Pending' || $incomingGood->status == 'Revised')
                <div class="card-footer bg-slate-50 p-4 border-top">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <!-- Left side: Cancel Button -->
                        <div>
                            <button type="button" class="btn btn-outline-danger px-3 py-2 rounded-3 text-xs font-semibold shadow-xs" onclick="submitVerifikasi('cancel')">
                                <i class="fas fa-ban me-1.5"></i> Batalkan / Tolak Penerimaan Ini
                            </button>
                        </div>

                        <!-- Right side: Submit Actions -->
                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                            <button type="button" class="btn btn-warning text-white px-4 py-2 rounded-3 font-semibold shadow-sm" onclick="submitVerifikasi('revise')">
                                <i class="fas fa-pen-to-square me-1.5"></i> Simpan Revisi Selisih
                            </button>
                            <button type="button" class="btn btn-success px-4 py-2 rounded-3 font-semibold shadow-sm" onclick="submitVerifikasi('verify')">
                                <i class="fas fa-circle-check me-1.5"></i> Verifikasi (Sesuai & Tambah Stok)
                            </button>
                        </div>
                    </div>
                </div>
                @else
                <div class="card-footer bg-slate-50 p-3.5 border-top d-flex justify-content-between align-items-center">
                    <div class="text-xs text-muted">
                        <i class="fas fa-lock me-1"></i> Transaksi penerimaan barang ini telah difinalisasi dan tidak dapat diubah lagi.
                    </div>
                    <a href="{{ route('warehouse.verifikasi.index') }}" class="btn btn-light btn-sm border px-3">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Verifikasi
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>

<!-- SweetAlert2 Scripts for interactive modern alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function adjustQty(btn, delta) {
    const group = btn.closest('.input-group');
    if (!group) return;
    const input = group.querySelector('.physical-qty');
    if (!input) return;
    
    let currentVal = parseInt(input.value) || 0;
    let newVal = Math.max(0, currentVal + delta);
    input.value = newVal;
    checkQtyMismatch(input);
}

function checkQtyMismatch(input) {
    const row = input.closest('tr');
    if (!row) return;
    const labelContainer = row.querySelector('.mismatch-label');
    const origQty = parseInt(input.dataset.original) || 0;
    const currentQty = parseInt(input.value) || 0;

    if (!labelContainer) return;

    if (currentQty === origQty) {
        labelContainer.innerHTML = '<span>Status Qty: <strong class="text-success"><i class="fas fa-check-circle me-1"></i>Sesuai</strong></span>';
    } else if (currentQty < origQty) {
        const diff = origQty - currentQty;
        labelContainer.innerHTML = `<span class="text-amber-600 font-semibold"><i class="fas fa-triangle-exclamation me-1"></i>Kurang ${diff} Unit dari Nota</span>`;
    } else {
        const diff = currentQty - origQty;
        labelContainer.innerHTML = `<span class="text-blue-600 font-semibold"><i class="fas fa-circle-plus me-1"></i>Lebih ${diff} Unit dari Nota</span>`;
    }
}

function submitVerifikasi(action) {
    const form = document.getElementById('verifikasiForm');
    const formData = new FormData(form);
    
    const items = [];
    let idx = 0;
    
    while (formData.has(`items[${idx}][id]`)) {
        items.push({
            id: formData.get(`items[${idx}][id]`),
            quantity: parseInt(formData.get(`items[${idx}][quantity]`) || 0)
        });
        idx++;
    }

    if (action === 'cancel') {
        Swal.fire({
            title: 'Batalkan Penerimaan Barang?',
            text: 'Dokumen penerimaan akan dibatalkan. Stok barang TIDAK AKAN ditambahkan ke sistem gudang.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Batalkan Penerimaan',
            cancelButtonText: 'Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                executeVerifikasiApi('cancel', items);
            }
        });
        return;
    }

    if (action === 'verify') {
        Swal.fire({
            title: 'Verifikasi & Tambah Stok?',
            text: 'Pastikan jumlah fisik sudah benar. Setelah diverifikasi, stok produk di gudang akan otomatis bertambah.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Verifikasi & Tambah Stok',
            cancelButtonText: 'Cek Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                executeVerifikasiApi('verify', items);
            }
        });
        return;
    }

    if (action === 'revise') {
        Swal.fire({
            title: 'Simpan Perubahan (Revisi)?',
            text: 'Perubahan jumlah fisik aktual akan diperbarui. Data penerimaan akan berstatus Revisi.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Simpan Revisi',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                executeVerifikasiApi('revise', items);
            }
        });
        return;
    }
}

function executeVerifikasiApi(action, items) {
    Swal.fire({
        title: 'Memproses Data...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const payload = {
        action: action,
        items: items
    };

    fetch('{{ route("warehouse.verifikasi.process", $incomingGood->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: result.message,
                confirmButtonColor: '#4f46e5'
            }).then(() => {
                window.location.href = '{{ route("warehouse.verifikasi.index") }}';
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Memproses',
                text: result.message || 'Terjadi kesalahan pada server.',
                confirmButtonColor: '#4f46e5'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: 'Gagal menghubungi server.',
            confirmButtonColor: '#4f46e5'
        });
    });
}
</script>
@endsection
