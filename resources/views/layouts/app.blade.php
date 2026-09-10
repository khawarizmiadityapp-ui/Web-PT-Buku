<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - PT Nusantara ERP</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        }
        
        body {
            background-color: #F8FAFC;
        }

        /* Modern Rounded Containers & Squircle Design */
        .rounded-squircle {
            border-radius: 1.25rem !important;
        }
        
        .sidebar-active {
            background: linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%);
            color: #4338CA;
            font-weight: 600;
            border-radius: 0.875rem;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.12);
        }
        
        .stat-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 1.25rem;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.07), 0 8px 15px -6px rgba(0, 0, 0, 0.04);
        }

        .glass-header {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* Rounded elements overrides for modern look */
        .card, div.bg-white:not(.flat-box) {
            border-radius: 1.25rem;
        }
        
        input, select, textarea {
            border-radius: 0.75rem !important;
        }
        
        .progress-bar {
            transition: width 0.5s ease;
            border-radius: 9999px;
        }
        
        /* Custom smooth scrollbar */
        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }
        
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 9999px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
        
        .dropdown-menu {
            transform-origin: top right;
            transition: opacity 0.15s ease, transform 0.15s ease;
            border-radius: 0.875rem !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05) !important;
            z-index: 1050 !important;
        }
        
        .dropdown-menu.hidden {
            display: none !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }

        /* Modern Country Code Picker */
        .custom-country-select-hidden {
            display: none !important;
        }

        .custom-country-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 0.5rem 0.85rem;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-right: none;
            color: #1e293b;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
            white-space: nowrap;
            height: auto;
            min-height: 42px;
            z-index: 2;
        }

        .custom-country-btn:hover {
            background-color: #f1f5f9;
            color: #0f172a;
        }

        .custom-country-btn:focus {
            outline: none;
            border-color: #3b82f6;
            z-index: 3;
        }

        .custom-country-btn.open {
            background-color: #e2e8f0;
        }

        .custom-country-btn .flag-img {
            width: 21px;
            height: 15px;
            border-radius: 3px;
            object-fit: cover;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
            display: inline-block;
            flex-shrink: 0;
        }

        .custom-country-btn .dial-code {
            font-size: 0.875rem;
            font-weight: 600;
            color: #334155;
            letter-spacing: -0.01em;
        }

        .custom-country-btn .chevron-icon {
            width: 12px;
            height: 12px;
            color: #94a3b8;
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            margin-left: 2px;
        }

        .custom-country-btn.open .chevron-icon {
            transform: rotate(180deg);
            color: #3b82f6;
        }

        /* Floating Country Dropdown Popover */
        .custom-country-dropdown {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            z-index: 1070;
            width: 320px;
            max-width: calc(100vw - 32px);
            background: #ffffff;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 30px -10px rgba(15, 23, 42, 0.18), 0 10px 15px -3px rgba(15, 23, 42, 0.08);
            padding: 10px;
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
            margin-bottom: 8px;
        }

        .country-search-box input {
            width: 100%;
            padding: 8px 12px 8px 34px !important;
            font-size: 13px;
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.625rem !important;
            background-color: #f8fafc;
            outline: none;
            transition: all 0.15s ease;
        }

        .country-search-box input:focus {
            border-color: #3b82f6 !important;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .country-search-box .search-icon {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 13px;
            pointer-events: none;
        }

        .country-list-scroll {
            max-height: 240px;
            overflow-y: auto;
            padding-right: 4px;
            overscroll-behavior: contain;
        }

        .country-list-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .country-list-scroll::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 10px;
        }

        .country-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 10px;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.12s ease;
            font-size: 13px;
            color: #1e293b;
            text-decoration: none;
        }

        .country-item:hover {
            background-color: #f0f7ff;
            color: #1d4ed8;
        }

        .country-item.active {
            background-color: #eff6ff;
            color: #2563eb;
            font-weight: 600;
        }

        .country-item-left {
            display: flex;
            align-items: center;
            gap: 10px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .country-item-left .flag-img {
            width: 22px;
            height: 15px;
            border-radius: 3px;
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
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            background: #f1f5f9;
            padding: 2px 7px;
            border-radius: 6px;
            margin-left: 8px;
            flex-shrink: 0;
            font-family: monospace;
        }

        .country-item:hover .country-item-dial,
        .country-item.active .country-item-dial {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .country-empty-hint {
            padding: 16px;
            text-align: center;
            color: #94a3b8;
            font-size: 13px;
        }

        /* Seamless join between country button and phone input */
        .input-group > .custom-country-btn,
        .relative.flex > .custom-country-btn {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
            border-top-left-radius: 0.75rem !important;
            border-bottom-left-radius: 0.75rem !important;
        }
        .input-group > .phone-number-input,
        .relative.flex > .phone-number-input {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            border-top-right-radius: 0.75rem !important;
            border-bottom-right-radius: 0.75rem !important;
        }

        .dropdown-menu.show {
            display: block !important;
            opacity: 1 !important;
            pointer-events: auto !important;
        }

        /* Prevent table container from clipping dropdown menus */
        .table-responsive {
            overflow-x: auto;
            overflow-y: visible;
        }

        /* Ensure table action dropdowns open inwards to the left so they never overflow card bounds */
        .table td .dropdown-menu,
        .table-responsive .dropdown-menu {
            right: 0 !important;
            left: auto !important;
            min-width: 160px !important;
            white-space: nowrap !important;
            margin-top: 0.25rem !important;
        }

        .table td .dropdown,
        .table td div.relative {
            position: relative !important;
        }

        /* Global Print Rules for Clean Document Printing */
        @media print {
            aside,
            aside *,
            header,
            header *,
            nav,
            nav *,
            .no-print,
            .action-header,
            .action-menu-container,
            .btn,
            button,
            form {
                display: none !important;
            }

            body, html {
                background: #ffffff !important;
                color: #000000 !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                height: auto !important;
                overflow: visible !important;
            }

            body > div {
                display: block !important;
                height: auto !important;
                overflow: visible !important;
            }

            main, .flex-1 {
                display: block !important;
                overflow: visible !important;
                height: auto !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .printable-area {
                border: none !important;
                box-shadow: none !important;
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            @page {
                size: A4 portrait;
                margin: 12mm;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 text-slate-800 antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('layouts.sidebar')
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            @include('layouts.header')
            
            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-8">
                @if(session('success'))
                    <div class="mb-5 bg-emerald-50 border border-emerald-200/80 text-emerald-800 px-5 py-3.5 rounded-xl shadow-sm flex items-center justify-between">
                        <div class="flex items-center">
                            <x-icon name="check-circle" class="w-5 h-5 text-emerald-500 mr-3" />
                            <span class="font-medium text-sm">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-5 bg-rose-50 border border-rose-200/80 text-rose-800 px-5 py-3.5 rounded-xl shadow-sm flex items-center justify-between">
                        <div class="flex items-center">
                            <x-icon name="exclamation-circle" class="w-5 h-5 text-rose-500 mr-3" />
                            <span class="font-medium text-sm">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Global Scripts -->
    <script>
        // Toggle Sidebar on Mobile
        function toggleSidebar() {
            const sidebar = document.querySelector('aside');
            sidebar.classList.toggle('-translate-x-full');
        }
        
        // Universal Action Popup Menu Toggle (3-dots popup)
        function toggleActionMenu(button, event) {
            if (event) event.stopPropagation();
            const container = button.closest('.action-menu-container');
            const popup = container ? container.querySelector('.action-menu-popup') : (button.nextElementSibling || button.parentElement.querySelector('.action-menu-popup'));
            if (!popup) return;
            
            const isCurrentlyVisible = popup.style.display === 'flex';
            
            // Close all open action popups & reset row z-indices
            document.querySelectorAll('.action-menu-popup').forEach(el => {
                el.style.display = 'none';
                const row = el.closest('tr');
                if (row) {
                    row.style.position = '';
                    row.style.zIndex = '';
                }
            });
            
            if (!isCurrentlyVisible) {
                popup.style.display = 'flex';
                // Elevate z-index of current table row so dropdown floats ABOVE lower rows!
                const parentRow = button.closest('tr');
                if (parentRow) {
                    parentRow.style.position = 'relative';
                    parentRow.style.zIndex = '999';
                }
            }
        }

        // Close dropdowns & popups when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.action-menu-container')) {
                document.querySelectorAll('.action-menu-popup').forEach(el => {
                    el.style.display = 'none';
                    const row = el.closest('tr');
                    if (row) {
                        row.style.position = '';
                        row.style.zIndex = '';
                    }
                });
            }
            if (!e.target.closest('.dropdown-container') && !e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu.show').forEach(m => m.classList.remove('show'));
                document.querySelectorAll('.dropdown-menu.custom-dropdown').forEach(d => d.style.display = 'none');
            }
        });
        
        // Confirm Delete
        function confirmDelete(formId, message = 'Apakah Anda yakin ingin menghapus data ini?') {
            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        // Confirm Logout (All Roles)
        function confirmLogout(event) {
            if (event) event.preventDefault();
            
            // Close dropdown menu if currently open
            const profileDropdown = document.getElementById('profileDropdown');
            if (profileDropdown) {
                profileDropdown.classList.add('hidden');
            }

            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Apakah Anda yakin ingin keluar dari sistem? Pastikan semua perubahan atau transaksi Anda telah disimpan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: '<svg class="w-4 h-4 inline-block mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg> Ya, Logout',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup: 'rounded-2xl shadow-xl',
                    confirmButton: 'rounded-xl px-4 py-2.5 text-sm font-semibold',
                    cancelButton: 'rounded-xl px-4 py-2.5 text-sm font-semibold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Sedang keluar...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const form = document.getElementById('global-logout-form') || document.getElementById('logout-form');
                    if (form) {
                        form.submit();
                    } else {
                        const logoutForm = document.createElement('form');
                        logoutForm.method = 'POST';
                        logoutForm.action = '{{ route('logout') }}';
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        if (csrfToken) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = '_token';
                            input.value = csrfToken;
                            logoutForm.appendChild(input);
                        }
                        document.body.appendChild(logoutForm);
                        logoutForm.submit();
                    }
                }
            });
        }
        
        // Format Currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }
        
        // Format Number
        function formatNumber(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }
        
        // Animate Numbers
        function animateNumber(element, target, duration = 1000) {
            const start = 0;
            const startTime = performance.now();
            
            function update(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                // Easing function
                const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                const current = Math.floor(start + (target - start) * easeOutQuart);
                
                element.textContent = formatNumber(current);
                
                if (progress < 1) {
                    requestAnimationFrame(update);
                } else {
                    element.textContent = formatNumber(target);
                }
            }
            
            requestAnimationFrame(update);
        }

        // Comprehensive International Country List
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

        // Global Phone Number Input Handler with Modern Country Selector
        function initPhoneInputs() {
            document.querySelectorAll('.phone-number-input, [data-phone-input]').forEach(function(input) {
                if (input.dataset.phoneInitialized) return;
                input.dataset.phoneInitialized = 'true';

                const container = input.closest('.input-group, .relative') || input.parentElement;
                let countrySelect = container ? container.querySelector('.country-code-select') : null;

                // Hide native select if present
                if (countrySelect) {
                    countrySelect.classList.add('custom-country-select-hidden');
                }

                // Initial state
                let currentCountry = COUNTRY_LIST[0]; // Default Indonesia (+62)

                const form = input.closest('form');
                const hidden = form ? form.querySelector('input[type="hidden"][name="phone"]') : null;
                const initialVal = input.value || '';
                const fullPhone = (hidden && hidden.value) ? hidden.value : initialVal;

                // Auto-detect country from initial value if it starts with '+'
                if (fullPhone && fullPhone.startsWith('+')) {
                    const sortedCountries = [...COUNTRY_LIST].sort((a, b) => b.dial.length - a.dial.length);
                    for (const c of sortedCountries) {
                        if (fullPhone.startsWith(c.dial)) {
                            currentCountry = c;
                            input.value = fullPhone.substring(c.dial.length).replace(/\D/g, '');
                            break;
                        }
                    }
                }

                if (countrySelect) {
                    countrySelect.value = currentCountry.dial;
                }

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

                if (input.value) {
                    input.value = cleanDigits(input.value);
                }

                input.setAttribute('inputmode', 'numeric');
                input.setAttribute('pattern', '[0-9]*');

                // Build Custom Trigger Button if not already built
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

                // Build Custom Dropdown Popover if not already built
                let customDropdown = container.querySelector('.custom-country-dropdown');
                if (!customDropdown) {
                    customDropdown = document.createElement('div');
                    customDropdown.className = 'custom-country-dropdown';

                    // Search box
                    const searchBox = document.createElement('div');
                    searchBox.className = 'country-search-box';
                    searchBox.innerHTML = `
                        <svg class="search-icon w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <input type="text" placeholder="Cari negara atau kode (+60, MY)..." class="country-search-input" autocomplete="off">
                    `;
                    customDropdown.appendChild(searchBox);

                    // Scrollable country list
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

                            // Update button
                            customBtn.querySelector('.flag-img').src = `https://flagcdn.com/w40/${country.code}.png`;
                            customBtn.querySelector('.flag-img').alt = country.code.toUpperCase();
                            customBtn.querySelector('.dial-code').textContent = country.dial;

                            // Update active class in list
                            listScroll.querySelectorAll('.country-item').forEach(el => el.classList.remove('active'));
                            item.classList.add('active');

                            // Update hidden select if present
                            if (countrySelect) {
                                countrySelect.value = country.dial;
                                countrySelect.dispatchEvent(new Event('change'));
                            }

                            // Sync hidden input
                            syncHidden();

                            // Close dropdown
                            customDropdown.classList.remove('show');
                            customBtn.classList.remove('open');

                            // Focus input field
                            input.focus();
                        });

                        listScroll.appendChild(item);
                    });

                    customDropdown.appendChild(listScroll);

                    // Empty search result hint
                    const emptyHint = document.createElement('div');
                    emptyHint.className = 'country-empty-hint';
                    emptyHint.textContent = 'Negara tidak ditemukan';
                    emptyHint.style.display = 'none';
                    customDropdown.appendChild(emptyHint);

                    // Search input live filtering
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
                        // Close any other open dropdowns first
                        document.querySelectorAll('.custom-country-dropdown.show').forEach(d => {
                            if (d !== customDropdown) {
                                d.classList.remove('show');
                                const otherBtn = d.parentElement.querySelector('.custom-country-btn');
                                if (otherBtn) otherBtn.classList.remove('open');
                            }
                        });

                        const isOpen = customDropdown.classList.toggle('show');
                        customBtn.classList.toggle('open', isOpen);

                        if (isOpen) {
                            searchInput.value = '';
                            searchInput.dispatchEvent(new Event('input'));
                            setTimeout(() => searchInput.focus(), 50);
                        }
                    });
                }

                // Block non-numeric keystrokes
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

        // Global Close on Click Outside or ESC
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.custom-country-btn') && !e.target.closest('.custom-country-dropdown')) {
                document.querySelectorAll('.custom-country-dropdown.show').forEach(d => {
                    d.classList.remove('show');
                    const btn = d.parentElement?.querySelector('.custom-country-btn');
                    if (btn) btn.classList.remove('open');
                });
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.custom-country-dropdown.show').forEach(d => {
                    d.classList.remove('show');
                    const btn = d.parentElement?.querySelector('.custom-country-btn');
                    if (btn) btn.classList.remove('open');
                });
            }
        });

        // Global Protection: Quantity & Numeric Inputs Cannot Be Minus / Negative
        function isQuantityOrNonNegativeInput(el) {
            if (!el || el.tagName !== 'INPUT') return false;
            if (el.type !== 'number' && !el.classList.contains('qty-input') && !el.classList.contains('item-qty') && !el.classList.contains('physical-qty')) {
                return false;
            }
            const name = (el.name || '').toLowerCase();
            const id = (el.id || '').toLowerCase();
            const cls = (el.className || '').toLowerCase();
            const minAttr = el.getAttribute('min');

            if (minAttr !== null && parseFloat(minAttr) >= 0) return true;
            if (name.includes('qty') || name.includes('quantity') || name.includes('stock') || name.includes('item') || name.includes('count') || name.includes('price')) return true;
            if (id.includes('qty') || id.includes('quantity') || id.includes('stock') || id.includes('item') || id.includes('count') || id.includes('price')) return true;
            if (cls.includes('qty') || cls.includes('quantity') || cls.includes('physical-input')) return true;
            return false;
        }

        // Block minus sign '-' and scientific 'e' / 'E'
        document.addEventListener('keydown', function(e) {
            if (isQuantityOrNonNegativeInput(e.target)) {
                if (e.key === '-' || e.key === 'Subtract' || e.key === 'e' || e.key === 'E') {
                    e.preventDefault();
                }
            }
        }, true);

        // Sanitize on input
        document.addEventListener('input', function(e) {
            if (isQuantityOrNonNegativeInput(e.target)) {
                const input = e.target;
                const minAttr = input.getAttribute('min');
                const minVal = minAttr !== null ? parseFloat(minAttr) : 0;
                
                if (input.value.includes('-')) {
                    input.value = input.value.replace(/-/g, '');
                }
                if (input.value !== '' && parseFloat(input.value) < 0) {
                    input.value = Math.max(0, minVal);
                }
            }
        }, true);

        // Enforce min value on change / blur
        document.addEventListener('change', function(e) {
            if (isQuantityOrNonNegativeInput(e.target)) {
                const input = e.target;
                const minAttr = input.getAttribute('min');
                const minVal = minAttr !== null ? parseFloat(minAttr) : 0;
                
                if (input.value === '' || isNaN(input.value)) {
                    input.value = minVal;
                } else if (parseFloat(input.value) < minVal) {
                    input.value = minVal;
                }
            }
        }, true);

        // Strip minus on paste
        document.addEventListener('paste', function(e) {
            if (isQuantityOrNonNegativeInput(e.target)) {
                const input = e.target;
                setTimeout(() => {
                    const minAttr = input.getAttribute('min');
                    const minVal = minAttr !== null ? parseFloat(minAttr) : 0;
                    let val = input.value.replace(/-/g, '');
                    if (val !== '' && parseFloat(val) < minVal) val = minVal;
                    input.value = val;
                }, 0);
            }
        }, true);

        document.addEventListener('DOMContentLoaded', initPhoneInputs);
        document.addEventListener('shown.bs.modal', initPhoneInputs);
        window.initPhoneInputs = initPhoneInputs;

        // Auto-handle forms on submit to ensure prefix is attached if name="phone" directly on input
        document.addEventListener('submit', function(e) {
            const form = e.target;
            const phoneInputs = form.querySelectorAll('.phone-number-input, [data-phone-input]');
            phoneInputs.forEach(input => {
                const container = input.closest('.input-group, .relative') || input.parentElement;
                const customBtn = container ? container.querySelector('.custom-country-btn .dial-code') : null;
                const dialCode = customBtn ? customBtn.textContent.trim() : '+62';
                const hidden = form.querySelector('input[type="hidden"][name="phone"]');
                if (hidden) {
                    hidden.value = input.value ? dialCode + input.value : '';
                } else if (input.name === 'phone' && input.value && !input.value.startsWith('+')) {
                    input.value = dialCode + input.value;
                }
            });
        });
    </script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
