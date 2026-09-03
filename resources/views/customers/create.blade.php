@extends('layouts.app')

@section('title', (isset($customer) ? 'Edit Customer' : 'Add Customer') . ' - PT Nusantara')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">{{ isset($customer) ? 'Edit Customer' : 'Add New Customer' }}</h1>
            <p class="text-muted mb-0">{{ isset($customer) ? 'Perbarui data pelanggan' : 'Create a new customer record' }}</p>
        </div>
        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ isset($customer) ? route('customers.update', $customer) : route('customers.store') }}" method="POST">
                @csrf
                @if(isset($customer))
                    @method('PUT')
                @endif
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label mb-0">Customer Code <span class="text-danger">*</span></label>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-normal text-xs px-2 py-0.5 rounded-pill">
                                <i class="fas fa-magic me-1"></i>Otomatis
                            </span>
                        </div>
                        <input type="text" name="customer_code" class="form-control font-semibold text-primary @error('customer_code') is-invalid @enderror" 
                               value="{{ old('customer_code', $customer->customer_code ?? ($customerCode ?? \App\Models\Customer::generateCode())) }}" required>
                        @error('customer_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $customer->name ?? '') }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $customer->email ?? '') }}">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">No. Telepon / WhatsApp <span class="text-danger">*</span></label>
                        @php
                            $phoneRaw = old('phone', $customer->phone ?? '');
                            $phoneDigits = preg_replace('/\D/', '', $phoneRaw);
                            if (str_starts_with($phoneDigits, '62')) {
                                $phoneDigits = substr($phoneDigits, 2);
                            } elseif (str_starts_with($phoneDigits, '0')) {
                                $phoneDigits = substr($phoneDigits, 1);
                            }
                        @endphp
                        <div class="input-group">
                            <select class="form-select country-code-select bg-light text-secondary fw-semibold border-end-0" style="max-width: 115px;" title="Pilih Kode Negara">
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
                                   id="customerPhone"
                                   class="form-control phone-number-input @error('phone') is-invalid @enderror" 
                                   value="{{ $phoneDigits }}" 
                                   placeholder="81234567890" 
                                   required 
                                   inputmode="numeric" 
                                   pattern="[0-9]*"
                                   maxlength="15">
                            <input type="hidden" name="phone" value="{{ $phoneRaw }}">
                        </div>
                        @error('phone')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Pilih negara & ketik nomor tanpa awalan 0 (contoh: 81234567890)</small>
                    </div>
                    
                    <div class="col-md-8">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                  rows="3">{{ old('address', $customer->address ?? '') }}</textarea>
                        @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
                               value="{{ old('city', $customer->city ?? '') }}">
                        @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> {{ isset($customer) ? 'Update Customer' : 'Save Customer' }}
                    </button>
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
