<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | PT Buku & ATK Nusantara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 text-white min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center space-y-6 bg-slate-800/80 backdrop-blur-md p-8 rounded-2xl border border-slate-700/80 shadow-2xl">
        <div class="w-20 h-20 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-full flex items-center justify-center mx-auto text-3xl">
            <i class="fas fa-shield-halved"></i>
        </div>
        
        <div class="space-y-2">
            <span class="inline-block px-3 py-1 bg-rose-500/20 text-rose-300 text-xs font-semibold rounded-full uppercase tracking-wider">
                Error 403 • Forbidden
            </span>
            <h1 class="text-2xl font-bold text-white">Akses Ditolak</h1>
            <p class="text-slate-400 text-sm leading-relaxed">
                {{ $exception->getMessage() ?: 'Anda tidak memiliki hak akses atau izin (permission) yang cukup untuk membuka sumber daya atau halaman ini.' }}
            </p>
        </div>

        <div class="pt-4 border-t border-slate-700/60 flex flex-col sm:flex-row gap-3 justify-center">
            @php
                $user = auth()->user();
                $homeRoute = route('dashboard');
                if ($user) {
                    if ($user->role === 'Cashier') {
                        $homeRoute = route('cashier.index');
                    } elseif ($user->role === 'Warehouse Manager') {
                        $homeRoute = route('warehouse.index');
                    }
                }
            @endphp
            <a href="{{ $homeRoute }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-blue-500/20">
                <i class="fas fa-home"></i> Kembali ke Dashboard
            </a>
            <button onclick="window.history.back()" class="px-5 py-2.5 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-semibold rounded-xl transition flex items-center justify-center gap-2">
                <i class="fas fa-arrow-left"></i> Halaman Sebelumnya
            </button>
        </div>
    </div>
</body>
</html>
