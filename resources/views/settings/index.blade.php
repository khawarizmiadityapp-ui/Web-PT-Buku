@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
        <p class="text-gray-500 mt-1">Kelola pengaturan akun dan preferensi Anda</p>
    </div>

    <!-- Tab Navigation -->
    <div class="bg-white rounded-t-xl border border-gray-200 border-b-0">
        <nav class="flex space-x-8 px-6" aria-label="Tabs">
            <button onclick="showTab('profile')" id="tab-btn-profile" class="tab-btn py-4 px-1 border-b-2 border-blue-500 text-blue-600 font-medium text-sm flex items-center space-x-2">
                <i class="fas fa-user"></i>
                <span>Profil</span>
            </button>
            <button onclick="showTab('password')" id="tab-btn-password" class="tab-btn py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm flex items-center space-x-2">
                <i class="fas fa-lock"></i>
                <span>Password</span>
            </button>
            <button onclick="showTab('notifications')" id="tab-btn-notifications" class="tab-btn py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm flex items-center space-x-2">
                <i class="fas fa-bell"></i>
                <span>Notifikasi</span>
            </button>
        </nav>
    </div>

    <!-- Tab Contents -->
    <div class="bg-white rounded-b-xl border border-gray-200 border-t-0 p-6">
        
        <!-- Profile Tab -->
        <div id="tab-profile" class="tab-content">
            <div class="max-w-2xl">
                <h2 class="text-lg font-semibold text-gray-900 mb-1">Informasi Profil</h2>
                <p class="text-sm text-gray-500 mb-6">Update informasi profil Anda</p>

                <form action="{{ route('settings.profile') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">No. Telepon / WhatsApp</label>
                                @php
                                    $phoneRaw = old('phone', Auth::user()->phone ?? '');
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
                                           id="settingsPhone"
                                           class="phone-number-input flex-1 min-w-0 block w-full px-3 py-2 border rounded-none rounded-r-lg border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror" 
                                           value="{{ $phoneDigits }}" 
                                           placeholder="81234567890" 
                                           inputmode="numeric" 
                                           pattern="[0-9]*" 
                                           maxlength="15">
                                    <input type="hidden" name="phone" value="{{ $phoneRaw }}">
                                </div>
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <span class="text-gray-400 text-xs mt-1 block">Pilih negara & ketik nomor tanpa awalan 0 (contoh: 81234567890)</span>
                            </div>

                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Departemen</label>
                                <input type="text" id="department" value="IT / Development" disabled 
                                       class="w-full px-3 py-2 border border-gray-200 bg-gray-50 rounded-lg text-gray-500">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-6 mt-6 border-t border-gray-200">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Password Tab -->
        <div id="tab-password" class="tab-content hidden">
            <div class="max-w-xl">
                <h2 class="text-lg font-semibold text-gray-900 mb-1">Ubah Password</h2>
                <p class="text-sm text-gray-500 mb-6">Pastikan password Anda aman dan mudah diingat</p>

                <form action="{{ route('settings.password') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('current_password') border-red-500 @enderror">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                            <input type="password" name="new_password" id="new_password" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('new_password') border-red-500 @enderror">
                            @error('new_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter, kombinasi huruf besar, huruf kecil, angka & simbol</p>
                        </div>

                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-6 mt-6 border-t border-gray-200">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                            <i class="fas fa-key mr-2"></i>Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Notifications Tab -->
        <div id="tab-notifications" class="tab-content hidden">
            <div class="max-w-2xl">
                <h2 class="text-lg font-semibold text-gray-900 mb-1">Pengaturan Notifikasi</h2>
                <p class="text-sm text-gray-500 mb-6">Pilih notifikasi yang ingin Anda terima</p>

                <form action="#" method="POST">
                    @csrf
                    
                    <div class="space-y-4">
                        <!-- Email Notifications -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-start space-x-3">
                                <div class="bg-blue-50 p-2 rounded-lg">
                                    <i class="fas fa-envelope text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Email Notifikasi</h4>
                                    <p class="text-sm text-gray-500">Terima notifikasi penting via email</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="email_notifications" value="1" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <!-- Stock Alert -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-start space-x-3">
                                <div class="bg-yellow-50 p-2 rounded-lg">
                                    <i class="fas fa-boxes text-yellow-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Alert Stok Rendah</h4>
                                    <p class="text-sm text-gray-500">Notifikasi saat stok barang hampir habis</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="stock_alerts" value="1" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <!-- Sales Report -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-start space-x-3">
                                <div class="bg-green-50 p-2 rounded-lg">
                                    <i class="fas fa-chart-line text-green-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Laporan Penjualan</h4>
                                    <p class="text-sm text-gray-500">Kirim laporan penjualan mingguan</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="sales_reports" value="1" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-6 mt-6 border-t border-gray-200">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                            <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Tab switching function
    function showTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });
        
        // Show selected tab
        document.getElementById('tab-' + tabName).classList.remove('hidden');
        
        // Update button styles
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-blue-500', 'text-blue-600');
            btn.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Highlight active button
        const activeBtn = document.getElementById('tab-btn-' + tabName);
        activeBtn.classList.remove('border-transparent', 'text-gray-500');
        activeBtn.classList.add('border-blue-500', 'text-blue-600');
        
        // Update URL hash
        window.location.hash = tabName;
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Check for hash in URL
        const hash = window.location.hash.replace('#', '');
        if (hash && ['profile', 'password', 'notifications'].includes(hash)) {
            showTab(hash);
        }
    });
</script>
@endpush
</content>
</invoke>