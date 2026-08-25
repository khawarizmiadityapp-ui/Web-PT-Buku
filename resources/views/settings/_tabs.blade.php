{{-- Settings Tab Navigation --}}
<div class="bg-white rounded-t-xl border border-gray-200 border-b-0">
    <nav class="flex space-x-8 px-6" aria-label="Tabs">
        <a href="{{ route('settings.profile.view') }}" class="tab-btn py-4 px-1 border-b-2 {{ request()->routeIs('settings', 'settings.profile.view') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }} font-medium text-sm flex items-center space-x-2">
            <i class="fas fa-user"></i>
            <span>Profil</span>
        </a>
        @if(auth()->user()?->isAdmin())
        <a href="{{ route('settings.system') }}" class="tab-btn py-4 px-1 border-b-2 {{ request()->routeIs('settings.system') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }} font-medium text-sm flex items-center space-x-2">
            <i class="fas fa-building"></i>
            <span>Sistem</span>
        </a>
        @endif

        @if(auth()->user()?->hasRole('System Admin', 'Manager'))
        <a href="{{ route('settings.audit') }}" class="tab-btn py-4 px-1 border-b-2 {{ request()->routeIs('settings.audit*') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }} font-medium text-sm flex items-center space-x-2">
            <i class="fas fa-clipboard-list"></i>
            <span>Audit Log</span>
        </a>
        @endif
    </nav>
</div>
