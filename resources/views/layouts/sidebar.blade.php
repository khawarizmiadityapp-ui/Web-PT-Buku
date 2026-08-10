<aside class="w-64 bg-white border-r border-gray-200 flex-shrink-0 flex flex-col h-full overflow-hidden">
    <!-- Logo -->
    <div class="p-6 border-b border-gray-200 flex justify-center flex-shrink-0">
        <img src="{{ asset('images/logo PT buku.png') }}" alt="PT Buku & ATK Nusantara" class="max-h-16 w-auto object-contain">
    </div>

    <!-- Navigation -->
    <nav class="p-4 space-y-1 flex-1 overflow-y-auto custom-scrollbar">
        @if(isset($sidebarMenus) && $sidebarMenus->count() > 0)
            @foreach($sidebarMenus as $menu)
                @if($menu->children->count() > 0)
                    <!-- Dropdown Menu -->
                    <div>
                        @php
                            // Check if any child route is active
                            $isActiveDropdown = false;
                            foreach($menu->children as $child) {
                                if($child->route && request()->routeIs($child->route . '*')) {
                                    $isActiveDropdown = true;
                                    break;
                                }
                            }
                        @endphp
                        <button onclick="toggleDropdown('menu-{{ $menu->id }}')" class="w-full flex items-center justify-between space-x-3 px-4 py-3 rounded-lg {{ $isActiveDropdown ? 'sidebar-active' : 'text-gray-600 hover:bg-gray-50' }}">
                            <div class="flex items-center space-x-3">
                                <i class="{{ $menu->icon ?? 'fas fa-circle' }} w-5"></i>
                                <span class="font-medium">{{ $menu->name }}</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div id="menu-{{ $menu->id }}" class="ml-8 mt-1 space-y-1 {{ $isActiveDropdown ? '' : 'hidden' }}">
                            @foreach($menu->children as $child)
                                <a href="{{ $child->route ? route($child->route) : '#' }}" class="{{ $child->route && request()->routeIs($child->route . '*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                                    <i class="{{ $child->icon ?? 'fas fa-circle' }} w-4"></i>
                                    <span>{{ $child->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    @php
                        $isCurrentRoute = false;
                        if ($menu->route) {
                            $isCurrentRoute = request()->routeIs($menu->route) || request()->routeIs($menu->route . '.*');
                            if ($menu->route === 'warehouse.index' && (request()->routeIs('warehouse.index') || request()->routeIs('dashboard'))) {
                                $isCurrentRoute = true;
                            }
                            if ($menu->route === 'dashboard' && (request()->routeIs('dashboard') || request()->routeIs('warehouse.index'))) {
                                $isCurrentRoute = true;
                            }
                        }
                    @endphp
                    <!-- Single Menu -->
                    <a href="{{ $menu->route ? route($menu->route) : '#' }}" class="{{ $isCurrentRoute ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                        <i class="{{ $menu->icon ?? 'fas fa-circle' }} w-5"></i>
                        <span class="font-medium">{{ $menu->name }}</span>
                    </a>
                @endif
            @endforeach
        @else
            {{-- FALLBACK MENUS --}}
            @if(Auth::user()->role === 'Cashier')
                {{-- CASHIER MENU --}}
                <a href="{{ route('cashier.index') }}" class="{{ request()->routeIs('cashier.index') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-th-large w-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('cashier.transaction.enhanced') }}" class="{{ request()->routeIs('cashier.transaction*') && !request()->routeIs('cashier.transaction.enhanced') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-cash-register w-5"></i>
                    <span class="font-medium">Transaction</span>
                </a>

                <a href="{{ route('returns.index') }}" class="{{ request()->routeIs('returns.*') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-undo w-5"></i>
                    <span class="font-medium">Returns</span>
                </a>

                <a href="{{ route('cashier.history') }}" class="{{ request()->routeIs('cashier.history') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-history w-5"></i>
                    <span class="font-medium">History</span>
                </a>

                <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-users w-5"></i>
                    <span class="font-medium">Customer</span>
                </a>
            @elseif(Auth::user()->role === 'Warehouse Manager')
                {{-- WAREHOUSE MENU --}}
                <a href="{{ route('warehouse.index') }}" class="{{ request()->routeIs('warehouse.index') || request()->routeIs('dashboard') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-th-large w-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('warehouse.incoming-goods') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-box-open w-5"></i>
                    <span class="font-medium">Barang Masuk</span>
                </a>

                <a href="{{ route('warehouse.stock') }}" class="{{ request()->routeIs('warehouse.stock') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-dolly w-5"></i>
                    <span class="font-medium">Stok Gudang</span>
                </a>

                <a href="{{ route('warehouse.picking') }}" class="{{ request()->routeIs('warehouse.picking') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-box w-5"></i>
                    <span class="font-medium">Picking</span>
                </a>

                <a href="{{ route('warehouse.packing') }}" class="{{ request()->routeIs('warehouse.packing') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-truck-loading w-5"></i>
                    <span class="font-medium">Packing</span>
                </a>

                <a href="{{ route('stock-outs.index') }}" class="{{ request()->routeIs('stock-outs.*') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-dolly-flatbed w-5"></i>
                    <span class="font-medium">Barang Keluar</span>
                </a>



                <a href="{{ route('warehouse.verifikasi.index') }}" class="{{ request()->routeIs('warehouse.verifikasi.*') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-exchange-alt w-5"></i>
                    <span class="font-medium">Verifikasi Masuk</span>
                </a>

                <a href="{{ route('warehouse.returns.index') }}" class="{{ request()->routeIs('warehouse.returns.*') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-undo-alt w-5"></i>
                    <span class="font-medium">Riwayat Aktivitas</span>
                </a>
            @else
                {{-- ADMIN & OTHER ROLES MENU --}}
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-th-large w-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <!-- Dropdown Master Data -->
                <div>
                    <button onclick="toggleDropdown('masterData')" class="w-full flex items-center justify-between space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-database w-5"></i>
                            <span class="font-medium">Master Data</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div id="masterData" class="ml-8 mt-1 space-y-1 hidden">
                        <a href="{{ route('suppliers.index') }}" class="{{ request()->routeIs('suppliers.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                            <i class="fas fa-truck-loading w-4"></i>
                            <span>Supplier</span>
                        </a>
                        <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                            <i class="fas fa-box w-4"></i>
                            <span>Produk</span>
                        </a>
                        <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                            <i class="fas fa-users w-4"></i>
                            <span>Customer</span>
                        </a>
                    </div>
                </div>

                <!-- Warehouse Dropdown -->
                <div>
                    <button onclick="toggleDropdown('warehouse')" class="w-full flex items-center justify-between space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('warehouse.*') ? 'sidebar-active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-warehouse w-5"></i>
                            <span class="font-medium">Warehouse</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div id="warehouse" class="ml-8 mt-1 space-y-1 {{ request()->routeIs('warehouse.*') ? '' : 'hidden' }}">
                        <a href="{{ route('warehouse.stock-audit.index') }}" class="{{ request()->routeIs('warehouse.stock-audit.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                            <i class="fas fa-clipboard-check w-4"></i>
                            <span>Stock Count & Audit</span>
                        </a>
                        <a href="{{ route('warehouse.returns.index') }}" class="{{ request()->routeIs('warehouse.returns.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                            <i class="fas fa-undo w-4"></i>
                            <span>Retur Barang</span>
                        </a>
                        <a href="#" class="text-gray-600 flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                            <i class="fas fa-dolly w-4"></i>
                            <span>Barang Masuk</span>
                        </a>
                    </div>
                </div>
                
                <!-- Sales Dropdown -->
                <div>
                    <button onclick="toggleDropdown('sales')" class="w-full flex items-center justify-between space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('sales.*') ? 'sidebar-active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-shopping-cart w-5"></i>
                            <span class="font-medium">Sales</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div id="sales" class="ml-8 mt-1 space-y-1 {{ request()->routeIs('sales.*') ? '' : 'hidden' }}">
                        <a href="{{ route('sales.invoices.index') }}" class="{{ request()->routeIs('sales.invoices.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                            <i class="fas fa-file-invoice w-4"></i>
                            <span>Invoices</span>
                        </a>
                        <a href="{{ route('sales.report') }}" class="{{ request()->routeIs('sales.report') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                            <i class="fas fa-chart-pie w-4"></i>
                            <span>Performance Report</span>
                        </a>
                    </div>
                </div>
                
                <!-- Purchase Orders -->
                <a href="{{ route('purchases.index') }}" class="{{ request()->routeIs('purchases.*') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-shopping-bag w-5"></i>
                    <span class="font-medium">Purchase Order</span>
                </a>
                
                <!-- Stock Out -->
                <a href="{{ route('stock-outs.index') }}" class="{{ request()->routeIs('stock-outs.*') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-dolly w-5"></i>
                    <span class="font-medium">Barang Keluar</span>
                </a>
                
                <!-- Reports -->
                <a href="{{ route('reports.financial') }}" class="{{ request()->routeIs('reports.*') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span class="font-medium">Reports</span>
                </a>
                
                <a href="{{ route('settings') }}" class="{{ request()->routeIs('settings*') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-cog w-5"></i>
                        <span class="font-medium">Settings</span>
                    </a>
            @endif
        @endif
    </nav>

    <script>
        function toggleDropdown(id) {
            const element = document.getElementById(id);
            element.classList.toggle('hidden');
        }
    </script>
</aside>
