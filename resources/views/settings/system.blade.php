@extends('layouts.app')

@section('title', 'Settings - System')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">System Settings</h1>
        <p class="text-gray-500 mt-1">Configure company profile and application preferences</p>
    </div>

    @include('settings._tabs')

    <div class="bg-white rounded-b-xl border border-gray-200 border-t-0 p-6">
        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Left Sub-menu --}}
            <div class="lg:w-56 flex-shrink-0">
                <nav class="space-y-1">
                    <button onclick="showSystemTab('company')" id="sys-btn-company" class="sys-tab-btn w-full text-left px-4 py-2.5 rounded-lg text-sm font-medium bg-blue-50 text-blue-700">
                        <i class="fas fa-building mr-2"></i>Company Profile
                    </button>
                    <button onclick="showSystemTab('general')" id="sys-btn-general" class="sys-tab-btn w-full text-left px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-sliders-h mr-2"></i>General Preferences
                    </button>
                    <button onclick="showSystemTab('notifications')" id="sys-btn-notifications" class="sys-tab-btn w-full text-left px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-bell mr-2"></i>Module Settings
                    </button>
                </nav>
            </div>

            {{-- Company Profile --}}
            <div id="system-tab-company" class="system-tab-content flex-1">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Company Profile</h2>
                        <p class="text-sm text-gray-500">Legal identity and branding assets</p>
                    </div>
                </div>

                <form action="{{ route('settings.company') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">Legal Identity</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                                    <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $company->company_name) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('company_name') border-red-500 @enderror">
                                    @error('company_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="tax_id" class="block text-sm font-medium text-gray-700 mb-2">Tax ID (NPWP)</label>
                                    <input type="text" name="tax_id" id="tax_id" value="{{ old('tax_id', $company->tax_id) }}"
                                           placeholder="01.234.567.8-910.111"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Headquarters Address</label>
                                    <textarea name="address" id="address" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('address', $company->address) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">Branding Assets</h3>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center">
                                @if($company->logo_path)
                                    <img src="{{ Storage::url($company->logo_path) }}" alt="Logo" class="mx-auto h-16 mb-4 object-contain">
                                @else
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-300 mb-4"></i>
                                @endif
                                <p class="text-sm font-medium text-gray-700 mb-1">Brand Logo</p>
                                <p class="text-xs text-gray-500 mb-4">Recommended: 200x60px, PNG or SVG</p>
                                <label class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 cursor-pointer">
                                    <i class="fas fa-upload mr-2"></i> Upload Logo
                                    <input type="file" name="logo" accept="image/*" class="hidden">
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-6 mt-6 border-t border-gray-200">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            {{-- General Preferences --}}
            <div id="system-tab-general" class="system-tab-content flex-1 hidden">
                <h2 class="text-lg font-semibold text-gray-900 mb-1">General Preferences</h2>
                <p class="text-sm text-gray-500 mb-6">Configure application-wide settings</p>

                <form action="{{ route('settings.system.update') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">Application Name</label>
                            <input type="text" name="app_name" id="app_name" value="{{ old('app_name', $company->app_name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                            <select name="timezone" id="timezone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="Asia/Jakarta" {{ $company->timezone === 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB)</option>
                                <option value="Asia/Makassar" {{ $company->timezone === 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar (WITA)</option>
                                <option value="Asia/Jayapura" {{ $company->timezone === 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura (WIT)</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center justify-end pt-6 mt-6 border-t border-gray-200">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                            Save Preferences
                        </button>
                    </div>
                </form>
            </div>

            {{-- Module Settings / Notifications --}}
            <div id="system-tab-notifications" class="system-tab-content flex-1 hidden">
                <h2 class="text-lg font-semibold text-gray-900 mb-1">Module Settings</h2>
                <p class="text-sm text-gray-500 mb-6">System-wide notification configuration</p>

                <form action="{{ route('settings.system.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="app_name" value="{{ $company->app_name }}">
                    <input type="hidden" name="timezone" value="{{ $company->timezone }}">

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Email Notifications</p>
                                <p class="text-xs text-gray-500">Send important notifications via email</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="email_notifications" value="0">
                                <input type="checkbox" name="email_notifications" value="1" {{ $company->email_notifications ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Low Stock Alerts</p>
                                <p class="text-xs text-gray-500">Notify when product stock is running low</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="stock_alerts" value="0">
                                <input type="checkbox" name="stock_alerts" value="1" {{ $company->stock_alerts ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center justify-end pt-6 mt-6 border-t border-gray-200">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                            Save Settings
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
    function showSystemTab(tab) {
        document.querySelectorAll('.system-tab-content').forEach(el => el.classList.add('hidden'));
        document.getElementById('system-tab-' + tab).classList.remove('hidden');

        document.querySelectorAll('.sys-tab-btn').forEach(btn => {
            btn.classList.remove('bg-blue-50', 'text-blue-700');
            btn.classList.add('text-gray-600', 'hover:bg-gray-50');
        });
        const active = document.getElementById('sys-btn-' + tab);
        active.classList.add('bg-blue-50', 'text-blue-700');
        active.classList.remove('text-gray-600', 'hover:bg-gray-50');
    }
</script>
@endpush
