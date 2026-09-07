<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - ERP System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
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
        
        .input-field {
            transition: all 0.2s ease;
        }
        
        .input-field:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .animate-fade-in {
            animation: fadeIn 0.6s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .logo-icon {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Left Side - Branding & Description -->
        <div class="hidden lg:flex lg:w-1/2 bg-warehouse text-white p-12 flex-col justify-between relative overflow-hidden">
            <!-- Warehouse Illustration Background -->
            <div class="warehouse-illustration"></div>
            
            <!-- Logo & Brand -->
            <div class="relative z-10">
                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20">
                    <img src="{{ asset('images/logo PT buku.png') }}" alt="PT Buku & ATK Nusantara" class="h-12 w-auto object-contain bg-white rounded-lg p-1">
                    <span class="text-2xl font-semibold">ERP System</span>
                </div>
            </div>

            <!-- Main Content -->
            <div class="relative z-10 space-y-6 max-w-lg">
                <h1 class="text-5xl font-bold leading-tight">
                    Logistik yang Efisien.<br>
                    Pengiriman yang Presisi.
                </h1>
                <p class="text-lg text-white/90 leading-relaxed">
                    Platform perencanaan sumber daya perusahaan (ERP) generasi terbaru yang dirancang khusus untuk distribusi buku dan alat tulis dalam volume tinggi.
                </p>
            </div>

            <!-- Footer -->
            <div class="relative z-10 text-white/80 text-sm">
                <p>&copy; {{ date('Y') }} PT Distribusi Buku dan Alat Tulis Nusantara</p>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md animate-fade-in">
                <!-- Logo for Mobile -->
                <div class="lg:hidden flex justify-center mb-8">
                    <div class="bg-white p-3 rounded-2xl shadow-sm border border-gray-100">
                        <img src="{{ asset('images/logo PT buku.png') }}" alt="PT Buku & ATK Nusantara" class="h-16 w-auto object-contain">
                    </div>
                </div>

                <!-- Card -->
                <div class="bg-white rounded-2xl card-shadow p-10">
                    <!-- Icon & Title -->
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 rounded-2xl mb-4">
                            <i class="fas fa-book-reader text-blue-600 text-2xl"></i>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                        <p class="text-gray-500 text-sm">Sign in to PT Distribusi Buku dan Alat Tulis Nusantara</p>
                    </div>

                    <!-- Alert Messages -->
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center">
                            <i class="fas fa-check-circle mr-3"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-circle mr-3 mt-0.5"></i>
                                <div class="flex-1">
                                    @foreach($errors->all() as $error)
                                        <p class="text-sm">{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Work Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400 text-sm"></i>
                                </div>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}"
                                    placeholder="name@company.com"
                                    class="input-field w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 bg-gray-50 focus:bg-white"
                                    required
                                    autofocus
                                >
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400 text-sm"></i>
                                </div>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    placeholder="••••••••"
                                    class="input-field w-full pl-11 pr-12 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 bg-gray-50 focus:bg-white"
                                    required
                                >
                                <button 
                                    type="button" 
                                    onclick="togglePassword()"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                                >
                                    <i id="toggleIcon" class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center text-sm">
                            <label class="flex items-center cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="remember" 
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 cursor-pointer"
                                    {{ old('remember') ? 'checked' : '' }}
                                >
                                <span class="ml-2 text-gray-600">Remember me</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            class="btn-primary w-full py-3.5 text-white font-medium rounded-lg shadow-sm"
                        >
                            Sign in to Dashboard
                        </button>
                    </form>

                    <!-- Footer Link -->
                    <div class="mt-6 text-center text-sm text-gray-600">
                        Need access? 
                        <a href="#" class="font-semibold text-blue-600 hover:text-blue-700">
                            Contact System Administrator
                        </a>
                    </div>
                </div>

                <!-- Mobile Footer -->
                <div class="lg:hidden mt-8 text-center text-sm text-gray-500">
                    <p>&copy; {{ date('Y') }} PT Distribusi Buku dan Alat Tulis Nusantara</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
