<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - ARIMA USS</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 min-h-screen text-slate-800 font-sans flex items-center justify-center p-4 py-12">

    <div class="max-w-5xl w-full">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 text-white font-extrabold text-3xl shadow-xl shadow-blue-500/30 mb-3">
                A
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">ARIMA UNIVERSITAS SUMATERA SELATAN</h1>
            <p class="text-blue-200 text-sm mt-1">Aplikasi Riset Internal dan Pengabdian kepada Masyarakat</p>
            <p class="text-slate-400 text-xs mt-0.5">Adaptasi Alur & Tata Kelola Hibah Nasional BIMA / DRTPM</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left: Login Form -->
            <div class="lg:col-span-5 bg-white rounded-2xl shadow-2xl p-6 sm:p-8 border border-slate-100">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-slate-900">Masuk ke Sistem</h2>
                    <p class="text-slate-500 text-xs mt-1">Gunakan akun surel institusi Anda untuk mengakses layanan hibah internal.</p>
                </div>

                @if($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-xs">
                    {{ $errors->first() }}
                </div>
                @endif

                @if(session('info'))
                <div class="mb-4 p-3 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 text-xs">
                    {{ session('info') }}
                </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email</label>
                        <div class="relative">
                            <i class="fa-solid fa-envelope absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                            <input type="email" name="email" value="{{ old('email', 'dosen1@uss.ac.id') }}" required autofocus
                                class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                            <input type="password" name="password" value="password" required
                                class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs">
                        <label class="flex items-center space-x-2 text-slate-600">
                            <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span>Ingat saya</span>
                        </label>
                        <span class="text-slate-400">Password default: <code class="text-blue-600 font-bold">password</code></span>
                    </div>

                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-700 to-indigo-600 hover:from-blue-800 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 text-sm transition transform active:scale-95 flex items-center justify-center space-x-2">
                        <span>Masuk Sekarang</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </form>

                <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                    <p class="text-[11px] text-slate-400">Universitas Sumatera Selatan &bull; Lembaga Penelitian dan Pengabdian kepada Masyarakat</p>
                </div>
            </div>

            <!-- Right: Quick Demo Accounts (7 Roles in SKPL) -->
            <div class="lg:col-span-7 bg-slate-800/80 backdrop-blur-md rounded-2xl p-6 border border-slate-700 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold text-white flex items-center">
                            <i class="fa-solid fa-bolt text-amber-400 mr-2"></i>
                            Masuk Cepat Uji Coba (Demo 7 Peran)
                        </h3>
                        <p class="text-xs text-slate-300 mt-0.5">Klik salah satu kartu akun di bawah ini untuk langsung masuk tanpa mengetik kata sandi:</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($demoUsers as $user)
                    <a href="{{ route('quick.login', $user->id) }}" class="p-3.5 rounded-xl bg-slate-900/80 hover:bg-blue-900/60 border border-slate-700 hover:border-blue-400 transition group flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[10px] uppercase font-extrabold px-2 py-0.5 rounded
                                    @if($user->role === 'dosen') bg-blue-500/20 text-blue-300 border border-blue-500/30
                                    @elseif($user->role === 'reviewer') bg-purple-500/20 text-purple-300 border border-purple-500/30
                                    @elseif($user->role === 'admin_lppm') bg-amber-500/20 text-amber-300 border border-amber-500/30
                                    @elseif($user->role === 'keuangan') bg-emerald-500/20 text-emerald-300 border border-emerald-500/30
                                    @elseif($user->role === 'pimpinan') bg-rose-500/20 text-rose-300 border border-rose-500/30
                                    @else bg-slate-500/20 text-slate-300 border border-slate-500/30
                                    @endif">
                                    {{ $user->role_label }}
                                </span>
                                <i class="fa-solid fa-arrow-right-to-bracket text-xs text-slate-500 group-hover:text-blue-300 transition"></i>
                            </div>
                            <div class="font-bold text-sm text-white group-hover:text-blue-200 transition">{{ $user->name }}</div>
                            <div class="text-[11px] text-slate-400">{{ $user->email }}</div>
                        </div>
                        <div class="mt-2 pt-2 border-t border-slate-800 text-[10px] text-slate-400 flex items-center justify-between">
                            <span>{{ $user->fakultas ?? 'USS' }}</span>
                            <span class="text-blue-400 font-semibold group-hover:underline">Klik Masuk &rarr;</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</body>
</html>
