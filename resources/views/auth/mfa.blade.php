<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verifikasi Keamanan Ganda (2FA / MFA) - ERP PT Buku & ATK Nusantara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        
        .bg-warehouse {
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.95) 0%, rgba(29, 78, 216, 0.95) 100%);
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
        
        .otp-input {
            transition: all 0.2s ease;
            font-feature-settings: "tnum";
            font-variant-numeric: tabular-nums;
        }
        
        .otp-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
            background-color: #ffffff;
        }

        .btn-primary {
            background: #2563eb;
            transition: all 0.25s ease;
        }
        
        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
        }
        
        .card-shadow {
            box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.08), 0 8px 15px -6px rgba(0, 0, 0, 0.04);
        }
        
        .tab-btn {
            transition: all 0.2s ease;
        }
        
        .tab-btn.active {
            background-color: #ffffff;
            color: #1d4ed8;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-color: #dbeafe;
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen text-slate-800">
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Left Side - Branding & Info -->
        <div class="hidden lg:flex lg:w-5/12 bg-warehouse text-white p-12 flex-col justify-between relative overflow-hidden">
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

            <!-- Main Content Info -->
            <div class="relative z-10 space-y-6 max-w-md">
                <div class="inline-flex items-center space-x-2 bg-blue-500/30 border border-blue-300/30 px-3.5 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider text-blue-100">
                    <i class="fas fa-shield-alt text-blue-300"></i>
                    <span>Verifikasi Berlapis (2 Pilihan)</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight">
                    Pilih Metode Autentikasi yang Paling Nyaman
                </h1>
                <p class="text-sm text-white/90 leading-relaxed">
                    Sistem menyediakan 2 opsi verifikasi keamanan fleksibel: menggunakan <strong>Google Authenticator</strong> (instan & offline) atau <strong>Kode OTP via Email</strong>.
                </p>

                <div class="space-y-3 pt-2">
                    <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm p-3.5 rounded-xl border border-white/10 text-xs">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-300 flex items-center justify-center text-base flex-shrink-0">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div>
                            <strong class="text-white block text-sm">Opsi 1: Google Authenticator</strong>
                            <span class="text-blue-100 text-[11px]">Bebas kuota email, scan QR code sekali dan kode berganti tiap 30 detik.</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm p-3.5 rounded-xl border border-white/10 text-xs">
                        <div class="w-8 h-8 rounded-lg bg-cyan-500/20 text-cyan-300 flex items-center justify-center text-base flex-shrink-0">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <div>
                            <strong class="text-white block text-sm">Opsi 2: Kode OTP Email</strong>
                            <span class="text-blue-100 text-[11px]">Kode 6 digit dikirimkan langsung ke kotak masuk email Anda.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="relative z-10 text-white/70 text-xs">
                <p>&copy; {{ date('Y') }} PT Distribusi Buku & ATK Nusantara. Seluruh Hak Cipta Dilindungi.</p>
            </div>
        </div>

        <!-- Right Side - 2FA/MFA Verification Container -->
        <div class="w-full lg:w-7/12 flex items-center justify-center p-4 sm:p-8 lg:p-12 overflow-y-auto">
            <div class="w-full max-w-xl">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex justify-center mb-6">
                    <div class="bg-white p-3 rounded-2xl shadow-sm border border-gray-200 flex items-center space-x-3">
                        <img src="{{ asset('images/logo PT buku.png') }}" alt="PT Buku & ATK Nusantara" class="h-9 w-auto object-contain">
                        <span class="text-lg font-bold text-gray-800">ERP PT Buku</span>
                    </div>
                </div>

                <!-- Main Card -->
                <div class="bg-white rounded-3xl card-shadow border border-slate-200/80 p-6 sm:p-9">
                    <!-- User Account Info Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                        <div>
                            <span class="text-xs text-slate-400 font-medium">Akun Terautentikasi:</span>
                            <h2 class="text-lg font-extrabold text-slate-900">{{ $user->name }}</h2>
                            <div class="text-xs font-mono text-blue-600 font-medium">{{ $user->email }}</div>
                        </div>
                        <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-full border border-slate-200">
                            {{ $user->role ?? 'User' }}
                        </span>
                    </div>

                    <!-- Flash Alerts -->
                    @if(session('success'))
                        <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center text-xs sm:text-sm">
                            <i class="fas fa-check-circle mr-2.5 text-emerald-500 text-base flex-shrink-0"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="mb-5 bg-amber-50 border border-amber-200 text-amber-900 px-4 py-3 rounded-xl text-xs sm:text-sm">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle mr-2.5 mt-0.5 text-amber-600 text-base flex-shrink-0"></i>
                                <div class="flex-1">
                                    <div class="font-bold text-amber-800">Perhatian Pengiriman Email</div>
                                    <p class="text-amber-700 text-xs mt-0.5">{{ session('warning') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-xs sm:text-sm">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-circle mr-2.5 mt-0.5 text-rose-500 text-base flex-shrink-0"></i>
                                <div class="flex-1 font-medium">
                                    @foreach($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Dual Method Selector (Tabs) -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                            Pilih Metode Verifikasi:
                        </label>
                        <div class="grid grid-cols-2 gap-2 bg-slate-100 p-1.5 rounded-2xl border border-slate-200">
                            <!-- Tab 1 Button: Authenticator -->
                            <button 
                                type="button" 
                                onclick="switchMethod('authenticator')" 
                                id="btnTabAuth"
                                class="tab-btn {{ ($activeTab ?? 'authenticator') === 'authenticator' ? 'active' : 'text-slate-600 hover:text-slate-900' }} py-2.5 px-3 rounded-xl font-bold text-xs flex items-center justify-center space-x-2 border border-transparent"
                            >
                                <i class="fas fa-mobile-alt text-sm text-blue-600"></i>
                                <span>1. Authenticator (QR)</span>
                            </button>

                            <!-- Tab 2 Button: Email OTP -->
                            <button 
                                type="button" 
                                onclick="switchMethod('email')" 
                                id="btnTabEmail"
                                class="tab-btn {{ ($activeTab ?? 'authenticator') === 'email' ? 'active' : 'text-slate-600 hover:text-slate-900' }} py-2.5 px-3 rounded-xl font-bold text-xs flex items-center justify-center space-x-2 border border-transparent"
                            >
                                <i class="fas fa-envelope text-sm text-emerald-600"></i>
                                <span>2. Kode Email OTP</span>
                            </button>
                        </div>
                    </div>

                    <!-- Panel 1: Google Authenticator -->
                    <div id="panelAuthenticator" class="{{ ($activeTab ?? 'authenticator') === 'authenticator' ? '' : 'hidden' }} space-y-4 mb-6">
                        <div class="bg-slate-50 p-4 sm:p-5 rounded-2xl border border-slate-200/70">
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                                <!-- QR Code Image -->
                                <div class="sm:col-span-5 flex flex-col items-center justify-center text-center">
                                    <div class="p-2 bg-white rounded-xl shadow-xs border border-slate-200 inline-block">
                                        <img 
                                            src="{{ $qrImageUrl }}" 
                                            alt="QR Code Authenticator" 
                                            class="w-32 h-32 sm:w-36 sm:h-36 rounded-lg object-contain"
                                        >
                                    </div>
                                    <span class="text-[11px] text-slate-500 mt-1.5 font-medium flex items-center gap-1">
                                        <i class="fas fa-camera text-blue-500"></i> Scan dari HP Anda
                                    </span>
                                </div>

                                <!-- Steps & Key -->
                                <div class="sm:col-span-7 space-y-2.5 text-xs text-slate-600">
                                    <div class="flex items-start space-x-2">
                                        <span class="w-4 h-4 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-[9px] flex-shrink-0 mt-0.5">1</span>
                                        <p>Buka <strong>Google Authenticator</strong> atau <strong>Microsoft Authenticator</strong> di HP.</p>
                                    </div>
                                    <div class="flex items-start space-x-2">
                                        <span class="w-4 h-4 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-[9px] flex-shrink-0 mt-0.5">2</span>
                                        <p>Scan barcode atau masukkan kunci manual:</p>
                                    </div>
                                    <div class="flex items-center space-x-1.5 pt-1">
                                        <div class="flex-1 bg-white border border-slate-300 rounded-lg px-2.5 py-1.5 font-mono text-[11px] font-bold text-blue-700 select-all">
                                            {{ chunk_split($secret, 4, ' ') }}
                                        </div>
                                        <button 
                                            type="button" 
                                            onclick="copySecret('{{ $secret }}')" 
                                            class="px-2.5 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg text-xs font-semibold"
                                            id="btnCopySecret"
                                        >
                                            Salin
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Panel 2: Email OTP -->
                    <div id="panelEmail" class="{{ ($activeTab ?? 'authenticator') === 'email' ? '' : 'hidden' }} space-y-4 mb-6">
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200/70 space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 text-lg">
                                    <i class="fas fa-paper-plane"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="text-xs text-slate-500 font-medium">Kirim kode verifikasi ke alamat email:</div>
                                    <div class="text-sm font-bold font-mono text-slate-800 mt-0.5">{{ $targetEmail }}</div>
                                    <p class="text-xs text-slate-500 mt-1">
                                        Klik tombol di bawah untuk mengirimkan 6 digit kode OTP baru ke email Anda.
                                    </p>
                                </div>
                            </div>

                            <form action="{{ route('login.mfa.send_email') }}" method="POST">
                                @csrf
                                <button 
                                    type="submit" 
                                    class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs flex items-center justify-center space-x-2 transition-colors"
                                >
                                    <i class="fas fa-envelope-open-text"></i>
                                    <span>Kirim Kode OTP ke Email Saya</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Unified 6-Digit OTP Form (Accepts either Authenticator or Email OTP) -->
                    <form id="mfaForm" action="{{ route('login.mfa.verify') }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="code" id="finalOtpCode" value="{{ old('code') }}">

                        <div>
                            <div class="flex items-center justify-between mb-2.5">
                                <label class="text-xs font-bold uppercase tracking-wider text-slate-700" id="otpLabel">
                                    Masukkan 6 Digit Kode Verifikasi
                                </label>
                                <span class="text-[11px] text-slate-400 font-medium">Bisa dari Authenticator atau Email</span>
                            </div>

                            <!-- 6 Digit Input Boxes -->
                            <div class="flex justify-between items-center gap-2 sm:gap-3" id="otpInputsContainer">
                                @for($i = 0; $i < 6; $i++)
                                    <input 
                                        type="text" 
                                        inputmode="numeric" 
                                        pattern="[0-9]*" 
                                        maxlength="1" 
                                        data-index="{{ $i }}" 
                                        class="otp-digit otp-input w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl font-extrabold text-slate-800 bg-slate-50 border border-slate-300 rounded-2xl focus:bg-white focus:border-blue-600"
                                        autocomplete="off"
                                        required
                                    >
                                @endfor
                            </div>
                        </div>

                        <!-- Timer countdown -->
                        <div class="flex items-center justify-between text-xs text-slate-500 pt-1">
                            <div class="flex items-center space-x-1.5 text-slate-600 font-medium">
                                <i class="far fa-clock text-blue-600"></i>
                                <span>Batas Sesi:</span>
                                <span id="countdownTimer" class="font-bold font-mono text-blue-700">01:00</span>
                            </div>
                            <span class="text-slate-400">Verifikasi otomatis saat 6 digit terisi</span>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            id="submitBtn"
                            class="btn-primary w-full py-3.5 text-white font-bold rounded-xl shadow-md flex items-center justify-center space-x-2 text-sm"
                        >
                            <i class="fas fa-shield-check"></i>
                            <span>Verifikasi & Masuk Dashboard</span>
                        </button>
                    </form>

                    <!-- Auxiliary Action Links -->
                    <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between text-xs">
                        <form action="{{ route('login.mfa.resend') }}" method="POST" onsubmit="return confirm('Buat QR Code Authenticator baru? Anda perlu scan ulang di HP.')">
                            @csrf
                            <button type="submit" class="font-semibold text-slate-500 hover:text-blue-700 hover:underline flex items-center space-x-1">
                                <i class="fas fa-sync-alt text-xs"></i>
                                <span>Reset QR Authenticator</span>
                            </button>
                        </form>

                        <form action="{{ route('login.mfa.cancel') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-slate-500 hover:text-slate-800 hover:underline flex items-center space-x-1">
                                <i class="fas fa-arrow-left text-xs"></i>
                                <span>Batal & Login Ulang</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Mobile Footer -->
                <div class="lg:hidden mt-6 text-center text-xs text-slate-500">
                    <p>&copy; {{ date('Y') }} PT Distribusi Buku dan Alat Tulis Nusantara</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const digits = document.querySelectorAll('.otp-digit');
        const finalInput = document.getElementById('finalOtpCode');
        const form = document.getElementById('mfaForm');
        let remainingSeconds = {{ $remainingSeconds ?? 60 }};

        // Tab Switching Logic
        function switchMethod(method) {
            const btnAuth = document.getElementById('btnTabAuth');
            const btnEmail = document.getElementById('btnTabEmail');
            const panelAuth = document.getElementById('panelAuthenticator');
            const panelEmail = document.getElementById('panelEmail');
            const label = document.getElementById('otpLabel');

            if (method === 'authenticator') {
                btnAuth.classList.add('active');
                btnAuth.classList.remove('text-slate-600');
                btnEmail.classList.remove('active');
                btnEmail.classList.add('text-slate-600');

                panelAuth.classList.remove('hidden');
                panelEmail.classList.add('hidden');
                label.textContent = 'Masukkan 6 Digit dari Google Authenticator:';
            } else {
                btnEmail.classList.add('active');
                btnEmail.classList.remove('text-slate-600');
                btnAuth.classList.remove('active');
                btnAuth.classList.add('text-slate-600');

                panelEmail.classList.remove('hidden');
                panelAuth.classList.add('hidden');
                label.textContent = 'Masukkan 6 Digit Kode OTP dari Email:';
            }
            digits[0].focus();
        }

        // Copy Secret Key
        function copySecret(secret) {
            navigator.clipboard.writeText(secret).then(() => {
                const btn = document.getElementById('btnCopySecret');
                if (btn) {
                    btn.textContent = 'Tersalin!';
                    setTimeout(() => { btn.textContent = 'Salin'; }, 2000);
                }
            });
        }

        // Countdown Timer Logic
        function updateTimer() {
            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = remainingSeconds % 60;
            const display = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            const timerEl = document.getElementById('countdownTimer');
            if (timerEl) {
                timerEl.textContent = display;
                if (remainingSeconds <= 20) {
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

        // Auto-fill functionality
        function autoFillCode(code) {
            const chars = String(code).trim().split('');
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
