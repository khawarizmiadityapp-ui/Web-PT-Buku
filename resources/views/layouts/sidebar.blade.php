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
                                <x-icon :name="$menu->icon ?? 'circle'" class="w-5 h-5" />
                                <span class="font-medium">{{ $menu->name }}</span>
                            </div>
                            <x-icon name="chevron-down" class="w-3.5 h-3.5" />
                        </button>
                        <div id="menu-{{ $menu->id }}" class="ml-8 mt-1 space-y-1 {{ $isActiveDropdown ? '' : 'hidden' }}">
                            @foreach($menu->children as $child)
                                <a href="{{ $child->route ? route($child->route) : '#' }}" class="{{ $child->route && request()->routeIs($child->route . '*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                                    <x-icon :name="$child->icon ?? 'circle'" class="w-4 h-4" />
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
                        <x-icon :name="$menu->icon ?? 'circle'" class="w-5 h-5" />
                        <span class="font-medium">{{ $menu->name }}</span>
                    </a>
                @endif
            @endforeach
        @else
            {{-- FALLBACK MENUS --}}
            @if(Auth::user()->role === 'Cashier')
                {{-- CASHIER MENU --}}
                <a href="{{ route('cashier.index') }}" class="{{ request()->routeIs('cashier.index') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <x-icon name="th-large" class="w-5 h-5" />
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('cashier.transaction.enhanced') }}" class="{{ request()->routeIs('cashier.transaction*') && !request()->routeIs('cashier.transaction.enhanced') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <x-icon name="cash-register" class="w-5 h-5" />
                    <span class="font-medium">Transaction</span>
                </a>

                <a href="{{ route('returns.index') }}" class="{{ request()->routeIs('returns.*') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <x-icon name="undo" class="w-5 h-5" />
                    <span class="font-medium">Returns</span>
                </a>

                <a href="{{ route('cashier.history') }}" class="{{ request()->routeIs('cashier.history') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <x-icon name="history" class="w-5 h-5" />
                    <span class="font-medium">History</span>
                </a>

                <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <x-icon name="users" class="w-5 h-5" />
                    <span class="font-medium">Customer</span>
                </a>
            @elseif(Auth::user()->role === 'Warehouse Manager')
                {{-- WAREHOUSE MENU --}}
                <a href="{{ route('warehouse.index') }}" class="{{ request()->routeIs('warehouse.index') || request()->routeIs('dashboard') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <x-icon name="th-large" class="w-5 h-5" />
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('warehouse.incoming-goods') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <x-icon name="box-open" class="w-5 h-5" />
                    <span class="font-medium">Barang Masuk</span>
                </a>

                <a href="{{ route('warehouse.stock') }}" class="{{ request()->routeIs('warehouse.stock') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <x-icon name="dolly" class="w-5 h-5" />
                    <span class="font-medium">Stok Gudang</span>
                </a>

                <a href="{{ route('warehouse.picking') }}" class="{{ request()->routeIs('warehouse.picking') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <x-icon name="box" class="w-5 h-5" />
                    <span class="font-medium">Picking</span>
                </a>

                <a href="{{ route('warehouse.packing') }}" class="{{ request()->routeIs('warehouse.packing') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <x-icon name="truck-loading" class="w-5 h-5" />
                    <span class="font-medium">Packing</span>
                </a>

                <a href="{{ route('stock-outs.index') }}" class="{{ request()->routeIs('stock-outs.*') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <x-icon name="dolly-flatbed" class="w-5 h-5" />
                    <span class="font-medium">Barang Keluar</span>
                </a>



                <a href="{{ route('warehouse.verifikasi.index') }}" class="{{ request()->routeIs('warehouse.verifikasi.*') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <x-icon name="exchange-alt" class="w-5 h-5" />
                    <span class="font-medium">Verifikasi Masuk</span>
                </a>

                <a href="{{ route('warehouse.returns.index') }}" class="{{ request()->routeIs('warehouse.returns.*') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <x-icon name="undo-alt" class="w-5 h-5" />
                    <span class="font-medium">Riwayat Aktivitas</span>
                </a>
            @else
                {{-- ADMIN & OTHER ROLES MENU --}}
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <x-icon name="th-large" class="w-5 h-5" />
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <!-- Dropdown Master Data -->
                <div>
                    <button onclick="toggleDropdown('masterData')" class="w-full flex items-center justify-between space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <x-icon name="database" class="w-5 h-5" />
                            <span class="font-medium">Master Data</span>
                        </div>
                        <x-icon name="chevron-down" class="w-3.5 h-3.5" />
                    </button>
                    <div id="masterData" class="ml-8 mt-1 space-y-1 hidden">
                        <a href="{{ route('suppliers.index') }}" class="{{ request()->routeIs('suppliers.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                            <x-icon name="truck-loading" class="w-4 h-4" />
                            <span>Supplier</span>
                        </a>
                        <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                            <x-icon name="box" class="w-4 h-4" />
                            <span>Produk</span>
                        </a>
                        <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                            <x-icon name="users" class="w-4 h-4" />
                            <span>Customer</span>
                        </a>
                    </div>
                </div>

                <!-- Warehouse Dropdown -->
                <div>
                    <button onclick="toggleDropdown('warehouse')" class="w-full flex items-center justify-between space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('warehouse.*') ? 'sidebar-active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <div class="flex items-center space-x-3">
                            <x-icon name="warehouse" class="w-5 h-5" />
                            <span class="font-medium">Warehouse</span>
                        </div>
                        <x-icon name="chevron-down" class="w-3.5 h-3.5" />
                    </button>
                    <div id="warehouse" class="ml-8 mt-1 space-y-1 {{ request()->routeIs('warehouse.*') ? '' : 'hidden' }}">
                        <a href="{{ route('warehouse.stock-audit.index') }}" class="{{ request()->routeIs('warehouse.stock-audit.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                            <x-icon name="clipboard-check" class="w-4 h-4" />
                            <span>Stock Count & Audit</span>
                        </a>
                        <a href="{{ route('warehouse.returns.index') }}" class="{{ request()->routeIs('warehouse.returns.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                            <x-icon name="undo" class="w-4 h-4" />
                            <span>Retur Barang</span>
                        </a>
                        <a href="#" class="text-gray-600 flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                            <x-icon name="dolly" class="w-4 h-4" />
                            <span>Barang Masuk</span>
                        </a>
                    </div>
                </div>
                
                <!-- Sales Dropdown -->
                <div>
                    <button onclick="toggleDropdown('sales')" class="w-full flex items-center justify-between space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('sales.*') ? 'sidebar-active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <div class="flex items-center space-x-3">
                            <x-icon name="shopping-cart" class="w-5 h-5" />
                            <span class="font-medium">Sales</span>
                        </div>
                        <x-icon name="chevron-down" class="w-3.5 h-3.5" />
                    </button>
                    <div id="sales" class="ml-8 mt-1 space-y-1 {{ request()->routeIs('sales.*') ? '' : 'hidden' }}">
                        <a href="{{ route('sales.invoices.index') }}" class="{{ request()->routeIs('sales.invoices.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                            <x-icon name="file-invoice" class="w-4 h-4" />
                            <span>Invoices</span>
                        </a>
                        <a href="{{ route('sales.report') }}" class="{{ request()->routeIs('sales.report') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                            <x-icon name="chart-pie" class="w-4 h-4" />
                            <span>Performance Report</span>
                        </a>
                    </div>
                </div>
                
                <!-- Purchase Orders -->
                <a href="{{ route('purchases.index') }}" class="{{ request()->routeIs('purchases.*') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <x-icon name="shopping-bag" class="w-5 h-5" />
                    <span class="font-medium">Purchase Order</span>
                </a>
                
                <!-- Stock Out -->
                <a href="{{ route('stock-outs.index') }}" class="{{ request()->routeIs('stock-outs.*') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                    <x-icon name="dolly" class="w-5 h-5" />
                    <span class="font-medium">Barang Keluar</span>
                </a>
                
                <!-- Reports Dropdown -->
                <div>
                    <button onclick="toggleDropdown('reports')" class="w-full flex items-center justify-between space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('reports.*') ? 'sidebar-active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <div class="flex items-center space-x-3">
                            <x-icon name="chart-bar" class="w-5 h-5" />
                            <span class="font-medium">Reports</span>
                        </div>
                        <x-icon name="chevron-down" class="w-3.5 h-3.5" />
                    </button>
                    <div id="reports" class="ml-8 mt-1 space-y-1 {{ request()->routeIs('reports.*') ? '' : 'hidden' }}">
                        <a href="{{ route('reports.financial') }}" class="{{ request()->routeIs('reports.financial') ? 'text-blue-600 font-semibold' : 'text-gray-600' }} flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                            <x-icon name="coins" class="w-4 h-4" />
                            <span>Financial Report</span>
                        </a>
                        <a href="{{ route('reports.analytics') }}" class="{{ request()->routeIs('reports.analytics') ? 'text-indigo-600 font-semibold' : 'text-gray-600' }} flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                            <x-icon name="brain" class="w-4 h-4 text-indigo-500" />
                            <span>Data Science & AI</span>
                        </a>
                    </div>
                </div>
                
                <a href="{{ route('settings') }}" class="{{ request()->routeIs('settings*') ? 'sidebar-active' : '' }} flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50">
                        <x-icon name="cog" class="w-5 h-5" />
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
