<header class="bg-white border-b border-gray-200 px-8 py-4">
    <div class="flex items-center justify-between">
        <!-- Large Search Bar -->
        <div class="flex-1 max-w-2xl">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <x-icon name="search" class="w-4 h-4 text-gray-400" />
                </span>
                <input type="text" 
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm" 
                       placeholder="Cari transaksi, produk, SKU, customer, atau laporan...">
            </div>
        </div>
        
        <!-- Right Side Icons -->
        <div class="flex items-center space-x-3">
            <!-- Real-time Clock Widget -->
            <div class="hidden sm:flex items-center space-x-2 px-3 py-2 bg-gray-50 rounded-lg border border-gray-100">
                <x-icon name="clock" class="text-gray-500 w-4 h-4" />
                <span id="currentTime" class="text-sm font-medium text-gray-700"></span>
            </div>

            <!-- Notification Bell -->
            <div class="relative dropdown-container">
                <button onclick="toggleGlobalDropdown('notificationDropdown')" class="p-2.5 hover:bg-gray-100 rounded-lg relative transition" title="Notifications">
                    <x-icon name="bell" class="w-4 h-4 text-gray-600" />
                    @if(isset($globalNotifications) && $globalNotifications->count() > 0)
                        <span id="notifBadge" class="absolute top-1 right-1 min-w-[16px] h-[16px] px-1 bg-red-500 text-white text-[9px] font-bold flex items-center justify-center rounded-full border border-white">
                            {{ $globalNotifications->count() }}
                        </span>
                    @endif
                </button>
                <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-200 z-50">
                    <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-xl">
                        <h3 class="text-sm font-semibold text-gray-800">Notifikasi Sistem</h3>
                        @if(isset($globalNotifications) && $globalNotifications->count() > 0)
                            <button onclick="clearNotifBadge()" class="text-xs font-medium text-blue-600 hover:text-blue-800">Tandai dibaca</button>
                        @endif
                    </div>
                    <div class="max-h-80 overflow-y-auto">
                        @if(isset($globalNotifications) && $globalNotifications->count() > 0)
                            @foreach($globalNotifications as $notif)
                                <a href="{{ $notif->link ?? '#' }}" class="flex px-4 py-3 hover:bg-gray-50 border-b border-gray-100 transition">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <div class="w-8 h-8 rounded-full {{ $notif->bg_color ?? 'bg-blue-100 text-blue-600' }} flex items-center justify-center">
                                            <x-icon :name="$notif->icon ?? 'bell'" class="w-3.5 h-3.5" />
                                        </div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-xs font-bold text-gray-900">{{ $notif->title }}</p>
                                        <p class="text-xs text-gray-600 mt-0.5">{{ $notif->message }}</p>
                                        <p class="text-[10px] text-gray-400 mt-1"><x-icon name="clock" class="w-4 h-4 me-1" />{{ $notif->time }}</p>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <div class="px-4 py-8 text-center text-gray-500">
                                <x-icon name="check-circle" class="w-8 h-8 text-green-400 mb-2" />
                                <p class="text-xs font-medium">Tidak ada notifikasi baru</p>
                            </div>
                        @endif
                    </div>
                    <div class="px-4 py-2 border-t border-gray-100 text-center rounded-b-xl hover:bg-gray-50">
                        <a href="{{ route('dashboard') }}" class="text-xs font-medium text-blue-600">Lihat Dashboard Overview</a>
                    </div>
                </div>
            </div>

            <!-- Quick Apps Dropdown -->
            <div class="relative dropdown-container">
                <button onclick="toggleGlobalDropdown('appsDropdown')" class="p-2.5 hover:bg-gray-100 rounded-lg transition" title="Quick Apps">
                    <x-icon name="th" class="w-4 h-4 text-gray-600" />
                </button>
                <div id="appsDropdown" class="hidden absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-lg border border-gray-200 p-4 z-50">
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 px-1">Quick Apps</h4>
                    <div class="grid grid-cols-3 gap-3 text-center">
                        <a href="{{ route('cashier.index') }}" class="flex flex-col items-center group p-2 hover:bg-gray-50 rounded-lg transition">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition shadow-sm">
                                <x-icon name="cash-register" class="w-5 h-5" />
                            </div>
                            <span class="text-xs mt-2 font-medium text-gray-600 group-hover:text-gray-900">POS</span>
                        </a>
                        <a href="{{ route('dashboard') }}" class="flex flex-col items-center group p-2 hover:bg-gray-50 rounded-lg transition">
                            <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition shadow-sm">
                                <x-icon name="warehouse" class="w-5 h-5" />
                            </div>
                            <span class="text-xs mt-2 font-medium text-gray-600 group-hover:text-gray-900">WMS</span>
                        </a>
                        <a href="{{ route('reports.financial') }}" class="flex flex-col items-center group p-2 hover:bg-gray-50 rounded-lg transition">
                            <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition shadow-sm">
                                <x-icon name="chart-line" class="w-5 h-5" />
                            </div>
                            <span class="text-xs mt-2 font-medium text-gray-600 group-hover:text-gray-900">Reports</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Avatar & User Info Dropdown -->
            <div class="relative dropdown-container flex items-center space-x-2 pl-2 border-l border-gray-200">
                <div class="text-right hidden md:block">
                    <div class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500">{{ Auth::user()->role ?? 'Staff' }}</div>
                </div>
                <button onclick="toggleProfileDropdown()" class="flex items-center space-x-1 p-1 rounded-full hover:bg-gray-100 transition">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4F46E5&color=fff" 
                         alt="Avatar" class="w-9 h-9 rounded-full border-2 border-indigo-500">
                    <x-icon name="chevron-down" class="w-3.5 h-3.5 text-gray-500 ml-1" />
                </button>
                
                <!-- Profile Dropdown Menu -->
                <div id="profileDropdown" class="hidden absolute right-0 top-14 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        <span class="inline-block mt-1 px-2 py-0.5 text-xs font-medium bg-indigo-50 text-indigo-700 rounded">
                            {{ Auth::user()->role ?? 'Staff' }}
                        </span>
                    </div>
                    
                    <div class="py-1">
                        <a href="{{ route('settings') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <x-icon name="user-circle" class="w-5 h-5 mr-3 text-gray-400" />
                            <span>Profile & Settings</span>
                        </a>
                    </div>
                    
                    <div class="border-t border-gray-100 mt-1 pt-1">
                        <form id="global-logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="button" onclick="confirmLogout(event)" class="flex items-center w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                <x-icon name="sign-out-alt" class="w-5 h-5 mr-3" />
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
// Global Dropdown Manager
function toggleGlobalDropdown(id) {
    const dropdown = document.getElementById(id);
    const allDropdowns = ['profileDropdown', 'notificationDropdown', 'appsDropdown'];
    
    // Close others
    allDropdowns.forEach(dropdownId => {
        if (dropdownId !== id) {
            const d = document.getElementById(dropdownId);
            if (d) d.classList.add('hidden');
        }
    });
    
    // Toggle requested
    if (dropdown) {
        dropdown.classList.toggle('hidden');
    }
}

// Kept for backward compatibility
function toggleProfileDropdown() {
    toggleGlobalDropdown('profileDropdown');
}

function clearNotifBadge() {
    const badge = document.getElementById('notifBadge');
    if (badge) badge.style.display = 'none';
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const allDropdowns = ['profileDropdown', 'notificationDropdown', 'appsDropdown'];
    const clickedInsideDropdown = event.target.closest('.dropdown-container');
    
    if (!clickedInsideDropdown) {
        allDropdowns.forEach(dropdownId => {
            const dropdown = document.getElementById(dropdownId);
            if (dropdown) {
                dropdown.classList.add('hidden');
            }
        });
    }
});

// Real-time Clock Script
function updateClock() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    
    const timeElement = document.getElementById('currentTime');
    if (timeElement) {
        timeElement.textContent = `${hours}:${minutes}:${seconds}`;
    }
}

setInterval(updateClock, 1000);
updateClock();
</script>
