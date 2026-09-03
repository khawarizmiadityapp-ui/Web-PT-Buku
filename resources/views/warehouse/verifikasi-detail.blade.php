@extends('layouts.app')

@section('title', 'Quality Control & Verifikasi Barang Masuk - LogiBook WMS')

@section('content')
<div class="container-fluid px-4 py-4 max-w-7xl mx-auto">
    <!-- Header Navigation & Status Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <!-- Breadcrumb Navigation -->
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-2">
                <a href="{{ route('warehouse.verifikasi.index') }}" class="inline-flex items-center gap-1.5 text-slate-600 hover:text-indigo-600 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                    <span>Daftar Verifikasi</span>
                </a>
                <span class="text-slate-300">•</span>
                <span class="text-indigo-600 font-medium bg-indigo-50 px-2 py-0.5 rounded-md">QC Physical Inspection</span>
            </div>

            <!-- Page Title & GR Number -->
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                    Verifikasi Penerimaan: 
                    <span class="font-mono text-indigo-600 bg-indigo-50/80 px-3 py-0.5 rounded-lg border border-indigo-100 select-all">
                        {{ $incomingGood->receipt_number }}
                    </span>
                </h1>
            </div>
            <p class="text-slate-500 text-sm mt-1">Formulir pemeriksaan fisik barang, pencocokan kuantitas aktual, dan penyesuaian stok gudang.</p>
        </div>

        <!-- Status Badge -->
        <div class="flex items-center gap-2 flex-shrink-0">
            @if($incomingGood->status == 'Pending')
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/80 shadow-xs">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-600"></span>
                    </span>
                    Menunggu Pencocokan Fisik
                </div>
            @elseif($incomingGood->status == 'Revised')
                <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200/80 shadow-xs">
                    <i class="fas fa-pen-to-square text-indigo-500"></i>
                    Telah Direvisi
                </div>
            @elseif($incomingGood->status == 'Verified')
                <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80 shadow-xs">
                    <i class="fas fa-circle-check text-emerald-600"></i>
                    Terverifikasi (Stok Aktif)
                </div>
            @elseif($incomingGood->status == 'Canceled')
                <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/80 shadow-xs">
                    <i class="fas fa-ban text-rose-600"></i>
                    Penerimaan Dibatalkan
                </div>
            @endif
        </div>
    </div>

    <!-- Info Overview Grid (3 Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Card 1: Supplier / Vendor -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-sm transition-all flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                <i class="fas fa-building text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase mb-1">SUPPLIER / VENDOR</p>
                <h3 class="text-base font-bold text-slate-900 truncate mb-0.5">
                    {{ $incomingGood->supplier->company_name ?? $incomingGood->supplier->name ?? 'Supplier Umum' }}
                </h3>
                <p class="text-xs text-slate-500 flex items-center gap-1.5 truncate">
                    <i class="fas fa-location-dot text-slate-400"></i>
                    {{ $incomingGood->supplier->city ?? 'Kantor Pusat / Gudang Vendor' }}
                </p>
            </div>
        </div>

        <!-- Card 2: Tanggal & Waktu -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-sm transition-all flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                <i class="fas fa-calendar-check text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase mb-1">TANGGAL PENERIMAAN</p>
                <h3 class="text-base font-bold text-slate-900 mb-0.5">
                    {{ \Carbon\Carbon::parse($incomingGood->receive_date)->format('d F Y') }}
                </h3>
                <p class="text-xs text-slate-500 flex items-center gap-1.5">
                    <i class="fas fa-clock text-slate-400"></i>
                    Dibuat: {{ \Carbon\Carbon::parse($incomingGood->created_at)->format('H:i') }} WIB
                </p>
            </div>
        </div>

        <!-- Card 3: Ringkasan Fisik Items -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-sm transition-all flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                <i class="fas fa-boxes-stacked text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase mb-1">RINGKASAN FISIK</p>
                <h3 class="text-base font-bold text-slate-900 mb-0.5">
                    {{ $incomingGood->items->count() }} Jenis Item Barang
                </h3>
                <p class="text-xs text-slate-600">
                    Total Kuantitas: <span class="font-bold text-slate-900">{{ number_format($incomingGood->items->sum('quantity')) }} Unit</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Instruction Helper Card (Clean Blue & Slate Glassmorphism) -->
    @if($incomingGood->status == 'Pending' || $incomingGood->status == 'Revised')
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-2xl p-4 md:p-5 text-white shadow-md mb-6 border border-slate-800">
        <div class="flex items-start gap-3.5">
            <div class="w-9 h-9 rounded-xl bg-indigo-500/20 text-indigo-300 border border-indigo-400/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fas fa-shield-halved text-sm"></i>
            </div>
            <div class="flex-1 text-xs leading-relaxed text-slate-300">
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="text-sm font-bold text-white">SOP Verifikasi Fisik & Quality Control</span>
                    <span class="bg-indigo-500/30 text-indigo-200 text-[10px] font-semibold px-2 py-0.5 rounded-full border border-indigo-400/30">Instruksi QC</span>
                </div>
                <p class="mb-0 text-slate-300">
                    Lakukan pemeriksaan fisik di *loading bay* gudang. Hitung jumlah aktual dan periksa kondisi segel produk. 
                    Jika seluruh kuantitas sesuai nota, klik <span class="text-emerald-400 font-semibold">Verifikasi (Sesuai & Tambah Stok)</span>. 
                    Jika ditemukan selisih atau barang rusak, sesuaikan angka di kolom <span class="text-indigo-300 font-semibold">QTY FISIK AKTUAL</span> lalu tekan <span class="text-slate-100 font-semibold underline">Simpan Revisi Selisih</span>.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Inspection Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden mb-8">
        <!-- Card Header -->
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i class="fas fa-list-check text-sm"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900">Tabel Inspection & Match Quantity</h2>
                    <p class="text-xs text-slate-500">Bandingkan kuantitas surat jalan/nota dengan hitungan fisik riil</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-xs">
                <span class="text-slate-400 font-medium">Status Dokumen:</span>
                <span class="font-mono font-bold text-slate-800 bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200">{{ $incomingGood->status }}</span>
            </div>
        </div>

        <!-- Table Form -->
        <form id="verifikasiForm">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-slate-50/90 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200/80">
                            <th class="py-3.5 px-4 text-center w-12">#</th>
                            <th class="py-3.5 px-4">PRODUK / BARANG</th>
                            <th class="py-3.5 px-4">KODE BARANG (SKU)</th>
                            <th class="py-3.5 px-4 text-center bg-slate-100/60 w-44">QTY SISTEM (NOTA)</th>
                            <th class="py-3.5 px-4 text-center bg-indigo-50/40 w-56">QTY FISIK AKTUAL <span class="text-rose-500">*</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($incomingGood->items as $index => $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Index -->
                            <td class="py-4 px-4 text-center text-xs font-bold text-slate-400">{{ $index + 1 }}</td>

                            <!-- Product Info -->
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0 border border-slate-200/60">
                                        <i class="fas fa-book-open text-sm text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 text-sm leading-snug">{{ $item->product->product_name ?? 'Produk' }}</div>
                                        <div class="text-xs text-slate-500 flex items-center gap-1.5 mt-0.5">
                                            <span>Stok Gudang Terkini:</span>
                                            <span class="font-semibold text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded text-[11px]">
                                                {{ number_format($item->product->system_stock ?? 0) }} {{ $item->product->unit ?? 'Pcs' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- SKU Badge -->
                            <td class="py-4 px-4">
                                <span class="font-mono text-xs font-semibold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200/80">
                                    #{{ $item->product->product_code ?? '-' }}
                                </span>
                            </td>

                            <!-- System Qty -->
                            <td class="py-4 px-4 text-center bg-slate-50/50">
                                <div class="font-bold text-slate-900 text-base">
                                    {{ number_format($item->quantity) }}
                                    <span class="text-xs font-normal text-slate-500">{{ $item->product->unit ?? 'Pcs' }}</span>
                                </div>
                            </td>

                            <!-- Physical Qty Interactive Input -->
                            <td class="py-4 px-4 bg-indigo-50/20 text-center">
                                <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                
                                @if($incomingGood->status == 'Pending' || $incomingGood->status == 'Revised')
                                <div class="inline-flex flex-col items-center">
                                    <!-- Stepper Container -->
                                    <div class="inline-flex items-center rounded-xl border border-slate-300 bg-white p-1 shadow-xs focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500">
                                        <button type="button" 
                                                class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold flex items-center justify-center transition active:scale-95"
                                                onclick="adjustQty(this, -1)">
                                            <i class="fas fa-minus text-xs"></i>
                                        </button>
                                        <input type="number" 
                                               class="w-20 text-center font-bold text-slate-900 text-base border-0 focus:outline-none focus:ring-0 physical-qty px-2 py-1" 
                                               name="items[{{ $index }}][quantity]" 
                                               value="{{ $item->quantity }}" 
                                               data-original="{{ $item->quantity }}"
                                               min="0" required 
                                               onchange="checkQtyMismatch(this)" 
                                               onkeyup="checkQtyMismatch(this)">
                                        <button type="button" 
                                                class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold flex items-center justify-center transition active:scale-95"
                                                onclick="adjustQty(this, 1)">
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                    </div>

                                    <!-- Status Indicator Label -->
                                    <div class="mt-1.5 mismatch-label text-xs">
                                        <span class="inline-flex items-center gap-1 font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/60 text-[11px]">
                                            <i class="fas fa-check-circle"></i> Status: Sesuai
                                        </span>
                                    </div>
                                </div>
                                @else
                                <div class="font-bold text-emerald-600 text-base">
                                    {{ number_format($item->quantity) }} 
                                    <span class="text-xs font-normal text-slate-500">{{ $item->product->unit ?? 'Pcs' }}</span>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Footer Action Controls (No Yellow!) -->
            @if($incomingGood->status == 'Pending' || $incomingGood->status == 'Revised')
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200/80 flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Left: Cancel Action -->
                <div>
                    <button type="button" 
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-rose-200 bg-rose-50/60 hover:bg-rose-100 text-rose-700 text-xs font-semibold transition-all shadow-xs active:scale-95"
                            onclick="submitVerifikasi('cancel')">
                        <i class="fas fa-ban"></i>
                        <span>Batalkan / Tolak Penerimaan Ini</span>
                    </button>
                </div>

                <!-- Right: Action Buttons (Indigo & Emerald) -->
                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" 
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold transition-all shadow-sm active:scale-95 border border-slate-700"
                            onclick="submitVerifikasi('revise')">
                        <i class="fas fa-pen-to-square text-indigo-300"></i>
                        <span>Simpan Revisi Selisih</span>
                    </button>

                    <button type="button" 
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-md shadow-emerald-500/20 active:scale-95"
                            onclick="submitVerifikasi('verify')">
                        <i class="fas fa-circle-check"></i>
                        <span>Verifikasi (Sesuai & Tambah Stok)</span>
                    </button>
                </div>
            </div>
            @else
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200/80 flex items-center justify-between">
                <div class="text-xs text-slate-500 flex items-center gap-2">
                    <i class="fas fa-lock text-slate-400"></i>
                    <span>Transaksi penerimaan barang ini telah difinalisasi dan tidak dapat diubah lagi.</span>
                </div>
                <a href="{{ route('warehouse.verifikasi.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-semibold shadow-xs transition">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Daftar Verifikasi</span>
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

<!-- Interactive Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function adjustQty(btn, delta) {
    const parent = btn.closest('.inline-flex');
    if (!parent) return;
    const input = parent.querySelector('.physical-qty');
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
    let currentQty = parseInt(input.value) || 0;
    if (currentQty < 0) {
        currentQty = 0;
        input.value = 0;
    }

    if (!labelContainer) return;

    if (currentQty === origQty) {
        labelContainer.innerHTML = '<span class="inline-flex items-center gap-1 font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/60 text-[11px]"><i class="fas fa-check-circle"></i> Status: Sesuai</span>';
    } else if (currentQty < origQty) {
        const diff = origQty - currentQty;
        labelContainer.innerHTML = `<span class="inline-flex items-center gap-1 font-semibold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200/60 text-[11px]"><i class="fas fa-triangle-exclamation"></i> Kurang ${diff} Unit dari Nota</span>`;
    } else {
        const diff = currentQty - origQty;
        labelContainer.innerHTML = `<span class="inline-flex items-center gap-1 font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-200/60 text-[11px]"><i class="fas fa-circle-plus"></i> Lebih ${diff} Unit dari Nota</span>`;
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
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Batalkan Penerimaan',
            cancelButtonText: 'Kembali',
            customClass: {
                popup: 'rounded-2xl shadow-xl border border-slate-100',
                confirmButton: 'rounded-xl px-4 py-2 font-semibold',
                cancelButton: 'rounded-xl px-4 py-2 font-semibold'
            }
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
            confirmButtonColor: '#059669',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Verifikasi & Tambah Stok',
            cancelButtonText: 'Cek Kembali',
            customClass: {
                popup: 'rounded-2xl shadow-xl border border-slate-100',
                confirmButton: 'rounded-xl px-4 py-2 font-semibold',
                cancelButton: 'rounded-xl px-4 py-2 font-semibold'
            }
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
            confirmButtonColor: '#1e293b',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Simpan Revisi',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-2xl shadow-xl border border-slate-100',
                confirmButton: 'rounded-xl px-4 py-2 font-semibold',
                cancelButton: 'rounded-xl px-4 py-2 font-semibold'
            }
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
                confirmButtonColor: '#4f46e5',
                customClass: {
                    popup: 'rounded-2xl shadow-xl'
                }
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
