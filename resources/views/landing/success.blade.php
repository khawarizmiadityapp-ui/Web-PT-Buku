@extends('layouts.public')

@section('title', 'Pesanan Berhasil Dibuat - PT Distribusi Buku dan Alat Tulis Nusantara')

@section('content')
<div class="py-16 bg-slate-50 min-h-[85vh] flex items-center justify-center">
    <div class="max-w-xl w-full mx-auto px-4">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden text-center p-8 sm:p-10">
            <!-- Success Icon -->
            <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-6 shadow-glow">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider block mb-1">Pemesanan Selesai</span>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mb-2">Pesanan Berhasil Dibuat!</h1>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed max-w-md mx-auto mb-8">
                Terima kasih, <strong>{{ $order->customer_name }}</strong>. Pesanan Anda telah resmi masuk ke antrean pemrosesan gudang PT Buku Nusantara.
            </p>

            <!-- Order Code Highlight Box -->
            <div class="bg-gradient-to-br from-indigo-50 to-brand-50 border border-brand-200 rounded-2xl p-6 mb-8 text-center relative overflow-hidden">
                <div class="text-xs text-brand-700 font-semibold uppercase tracking-wider mb-1">Nomor Pesanan Anda</div>
                <div class="text-2xl sm:text-3xl font-black text-brand-950 font-mono tracking-wider py-1 select-all" id="order-code-display">
                    {{ $order->order_code ?? $order->invoice_number }}
                </div>
                <div class="text-[11px] text-slate-500 mt-2">
                    Simpan nomor pesanan ini untuk mengecek status persiapan dan pengiriman barang Anda.
                </div>
            </div>

            <!-- Order Quick Details -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 space-y-2 mb-8 text-left">
                <div class="flex justify-between">
                    <span class="text-slate-500">Tanggal Transaksi:</span>
                    <span class="font-bold text-slate-800">{{ $order->date ? $order->date->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Jumlah Barang:</span>
                    <span class="font-bold text-slate-800">{{ $order->items->sum('quantity') }} pcs ({{ $order->items->count() }} produk)</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Metode Pembayaran:</span>
                    <span class="font-bold text-slate-800">{{ $order->payment_method }}</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-slate-200 text-sm">
                    <span class="font-bold text-slate-800">Total Pembayaran:</span>
                    <span class="font-black text-brand-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('public.tracking', ['code' => $order->order_code ?? $order->invoice_number]) }}" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Lacak Pesanan</span>
                </a>

                <button type="button" onclick="copyCode('{{ $order->order_code ?? $order->invoice_number }}')" class="w-full sm:w-auto px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span>Salin Nomor Pesanan</span>
                </button>

                <a href="{{ route('home') }}" class="w-full sm:w-auto px-5 py-3 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold text-xs transition">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function copyCode(code) {
        navigator.clipboard.writeText(code).then(() => {
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: 'Nomor pesanan berhasil disalin!',
                showConfirmButton: false,
                timer: 2000
            });
        });
    }
</script>
@endsection
