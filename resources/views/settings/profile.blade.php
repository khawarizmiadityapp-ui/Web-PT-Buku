@extends('layouts.app')

@section('title', 'Settings - Profile')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">User Profile</h1>
        <p class="text-gray-500 mt-1">Manage your account information and preferences</p>
    </div>

    @include('settings._tabs')

    <div class="bg-white rounded-b-xl border border-gray-200 border-t-0 p-6">
        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Left Sub-menu --}}
            <div class="lg:w-56 flex-shrink-0">
                <nav class="space-y-1">
                    <button onclick="showProfileTab('personal')" id="sub-btn-personal" class="sub-tab-btn w-full text-left px-4 py-2.5 rounded-lg text-sm font-medium bg-blue-50 text-blue-700">
                        <i class="fas fa-id-card mr-2"></i>Personal Info
                    </button>
                    <button onclick="showProfileTab('security')" id="sub-btn-security" class="sub-tab-btn w-full text-left px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-shield-alt mr-2"></i>Account Security
                    </button>
                    <button onclick="showProfileTab('notifications')" id="sub-btn-notifications" class="sub-tab-btn w-full text-left px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-bell mr-2"></i>Notifications
                    </button>
                </nav>

                {{-- Profile Strength --}}
                <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Profile Strength</p>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-2xl font-bold text-gray-900">{{ $user->profile_strength }}%</span>
                        <span class="text-xs text-gray-500">{{ $user->profile_strength >= 80 ? 'Strong' : ($user->profile_strength >= 50 ? 'Good' : 'Weak') }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $user->profile_strength }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Lengkapi profil untuk keamanan akun yang lebih baik</p>
                </div>
            </div>

            {{-- Right Content --}}
            <div class="flex-1 min-w-0">
                {{-- Personal Info --}}
                <div id="profile-tab-personal" class="profile-tab-content">
                    <h2 class="text-lg font-semibold text-gray-900 mb-1">Personal Info</h2>
                    <p class="text-sm text-gray-500 mb-6">Update your personal information</p>

                    <form action="{{ route('settings.profile') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Avatar --}}
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="relative">
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4F46E5&color=fff&size=80" alt="Avatar" class="w-20 h-20 rounded-full border-2 border-gray-200">
                                @endif
                                <label for="avatar" class="absolute bottom-0 right-0 bg-blue-600 text-white p-1.5 rounded-full cursor-pointer hover:bg-blue-700">
                                    <i class="fas fa-camera text-xs"></i>
                                    <input type="file" name="avatar" id="avatar" accept="image/png,image/jpeg,image/jpg,image/webp" class="hidden">
                                </label>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $user->role ?? 'Staff' }}</p>
                                @error('avatar')<p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                                <input type="text" name="position" id="position" value="{{ old('position', $user->position ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                                @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone ?? '') }}"
                                       placeholder="+62 812-3456-7890"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="md:col-span-2">
                                <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                                <input type="text" id="department" value="{{ $user->department ?? 'General' }}" disabled
                                       class="w-full px-3 py-2 border border-gray-200 bg-gray-50 rounded-lg text-gray-500">
                            </div>
                            <div class="md:col-span-2">
                                <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Bio / Professional Summary</label>
                                <textarea name="bio" id="bio" rows="3" placeholder="Tell us about yourself..."
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('bio', $user->bio ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200">
                            <button type="button" onclick="window.location.reload()" class="text-sm text-gray-500 hover:text-gray-700">Discard Changes</button>
                            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                                Save Profile
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Account Security --}}
                <div id="profile-tab-security" class="profile-tab-content hidden">
                    <h2 class="text-lg font-semibold text-gray-900 mb-1">Account Security</h2>
                    <p class="text-sm text-gray-500 mb-6">Manage your password and security settings</p>

                    <form action="{{ route('settings.password') }}" method="POST" class="max-w-xl">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                <input type="password" name="current_password" id="current_password"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('current_password') border-red-500 @enderror">
                                @error('current_password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                <input type="password" name="new_password" id="new_password"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('new_password') border-red-500 @enderror">
                                @error('new_password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter, kombinasi huruf besar, huruf kecil, angka & simbol</p>
                            </div>
                            <div>
                                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        <div class="flex items-center justify-end pt-6 mt-6 border-t border-gray-200">
                            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-key mr-2"></i>Update Password
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 p-4 border border-gray-200 rounded-lg flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Two Factor Authentication (2FA)</p>
                            <p class="text-xs text-gray-500">Add an extra layer of security to your account</p>
                        </div>
                        <button type="button" onclick="alert('Fitur 2FA akan segera hadir!')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium">
                            Enable 2FA
                        </button>
                    </div>
                </div>

                {{-- Notifications --}}
                <div id="profile-tab-notifications" class="profile-tab-content hidden">
                    <h2 class="text-lg font-semibold text-gray-900 mb-1">Notifications</h2>
                    <p class="text-sm text-gray-500 mb-6">Choose which notifications you want to receive</p>

                    <form action="{{ route('settings.notifications') }}" method="POST" class="max-w-2xl">
                        @csrf
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-start space-x-3">
                                    <div class="bg-blue-50 p-2 rounded-lg"><i class="fas fa-envelope text-blue-600"></i></div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Email Notifications</h4>
                                        <p class="text-sm text-gray-500">Receive important notifications via email</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="email_notifications" value="0">
                                    <input type="checkbox" name="email_notifications" value="1" {{ $preferences->email_notifications ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-start space-x-3">
                                    <div class="bg-yellow-50 p-2 rounded-lg"><i class="fas fa-boxes text-yellow-600"></i></div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Low Stock Alerts</h4>
                                        <p class="text-sm text-gray-500">Get notified when stock is running low</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="stock_alerts" value="0">
                                    <input type="checkbox" name="stock_alerts" value="1" {{ $preferences->stock_alerts ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-start space-x-3">
                                    <div class="bg-green-50 p-2 rounded-lg"><i class="fas fa-chart-line text-green-600"></i></div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Sales Reports</h4>
                                        <p class="text-sm text-gray-500">Receive weekly sales performance reports</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="sales_reports" value="0">
                                    <input type="checkbox" name="sales_reports" value="1" {{ $preferences->sales_reports ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center justify-end pt-6 mt-6 border-t border-gray-200">
                            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                                Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showProfileTab(tab) {
        document.querySelectorAll('.profile-tab-content').forEach(el => el.classList.add('hidden'));
        document.getElementById('profile-tab-' + tab).classList.remove('hidden');

        document.querySelectorAll('.sub-tab-btn').forEach(btn => {
            btn.classList.remove('bg-blue-50', 'text-blue-700');
            btn.classList.add('text-gray-600', 'hover:bg-gray-50');
        });
        const active = document.getElementById('sub-btn-' + tab);
        active.classList.add('bg-blue-50', 'text-blue-700');
        active.classList.remove('text-gray-600', 'hover:bg-gray-50');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const hash = window.location.hash.replace('#', '');
        if (['personal', 'security', 'notifications'].includes(hash)) {
            showProfileTab(hash);
        }
    });
</script>
@endpush
