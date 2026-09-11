@extends('layouts.public')

@section('title', 'Riwayat Pesanan - PT Distribusi Buku dan Alat Tulis Nusantara')

@section('content')
<div class="py-12 sm:py-16 bg-slate-50 min-h-[85vh]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header & Search by Phone/Email -->
        <div class="text-center max-w-xl mx-auto mb-10">
            <span class="text-xs font-bold text-brand-600 uppercase tracking-wider block mb-1">Riwayat Pelanggan</span>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Cari Riwayat Pesanan</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-2">
                Masukkan nomor WhatsApp atau email yang Anda gunakan saat pemesanan untuk melihat daftar semua pesanan Anda.
            </p>

            <form action="{{ route('public.orders') }}" method="GET" class="mt-6 flex flex-col sm:flex-row gap-2">
                <div class="relative flex-1">
                    <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <input type="text" name="contact" value="{{ $query ?? '' }}" placeholder="Nomor WhatsApp atau Email (contoh: 0812...)" required
                        class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-brand-600 focus:ring-2 focus:ring-brand-500/20 shadow-xs">
                </div>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl transition shadow-xs">
                    Cari Pesanan
                </button>
            </form>
        </div>

        @if(!empty($query))
            @if($orders->count() > 0)
                <div class="space-y-4">
                    <div class="flex items-center justify-between text-xs text-slate-500 px-1">
                        <span>Ditemukan <strong>{{ $orders->count() }}</strong> pesanan untuk "{{ $query }}"</span>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        @foreach($orders as $ord)
                            @php
                                $statusMap = \App\Models\SalesInvoice::statusMap();
                                $st = $ord->order_status ?: 'pending';
                                $meta = $statusMap[$st] ?? ['title' => ucfirst($st), 'badge' => 'bg-slate-100 text-slate-700 border-slate-200'];
                            @endphp
                            <div class="p-5 bg-white rounded-2xl border border-slate-200 shadow-xs hover:border-brand-300 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-black text-slate-900 font-mono tracking-wide">
                                            {{ $ord->order_code ?? $ord->invoice_number }}
                                        </span>
                                        <span class="text-xs px-2.5 py-0.5 rounded-full font-bold {{ $meta['badge'] }} border">
                                            {{ $meta['title'] }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500">
                                        Pemesan: <strong>{{ $ord->customer_name }}</strong> • {{ $ord->date ? $ord->date->translatedFormat('d M Y') : $ord->created_at->translatedFormat('d M Y') }}
                                    </p>
                                    <p class="text-xs text-slate-600">
                                        {{ $ord->items->sum('quantity') }} produk • Total: <strong class="text-brand-700">Rp {{ number_format($ord->total_amount, 0, ',', '.') }}</strong> ({{ $ord->payment_method ?? 'Bank Transfer' }})
                                    </p>
                                </div>

                                <div class="flex items-center gap-2">
                                    <a href="{{ route('public.tracking', ['code' => $ord->order_code ?? $ord->invoice_number]) }}" class="px-4 py-2 rounded-xl bg-brand-50 hover:bg-brand-100 text-brand-700 font-bold text-xs border border-brand-200 transition flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Lihat Tracking</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center max-w-md mx-auto">
                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Belum Ada Pesanan Ditemukan</h3>
                    <p class="text-xs text-slate-500 mb-4">Tidak ada riwayat pemesanan dengan kontak "{{ $query }}". Pastikan nomor yang dimasukkan sama dengan saat checkout.</p>
                    <a href="{{ route('home') }}#catalog" class="inline-block px-5 py-2 rounded-xl bg-brand-600 text-white font-bold text-xs">
                        Belanja Sekarang
                    </a>
                </div>
            @endif
        @else
            <div class="p-8 text-center bg-white rounded-2xl border border-slate-200 max-w-lg mx-auto">
                <p class="text-xs text-slate-500">Masukkan kontak di atas untuk mencari daftar pesanan yang pernah Anda buat di PT Buku Nusantara.</p>
            </div>
        @endif

    </div>
</div>
@endsection
