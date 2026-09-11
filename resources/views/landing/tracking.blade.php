@extends('layouts.public')

@section('title', 'Lacak Pesanan Online - PT Distribusi Buku dan Alat Tulis Nusantara')

@section('content')
<div class="py-12 sm:py-16 bg-slate-50 min-h-[85vh]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Tracking Header & Search Box -->
        <div class="text-center max-w-2xl mx-auto mb-10">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-50 border border-brand-200 text-brand-700 text-xs font-bold mb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Sistem Pelacakan Pengiriman Terpadu</span>
            </div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Lacak Pesanan Anda</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-2">
                Masukkan nomor pesanan unik (contoh: <code class="bg-slate-200 text-slate-800 px-1.5 py-0.5 rounded font-mono font-bold">ORD-2026-00001</code> atau nomor invoice) untuk memantau tahapan proses penyiapan dan pengiriman secara real-time.
            </p>

            <!-- Search Form -->
            <form action="{{ route('public.tracking') }}" method="GET" class="mt-6 flex flex-col sm:flex-row gap-2 max-w-lg mx-auto">
                <div class="relative flex-1">
                    <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="code" value="{{ $code ?? '' }}" placeholder="Masukkan Nomor Pesanan (ORD-2026-XXXXX)" required
                        class="w-full pl-11 pr-4 py-3 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-brand-600 focus:ring-2 focus:ring-brand-500/20 shadow-xs uppercase">
                </div>
                <button type="submit" class="px-6 py-3 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold text-sm rounded-xl transition shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span>Lacak Status</span>
                </button>
            </form>
        </div>

        @if($error)
            <!-- Error State Card -->
            <div class="bg-white rounded-2xl border border-rose-200 p-8 text-center max-w-lg mx-auto shadow-xs mb-8">
                <div class="w-14 h-14 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-1">Pesanan Tidak Ditemukan</h3>
                <p class="text-xs text-slate-600 leading-relaxed mb-6">{{ $error }}</p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 text-xs font-semibold">
                    <a href="{{ route('public.orders') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                        Cari Lewat No. HP / Email
                    </a>
                    <a href="{{ route('home') }}#catalog" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white transition">
                        Belanja Buku Baru
                    </a>
                </div>
            </div>
        @endif

        @if($order)
            @php
                $statusMap = \App\Models\SalesInvoice::statusMap();
                $curStatus = $order->order_status ?: 'pending';
                $curMeta = $statusMap[$curStatus] ?? [
                    'title' => ucfirst($curStatus),
                    'description' => '',
                    'badge' => 'bg-gray-100 text-gray-800 border-gray-300',
                    'color' => '#64748B',
                    'step' => 1,
                ];

                $steps = [
                    1 => ['key' => 'pending', 'title' => 'Pesanan Dibuat'],
                    2 => ['key' => 'confirmed', 'title' => 'Dikonfirmasi'],
                    3 => ['key' => 'processing', 'title' => 'Sedang Diproses'],
                    4 => ['key' => 'ready', 'title' => 'Pesanan Siap'],
                    5 => ['key' => 'completed', 'title' => 'Selesai'],
                ];
                $curStepNum = $curMeta['step'];
            @endphp

            <!-- Tracking Card Container -->
            <div id="tracking-card" class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden" data-order-code="{{ $order->order_code ?? $order->invoice_number }}">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-slate-900 via-brand-950 to-slate-900 p-6 sm:p-8 text-white">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-mono text-brand-300 tracking-wider">PESANAN RESMI</span>
                                <span class="text-xs text-slate-400">•</span>
                                <span class="text-xs text-slate-300">{{ $order->date ? $order->date->translatedFormat('d F Y') : ($order->created_at ? $order->created_at->translatedFormat('d F Y') : '-') }}</span>
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-black text-white tracking-wide">
                                {{ $order->order_code ?? $order->invoice_number }}
                            </h2>
                            <p class="text-xs text-slate-400 mt-1">No. Invoice: <span class="font-mono text-slate-300">{{ $order->invoice_number }}</span></p>
                        </div>

                        <!-- Current Status Badge -->
                        <div class="flex sm:flex-col items-start sm:items-end justify-between sm:justify-center">
                            <span class="text-[11px] text-slate-400 mb-1 hidden sm:block">Status Saat Ini:</span>
                            <div id="status-badge" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl {{ $curMeta['badge'] }} text-xs font-black tracking-wide border shadow-sm">
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $curMeta['color'] }};"></span>
                                <span id="status-badge-title">{{ $curMeta['title'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Step Bar -->
                    @if($curStatus !== 'cancelled')
                        <div class="mt-8 pt-6 border-t border-white/10">
                            <div class="relative flex items-center justify-between">
                                <!-- Background line -->
                                <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-white/15 w-full -z-0"></div>
                                <!-- Progress line -->
                                <div id="progress-bar-fill" class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-gradient-to-r from-brand-400 to-emerald-400 transition-all duration-500 -z-0"
                                     style="width: {{ (($curStepNum - 1) / 4) * 100 }}%;"></div>

                                @foreach($steps as $num => $s)
                                    <div class="relative z-10 flex flex-col items-center step-milestone" data-step-num="{{ $num }}">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300
                                            {{ $curStepNum > $num ? 'bg-emerald-500 text-white shadow-md' : ($curStepNum == $num ? 'bg-brand-500 text-white ring-4 ring-brand-500/30 shadow-lg scale-110' : 'bg-slate-800 text-slate-400 border border-white/20') }}">
                                            @if($curStepNum > $num)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @else
                                                {{ $num }}
                                            @endif
                                        </div>
                                        <span class="mt-2 text-[10px] sm:text-[11px] font-semibold text-center whitespace-nowrap {{ $curStepNum >= $num ? 'text-white' : 'text-slate-400' }}">
                                            {{ $s['title'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mt-6 p-4 rounded-xl bg-rose-950/60 border border-rose-800/80 text-rose-200 text-xs">
                            <strong>Status: Pesanan ini telah dibatalkan.</strong> Silakan hubungi customer support untuk konfirmasi lebih lanjut.
                        </div>
                    @endif
                </div>

                <!-- Card Body: Timeline & Order Summary -->
                <div class="p-6 sm:p-8 grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Left: Modern Timeline -->
                    <div class="lg:col-span-7 space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wide flex items-center gap-2">
                                <span>Riwayat Perjalanan Pesanan</span>
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                            </h3>
                            <span class="text-[11px] text-slate-400">Pembaruan otomatis</span>
                        </div>

                        <!-- Timeline Milestones -->
                        <div id="timeline-container" class="relative pl-6 border-l-2 border-slate-200 space-y-6">
                            @php
                                $history = is_array($order->status_history) ? $order->status_history : [];
                            @endphp

                            @forelse(array_reverse($history) as $idx => $event)
                                @php
                                    $isLatest = $idx === 0;
                                @endphp
                                <div class="relative timeline-event">
                                    <!-- Dot -->
                                    <div class="absolute -left-[31px] top-0.5 w-4 h-4 rounded-full border-2 border-white {{ $isLatest ? 'bg-brand-600 ring-4 ring-brand-100' : 'bg-slate-300' }}"></div>
                                    
                                    <div>
                                        <div class="flex items-baseline justify-between gap-2">
                                            <h4 class="text-xs font-bold {{ $isLatest ? 'text-brand-700 text-sm' : 'text-slate-800' }}">
                                                {{ $event['title'] ?? ucfirst($event['status']) }}
                                            </h4>
                                            <span class="text-[10px] text-slate-400 font-mono">
                                                {{ $event['formatted_time'] ?? ($event['timestamp'] ?? '-') }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                                            {{ $event['description'] ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-xs text-slate-400 italic">
                                    Pesanan telah masuk ke sistem dan menunggu proses verifikasi awal.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Right: Customer & Order Summary -->
                    <div class="lg:col-span-5 space-y-6 border-t lg:border-t-0 lg:border-l border-slate-100 lg:pl-8">
                        <!-- Customer Info Box -->
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Tujuan Pengiriman</h3>
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs space-y-2">
                                <div class="font-extrabold text-slate-900 text-sm">{{ $order->customer_name }}</div>
                                @if($order->customer_phone)
                                    <div class="text-slate-600 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <span>{{ $order->customer_phone }}</span>
                                    </div>
                                @endif
                                <div class="text-slate-600 flex items-start gap-1.5 pt-1 border-t border-slate-200/60">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    <span class="leading-relaxed">{{ $order->shipping_address ?? ($order->customer->address ?? 'Alamat tidak dilampirkan') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Ordered Items Summary -->
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Daftar Barang ({{ $order->items->count() }})</h3>
                            <div class="space-y-2 max-h-56 overflow-y-auto pr-1 divide-y divide-slate-100">
                                @foreach($order->items as $item)
                                    <div class="pt-2 first:pt-0 flex items-center justify-between text-xs">
                                        <div class="flex-1 pr-2">
                                            <span class="font-bold text-slate-800 block truncate">{{ $item->product_name }}</span>
                                            <span class="text-[11px] text-slate-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                        </div>
                                        <span class="font-extrabold text-slate-900">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-200 flex justify-between text-sm">
                                <span class="font-bold text-slate-700">Total Pembayaran:</span>
                                <span class="font-black text-brand-700 text-base">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-[11px] text-slate-500 mt-1">
                                <span>Metode Bayar:</span>
                                <span class="font-medium text-slate-700">{{ $order->payment_method ?? 'Bank Transfer' }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-2 flex gap-2">
                            <button type="button" onclick="copyOrderCode('{{ $order->order_code ?? $order->invoice_number }}')" class="flex-1 py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <span>Salin Kode</span>
                            </button>
                            <a href="{{ route('home') }}#catalog" class="flex-1 py-2 px-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs transition text-center">
                                Belanja Lagi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Search Guide when no query is executed yet -->
            <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-12 text-center max-w-2xl mx-auto shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-900 mb-2">Belum Memiliki Nomor Pesanan?</h3>
                <p class="text-xs text-slate-500 leading-relaxed max-w-md mx-auto mb-6">
                    Nomor pesanan didapatkan setelah Anda menyelesaikan proses checkout di katalog kami. Anda juga dapat mencari pesanan berdasarkan nomor WhatsApp yang didaftarkan.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('home') }}#catalog" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-brand-600 text-white font-bold text-xs shadow hover:bg-brand-700 transition">
                        Buka Katalog Buku & ATK
                    </a>
                    <a href="{{ route('public.orders') }}" class="w-full sm:w-auto px-6 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-semibold text-xs hover:bg-slate-50 transition">
                        Cari Riwayat Pembelian
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

@section('scripts')
@if($order)
<script>
    // Copy Order Code Helper
    function copyOrderCode(code) {
        navigator.clipboard.writeText(code).then(() => {
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: 'Nomor pesanan disalin!',
                showConfirmButton: false,
                timer: 2000
            });
        });
    }

    // Real-Time Polling Logic (Refreshes status every 8 seconds without page reload)
    (function initOrderLivePolling() {
        const orderCode = "{{ $order->order_code ?? $order->invoice_number }}";
        if (!orderCode) return;

        let currentStatus = "{{ $order->order_status ?: 'pending' }}";

        setInterval(async () => {
            try {
                const res = await fetch(`{{ route('public.track.api') }}?code=${encodeURIComponent(orderCode)}`, {
                    headers: { 'Accept': 'application/json' }
                });

                if (!res.ok) return;
                const data = await res.json();

                if (data.success && data.order) {
                    const newStatus = data.order.order_status;

                    if (newStatus !== currentStatus) {
                        currentStatus = newStatus;
                        // Status changed by admin! Show toast and reload content gracefully
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'info',
                            title: `Status diperbarui: ${data.order.status_title}`,
                            showConfirmButton: false,
                            timer: 3500
                        });

                        // Update Status Badge
                        const titleEl = document.getElementById('status-badge-title');
                        if (titleEl) titleEl.innerText = data.order.status_title;

                        // Update Progress fill
                        const fill = document.getElementById('progress-bar-fill');
                        if (fill && data.order.current_step) {
                            fill.style.width = `${((data.order.current_step - 1) / 4) * 100}%`;
                        }

                        // Re-render Timeline history
                        const container = document.getElementById('timeline-container');
                        if (container && data.order.status_history) {
                            let html = '';
                            const reversed = [...data.order.status_history].reverse();
                            reversed.forEach((ev, idx) => {
                                const isLatest = idx === 0;
                                html += `
                                    <div class="relative timeline-event animate-pulse-subtle">
                                        <div class="absolute -left-[31px] top-0.5 w-4 h-4 rounded-full border-2 border-white ${isLatest ? 'bg-brand-600 ring-4 ring-brand-100' : 'bg-slate-300'}"></div>
                                        <div>
                                            <div class="flex items-baseline justify-between gap-2">
                                                <h4 class="text-xs font-bold ${isLatest ? 'text-brand-700 text-sm' : 'text-slate-800'}">
                                                    ${ev.title || ev.status}
                                                </h4>
                                                <span class="text-[10px] text-slate-400 font-mono">
                                                    ${ev.formatted_time || ev.timestamp || '-'}
                                                </span>
                                            </div>
                                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">${ev.description || '-'}</p>
                                        </div>
                                    </div>
                                `;
                            });
                            container.innerHTML = html;
                        }
                    }
                }
            } catch (e) {
                // Silently ignore temporary network hiccups during polling
            }
        }, 8000);
    })();
</script>
@endif
@endsection
