@extends('layouts.public')

@section('title', 'PT Distribusi Buku dan Alat Tulis Nusantara - Solusi Distribusi Buku & ATK')

@section('styles')
<style>
    /* Hero canvas container */
    #hero-canvas-container {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        pointer-events: auto;
        z-index: 1;
    }
    #hero-canvas {
        width: 100%;
        height: 100%;
        display: block;
    }

    /* Glow backdrop */
    .hero-glow {
        position: absolute;
        top: 30%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, rgba(59, 130, 246, 0.12) 45%, rgba(11, 15, 25, 0) 70%);
        pointer-events: none;
        z-index: 0;
    }

    /* Subtle grid pattern */
    .hero-grid-pattern {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        background-size: 40px 40px;
        mask-image: radial-gradient(ellipse at center, black 40%, transparent 75%);
        -webkit-mask-image: radial-gradient(ellipse at center, black 40%, transparent 75%);
        pointer-events: none;
        z-index: 0;
    }
</style>
@endsection

@section('content')

<!-- ========================================================================= -->
<!-- 1. HERO SECTION — INTERACTIVE 3D GLASS SHARDS EXPERIENCE                  -->
<!-- ========================================================================= -->
<section id="hero" class="relative min-h-[92vh] flex items-center justify-center bg-gradient-to-b from-corporate-dark via-[#0d1222] to-corporate-dark text-white overflow-hidden py-20 px-4 sm:px-6 lg:px-8">
    <!-- Ambient Lighting & Subtle Grid Background -->
    <div class="hero-glow"></div>
    <div class="hero-grid-pattern"></div>

    <!-- 3D Three.js Interactive Shard Canvas Container -->
    <div id="hero-canvas-container">
        <canvas id="hero-canvas"></canvas>
    </div>

    <!-- Fallback 2D Animated Glass Book in case WebGL is unavailable or prefers-reduced-motion -->
    <div id="hero-canvas-fallback" class="hidden absolute inset-0 flex items-center justify-center pointer-events-none z-0">
        <div class="relative w-72 h-80 opacity-60">
            <div class="absolute inset-0 rounded-2xl bg-gradient-to-tr from-brand-600/30 to-cyan-400/20 backdrop-blur-xl border border-white/20 transform -rotate-6 shadow-2xl animate-pulse-subtle"></div>
            <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-indigo-500/20 to-brand-300/10 backdrop-blur-2xl border border-white/30 transform rotate-6 shadow-glow"></div>
            <div class="absolute inset-4 rounded-xl bg-white/5 border border-white/20 backdrop-blur flex items-center justify-center text-center p-4">
                <span class="text-xs font-bold text-white/80 tracking-wider uppercase">PT BUKU NUSANTARA<br><span class="text-[10px] text-brand-300 font-normal">Interactive Crystalline Structure</span></span>
            </div>
        </div>
    </div>

    <!-- Hero Foreground Content -->
    <div class="relative z-10 max-w-5xl mx-auto text-center pointer-events-none">
        <!-- Eyebrow Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-brand-300 text-xs font-semibold tracking-wide mb-6 shadow-inner pointer-events-auto">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
            <span>PT Distribusi Buku dan Alat Tulis NUSANTARA</span>
        </div>

        <!-- Headline -->
        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight mb-6">
            Solusi Buku & Alat Tulis<br>
            <span class="bg-gradient-to-r from-brand-300 via-indigo-200 to-cyan-300 bg-clip-text text-transparent">
                untuk Kebutuhan Anda
            </span>
        </h1>

        <!-- Supporting Text -->
        <p class="max-w-2xl mx-auto text-sm sm:text-base lg:text-lg text-slate-300 font-normal leading-relaxed mb-10">
            Temukan berbagai pilihan buku teks pelajaran, novel, komik, dan perlengkapan alat tulis berkualitas tinggi dengan proses pemesanan yang mudah, cepat, dan transparan.
        </p>

        <!-- CTAs -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pointer-events-auto mb-14">
            <a href="#catalog" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-gradient-to-r from-brand-600 via-brand-500 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold text-sm shadow-lg shadow-brand-600/30 hover:shadow-glow hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-2">
                <span>Pesan Sekarang</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </a>

            <a href="{{ route('public.tracking') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-white/10 hover:bg-white/15 text-white font-bold text-sm border border-white/20 backdrop-blur-md hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Lacak Pesanan</span>
            </a>
        </div>

        <!-- Trust Badges & Micro Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 max-w-4xl mx-auto pointer-events-auto">
            <div class="p-3.5 rounded-xl bg-white/5 backdrop-blur-md border border-white/10 text-left">
                <span class="text-xl sm:text-2xl font-black text-white block">{{ number_format($stats['total_stock'] ?? 500000) }}+</span>
                <span class="text-[11px] text-slate-400 font-medium">Stok Fisik Tersedia</span>
            </div>
            <div class="p-3.5 rounded-xl bg-white/5 backdrop-blur-md border border-white/10 text-left">
                <span class="text-xl sm:text-2xl font-black text-white block">100%</span>
                <span class="text-[11px] text-slate-400 font-medium">Produk Original Resmi</span>
            </div>
            <div class="p-3.5 rounded-xl bg-white/5 backdrop-blur-md border border-white/10 text-left">
                <span class="text-xl sm:text-2xl font-black text-white block">34 Provinsi</span>
                <span class="text-[11px] text-slate-400 font-medium">Jangkauan Distribusi</span>
            </div>
            <div class="p-3.5 rounded-xl bg-white/5 backdrop-blur-md border border-white/10 text-left">
                <span class="text-xl sm:text-2xl font-black text-white block">Real-Time</span>
                <span class="text-[11px] text-slate-400 font-medium">Pelacakan Pesanan</span>
            </div>
        </div>

        <!-- Micro-hint about interaction -->
        <p class="text-[11px] text-slate-400 mt-6 tracking-wide opacity-75">
            <span class="hidden sm:inline">✦ Gerakkan kursor pada layar untuk memengaruhi kristal kaca ✦</span>
            <span class="sm:hidden">✦ Sentuh layar untuk berinteraksi dengan kristal kaca ✦</span>
        </p>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 2. PRODUCT CATALOG SECTION                                                -->
