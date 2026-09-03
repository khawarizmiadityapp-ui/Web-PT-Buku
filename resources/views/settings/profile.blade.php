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
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">No. Telepon / WhatsApp</label>
                                @php
                                    $phoneRaw = old('phone', $user->phone ?? '');
                                    $phoneDigits = preg_replace('/\D/', '', $phoneRaw);
                                    if (str_starts_with($phoneDigits, '62')) {
                                        $phoneDigits = substr($phoneDigits, 2);
                                    } elseif (str_starts_with($phoneDigits, '0')) {
                                        $phoneDigits = substr($phoneDigits, 1);
                                    }
                                @endphp
                                <div class="relative flex rounded-lg shadow-sm">
                                    <select class="country-code-select inline-flex items-center px-2 py-2 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-700 font-semibold text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none cursor-pointer" style="max-width: 115px;" title="Pilih Kode Negara">
                                        <option value="+62" selected>🇮🇩 +62</option>
                                        <option value="+60">🇲🇾 +60</option>
                                        <option value="+65">🇸🇬 +65</option>
                                        <option value="+63">🇵🇭 +63</option>
                                        <option value="+66">🇹🇭 +66</option>
                                        <option value="+84">🇻🇳 +84</option>
                                        <option value="+1">🇺🇸 +1</option>
                                        <option value="+44">🇬🇧 +44</option>
                                        <option value="+61">🇦🇺 +61</option>
                                        <option value="+81">🇯🇵 +81</option>
                                        <option value="+82">🇰🇷 +82</option>
                                        <option value="+86">🇨🇳 +86</option>
                                        <option value="+966">🇸🇦 +966</option>
                                        <option value="+971">🇦🇪 +971</option>
                                        <option value="+49">🇩🇪 +49</option>
                                        <option value="+31">🇳🇱 +31</option>
                                    </select>
                                    <input type="tel" 
                                           id="profilePhone"
                                           class="phone-number-input flex-1 min-w-0 block w-full px-3 py-2 border rounded-none rounded-r-lg border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror" 
                                           value="{{ $phoneDigits }}" 
                                           placeholder="81234567890" 
                                           inputmode="numeric" 
                                           pattern="[0-9]*" 
                                           maxlength="15">
                                    <input type="hidden" name="phone" value="{{ $phoneRaw }}">
                                </div>
                                @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                <span class="text-gray-400 text-xs mt-1 block">Pilih negara & ketik nomor tanpa awalan 0 (contoh: 81234567890)</span>
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
                    <h2 class="text-lg font-semibold text-gray-900 mb-1">Keamanan Akun & Ganti Password</h2>
                    <p class="text-sm text-gray-500 mb-6">Kelola kata sandi akun dan verifikasi keamanan dua faktor (2FA)</p>

                    <!-- Form to request Email OTP for password change (separate form) -->
                    <form id="sendEmailOtpForm" action="{{ route('settings.password.send_otp') }}" method="POST" class="hidden">
                        @csrf
                    </form>

                    <form action="{{ route('settings.password') }}" method="POST" class="max-w-2xl space-y-6">
                        @csrf
                        <div class="space-y-4 bg-white p-5 rounded-2xl border border-gray-200">
                            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-2">1. Masukkan Password</h3>
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini (Lama)</label>
                                <input type="password" name="current_password" id="current_password" required
                                       placeholder="Masukkan password yang sedang aktif"
                                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('current_password') border-red-500 @enderror">
                                @error('current_password')<p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                                    <input type="password" name="new_password" id="new_password" required
                                           placeholder="Minimal 8 karakter"
                                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('new_password') border-red-500 @enderror">
                                    @error('new_password')<p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                                           placeholder="Ketik ulang password baru"
                                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-info-circle text-blue-500 mr-1"></i>Kombinasi minimal 8 karakter dengan huruf besar, huruf kecil, angka, dan simbol.
                            </p>
                        </div>

                        <!-- 2FA Verification Box -->
                        <div class="bg-blue-50/70 border border-blue-200 rounded-2xl p-5 space-y-3.5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center text-sm shadow-xs">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">2. Verifikasi Keamanan 2FA (Wajib)</h4>
                                        <p class="text-xs text-gray-600">Masukkan 6 digit kode dari Google Authenticator atau Email OTP</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 bg-blue-100 text-blue-800 text-[11px] font-bold rounded-full">
                                    Diperlukan
                                </span>
                            </div>

                            <div class="pt-2">
                                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                                    <div class="relative flex-1">
                                        <input 
                                            type="text" 
                                            name="two_factor_code" 
                                            id="two_factor_code" 
                                            maxlength="6" 
                                            inputmode="numeric" 
                                            placeholder="Contoh: 123456" 
                                            value="{{ old('two_factor_code') }}"
                                            class="w-full pl-10 pr-4 py-2.5 border border-blue-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono font-bold text-base tracking-widest text-gray-800 bg-white @error('two_factor_code') border-red-500 @enderror"
                                            required
                                        >
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-blue-500">
                                            <i class="fas fa-key text-xs"></i>
                                        </div>
                                    </div>

                                    <button 
                                        type="submit" 
                                        form="sendEmailOtpForm" 
                                        class="px-4 py-2.5 bg-white hover:bg-slate-50 border border-blue-300 text-blue-700 text-xs font-semibold rounded-xl shadow-xs flex items-center justify-center space-x-1.5 transition-colors whitespace-nowrap"
                                        title="Kirim kode verifikasi ke email"
                                    >
                                        <i class="fas fa-paper-plane text-blue-600"></i>
                                        <span>Kirim OTP ke Email</span>
                                    </button>
                                </div>
                                @error('two_factor_code')
                                    <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                                <p class="text-[11px] text-gray-500 mt-2">
                                    💡 <strong>Tips:</strong> Anda bisa langsung memasukkan 6 digit kode yang tampil di aplikasi <strong>Google Authenticator</strong> HP Anda, atau klik tombol <em>"Kirim OTP ke Email"</em> untuk menerima kode via inbox <strong>{{ $targetEmail }}</strong>.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end pt-2">
                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-md transition flex items-center space-x-2 text-sm">
                                <i class="fas fa-save"></i>
                                <span>Verifikasi & Perbarui Password</span>
                            </button>
                        </div>
                    </form>

                    <!-- Google Authenticator Device Card -->
                    <div class="mt-10 p-5 bg-slate-50 border border-gray-200 rounded-2xl">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg flex-shrink-0">
                                    <i class="fas fa-qrcode"></i>
                                </div>
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <h4 class="text-sm font-bold text-gray-900">Google Authenticator (2FA)</h4>
                                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-full">
                                            Aktif & Terlindungi
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        Akun ini telah terhubung dengan otentikasi dua langkah (TOTP). Anda dapat scan ulang barcode di bawah jika berpindah perangkat HP.
                                    </p>
                                </div>
                            </div>
                            <button 
                                type="button" 
                                onclick="document.getElementById('qrCodeDrawer').classList.toggle('hidden')" 
                                class="px-3.5 py-1.5 bg-white border border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-100 transition shadow-xs whitespace-nowrap"
                            >
                                <i class="fas fa-eye mr-1 text-gray-500"></i>Lihat QR Code
                            </button>
                        </div>

                        <!-- QR Code Collapsible Details -->
                        <div id="qrCodeDrawer" class="hidden mt-4 pt-4 border-t border-gray-200 grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                            <div class="sm:col-span-4 flex justify-center">
                                <div class="p-2 bg-white rounded-xl shadow-xs border border-gray-200 inline-block">
                                    <img src="{{ $qrImageUrl }}" alt="QR Code Google Authenticator" class="w-32 h-32 rounded-lg object-contain">
                                </div>
                            </div>
                            <div class="sm:col-span-8 space-y-2 text-xs text-gray-600">
                                <p><strong>Cara Scan Ulang:</strong> Buka aplikasi Google / Microsoft Authenticator di HP, lalu scan QR Code di samping.</p>
                                <div>
                                    <span class="text-gray-500 font-semibold block mb-1">Kunci Rahasia Manual:</span>
                                    <div class="inline-block bg-white border border-gray-300 rounded-lg px-2.5 py-1 font-mono font-bold text-blue-700 select-all">
                                        {{ chunk_split($secret, 4, ' ') }}
                                    </div>
                                </div>
                            </div>
                        </div>
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
