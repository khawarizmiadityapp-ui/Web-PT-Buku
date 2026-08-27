<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verifikasi Keamanan (MFA) - ERP PT Buku & ATK Nusantara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .bg-warehouse {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.9) 0%, rgba(29, 78, 216, 0.95) 100%);
            position: relative;
            overflow: hidden;
        }
        
        .bg-warehouse::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            opacity: 0.3;
        }
        
        .warehouse-illustration {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            height: 70%;
            opacity: 0.08;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 600"><rect x="50" y="200" width="150" height="200" fill="white" opacity="0.6"/><rect x="220" y="180" width="130" height="220" fill="white" opacity="0.5"/><rect x="370" y="210" width="140" height="190" fill="white" opacity="0.6"/><rect x="530" y="190" width="150" height="210" fill="white" opacity="0.5"/><rect x="100" y="150" width="100" height="40" fill="white" opacity="0.4"/><rect x="250" y="130" width="90" height="40" fill="white" opacity="0.4"/><rect x="400" y="160" width="100" height="40" fill="white" opacity="0.4"/><rect x="560" y="140" width="100" height="40" fill="white" opacity="0.4"/></svg>') no-repeat center;
            background-size: contain;
        }
        
        .otp-input {
            transition: all 0.2s ease;
            font-feature-settings: "tnum";
            font-variant-numeric: tabular-nums;
        }
        
        .otp-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
            background-color: #ffffff;
        }

        .btn-primary {
            background: #2563eb;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
        }
        
        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .pulse-shield {
            animation: shieldPulse 2.5s infinite;
        }

        @keyframes shieldPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="min-h-screen flex">
        <!-- Left Side - Branding & Description -->
        <div class="hidden lg:flex lg:w-1/2 bg-warehouse text-white p-12 flex-col justify-between relative overflow-hidden">
            <div class="warehouse-illustration"></div>
            
            <!-- Logo & Brand -->
            <div class="relative z-10">
                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20">
                    <img src="{{ asset('images/logo PT buku.png') }}" alt="PT Buku & ATK Nusantara" class="h-12 w-auto object-contain bg-white rounded-lg p-1">
                    <div>
                        <span class="text-2xl font-bold tracking-tight block">ERP System</span>
                        <span class="text-xs text-blue-100 uppercase tracking-widest font-medium">PT Distribusi Buku & ATK</span>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="relative z-10 space-y-6 max-w-lg">
                <div class="inline-flex items-center space-x-2 bg-blue-500/30 border border-blue-300/30 px-3 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider text-blue-100">
                    <i class="fas fa-shield-alt text-blue-200"></i>
                    <span>Verifikasi Berlapis (2FA / MFA)</span>
                </div>
                <h1 class="text-4xl font-extrabold leading-tight">
                    Perlindungan Keamanan Tingkat Lanjut untuk Akun Anda
                </h1>
                <p class="text-base text-white/90 leading-relaxed">
                    Sistem memastikan hanya staf terotorisasi yang dapat mengakses data inventaris, kasir, logistik, dan transaksi keuangan perusahaan.
                </p>
                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/10">
                        <div class="text-blue-200 text-xs font-medium mb-1">Masa Sesi Aktif</div>
                        <div class="text-lg font-bold">15 Menit</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/10">
                        <div class="text-blue-200 text-xs font-medium mb-1">Status Enkripsi</div>
                        <div class="text-lg font-bold text-emerald-300 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                            Terlindungi
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="relative z-10 text-white/70 text-sm">
                <p>&copy; {{ date('Y') }} PT Distribusi Buku dan Alat Tulis Nusantara. Seluruh Hak Cipta Dilindungi.</p>
            </div>
        </div>

        <!-- Right Side - MFA Verification Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-10">
            <div class="w-full max-w-md animate-fade-in">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex justify-center mb-6">
                    <div class="bg-white p-3 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-3">
                        <img src="{{ asset('images/logo PT buku.png') }}" alt="PT Buku & ATK Nusantara" class="h-10 w-auto object-contain">
                        <span class="text-lg font-bold text-gray-800">ERP PT Buku</span>
                    </div>
                </div>

                <!-- Main Card -->
                <div class="bg-white rounded-2xl card-shadow border border-gray-100 p-8 sm:p-10">
                    <!-- Icon & Header -->
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl mb-4 pulse-shield border border-blue-100 shadow-sm">
                            <i class="fas fa-key text-2xl"></i>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Verifikasi Dua Langkah</h2>
                        <p class="text-gray-500 text-sm mt-1">
                            Masukkan 6 digit kode verifikasi untuk akun
                        </p>
                        <div class="inline-block mt-2 px-3 py-1 bg-slate-100 text-slate-700 font-semibold text-xs rounded-full border border-slate-200">
                            <i class="fas fa-user-circle mr-1 text-slate-500"></i>
                            {{ $user->name }} ({{ $user->email }})
                        </div>
                    </div>

                    <!-- Alerts -->
                    @if(session('success'))
                        <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center text-sm">
                            <i class="fas fa-check-circle mr-2.5 text-emerald-500 text-base"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="mb-5 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-xl flex items-center text-sm">
                            <i class="fas fa-info-circle mr-2.5 text-blue-500 text-base"></i>
                            <div>{{ session('info') }}</div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-circle mr-2.5 mt-0.5 text-rose-500 text-base"></i>
                                <div class="flex-1 font-medium">
                                    @foreach($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Static Code Development Demo Banner -->
                    <div class="mb-6 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-4 text-amber-900 shadow-sm">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 mt-0.5">
                                <i class="fas fa-code text-amber-600 text-base"></i>
                            </div>
                            <div class="flex-1 text-xs">
                                <div class="font-bold uppercase tracking-wider text-amber-800 mb-1 flex items-center justify-between">
                                    <span>Mode Pengujian (Statis)</span>
                                    <span class="px-2 py-0.5 bg-amber-200 text-amber-900 rounded font-mono font-bold tracking-widest">{{ $staticCode }}</span>
                                </div>
                                <p class="text-amber-700 leading-relaxed">
                                    Aplikasi berjalan dalam mode lokal. Gunakan kode statis di atas atau klik tombol cepat di bawah ini.
                                </p>
                                <button 
                                    type="button" 
                                    onclick="autoFillCode('{{ $staticCode }}')" 
                                    class="mt-2 inline-flex items-center space-x-1.5 px-3 py-1 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-medium text-xs shadow-sm transition-colors"
                                >
                                    <i class="fas fa-magic"></i>
                                    <span>Isi Kode Otomatis ({{ $staticCode }})</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- OTP Form -->
                    <form id="mfaForm" action="{{ route('login.mfa.verify') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="code" id="finalOtpCode" value="{{ old('code') }}">

                        <!-- 6-digit OTP Input Boxes -->
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-3 text-center">
                                Masukkan 6 Digit Kode MFA
                            </label>
                            <div class="flex justify-between items-center gap-2 sm:gap-3" id="otpInputsContainer">
                                @for($i = 0; $i < 6; $i++)
                                    <input 
                                        type="text" 
                                        inputmode="numeric" 
                                        pattern="[0-9]*" 
                                        maxlength="1" 
                                        data-index="{{ $i }}" 
                                        class="otp-digit otp-input w-12 h-14 sm:w-13 sm:h-16 text-center text-2xl font-extrabold text-gray-800 bg-gray-50 border border-gray-300 rounded-xl focus:bg-white"
                                        autocomplete="off"
                                        required
                                    >
                                @endfor
                            </div>
                        </div>

                        <!-- Timer & Resend Info -->
                        <div class="flex items-center justify-between text-xs text-gray-500 pt-1">
                            <div class="flex items-center space-x-1.5 text-gray-600 font-medium">
                                <i class="far fa-clock text-blue-600"></i>
                                <span>Sisa waktu:</span>
                                <span id="countdownTimer" class="font-bold font-mono text-blue-700">15:00</span>
                            </div>
                            <span class="text-gray-400">Batas sesi: 15 menit</span>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            id="submitBtn"
                            class="btn-primary w-full py-3.5 text-white font-semibold rounded-xl shadow-md flex items-center justify-center space-x-2 text-sm"
                        >
                            <i class="fas fa-shield-check"></i>
                            <span>Verifikasi & Masuk Dashboard</span>
                        </button>
                    </form>

                    <!-- Auxiliary Action Links -->
                    <div class="mt-6 pt-5 border-t border-gray-100 flex items-center justify-between text-xs">
                        <form action="{{ route('login.mfa.resend') }}" method="POST">
                            @csrf
                            <button type="submit" class="font-semibold text-blue-600 hover:text-blue-800 hover:underline flex items-center space-x-1">
                                <i class="fas fa-redo-alt text-xs"></i>
                                <span>Reset / Perpanjang Waktu (15m)</span>
                            </button>
                        </form>

                        <form action="{{ route('login.mfa.cancel') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-gray-800 hover:underline flex items-center space-x-1">
                                <i class="fas fa-arrow-left text-xs"></i>
                                <span>Batal & Login Ulang</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Footer copyright on mobile -->
                <div class="lg:hidden mt-6 text-center text-xs text-gray-500">
                    <p>&copy; {{ date('Y') }} PT Distribusi Buku dan Alat Tulis Nusantara</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const digits = document.querySelectorAll('.otp-digit');
        const finalInput = document.getElementById('finalOtpCode');
        const form = document.getElementById('mfaForm');
        let remainingSeconds = {{ $remainingSeconds ?? 900 }};

        // Countdown Timer Logic (15 minutes)
        function updateTimer() {
            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = remainingSeconds % 60;
            const display = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            const timerEl = document.getElementById('countdownTimer');
            if (timerEl) {
                timerEl.textContent = display;
                if (remainingSeconds <= 120) {
                    timerEl.classList.remove('text-blue-700');
                    timerEl.classList.add('text-rose-600', 'animate-pulse');
                }
            }

            if (remainingSeconds > 0) {
                remainingSeconds--;
            } else {
                window.location.href = "{{ route('login') }}";
            }
        }

        setInterval(updateTimer, 1000);
        updateTimer();

        // Helper to update the hidden code field
        function syncFinalCode() {
            let code = '';
            digits.forEach(d => { code += d.value.trim(); });
            finalInput.value = code;
        }

        // Auto-fill functionality for demo mode
        function autoFillCode(code) {
            const chars = String(code).split('');
            digits.forEach((d, idx) => {
                d.value = chars[idx] || '';
            });
            syncFinalCode();
            if (digits[5]) digits[5].focus();
        }

        // Handle OTP input interactions
        digits.forEach((input, index) => {
            // Keydown navigation (Backspace & arrows)
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace') {
                    if (input.value === '' && index > 0) {
                        digits[index - 1].focus();
                        digits[index - 1].value = '';
                    } else {
                        input.value = '';
                    }
                    syncFinalCode();
                } else if (e.key === 'ArrowLeft' && index > 0) {
                    digits[index - 1].focus();
                } else if (e.key === 'ArrowRight' && index < digits.length - 1) {
                    digits[index + 1].focus();
                }
            });

            // Input event (digits only and auto-advance)
            input.addEventListener('input', (e) => {
                const val = e.target.value.replace(/[^0-9]/g, '');
                input.value = val.slice(-1); // Only keep last digit

                if (input.value && index < digits.length - 1) {
                    digits[index + 1].focus();
                }

                syncFinalCode();

                // Auto-submit when all 6 digits are typed
                if (index === 5 && finalInput.value.length === 6) {
                    form.submit();
                }
            });

            // Handle Paste event on any box
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
                if (!pasteData) return;

                const chars = pasteData.slice(0, 6).split('');
                chars.forEach((char, i) => {
                    if (digits[i]) {
                        digits[i].value = char;
                    }
                });

                syncFinalCode();

                const nextFocus = Math.min(chars.length, digits.length - 1);
                digits[nextFocus].focus();

                if (chars.length >= 6) {
                    form.submit();
                }
            });
        });

        // Focus first box on page load if empty
        window.addEventListener('DOMContentLoaded', () => {
            if (finalInput.value && finalInput.value.length === 6) {
                autoFillCode(finalInput.value);
            } else {
                if (digits[0]) digits[0].focus();
            }
        });

        // Sync before submit
        form.addEventListener('submit', (e) => {
            syncFinalCode();
            if (finalInput.value.length !== 6) {
                e.preventDefault();
                alert('Silakan masukkan 6 digit kode verifikasi secara lengkap.');
                digits[0].focus();
            }
        });
    </script>
</body>
</html>
