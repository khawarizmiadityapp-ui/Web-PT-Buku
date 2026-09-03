@extends('layouts.app')

@section('title', 'Tambah Supplier - PT Nusantara ERP')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Supplier</h1>
        <p class="text-gray-500 mt-1">Tambahkan data supplier (pemasok) baru ke dalam sistem</p>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 max-w-4xl">
        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Supplier Code -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700">Kode Supplier <span class="text-red-500">*</span></label>
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-full">
                            <i class="fas fa-magic text-[9px]"></i> Otomatis
                        </span>
                    </div>
                    <input type="text" name="supplier_code" value="{{ old('supplier_code', $supplierCode ?? \App\Models\Supplier::generateCode()) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none font-semibold text-blue-600" required placeholder="Contoh: SUP-2026-001">
                    @error('supplier_code') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <!-- Contact Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kontak <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required placeholder="Nama PIC">
                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Company Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Perusahaan <span class="text-red-500">*</span></label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required placeholder="PT / CV Supplier">
                    @error('company_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon / WhatsApp <span class="text-red-500">*</span></label>
                    @php
                        $phoneRaw = old('phone');
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
                               id="supplierPhone"
                               class="phone-number-input flex-1 min-w-0 block w-full px-4 py-2 rounded-none rounded-r-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('phone') border-red-500 @enderror" 
                               value="{{ $phoneDigits }}" 
                               placeholder="81234567890" 
                               required
                               inputmode="numeric" 
                               pattern="[0-9]*" 
                               maxlength="15">
                        <input type="hidden" name="phone" value="{{ $phoneRaw }}">
                    </div>
                    @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    <span class="text-gray-400 text-xs mt-1 block">Pilih negara & ketik nomor tanpa awalan 0 (contoh: 81234567890)</span>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="email@perusahaan.com">
                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Non-aktif" {{ old('status') == 'Non-aktif' ? 'selected' : '' }}>Non-aktif</option>
                    </select>
                    @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- City -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kota</label>
                    <input type="text" name="city" value="{{ old('city') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Kota Supplier">
                    @error('city') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                    <textarea name="address" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Alamat lengkap supplier">{{ old('address') }}</textarea>
                    @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8 border-t border-gray-100 pt-6">
                <a href="{{ route('suppliers.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">
                    <i class="fas fa-save mr-2"></i> Simpan Supplier
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
