<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PT Distribusi Buku dan Alat Tulis Nusantara - Solusi Distribusi Buku & ATK')</title>
    <meta name="description" content="@yield('meta_description', 'Distributor resmi buku pelajaran, novel, komik, dan alat tulis berkualitas untuk sekolah, toko buku, dan instansi di seluruh Indonesia.')">

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#EEF2FF',
                            100: '#E0E7FF',
                            200: '#C7D2FE',
                            300: '#A5B4FC',
                            400: '#818CF8',
                            500: '#6366F1',
                            600: '#4F46E5',
                            700: '#4338CA',
                            800: '#3730A3',
                            900: '#312E81',
                            950: '#1E1B4B',
                        },
                        corporate: {
                            dark: '#0B0F19',
                            card: '#111827',
                            border: '#1F2937',
                            muted: '#94A3B8',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                        inter: ['Inter', 'sans-serif'],
                    },
                    borderRadius: {
                        'squircle': '1.25rem',
                        'squircle-lg': '1.5rem',
                    },
                    boxShadow: {
                        'glow': '0 0 25px -5px rgba(99, 102, 241, 0.35)',
                        'glow-lg': '0 0 40px -10px rgba(99, 102, 241, 0.45)',
                        'glass': '0 8px 32px 0 rgba(0, 0, 0, 0.08)',
                    }
                }
            }
        }
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        }

        body {
            background-color: #F8FAFC;
            color: #0F172A;
            overflow-x: hidden;
        }

        /* Glassmorphism Classes */
        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(241, 245, 249, 1);
        }

        .glass-dark {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }
        ::-webkit-scrollbar-track {
            background: #F1F5F9;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }

        /* Smooth UI transitions */
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.08), 0 8px 15px -6px rgba(0, 0, 0, 0.04);
        }

        /* Pulse badge */
        @keyframes pulse-subtle {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.85; transform: scale(1.04); }
        }
        .animate-pulse-subtle {
            animation: pulse-subtle 2.5s infinite ease-in-out;
        }

        /* Custom Country Selector & International Phone Input (Matches ERP System) */
        .custom-country-select-hidden {
            display: none !important;
        }

        .custom-country-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.55rem 0.75rem;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-right: none;
            color: #1e293b;
            font-weight: 600;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
            white-space: nowrap;
            border-top-left-radius: 0.75rem;
            border-bottom-left-radius: 0.75rem;
            height: 38px;
            box-sizing: border-box;
        }

        .custom-country-btn:hover {
            background-color: #f1f5f9;
            color: #0f172a;
        }

        .custom-country-btn:focus {
            outline: none;
            border-color: #6366f1;
        }

        .custom-country-btn.open {
            background-color: #e2e8f0;
        }

        .custom-country-btn .flag-img {
            width: 20px;
            height: 14px;
            border-radius: 2px;
            object-fit: cover;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
            flex-shrink: 0;
        }

        .custom-country-btn .dial-code {
            font-size: 0.75rem;
            font-weight: 700;
            color: #334155;
            letter-spacing: -0.01em;
        }

        .custom-country-btn .chevron-icon {
            width: 12px;
            height: 12px;
            color: #94a3b8;
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .custom-country-btn.open .chevron-icon {
            transform: rotate(180deg);
            color: #4f46e5;
        }

        .custom-country-dropdown {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            z-index: 100;
            width: 290px;
            max-width: calc(100vw - 32px);
            background: #ffffff;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.15), 0 8px 10px -6px rgba(15, 23, 42, 0.1);
            padding: 8px;
            display: none;
            animation: countryDropdownFade 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes countryDropdownFade {
            from {
                opacity: 0;
                transform: translateY(-8px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .custom-country-dropdown.show {
            display: block;
        }

        .country-search-box {
            position: relative;
            margin-bottom: 6px;
        }

        .country-search-box input {
            width: 100%;
            padding: 6px 10px 6px 30px !important;
            font-size: 11px;
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.5rem !important;
            background-color: #f8fafc;
            outline: none;
            transition: all 0.15s ease;
        }

        .country-search-box input:focus {
            border-color: #6366f1 !important;
            background-color: #ffffff;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.15);
        }

        .country-search-box .search-icon {
            position: absolute;
            left: 9px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            width: 13px;
            height: 13px;
            pointer-events: none;
        }

        .country-list-scroll {
            max-height: 200px;
            overflow-y: auto;
            padding-right: 2px;
            overscroll-behavior: contain;
        }

        .country-list-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .country-list-scroll::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 8px;
        }

        .country-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 6px 8px;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.12s ease;
            font-size: 11.5px;
            color: #1e293b;
            text-decoration: none;
        }

        .country-item:hover {
            background-color: #eef2ff;
            color: #4338ca;
        }

        .country-item.active {
            background-color: #e0e7ff;
            color: #3730a3;
            font-weight: 700;
        }

        .country-item-left {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .country-item-left .flag-img {
            width: 20px;
            height: 14px;
            border-radius: 2px;
            object-fit: cover;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.12);
            flex-shrink: 0;
        }

        .country-item-name {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .country-item-dial {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            background: #f1f5f9;
            padding: 1.5px 6px;
            border-radius: 4px;
            margin-left: 6px;
            flex-shrink: 0;
            font-family: monospace;
        }

        .country-item:hover .country-item-dial,
        .country-item.active .country-item-dial {
            background: #c7d2fe;
            color: #312e81;
        }

        .country-empty-hint {
            padding: 12px;
            text-align: center;
            color: #94a3b8;
            font-size: 11px;
        }

        .phone-input-joined {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            border-top-right-radius: 0.75rem !important;
            border-bottom-right-radius: 0.75rem !important;
            height: 38px;
            box-sizing: border-box;
        }
    </style>

    @yield('styles')
</head>
<body class="flex flex-col min-h-screen antialiased selection:bg-brand-500 selection:text-white">

    <!-- Sticky Navigation Bar -->
    <header id="main-header" class="sticky top-0 z-50 transition-all duration-300 glass-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-brand-700 to-brand-500 p-0.5 shadow-md group-hover:scale-105 transition-transform">
                        <img src="{{ asset('images/logo PT buku.png') }}" alt="Logo PT Buku Nusantara" class="w-full h-full object-contain rounded-[10px] bg-white p-1">
                    </div>
                    <div>
                        <span class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight block leading-tight group-hover:text-brand-700 transition-colors">
                            PT BUKU NUSANTARA
                        </span>
                        <span class="text-[11px] font-medium text-slate-500 tracking-wide block uppercase">
                            Distribusi Buku & Alat Tulis
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden lg:flex items-center gap-1 xl:gap-2">
                    <a href="{{ route('home') }}" class="px-3.5 py-2 text-sm font-semibold {{ request()->routeIs('home') ? 'text-brand-700' : 'text-slate-600 hover:text-slate-900' }} hover:bg-slate-100/70 rounded-xl transition">
                        Beranda
                    </a>
                    <a href="{{ route('home') }}#catalog" class="px-3.5 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 rounded-xl transition">
                        Katalog Produk
                    </a>
                    <a href="{{ route('home') }}#how-it-works" class="px-3.5 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 rounded-xl transition">
                        Cara Pemesanan
                    </a>
                    <a href="{{ route('home') }}#about" class="px-3.5 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 rounded-xl transition">
                        Tentang Kami
                    </a>
                    <a href="{{ route('public.tracking') }}" class="px-3.5 py-2 text-sm font-semibold {{ request()->routeIs('public.tracking') ? 'text-brand-700 font-bold' : 'text-slate-600 hover:text-slate-900' }} hover:bg-slate-100/70 rounded-xl transition flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Lacak Pesanan
                    </a>
                </nav>

                <!-- Actions: Cart & CTA -->
                <div class="flex items-center gap-3">
                    <!-- Shopping Cart Trigger Button -->
                    <button id="cart-open-btn" type="button" class="relative p-2.5 rounded-xl border border-slate-200 text-slate-700 hover:text-brand-700 hover:border-brand-300 hover:bg-brand-50/50 transition-all focus:outline-none" title="Keranjang Belanja">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span id="cart-badge" class="absolute -top-1.5 -right-1.5 bg-brand-600 text-white text-[11px] font-bold rounded-full h-5 min-w-[20px] px-1 flex items-center justify-center shadow-sm transition-transform scale-0">
                            0
                        </span>
                    </button>

                    <!-- Order History Shortcut -->
                    <a href="{{ route('public.orders') }}" class="hidden sm:flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 transition" title="Riwayat Pesanan">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Riwayat</span>
                    </a>

                    <!-- Primary CTA -->
                    <a href="{{ route('home') }}#catalog" class="hidden sm:inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-gradient-to-r from-brand-700 via-brand-600 to-indigo-600 text-white text-sm font-bold shadow-md shadow-brand-600/20 hover:shadow-glow hover:translate-y-[-1px] active:translate-y-0 transition-all">
                        <span>Pesan Sekarang</span>
                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>

                    <!-- Mobile Hamburger Button -->
                    <button id="mobile-menu-toggle" type="button" class="lg:hidden p-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-100 transition focus:outline-none" aria-label="Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div id="mobile-menu" class="hidden lg:hidden border-t border-slate-200 bg-white/95 backdrop-blur-xl px-4 py-4 space-y-2 shadow-xl">
            <a href="{{ route('home') }}" class="block px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-brand-50 hover:text-brand-700">
                Beranda
            </a>
            <a href="{{ route('home') }}#catalog" class="block px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-brand-50 hover:text-brand-700">
                Katalog Produk
            </a>
            <a href="{{ route('home') }}#how-it-works" class="block px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-brand-50 hover:text-brand-700">
                Cara Pemesanan
            </a>
            <a href="{{ route('home') }}#about" class="block px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-brand-50 hover:text-brand-700">
                Tentang Kami
            </a>
            <a href="{{ route('public.tracking') }}" class="block px-4 py-2.5 rounded-xl text-sm font-semibold text-brand-700 bg-brand-50/70">
                Lacak Pesanan
            </a>
            <a href="{{ route('public.orders') }}" class="block px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-brand-50">
                Riwayat Pesanan
            </a>
            <div class="pt-3 border-t border-slate-100">
                <a href="{{ route('home') }}#catalog" class="w-full block text-center py-2.5 px-4 rounded-xl bg-brand-600 text-white font-bold text-sm shadow hover:bg-brand-700 transition">
                    Pesan Sekarang
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Global Shopping Cart Drawer -->
    <div id="cart-drawer" class="fixed inset-0 z-50 overflow-hidden pointer-events-none transition-opacity duration-300 opacity-0 invisible" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div id="cart-backdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div id="cart-panel" class="pointer-events-auto w-screen max-w-md bg-white shadow-2xl flex flex-col transform translate-x-full transition-transform duration-300 ease-in-out">
                <!-- Cart Header -->
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg bg-brand-100 text-brand-700 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900" id="slide-over-title">Keranjang Belanja</h2>
                            <p class="text-xs text-slate-500"><span id="cart-total-items-count">0</span> item terpilih</p>
                        </div>
                    </div>
                    <button id="cart-close-btn" type="button" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-200/60 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Cart Items List Container -->
                <div id="cart-items-container" class="flex-1 overflow-y-auto p-6 space-y-4">
                    <!-- Dynamic Cart Items rendered via JS -->
                </div>

                <!-- Empty State (Hidden when items exist) -->
                <div id="cart-empty-state" class="hidden flex-1 flex flex-col items-center justify-center p-8 text-center">
                    <div class="w-20 h-20 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-1">Keranjang Anda masih kosong</h3>
                    <p class="text-xs text-slate-500 max-w-xs mb-6">Pilih buku atau alat tulis kebutuhan Anda dari katalog kami sekarang.</p>
                    <a href="{{ route('home') }}#catalog" onclick="closeCart()" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition shadow-sm">
                        Mulai Belanja
                    </a>
                </div>

                <!-- Cart Footer & Checkout Action -->
                <div id="cart-footer" class="p-6 border-t border-slate-100 bg-slate-50/50 space-y-4">
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs text-slate-500">
                            <span>Subtotal Barang:</span>
                            <span id="cart-subtotal" class="font-semibold text-slate-700">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-xs text-slate-500">
                            <span>Biaya Pengiriman:</span>
                            <span class="font-semibold text-emerald-600">Dihitung saat konfirmasi</span>
                        </div>
                        <div class="pt-2 border-t border-slate-200 flex justify-between text-base font-extrabold text-slate-900">
                            <span>Total Tagihan:</span>
                            <span id="cart-grandtotal" class="text-brand-700">Rp 0</span>
                        </div>
                    </div>

                    <button id="cart-checkout-btn" type="button" class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-brand-700 to-indigo-600 hover:from-brand-800 hover:to-indigo-700 text-white font-bold text-sm shadow-md hover:shadow-glow transition flex items-center justify-center gap-2">
                        <span>Lanjut ke Checkout</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                    <button type="button" onclick="clearCart()" class="w-full text-center text-xs text-slate-400 hover:text-rose-600 transition">
                        Kosongkan Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Multi-Step Checkout Modal -->
    <div id="checkout-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div id="checkout-backdrop" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm transition-opacity"></div>

            <div class="relative bg-white rounded-2xl max-w-2xl w-full shadow-2xl overflow-hidden z-10 border border-slate-100">
                <!-- Modal Header with 4-Step Indicator -->
                <div class="bg-gradient-to-r from-slate-900 via-brand-950 to-slate-900 p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <span class="text-xs font-semibold text-brand-300 uppercase tracking-wider block">Checkout Pemesanan Online</span>
                            <h2 class="text-xl font-extrabold text-white">PT Buku Nusantara</h2>
                        </div>
                        <button type="button" onclick="closeCheckoutModal()" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Step indicators -->
                    <div class="flex items-center justify-between max-w-md mx-auto text-xs">
                        <div class="flex flex-col items-center step-indicator" data-step="1">
                            <div class="w-7 h-7 rounded-full bg-brand-500 text-white font-bold flex items-center justify-center text-xs shadow">1</div>
                            <span class="mt-1 text-[11px] font-medium text-brand-200">Keranjang</span>
                        </div>
                        <div class="flex-1 h-0.5 bg-white/20 mx-2"></div>
                        <div class="flex flex-col items-center step-indicator" data-step="2">
                            <div class="w-7 h-7 rounded-full bg-white/20 text-white font-bold flex items-center justify-center text-xs">2</div>
                            <span class="mt-1 text-[11px] font-medium text-slate-400">Data Pemesan</span>
                        </div>
                        <div class="flex-1 h-0.5 bg-white/20 mx-2"></div>
                        <div class="flex flex-col items-center step-indicator" data-step="3">
                            <div class="w-7 h-7 rounded-full bg-white/20 text-white font-bold flex items-center justify-center text-xs">3</div>
                            <span class="mt-1 text-[11px] font-medium text-slate-400">Konfirmasi</span>
                        </div>
                    </div>
                </div>

                <!-- Checkout Step Forms -->
                <div class="p-6">
                    <form id="checkout-form" onsubmit="handleCheckoutSubmit(event)">
                        <!-- STEP 1: Review Items -->
                        <div id="checkout-step-1" class="space-y-4">
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Ringkasan Produk</h3>
                            <div id="checkout-items-preview" class="max-h-60 overflow-y-auto space-y-2 divide-y divide-slate-100 pr-1">
                                <!-- Populated dynamically -->
                            </div>
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1 text-sm">
                                <div class="flex justify-between text-slate-600">
                                    <span>Total Item:</span>
                                    <span id="checkout-preview-count" class="font-bold text-slate-900">0</span>
                                </div>
                                <div class="flex justify-between text-slate-900 font-extrabold text-base pt-2 border-t border-slate-200">
                                    <span>Total Pesanan:</span>
                                    <span id="checkout-preview-total" class="text-brand-700">Rp 0</span>
                                </div>
                            </div>
                            <div class="flex justify-end gap-3 pt-2">
                                <button type="button" onclick="closeCheckoutModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800">
                                    Batal
                                </button>
                                <button type="button" onclick="goToCheckoutStep(2)" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition shadow">
                                    Lanjut: Informasi Customer →
                                </button>
                            </div>
                        </div>

                        <!-- STEP 2: Customer Information -->
                        <div id="checkout-step-2" class="space-y-4 hidden">
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Informasi Customer & Alamat Pengiriman</h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                                    <input type="text" name="customer_name" required placeholder="Contoh: Budi Santoso" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor WhatsApp / HP *</label>
                                    <div class="relative flex items-center">
                                        <select class="country-code-select custom-country-select-hidden">
                                            <option value="+62" selected>🇮🇩 +62</option>
                                        </select>
                                        <input type="tel" 
                                               id="checkout-phone-input"
                                               class="phone-number-input phone-input-joined flex-1 px-3.5 py-2 text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition font-medium" 
                                               placeholder="81234567890" 
                                               required 
                                               inputmode="numeric" 
                                               pattern="[0-9]*"
                                               maxlength="15">
                                        <input type="hidden" name="customer_phone" id="checkout-customer-phone" value="">
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                                        <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <span>Ketik angka saja tanpa awalan 0 (contoh: <strong>81234567890</strong>)</span>
                                    </p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Email (Opsional untuk bukti invoice)</label>
                                <input type="email" name="customer_email" placeholder="nama@email.com" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kota / Kabupaten *</label>
                                    <input type="text" name="city" required placeholder="Contoh: Jakarta Timur / Surabaya" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Metode Pembayaran *</label>
                                    <select name="payment_method" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition">
                                        <option value="Bank Transfer">Transfer Bank (BCA / Mandiri / BRI)</option>
                                        <option value="QRIS">QRIS (Semua E-Wallet / M-Banking)</option>
                                        <option value="Cash on Delivery">Bayar di Tempat (COD)</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Lengkap Pengiriman *</label>
                                <textarea name="shipping_address" rows="2" required placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kode pos" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition"></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Tambahan untuk Gudang / Kurir</label>
                                <input type="text" name="notes" placeholder="Contoh: Mohon packing bubble wrap tebal, kirim hari kerja" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition">
                            </div>

                            <div class="flex justify-between items-center pt-2">
                                <button type="button" onclick="goToCheckoutStep(1)" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800">
                                    ← Kembali
                                </button>
                                <button type="button" onclick="goToCheckoutStep(3)" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition shadow">
                                    Lanjut: Konfirmasi Pesanan →
                                </button>
                            </div>
                        </div>

                        <!-- STEP 3: Final Confirmation -->
                        <div id="checkout-step-3" class="space-y-4 hidden">
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Konfirmasi & Penyelesaian Pesanan</h3>
                            
                            <div class="p-4 rounded-xl bg-brand-50/60 border border-brand-100 text-xs space-y-2">
                                <div class="font-bold text-brand-900 border-b border-brand-200/60 pb-1">Detail Pemesan:</div>
                                <div class="grid grid-cols-2 gap-2 text-slate-700">
                                    <div>Nama: <strong id="confirm-name">-</strong></div>
                                    <div>No HP: <strong id="confirm-phone">-</strong></div>
                                    <div class="col-span-2">Alamat: <strong id="confirm-address">-</strong></div>
                                    <div class="col-span-2">Metode Bayar: <strong id="confirm-payment">-</strong></div>
                                </div>
                            </div>

                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs space-y-1.5">
                                <div class="flex justify-between text-slate-600">
                                    <span>Jumlah Item:</span>
                                    <span id="confirm-items-count" class="font-bold text-slate-800">0</span>
                                </div>
                                <div class="flex justify-between text-slate-600">
                                    <span>Estimasi Pengiriman:</span>
                                    <span class="font-bold text-slate-800">1-3 Hari Kerja</span>
                                </div>
                                <div class="flex justify-between text-base font-extrabold text-slate-900 pt-2 border-t border-slate-200">
                                    <span>Total Akhir:</span>
                                    <span id="confirm-total" class="text-brand-700 text-lg">Rp 0</span>
                                </div>
                            </div>

                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 flex items-start gap-2 text-[11px] text-amber-800">
                                <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Setelah pesanan dibuat, Anda akan mendapatkan <strong>Nomor Pesanan unik (ORD-2026-XXXXX)</strong> untuk melacak status proses secara langsung.</span>
                            </div>

                            <div class="flex justify-between items-center pt-2">
                                <button type="button" onclick="goToCheckoutStep(2)" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800">
                                    ← Edit Data
                                </button>
                                <button id="submit-order-btn" type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-xs font-extrabold tracking-wide transition shadow-lg flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>BUAT PESANAN SEKARANG</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Detail Modal -->
    <div id="product-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div id="product-modal-backdrop" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm transition-opacity"></div>

            <div class="relative bg-white rounded-2xl max-w-2xl w-full shadow-2xl overflow-hidden z-10 border border-slate-100">
                <button type="button" onclick="closeProductModal()" class="absolute top-4 right-4 z-20 text-slate-400 hover:text-slate-600 p-2 rounded-full bg-white/80 backdrop-blur shadow-sm transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="grid grid-cols-1 md:grid-cols-2">
                    <!-- Product Visual Preview Side -->
                    <div id="modal-product-artwork" class="p-8 flex flex-col items-center justify-center bg-gradient-to-br from-slate-900 via-brand-950 to-slate-900 text-white min-h-[300px]">
                        <div id="modal-product-badge" class="px-3 py-1 rounded-full text-xs font-semibold mb-4 bg-white/10 text-white border border-white/20">
                            Buku
                        </div>
                        <div class="w-32 h-44 rounded-xl shadow-2xl bg-white/10 border border-white/20 backdrop-blur flex items-center justify-center p-3 text-center mb-4">
                            <span id="modal-product-title-visual" class="text-xs font-bold text-white leading-tight">
                                Judul Produk
                            </span>
                        </div>
                        <span id="modal-product-code" class="text-[11px] text-slate-400 tracking-wider">SKU-000</span>
                    </div>

                    <!-- Product Details Side -->
                    <div class="p-6 md:p-8 flex flex-col justify-between space-y-4">
                        <div>
                            <span id="modal-category" class="inline-block text-[11px] font-bold uppercase tracking-wider text-brand-600 mb-1">Kategori</span>
                            <h3 id="modal-name" class="text-xl font-extrabold text-slate-900 leading-tight mb-2">Nama Produk</h3>
                            <p id="modal-description" class="text-xs text-slate-600 leading-relaxed line-clamp-4 mb-4">Deskripsi produk...</p>
                            
                            <div class="space-y-2 py-3 border-y border-slate-100 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Ketersediaan:</span>
                                    <span id="modal-stock" class="font-bold text-emerald-600">Tersedia</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Satuan:</span>
                                    <span id="modal-unit" class="font-semibold text-slate-700">Pcs</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-baseline justify-between mb-4">
                                <span class="text-xs text-slate-500 font-medium">Harga Resmi:</span>
                                <span id="modal-price" class="text-2xl font-black text-brand-700">Rp 0</span>
                            </div>

                            <!-- Qty & Add to Cart -->
                            <div class="flex items-center gap-3">
                                <div class="flex items-center border border-slate-300 rounded-xl overflow-hidden">
                                    <button type="button" onclick="decrementModalQty()" class="px-3 py-2 text-slate-600 hover:bg-slate-100 font-bold">-</button>
                                    <input id="modal-qty" type="number" value="1" min="1" max="500" class="w-12 text-center text-xs font-bold text-slate-800 outline-none border-0 p-0">
                                    <button type="button" onclick="incrementModalQty()" class="px-3 py-2 text-slate-600 hover:bg-slate-100 font-bold">+</button>
                                </div>
                                <button id="modal-add-cart-btn" type="button" class="flex-1 py-2.5 px-4 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition shadow flex items-center justify-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <span>Tambah Keranjang</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Corporate Footer -->
    <footer class="bg-corporate-dark text-slate-400 text-xs border-t border-slate-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                <!-- Company Info -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-600 p-0.5 shadow-md">
                            <img src="{{ asset('images/logo PT buku.png') }}" alt="Logo" class="w-full h-full object-contain rounded-[10px] bg-white p-1">
                        </div>
                        <div>
                            <span class="text-base font-extrabold text-white tracking-tight block">
                                PT Distribusi Buku dan Alat Tulis Nusantara
                            </span>
                            <span class="text-[11px] text-slate-400">Pusat Distribusi Buku & Alat Tulis Terpercaya</span>
                        </div>
                    </div>
                    <p class="text-slate-400 leading-relaxed pr-6">
                        Penyedia dan distributor resmi buku pelajaran kurikulum merdeka, buku tulis berkualitas, novel pilihan, komik, dan perlengkapan kantor/sekolah untuk kebutuhan instansi, toko buku, dan masyarakat luas di seluruh Indonesia.
                    </p>
                    <div class="pt-2 text-slate-400 text-[11px] space-y-1">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                            <span>{{ $company->address ?? 'Jl. Industri Raya No. 45, Kawasan Industri Pulogadung, Jakarta Timur' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>info@ptbuku-nusantara.co.id | NPWP: {{ $company->tax_id ?? '01.234.567.8-910.111' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Fast Links -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Navigasi Utama</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a></li>
                        <li><a href="{{ route('home') }}#catalog" class="hover:text-white transition">Katalog Buku & ATK</a></li>
                        <li><a href="{{ route('home') }}#how-it-works" class="hover:text-white transition">Cara Pemesanan</a></li>
                        <li><a href="{{ route('home') }}#about" class="hover:text-white transition">Profil Perusahaan</a></li>
                        <li><a href="{{ route('home') }}#why-us" class="hover:text-white transition">Keunggulan Kami</a></li>
                    </ul>
                </div>

                <!-- Customer Services -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Layanan Pelanggan</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('public.tracking') }}" class="hover:text-white transition text-brand-300 font-semibold flex items-center gap-1">
                            <span>Lacak Pesanan Anda</span>
                            <span class="text-[9px] px-1.5 py-0.2 bg-brand-500/30 rounded text-brand-200">Live</span>
                        </a></li>
                        <li><a href="{{ route('public.orders') }}" class="hover:text-white transition">Riwayat Pemesanan</a></li>
                        <li><a href="{{ route('home') }}#how-it-works" class="hover:text-white transition">Proses Pengiriman</a></li>
                        <li><a href="{{ route('home') }}#contact" class="hover:text-white transition">Pusat Bantuan</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-slate-400">
                <p>© 2026 PT Distribusi Buku dan Alat Tulis NUSANTARA. Seluruh hak cipta dilindungi undang-undang.</p>
                <div class="flex items-center gap-4">
                    <span>Kebijakan Privasi</span>
                    <span>•</span>
                    <span>Syarat & Ketentuan Distribusi</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Global Cart & Modal JavaScript Logic -->
    <script>
        // Global Shopping Cart State Management (LocalStorage + DOM sync)
        const CART_KEY = 'ptbuku_shopping_cart';

        function getCart() {
            try {
                const data = localStorage.getItem(CART_KEY);
                return data ? JSON.parse(data) : [];
            } catch (e) {
                return [];
            }
        }

        function saveCart(cart) {
            localStorage.setItem(CART_KEY, JSON.stringify(cart));
            updateCartBadge();
            renderCartItems();
        }

        function addToCart(product, quantity = 1) {
            let cart = getCart();
            const existingIndex = cart.findIndex(item => item.id === product.id);

            if (existingIndex > -1) {
                cart[existingIndex].quantity += parseInt(quantity);
            } else {
                cart.push({
                    id: product.id,
                    product_code: product.product_code,
                    product_name: product.product_name,
                    category: product.category,
                    price: parseFloat(product.price),
                    formatted_price: product.formatted_price || ('Rp ' + Number(product.price).toLocaleString('id-ID')),
                    unit: product.unit || 'Pcs',
                    quantity: parseInt(quantity)
                });
            }

            saveCart(cart);
            showToast('success', `${product.product_name} (${quantity} ${product.unit || 'pcs'}) ditambahkan ke keranjang!`);
            openCart();
        }

        function updateItemQuantity(productId, delta) {
            let cart = getCart();
            const index = cart.findIndex(item => item.id === productId);
            if (index > -1) {
                cart[index].quantity += delta;
                if (cart[index].quantity <= 0) {
                    cart.splice(index, 1);
                }
                saveCart(cart);
            }
        }

        function removeFromCart(productId) {
            let cart = getCart();
            cart = cart.filter(item => item.id !== productId);
            saveCart(cart);
        }

        function clearCart() {
            saveCart([]);
        }

        function updateCartBadge() {
            const cart = getCart();
            const badge = document.getElementById('cart-badge');
            const totalCount = cart.reduce((sum, item) => sum + item.quantity, 0);

            if (badge) {
                badge.innerText = totalCount;
                if (totalCount > 0) {
                    badge.classList.remove('scale-0');
                    badge.classList.add('scale-100');
                } else {
                    badge.classList.remove('scale-100');
                    badge.classList.add('scale-0');
                }
            }

            const totalItemEl = document.getElementById('cart-total-items-count');
            if (totalItemEl) {
                totalItemEl.innerText = totalCount;
            }
        }

        function renderCartItems() {
            const cart = getCart();
            const container = document.getElementById('cart-items-container');
            const emptyState = document.getElementById('cart-empty-state');
            const footer = document.getElementById('cart-footer');
            const subtotalEl = document.getElementById('cart-subtotal');
            const grandtotalEl = document.getElementById('cart-grandtotal');

            if (!container) return;

            if (cart.length === 0) {
                container.innerHTML = '';
                container.classList.add('hidden');
                if (emptyState) emptyState.classList.remove('hidden');
                if (footer) footer.classList.add('hidden');
                return;
            }

            container.classList.remove('hidden');
            if (emptyState) emptyState.classList.add('hidden');
            if (footer) footer.classList.remove('hidden');

            let subtotal = 0;
            let html = '';

            cart.forEach(item => {
                const itemSubtotal = item.price * item.quantity;
                subtotal += itemSubtotal;

                html += `
                    <div class="p-3.5 rounded-xl border border-slate-200 bg-white hover:border-brand-300 transition-colors flex items-center justify-between gap-3 shadow-2xs">
                        <div class="flex-1 min-w-0">
                            <span class="text-[10px] font-bold text-brand-600 uppercase tracking-wide">${escapeHtml(item.category || 'Buku/ATK')}</span>
                            <h4 class="text-xs font-bold text-slate-900 truncate leading-snug">${escapeHtml(item.product_name)}</h4>
                            <p class="text-[11px] font-semibold text-slate-500 mt-0.5">${item.formatted_price} / ${item.unit}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50 text-xs">
                                <button type="button" onclick="updateItemQuantity(${item.id}, -1)" class="w-6 h-6 flex items-center justify-center hover:bg-slate-200 text-slate-700 font-bold">-</button>
                                <span class="w-6 text-center font-bold text-slate-900">${item.quantity}</span>
                                <button type="button" onclick="updateItemQuantity(${item.id}, 1)" class="w-6 h-6 flex items-center justify-center hover:bg-slate-200 text-slate-700 font-bold">+</button>
                            </div>
                            <button type="button" onclick="removeFromCart(${item.id})" class="text-slate-400 hover:text-rose-600 p-1 transition" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
            const formattedTotal = 'Rp ' + subtotal.toLocaleString('id-ID');
            if (subtotalEl) subtotalEl.innerText = formattedTotal;
            if (grandtotalEl) grandtotalEl.innerText = formattedTotal;
        }

        // Cart Drawer Open/Close
        function openCart() {
            const drawer = document.getElementById('cart-drawer');
            const panel = document.getElementById('cart-panel');
            if (drawer && panel) {
                renderCartItems();
                drawer.classList.remove('invisible', 'opacity-0');
                drawer.classList.add('opacity-100');
                panel.classList.remove('translate-x-full');
            }
        }

        function closeCart() {
            const drawer = document.getElementById('cart-drawer');
            const panel = document.getElementById('cart-panel');
            if (drawer && panel) {
                panel.classList.add('translate-x-full');
                setTimeout(() => {
                    drawer.classList.remove('opacity-100');
                    drawer.classList.add('invisible', 'opacity-0');
                }, 250);
            }
        }

        // Comprehensive International Country List (Matches ERP Admin System)
        const COUNTRY_LIST = [
            { code: 'id', name: 'Indonesia', dial: '+62' },
            { code: 'my', name: 'Malaysia', dial: '+60' },
            { code: 'sg', name: 'Singapura', dial: '+65' },
            { code: 'ph', name: 'Filipina', dial: '+63' },
            { code: 'th', name: 'Thailand', dial: '+66' },
            { code: 'vn', name: 'Vietnam', dial: '+84' },
            { code: 'bn', name: 'Brunei', dial: '+673' },
            { code: 'kh', name: 'Kamboja', dial: '+855' },
            { code: 'mm', name: 'Myanmar', dial: '+95' },
            { code: 'la', name: 'Laos', dial: '+856' },
            { code: 'tl', name: 'Timor Leste', dial: '+670' },
            { code: 'us', name: 'Amerika Serikat', dial: '+1' },
            { code: 'ca', name: 'Kanada', dial: '+1' },
            { code: 'gb', name: 'Inggris (UK)', dial: '+44' },
            { code: 'au', name: 'Australia', dial: '+61' },
            { code: 'jp', name: 'Jepang', dial: '+81' },
            { code: 'kr', name: 'Korea Selatan', dial: '+82' },
            { code: 'cn', name: 'China', dial: '+86' },
            { code: 'hk', name: 'Hong Kong', dial: '+852' },
            { code: 'tw', name: 'Taiwan', dial: '+886' },
            { code: 'sa', name: 'Arab Saudi', dial: '+966' },
            { code: 'ae', name: 'Uni Emirat Arab', dial: '+971' },
            { code: 'qa', name: 'Qatar', dial: '+974' },
            { code: 'kw', name: 'Kuwait', dial: '+965' },
            { code: 'tr', name: 'Turki', dial: '+90' },
            { code: 'de', name: 'Jerman', dial: '+49' },
            { code: 'nl', name: 'Belanda', dial: '+31' },
            { code: 'fr', name: 'Prancis', dial: '+33' },
            { code: 'it', name: 'Italia', dial: '+39' },
            { code: 'es', name: 'Spanyol', dial: '+34' },
            { code: 'ch', name: 'Swiss', dial: '+41' },
            { code: 'ru', name: 'Rusia', dial: '+7' },
            { code: 'in', name: 'India', dial: '+91' },
            { code: 'br', name: 'Brasil', dial: '+55' },
            { code: 'za', name: 'Afrika Selatan', dial: '+27' },
            { code: 'nz', name: 'Selandia Baru', dial: '+64' }
        ];

        function initPhoneInputs() {
            document.querySelectorAll('.phone-number-input').forEach(function(input) {
                if (input.dataset.phoneInitialized) return;
                input.dataset.phoneInitialized = 'true';

                const container = input.closest('.relative') || input.parentElement;
                let countrySelect = container ? container.querySelector('.country-code-select') : null;

                if (countrySelect) {
                    countrySelect.classList.add('custom-country-select-hidden');
                }

                let currentCountry = COUNTRY_LIST[0]; // Default Indonesia (+62)

                const form = input.closest('form') || document.getElementById('checkout-form');
                const hidden = form ? form.querySelector('input[name="customer_phone"]') : document.getElementById('checkout-customer-phone');

                const getDialCode = () => currentCountry.dial;
                const getDialDigits = () => getDialCode().replace(/\D/g, '');

                const cleanDigits = (val) => {
                    if (!val) return '';
                    let d = val.toString().replace(/\D/g, '');
                    const dial = getDialDigits();
                    if (dial && d.startsWith(dial)) {
                        d = d.substring(dial.length);
                    }
                    if (d.startsWith('0')) {
                        d = d.substring(1);
                    }
                    return d;
                };

                input.setAttribute('inputmode', 'numeric');
                input.setAttribute('pattern', '[0-9]*');

                // Build Custom Trigger Button
                let customBtn = container.querySelector('.custom-country-btn');
                if (!customBtn) {
                    customBtn = document.createElement('button');
                    customBtn.type = 'button';
                    customBtn.className = 'custom-country-btn';
                    customBtn.setAttribute('aria-haspopup', 'true');
                    customBtn.innerHTML = `
                        <img src="https://flagcdn.com/w40/${currentCountry.code}.png" class="flag-img" alt="${currentCountry.code.toUpperCase()}">
                        <span class="dial-code">${currentCountry.dial}</span>
                        <svg class="chevron-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                        </svg>
                    `;
                    input.parentNode.insertBefore(customBtn, input);
                }

                // Build Custom Dropdown Popover
                let customDropdown = container.querySelector('.custom-country-dropdown');
                if (!customDropdown) {
                    customDropdown = document.createElement('div');
                    customDropdown.className = 'custom-country-dropdown';

                    // Search box
                    const searchBox = document.createElement('div');
                    searchBox.className = 'country-search-box';
                    searchBox.innerHTML = `
                        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <input type="text" placeholder="Cari negara atau kode (+60, MY)..." class="country-search-input" autocomplete="off">
                    `;
                    customDropdown.appendChild(searchBox);

                    // Scrollable list
                    const listScroll = document.createElement('div');
                    listScroll.className = 'country-list-scroll';

                    COUNTRY_LIST.forEach(country => {
                        const item = document.createElement('div');
                        item.className = 'country-item' + (country.code === currentCountry.code ? ' active' : '');
                        item.dataset.code = country.code;
                        item.dataset.dial = country.dial;
                        item.dataset.name = country.name.toLowerCase();
                        item.innerHTML = `
                            <div class="country-item-left">
                                <img src="https://flagcdn.com/w40/${country.code}.png" class="flag-img" alt="${country.code.toUpperCase()}">
                                <span class="country-item-name">${country.name}</span>
                            </div>
                            <span class="country-item-dial">${country.dial}</span>
                        `;

                        item.addEventListener('click', function(e) {
                            e.stopPropagation();
                            currentCountry = country;

                            customBtn.querySelector('.flag-img').src = `https://flagcdn.com/w40/${country.code}.png`;
                            customBtn.querySelector('.flag-img').alt = country.code.toUpperCase();
                            customBtn.querySelector('.dial-code').textContent = country.dial;

                            listScroll.querySelectorAll('.country-item').forEach(el => el.classList.remove('active'));
                            item.classList.add('active');

                            if (countrySelect) {
                                countrySelect.value = country.dial;
                            }

                            syncHidden();
                            customDropdown.classList.remove('show');
                            customBtn.classList.remove('open');
                            input.focus();
                        });

                        listScroll.appendChild(item);
                    });

                    customDropdown.appendChild(listScroll);

                    const emptyHint = document.createElement('div');
                    emptyHint.className = 'country-empty-hint';
                    emptyHint.textContent = 'Negara tidak ditemukan';
                    emptyHint.style.display = 'none';
                    customDropdown.appendChild(emptyHint);

                    const searchInput = searchBox.querySelector('.country-search-input');
                    searchInput.addEventListener('input', function() {
                        const q = this.value.trim().toLowerCase();
                        let matches = 0;
                        listScroll.querySelectorAll('.country-item').forEach(item => {
                            const name = item.dataset.name;
                            const dial = item.dataset.dial;
                            const code = item.dataset.code;
                            if (!q || name.includes(q) || dial.includes(q) || code.includes(q)) {
                                item.style.display = 'flex';
                                matches++;
                            } else {
                                item.style.display = 'none';
                            }
                        });
                        emptyHint.style.display = matches === 0 ? 'block' : 'none';
                    });

                    container.appendChild(customDropdown);

                    // Toggle Dropdown Button Click
                    customBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const isOpen = customDropdown.classList.toggle('show');
                        customBtn.classList.toggle('open', isOpen);

                        if (isOpen) {
                            searchInput.value = '';
                            searchInput.dispatchEvent(new Event('input'));
                            setTimeout(() => searchInput.focus(), 50);
                        }
                    });

                    // Close when clicking outside
                    document.addEventListener('click', function(e) {
                        if (!e.target.closest('.custom-country-btn') && !e.target.closest('.custom-country-dropdown')) {
                            customDropdown.classList.remove('show');
                            customBtn.classList.remove('open');
                        }
                    });
                }

                // Block non-numeric keystrokes (prevents typing letters)
                input.addEventListener('keydown', function(e) {
                    if (
                        ['Backspace', 'Tab', 'ArrowLeft', 'ArrowRight', 'Delete', 'Home', 'End', 'Enter'].includes(e.key) ||
                        (e.ctrlKey || e.metaKey)
                    ) {
                        return;
                    }
                    if (!/^\d$/.test(e.key)) {
                        e.preventDefault();
                    }
                });

                // Sync with hidden phone input
                const syncHidden = () => {
                    if (hidden) {
                        hidden.value = input.value ? getDialCode() + input.value : '';
                    }
                };

                input.addEventListener('input', function() {
                    let val = cleanDigits(this.value);
                    if (this.value !== val) {
                        this.value = val;
                    }
                    syncHidden();
                });

                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const text = (e.clipboardData || window.clipboardData).getData('text');
                    const clean = cleanDigits(text);
                    const start = this.selectionStart || 0;
                    const end = this.selectionEnd || 0;
                    const current = this.value || '';
                    const next = current.substring(0, start) + clean + current.substring(end);
                    this.value = cleanDigits(next);
                    syncHidden();
                });

                syncHidden();
            });
        }

        // Checkout Modal Flow
        function openCheckoutModal() {
            const cart = getCart();
            if (cart.length === 0) {
                showToast('warning', 'Keranjang belanja Anda masih kosong!');
                return;
            }

            closeCart();
            goToCheckoutStep(1);
            const modal = document.getElementById('checkout-modal');
            if (modal) {
                modal.classList.remove('hidden');
                setTimeout(initPhoneInputs, 50);
            }
        }

        function closeCheckoutModal() {
            const modal = document.getElementById('checkout-modal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        function goToCheckoutStep(step) {
            const cart = getCart();
            const step1 = document.getElementById('checkout-step-1');
            const step2 = document.getElementById('checkout-step-2');
            const step3 = document.getElementById('checkout-step-3');

            [step1, step2, step3].forEach(el => el && el.classList.add('hidden'));

            // Update step indicators
            document.querySelectorAll('.step-indicator').forEach(ind => {
                const s = parseInt(ind.getAttribute('data-step'));
                const circle = ind.querySelector('div');
                const text = ind.querySelector('span');
                if (s <= step) {
                    circle.className = 'w-7 h-7 rounded-full bg-brand-500 text-white font-bold flex items-center justify-center text-xs shadow';
                    text.className = 'mt-1 text-[11px] font-bold text-white';
                } else {
                    circle.className = 'w-7 h-7 rounded-full bg-white/20 text-white font-bold flex items-center justify-center text-xs';
                    text.className = 'mt-1 text-[11px] font-medium text-slate-400';
                }
            });

            if (step === 1) {
                step1.classList.remove('hidden');
                let html = '';
                let total = 0;
                let count = 0;

                cart.forEach(item => {
                    total += item.price * item.quantity;
                    count += item.quantity;
                    html += `
                        <div class="flex items-center justify-between py-2 text-xs">
                            <div>
                                <h4 class="font-bold text-slate-800">${escapeHtml(item.product_name)}</h4>
                                <span class="text-slate-500">${item.quantity} x ${item.formatted_price}</span>
                            </div>
                            <span class="font-extrabold text-slate-900">Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</span>
                        </div>
                    `;
                });

                document.getElementById('checkout-items-preview').innerHTML = html;
                document.getElementById('checkout-preview-count').innerText = `${count} barang`;
                document.getElementById('checkout-preview-total').innerText = 'Rp ' + total.toLocaleString('id-ID');
            } else if (step === 2) {
                step2.classList.remove('hidden');
            } else if (step === 3) {
                // Validate step 2 inputs before moving to 3
                const form = document.getElementById('checkout-form');
                const phoneInput = document.getElementById('checkout-phone-input');
                if (!form.customer_name.value || !form.customer_phone.value || !form.shipping_address.value || !form.city.value) {
                    showToast('warning', 'Harap lengkapi semua kolom wajib (Nama, No HP, Kota, Alamat)!');
                    goToCheckoutStep(2);
                    return;
                }

                if (phoneInput && phoneInput.value.length < 8) {
                    showToast('warning', 'Nomor WhatsApp / HP minimal 8 digit angka!');
                    goToCheckoutStep(2);
                    phoneInput.focus();
                    return;
                }

                step3.classList.remove('hidden');
                document.getElementById('confirm-name').innerText = form.customer_name.value;
                document.getElementById('confirm-phone').innerText = form.customer_phone.value;
                document.getElementById('confirm-address').innerText = form.shipping_address.value + ', ' + form.city.value;
                document.getElementById('confirm-payment').innerText = form.payment_method.value;

                let total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                let count = cart.reduce((sum, item) => sum + item.quantity, 0);

                document.getElementById('confirm-items-count').innerText = `${count} barang (${cart.length} jenis produk)`;
                document.getElementById('confirm-total').innerText = 'Rp ' + total.toLocaleString('id-ID');
            }
        }

        async function handleCheckoutSubmit(e) {
            e.preventDefault();
            const form = document.getElementById('checkout-form');
            const submitBtn = document.getElementById('submit-order-btn');
            const cart = getCart();

            if (cart.length === 0) {
                showToast('error', 'Keranjang belanja kosong!');
                return;
            }

            const payload = {
                customer_name: form.customer_name.value,
                customer_phone: form.customer_phone.value,
                customer_email: form.customer_email.value || null,
                shipping_address: form.shipping_address.value,
                city: form.city.value,
                notes: form.notes.value || null,
                payment_method: form.payment_method.value,
                items: cart.map(item => ({
                    id: item.id,
                    quantity: item.quantity
                }))
            };

            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memproses Pesanan...</span>
            `;

            try {
                const response = await fetch('{{ route("public.checkout") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (result.success) {
                    clearCart();
                    Swal.fire({
                        title: 'Pesanan Berhasil Dibuat!',
                        html: `
                            <div class="text-center py-2">
                                <p class="text-sm text-slate-600 mb-2">Terima kasih atas pesanan Anda.</p>
                                <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-3 mb-3">
                                    <span class="text-xs text-indigo-700 block font-medium">Nomor Pesanan Anda:</span>
                                    <span class="text-xl font-black text-indigo-900 tracking-wider">${result.order_code}</span>
                                </div>
                                <p class="text-xs text-slate-500">Anda dapat melacak proses penyiapan dan pengiriman secara langsung.</p>
                            </div>
                        `,
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Lacak Pesanan',
                        cancelButtonText: 'Kembali ke Beranda',
                        confirmButtonColor: '#4F46E5',
                        cancelButtonColor: '#64748B',
                    }).then((swalResult) => {
                        if (swalResult.isConfirmed) {
                            window.location.href = result.tracking_url;
                        } else {
                            window.location.href = result.redirect_url;
                        }
                    });
                } else {
                    throw new Error(result.message || 'Gagal membuat pesanan.');
                }
            } catch (err) {
                Swal.fire({
                    title: 'Pesanan Gagal',
                    text: err.message,
                    icon: 'error',
                    confirmButtonColor: '#EF4444'
                });
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>BUAT PESANAN SEKARANG</span>
                `;
            }
        }

        // Product Modal Logic
        let currentModalProduct = null;

        function openProductDetailModal(product) {
            currentModalProduct = product;
            const modal = document.getElementById('product-modal');
            if (!modal) return;

            document.getElementById('modal-category').innerText = product.category || 'Umum';
            document.getElementById('modal-name').innerText = product.product_name;
            document.getElementById('modal-description').innerText = product.description || 'Produk berkualitas dari PT Distribusi Buku dan Alat Tulis Nusantara.';
            document.getElementById('modal-price').innerText = product.formatted_price || ('Rp ' + Number(product.price).toLocaleString('id-ID'));
            document.getElementById('modal-unit').innerText = product.unit || 'Pcs';
            document.getElementById('modal-product-code').innerText = product.product_code;
            document.getElementById('modal-product-badge').innerText = product.category || 'Buku';
            document.getElementById('modal-product-title-visual').innerText = product.product_name;
            document.getElementById('modal-qty').value = 1;

            const stockEl = document.getElementById('modal-stock');
            if (product.system_stock > 10) {
                stockEl.innerText = `Tersedia (${product.system_stock} ${product.unit})`;
                stockEl.className = 'font-bold text-emerald-600';
            } else if (product.system_stock > 0) {
                stockEl.innerText = `Tersisa ${product.system_stock} ${product.unit}`;
                stockEl.className = 'font-bold text-amber-600';
            } else {
                stockEl.innerText = 'Stok Habis';
                stockEl.className = 'font-bold text-rose-600';
            }

            const addBtn = document.getElementById('modal-add-cart-btn');
            addBtn.onclick = () => {
                const qty = parseInt(document.getElementById('modal-qty').value) || 1;
                addToCart(product, qty);
                closeProductModal();
            };

            modal.classList.remove('hidden');
        }

        function closeProductModal() {
            const modal = document.getElementById('product-modal');
            if (modal) modal.classList.add('hidden');
        }

        function incrementModalQty() {
            const input = document.getElementById('modal-qty');
            if (input) input.value = parseInt(input.value || 1) + 1;
        }

        function decrementModalQty() {
            const input = document.getElementById('modal-qty');
            if (input && parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        // Toast Helper
        function showToast(type, message) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 2800,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            Toast.fire({
                icon: type,
                title: message
            });
        }

        function escapeHtml(string) {
            return String(string).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        // DOM Loaded Event Listeners
        document.addEventListener('DOMContentLoaded', () => {
            updateCartBadge();
            initPhoneInputs();

            // Cart trigger listeners
            const openBtn = document.getElementById('cart-open-btn');
            const closeBtn = document.getElementById('cart-close-btn');
            const backdrop = document.getElementById('cart-backdrop');
            const checkoutBtn = document.getElementById('cart-checkout-btn');

            if (openBtn) openBtn.addEventListener('click', openCart);
            if (closeBtn) closeBtn.addEventListener('click', closeCart);
            if (backdrop) backdrop.addEventListener('click', closeCart);
            if (checkoutBtn) checkoutBtn.addEventListener('click', openCheckoutModal);

            // Checkout backdrop listener
            const checkoutBackdrop = document.getElementById('checkout-backdrop');
            if (checkoutBackdrop) checkoutBackdrop.addEventListener('click', closeCheckoutModal);

            // Product modal backdrop
            const productBackdrop = document.getElementById('product-modal-backdrop');
            if (productBackdrop) productBackdrop.addEventListener('click', closeProductModal);

            // Mobile menu toggle
            const menuToggle = document.getElementById('mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            if (menuToggle && mobileMenu) {
                menuToggle.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Header scroll elevation
            window.addEventListener('scroll', () => {
                const header = document.getElementById('main-header');
                if (window.scrollY > 20) {
                    header.classList.add('shadow-md');
                } else {
                    header.classList.remove('shadow-md');
                }
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
