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
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
            header,
            nav,
            .no-print,
            .action-header,
            .action-menu-container,
            .btn,
            button {
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

            .flex, .flex-1, main, .overflow-hidden, .overflow-y-auto {
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
                            <i class="fas fa-check-circle text-emerald-500 mr-3 text-lg"></i>
                            <span class="font-medium text-sm">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-5 bg-rose-50 border border-rose-200/80 text-rose-800 px-5 py-3.5 rounded-xl shadow-sm flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-rose-500 mr-3 text-lg"></i>
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
    </script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