<!-- ========================================================================= -->
<section id="catalog" class="py-20 bg-slate-50 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
            <div>
                <span class="text-xs font-bold text-brand-600 uppercase tracking-wider block mb-1">Katalog Resmi</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Jelajahi Buku & Alat Tulis</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-xl">Pilih produk berkualitas langsung dari stok gudang pusat kami dengan jaminan ketersediaan real-time.</p>
            </div>

            <!-- Search Bar -->
            <div class="w-full md:w-80">
                <div class="relative">
                    <input id="catalog-search" type="text" placeholder="Cari buku, komik, atau alat tulis..." 
                        class="w-full pl-10 pr-4 py-2.5 text-xs bg-white border border-slate-300 rounded-xl shadow-xs focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Filter Pills & Sorting Toolbar -->
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200 shadow-2xs mb-8 flex flex-col lg:flex-row items-center justify-between gap-4">
            <!-- Category Filter Pills -->
            <div class="flex items-center gap-1.5 overflow-x-auto w-full lg:w-auto pb-2 lg:pb-0 scrollbar-none">
                <button type="button" onclick="filterCategory('all', this)" class="cat-pill px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap bg-brand-600 text-white shadow-xs">
                    Semua Kategori
                </button>
                @foreach($categories as $cat)
                    <button type="button" onclick="filterCategory('{{ $cat->category }}', this)" class="cat-pill px-3.5 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition whitespace-nowrap border border-transparent">
                        {{ $cat->category }}
                    </button>
                @endforeach
            </div>

            <!-- Sorting & Stock availability Filters -->
            <div class="flex items-center justify-between w-full lg:w-auto gap-3 text-xs">
                <div class="flex items-center gap-2">
                    <span class="text-slate-500 font-medium">Stok:</span>
                    <select id="stock-filter" onchange="applyFilters()" class="px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-700 outline-none focus:border-brand-500">
                        <option value="">Semua Stok</option>
                        <option value="ready">Tersedia Banyak</option>
                        <option value="limited">Stok Terbatas</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-slate-500 font-medium">Urutkan:</span>
                    <select id="sort-filter" onchange="applyFilters()" class="px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-700 outline-none focus:border-brand-500">
                        <option value="popular">Paling Populer</option>
                        <option value="newest">Produk Terbaru</option>
                        <option value="price_asc">Harga Terendah</option>
                        <option value="price_desc">Harga Tertinggi</option>
                        <option value="name_asc">Nama (A-Z)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Product Cards Grid Container -->
        <div id="product-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($catalogProducts as $product)
                @php
                    $badge = $product->stock_badge;
                    $catColor = $product->category_color;
                @endphp
                <div class="product-card card-hover bg-white rounded-2xl border border-slate-200 overflow-hidden flex flex-col justify-between shadow-xs transition-all group"
                     data-name="{{ strtolower($product->product_name) }}"
                     data-code="{{ strtolower($product->product_code) }}"
                     data-category="{{ $product->category }}"
                     data-price="{{ $product->price }}"
                     data-stock="{{ $product->system_stock }}">
                    
                    <!-- Card Media / Cover Visual -->
                    <div class="relative p-6 bg-gradient-to-br {{ $catColor['gradient'] }} text-white flex flex-col items-center justify-center min-h-[190px] overflow-hidden">
                        <!-- Dynamic ambient light behind cover -->
                        <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-white/10 blur-xl"></div>

                        <!-- Category Tag Top Left -->
                        <div class="absolute top-3 left-3 z-10">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $catColor['badge'] }} shadow-xs">
                                {{ $product->category ?? 'Umum' }}
                            </span>
                        </div>

                        <!-- Product Code Top Right -->
                        <div class="absolute top-3 right-3 z-10 text-[10px] font-mono text-white/70">
                            {{ $product->product_code }}
                        </div>

                        <!-- Product Cover Visual -->
                        <div class="w-24 h-32 rounded-lg bg-white/10 border border-white/20 shadow-xl backdrop-blur flex flex-col items-center justify-center p-2 text-center transform group-hover:scale-105 group-hover:-rotate-1 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white/80 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span class="text-[10px] font-extrabold text-white leading-tight line-clamp-3">
                                {{ $product->product_name }}
                            </span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                        <div>
                            <div class="flex items-center gap-1.5 mb-1.5">
                                <span class="w-2 h-2 rounded-full {{ $badge['dot'] }}"></span>
                                <span class="text-[11px] font-semibold {{ $badge['class'] }} px-2 py-0.5 rounded-md">
                                    {{ $badge['text'] }}
                                </span>
                            </div>

                            <h3 class="text-sm font-bold text-slate-900 leading-snug group-hover:text-brand-700 transition-colors line-clamp-2" title="{{ $product->product_name }}">
                                {{ $product->product_name }}
                            </h3>

                            <p class="text-xs text-slate-500 line-clamp-2 mt-1 leading-relaxed">
                                {{ $product->description ?: 'Buku dan perlengkapan resmi PT Distribusi Buku dan Alat Tulis Nusantara.' }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-100">
                            <div class="flex items-baseline justify-between mb-3">
                                <span class="text-[11px] text-slate-500 font-medium">Harga:</span>
                                <span class="text-base font-black text-slate-900">{{ $product->formatted_price }}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" onclick='openProductDetailModal(@json($product))' class="py-2 px-2 text-center text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                                    Detail
                                </button>
                                <button type="button" onclick='addToCart(@json($product), 1)' class="py-2 px-2 text-center text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 rounded-xl transition shadow-xs flex items-center justify-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <span>+ Keranjang</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full p-12 text-center bg-white rounded-2xl border border-slate-200">
                    <p class="text-sm text-slate-500">Tidak ada produk yang cocok dengan pencarian Anda.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 3. HOW IT WORKS SECTION ("Bagaimana Cara Memesan?")                       -->
<!-- ========================================================================= -->
<section id="how-it-works" class="py-20 bg-white border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold text-brand-600 uppercase tracking-wider block mb-1">Panduan Praktis</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Bagaimana Cara Memesan?</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-2">Proses pemesanan buku dan alat tulis secara online di PT Buku Nusantara dirancang sederhana, transparan, dan mudah dipantau.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <!-- Step 1 -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-brand-300 card-hover transition text-center flex flex-col items-center">
                <div class="w-10 h-10 rounded-full bg-brand-100 text-brand-700 font-extrabold text-sm flex items-center justify-center mb-3 shadow-xs">
                    01
                </div>
                <h3 class="text-xs font-bold text-slate-900 mb-1">Pilih Produk</h3>
                <p class="text-[11px] text-slate-500 leading-relaxed">Jelajahi katalog buku teks atau alat tulis sesuai kebutuhan Anda.</p>
            </div>

            <!-- Step 2 -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-brand-300 card-hover transition text-center flex flex-col items-center">
                <div class="w-10 h-10 rounded-full bg-brand-100 text-brand-700 font-extrabold text-sm flex items-center justify-center mb-3 shadow-xs">
                    02
                </div>
                <h3 class="text-xs font-bold text-slate-900 mb-1">Masuk Keranjang</h3>
                <p class="text-[11px] text-slate-500 leading-relaxed">Atur jumlah pesanan dan cek subtotal di drawer keranjang belanja.</p>
            </div>

            <!-- Step 3 -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-brand-300 card-hover transition text-center flex flex-col items-center">
                <div class="w-10 h-10 rounded-full bg-brand-100 text-brand-700 font-extrabold text-sm flex items-center justify-center mb-3 shadow-xs">
                    03
                </div>
                <h3 class="text-xs font-bold text-slate-900 mb-1">Isi Data & Checkout</h3>
                <p class="text-[11px] text-slate-500 leading-relaxed">Masukkan alamat pengiriman dan pilih metode pembayaran resmi.</p>
            </div>

            <!-- Step 4 -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-brand-300 card-hover transition text-center flex flex-col items-center">
                <div class="w-10 h-10 rounded-full bg-brand-100 text-brand-700 font-extrabold text-sm flex items-center justify-center mb-3 shadow-xs">
                    04
                </div>
                <h3 class="text-xs font-bold text-slate-900 mb-1">Pesanan Diproses</h3>
                <p class="text-[11px] text-slate-500 leading-relaxed">Admin memverifikasi pesanan dan tim gudang menyiapkan barang.</p>
            </div>

            <!-- Step 5 -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-brand-300 card-hover transition text-center flex flex-col items-center">
                <div class="w-10 h-10 rounded-full bg-brand-100 text-brand-700 font-extrabold text-sm flex items-center justify-center mb-3 shadow-xs">
                    05
                </div>
                <h3 class="text-xs font-bold text-slate-900 mb-1">Pantau Status</h3>
                <p class="text-[11px] text-slate-500 leading-relaxed">Lacak posisi & perkembangan pesanan lewat nomor pesanan Anda.</p>
            </div>

            <!-- Step 6 -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-brand-300 card-hover transition text-center flex flex-col items-center">
                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 font-extrabold text-sm flex items-center justify-center mb-3 shadow-xs">
                    06
                </div>
                <h3 class="text-xs font-bold text-slate-900 mb-1">Pesanan Diterima</h3>
                <p class="text-[11px] text-slate-500 leading-relaxed">Paket tiba dengan aman, rapi, dan sesuai daftar faktur penjualan.</p>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 4. COMPANY PROFILE SECTION ("Tentang PT Buku Nusantara")                  -->
<!-- ========================================================================= -->
<section id="about" class="py-20 bg-slate-50 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-xs font-bold text-brand-600 uppercase tracking-wider block mb-2">Profil Korporasi</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-snug mb-4">
                    Mendistribusikan Pengetahuan & Perlengkapan Belajar ke Penjuru Nusantara
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                    <strong>{{ $company->company_name ?? 'PT Distribusi Buku dan Alat Tulis Nusantara' }}</strong> adalah perusahaan distribusi skala nasional yang bergerak dalam rantai pasok penerbitan, buku pendidikan kurikulum nasional, buku literatur umum, dan alat tulis kantor/sekolah.
                </p>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-6">
                    Didukung oleh sistem pergudangan modern dan jaringan logistik terintegrasi, kami memastikan setiap pengiriman sampai tepat waktu, berkualitas asli, dan didukung transparansi pelacakan terpercaya.
                </p>

                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div class="p-4 rounded-xl bg-white border border-slate-200">
                        <h4 class="text-xs font-bold text-brand-700 mb-1">Visi Perusahaan</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed">Menjadi mitra distribusi buku dan alat tulis paling terpercaya, modern, dan berintegritas di Indonesia.</p>
                    </div>
                    <div class="p-4 rounded-xl bg-white border border-slate-200">
                        <h4 class="text-xs font-bold text-brand-700 mb-1">Misi Distribusi</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed">Mempercepat ketersediaan bahan literatur bermutu tinggi dengan harga kompetitif dan pelacakan transparan.</p>
                    </div>
                </div>
            </div>

            <!-- Illustration & Network Card -->
            <div class="relative">
                <div class="p-8 rounded-3xl bg-gradient-to-tr from-slate-900 via-brand-950 to-slate-900 text-white shadow-2xl border border-white/10 relative overflow-hidden">
                    <div class="absolute -right-20 -bottom-20 w-64 h-64 rounded-full bg-brand-500/20 blur-3xl"></div>
                    
                    <span class="text-xs font-bold text-brand-300 uppercase tracking-wider block mb-2">Konektivitas Terpadu</span>
                    <h3 class="text-xl font-extrabold text-white mb-4">Jaringan Logistik Nasional</h3>

                    <div class="space-y-3 text-xs text-slate-300">
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10">
                            <svg class="w-5 h-5 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span>Gudang Pusat: Kawasan Industri Pulogadung, Jakarta Timur</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10">
                            <svg class="w-5 h-5 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span>Sertifikasi Standar Mutu dan Perlindungan Pengiriman</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10">
                            <svg class="w-5 h-5 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span>Pengiriman Ekspres & Kargo Kontainer Kapasitas Besar</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 5. WHY CHOOSE US SECTION                                                  -->
<!-- ========================================================================= -->
<section id="why-us" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold text-brand-600 uppercase tracking-wider block mb-1">Nilai Unggulan</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kenapa Memilih PT Buku Nusantara?</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-2">Enam komitmen layanan yang membedakan kami sebagai partner distribusi andalan institusi pendidikan dan toko buku.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 card-hover transition">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900 mb-1.5">Produk Berkualitas & Asli</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Semua buku dan alat tulis bersumber langsung dari penerbit dan pabrik terverifikasi tanpa perantara pihak ketiga palsu.</p>
            </div>

            <!-- Card 2 -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 card-hover transition">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900 mb-1.5">Pemesanan Mudah & Cepat</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Checkout online tanpa birokrasi rumit, cukup pilih produk, isi alamat, dan pesanan langsung tercatat di sistem pusat.</p>
            </div>

            <!-- Card 3 -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 card-hover transition">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900 mb-1.5">Proses Transparan</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Harga, ketersediaan stok, dan status pesanan terpampang jelas secara transparan tanpa biaya tersembunyi.</p>
            </div>

            <!-- Card 4 -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 card-hover transition">
                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900 mb-1.5">Tracking Pesanan Real-Time</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Pantau setiap tahapan pemesanan mulai dari konfirmasi, penyiapan gudang, hingga pengiriman dengan nomor pesanan.</p>
            </div>

            <!-- Card 5 -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 card-hover transition">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900 mb-1.5">Distribusi Terpercaya</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Mitra logistik resmi dengan jangkauan pengantaran ke seluruh pulau dan kota di Indonesia.</p>
            </div>

            <!-- Card 6 -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 card-hover transition">
                <div class="w-10 h-10 rounded-xl bg-cyan-100 text-cyan-700 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900 mb-1.5">Layanan Responsif</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Tim customer service dan administrasi siap mendampingi kebutuhan pengadaan dan verifikasi pesanan Anda.</p>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<!-- Three.js Interactive Glass Shards Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

<script>
    (function () {
        const container = document.getElementById('hero-canvas-container');
        const canvas = document.getElementById('hero-canvas');
        const fallback = document.getElementById('hero-canvas-fallback');

        // Check prefers-reduced-motion
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // Check WebGL support
        function isWebGLAvailable() {
            try {
                const testCanvas = document.createElement('canvas');
                return !!(window.WebGLRenderingContext && (testCanvas.getContext('webgl') || testCanvas.getContext('experimental-webgl')));
            } catch (e) {
                return false;
            }
        }

        if (!isWebGLAvailable() || typeof THREE === 'undefined') {
            if (canvas) canvas.style.display = 'none';
            if (fallback) fallback.classList.remove('hidden');
            return;
        }

        // Initialize Three.js Scene
        const scene = new THREE.Scene();

        // Camera setup
        const camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 100);
        camera.position.set(0, 0, 11);

        // Renderer setup with GPU optimization
        const renderer = new THREE.WebGLRenderer({
            canvas: canvas,
            alpha: true,
            antialias: window.devicePixelRatio < 2,
            powerPreference: 'high-performance',
        });
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.75));
        renderer.setSize(container.clientWidth, container.clientHeight);

        // Lighting (Ambient & Point lights for glass reflections)
        const ambientLight = new THREE.AmbientLight(0x6366F1, 1.2);
        scene.add(ambientLight);

        const primaryLight = new THREE.PointLight(0x818CF8, 2.8, 30);
        primaryLight.position.set(4, 5, 8);
        scene.add(primaryLight);

        const cyanLight = new THREE.PointLight(0x38BDF8, 2.2, 30);
        cyanLight.position.set(-6, -3, 6);
        scene.add(cyanLight);

        const rimLight = new THREE.DirectionalLight(0xFFFFFF, 1.5);
        rimLight.position.set(0, 8, -5);
        scene.add(rimLight);

        // Shards Physics System Setup
        const isMobile = window.innerWidth < 768;
        const SHARD_COUNT = isMobile ? 16 : 32; // 32 shards on desktop, 16 on mobile
        const shards = [];

        // Glass Material
        const glassMaterial = new THREE.MeshPhysicalMaterial({
            color: 0xEEF2FF,
            roughness: 0.12,
            metalness: 0.15,
            transmission: 0.85,
            transparent: true,
            opacity: 0.82,
            reflectivity: 0.9,
            clearcoat: 1.0,
            clearcoatRoughness: 0.1,
            ior: 1.52, // Glass refraction index
            side: THREE.DoubleSide,
        });

        // Fallback for devices without MeshPhysical transmission
        if (!renderer.capabilities.isWebGL2) {
            glassMaterial.opacity = 0.65;
        }

        // Group container
        const shardGroup = new THREE.Group();
        scene.add(shardGroup);

        // Generate 32 geometric glass shards in open-book / crystalline arch configuration
        for (let i = 0; i < SHARD_COUNT; i++) {
            // Irregular shard polygon shape
            const shape = new THREE.Shape();
            const side = i % 2 === 0 ? 1 : -1;
            const row = Math.floor(i / 2);
            const w = 0.55 + Math.random() * 0.35;
            const h = 0.9 + Math.random() * 0.45;

            shape.moveTo(-w / 2, -h / 2);
            shape.lineTo(w / 2 + (Math.random() - 0.5) * 0.2, -h / 2);
            shape.lineTo(w / 2, h / 2);
            shape.lineTo(-w / 2 + (Math.random() - 0.5) * 0.2, h / 2);
            shape.closePath();

            const extrudeSettings = {
                depth: 0.05,
                bevelEnabled: true,
                bevelSegments: 1,
                steps: 1,
                bevelSize: 0.02,
                bevelThickness: 0.02,
            };

            const geom = new THREE.ExtrudeGeometry(shape, extrudeSettings);
            geom.center();

            const mesh = new THREE.Mesh(geom, glassMaterial);

            // Compute origin position resembling floating book facets
            const angle = (row / (SHARD_COUNT / 2)) * Math.PI * 0.65 - 0.5;
            const radius = 2.4 + (row * 0.15);
            const originX = side * (Math.sin(angle) * radius + 0.6);
            const originY = (row - SHARD_COUNT / 4) * 0.32;
            const originZ = Math.cos(angle) * 0.7 - 0.4;

            mesh.position.set(originX, originY, originZ);

            // Origin rotation (angled slightly like pages of a book)
            const originRotX = (Math.random() - 0.5) * 0.3;
            const originRotY = side * (angle * 0.8 + 0.2);
            const originRotZ = (Math.random() - 0.5) * 0.2;
            mesh.rotation.set(originRotX, originRotY, originRotZ);

            shardGroup.add(mesh);

            shards.push({
                mesh: mesh,
                originPos: new THREE.Vector3(originX, originY, originZ),
                pos: mesh.position.clone(),
                vel: new THREE.Vector3(),
                originRot: new THREE.Vector3(originRotX, originRotY, originRotZ),
                rot: new THREE.Vector3(originRotX, originRotY, originRotZ),
                rotVel: new THREE.Vector3(),
                mass: 0.85 + Math.random() * 0.4,
            });
        }

        // Pointer-Driven Collider State
        const mouse = new THREE.Vector2(-999, -999);
        const mouseWorld = new THREE.Vector3(0, 0, 0);
        const raycaster = new THREE.Raycaster();
        const planeZ = new THREE.Plane(new THREE.Vector3(0, 0, 1), 0);

        function updateMouseCoordinates(clientX, clientY) {
            const rect = canvas.getBoundingClientRect();
            mouse.x = ((clientX - rect.left) / rect.width) * 2 - 1;
            mouse.y = -((clientY - rect.top) / rect.height) * 2 + 1;

            raycaster.setFromCamera(mouse, camera);
            raycaster.ray.intersectPlane(planeZ, mouseWorld);
        }

        window.addEventListener('mousemove', (e) => {
            updateMouseCoordinates(e.clientX, e.clientY);
        }, { passive: true });

        window.addEventListener('touchmove', (e) => {
            if (e.touches.length > 0) {
                updateMouseCoordinates(e.touches[0].clientX, e.touches[0].clientY);
            }
        }, { passive: true });

        // Click / Tap Impulse Wave
        function applyClickImpulse(e) {
            const clickPos = mouseWorld.clone();
            shards.forEach((shard) => {
                const diff = shard.pos.clone().sub(clickPos);
                const dist = Math.max(diff.length(), 0.1);
                const impulseForce = Math.max(0, (1 - dist / 5.5)) * 0.35;
                diff.normalize().multiplyScalar(impulseForce);
                shard.vel.add(diff);
                shard.rotVel.x += (Math.random() - 0.5) * 0.15;
                shard.rotVel.y += (Math.random() - 0.5) * 0.15;
            });
        }

        window.addEventListener('click', applyClickImpulse);

        // Window resize
        function onResize() {
            if (!container) return;
            const w = container.clientWidth;
            const h = container.clientHeight;
            camera.aspect = w / h;
            camera.updateProjectionMatrix();
            renderer.setSize(w, h);
        }
        window.addEventListener('resize', onResize);

        // Pause animation when scrolled out of view or tab hidden
        let isVisible = true;
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                isVisible = entry.isIntersecting;
            });
        }, { threshold: 0.05 });

        observer.observe(container);

        document.addEventListener('visibilitychange', () => {
            isVisible = !document.hidden;
        });

        // Animation Loop with Spring Physics
        let clock = new THREE.Clock();
        let frameId = null;

        function animate() {
            frameId = requestAnimationFrame(animate);

            if (!isVisible) return;

            const delta = Math.min(clock.getDelta(), 0.05);
            const time = clock.getElapsedTime();

            // Ambient gentle floating rotation of entire shard group
            shardGroup.rotation.y = Math.sin(time * 0.35) * 0.08;
            shardGroup.rotation.x = Math.cos(time * 0.25) * 0.04;
            shardGroup.position.y = Math.sin(time * 0.7) * 0.1;

            if (prefersReducedMotion) {
                // Skip displacement physics for reduced motion preference
                renderer.render(scene, camera);
                return;
            }

            // Spring Physics constants
            const SPRING_K = 28.0; // Hooke's spring constant pulling to origin
            const DAMPING = 0.88;  // Velocity damping
            const COLLIDER_RADIUS = 2.8;
            const COLLIDER_FORCE = 3.5;

            // Update each glass shard
            for (let i = 0; i < shards.length; i++) {
                const s = shards[i];

                // 1. Spring force pulling back to origin
                const displacement = s.pos.clone().sub(s.originPos);
                const springForce = displacement.multiplyScalar(-SPRING_K);

                // 2. Pointer collider repulsion
                const toCollider = s.pos.clone().sub(mouseWorld);
                const distToCollider = toCollider.length();

                if (distToCollider < COLLIDER_RADIUS) {
                    const repel = (1 - (distToCollider / COLLIDER_RADIUS));
                    const repelForce = repel * repel * COLLIDER_FORCE;
                    toCollider.normalize().multiplyScalar(repelForce);
                    springForce.add(toCollider);

                    // Add subtle rotation torque when influenced by pointer
                    s.rotVel.x += (Math.random() - 0.5) * 0.05 * repel;
                    s.rotVel.y += (Math.random() - 0.5) * 0.05 * repel;
                }

                // 3. Integrate linear acceleration and velocity
                const accel = springForce.divideScalar(s.mass);
                s.vel.add(accel.multiplyScalar(delta));
                s.vel.multiplyScalar(DAMPING);
                s.pos.add(s.vel.clone().multiplyScalar(delta));
                s.mesh.position.copy(s.pos);

                // 4. Integrate rotational spring back to original orientation
                const rotDiff = s.rot.clone().sub(s.originRot);
                const rotSpring = rotDiff.multiplyScalar(-18.0);
                s.rotVel.add(rotSpring.multiplyScalar(delta));
                s.rotVel.multiplyScalar(0.85);
                s.rot.add(s.rotVel.clone().multiplyScalar(delta));
                s.mesh.rotation.set(s.rot.x, s.rot.y, s.rot.z);
            }

            renderer.render(scene, camera);
        }

        animate();
    })();

    // Dynamic Catalog Filtering Logic
    function filterCategory(cat, btn) {
        document.querySelectorAll('.cat-pill').forEach(el => {
            el.className = 'cat-pill px-3.5 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition whitespace-nowrap border border-transparent';
        });

        if (btn) {
            btn.className = 'cat-pill px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap bg-brand-600 text-white shadow-xs';
        }

        applyFilters(cat);
    }

    function applyFilters(selectedCategory = null) {
        const activeCategoryBtn = document.querySelector('.cat-pill.bg-brand-600');
        const currentCategory = selectedCategory || (activeCategoryBtn ? (activeCategoryBtn.innerText.includes('Semua') ? 'all' : activeCategoryBtn.innerText.trim()) : 'all');
        const searchTerm = document.getElementById('catalog-search').value.toLowerCase().trim();
        const stockFilter = document.getElementById('stock-filter').value;
        const sortFilter = document.getElementById('sort-filter').value;

        const cards = Array.from(document.querySelectorAll('.product-card'));
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name');
            const code = card.getAttribute('data-code');
            const category = card.getAttribute('data-category');
            const stock = parseInt(card.getAttribute('data-stock'));

            let matchesCat = (currentCategory === 'all' || category === currentCategory);
            let matchesSearch = (!searchTerm || name.includes(searchTerm) || code.includes(searchTerm));
            let matchesStock = true;

            if (stockFilter === 'ready') matchesStock = stock > 10;
            if (stockFilter === 'limited') matchesStock = stock > 0 && stock <= 10;

            if (matchesCat && matchesSearch && matchesStock) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        // Client-side sorting
        const grid = document.getElementById('product-grid');
        const visibleCards = cards.filter(c => !c.classList.contains('hidden'));

        visibleCards.sort((a, b) => {
            if (sortFilter === 'price_asc') {
                return parseFloat(a.getAttribute('data-price')) - parseFloat(b.getAttribute('data-price'));
            } else if (sortFilter === 'price_desc') {
                return parseFloat(b.getAttribute('data-price')) - parseFloat(a.getAttribute('data-price'));
            } else if (sortFilter === 'name_asc') {
                return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
            } else {
                return parseInt(b.getAttribute('data-stock')) - parseInt(a.getAttribute('data-stock'));
            }
        });

        visibleCards.forEach(card => grid.appendChild(card));
    }

    document.getElementById('catalog-search')?.addEventListener('input', () => applyFilters());
</script>
@endsection
