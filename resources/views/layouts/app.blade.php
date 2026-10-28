<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ARIMA') - Universitas Sumatera Selatan</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN & FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        uss: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#1d4ed8',
                            600: '#1e40af',
                            700: '#1e3a8a',
                            800: '#172554',
                            gold: '#f59e0b',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: black !important; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col" x-data="{ sidebarOpen: false }">

    <!-- Top Navigation Bar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm no-print">
        <div class="px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            <!-- Left: Brand & Mobile toggle -->
            <div class="flex items-center space-x-3">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-500 hover:text-slate-700 p-2 rounded-lg">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-700 to-indigo-600 flex items-center justify-center text-white font-extrabold text-xl shadow-md shadow-blue-500/20">
                        A
                    </div>
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-blue-800 to-indigo-700 bg-clip-text text-transparent">ARIMA</span>
                            <span class="text-xs px-2 py-0.5 font-bold uppercase rounded bg-blue-100 text-blue-800 border border-blue-200">USS</span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-none hidden sm:block">Aplikasi Riset Internal & Pengabdian kepada Masyarakat</p>
                    </div>
                </a>
            </div>

            <!-- Right: Quick Role Switcher (For Demo/Testing), Notification, Profile -->
            <div class="flex items-center space-x-3 sm:space-x-4">
                <!-- Role Badge -->
                <div class="hidden md:flex items-center space-x-2 bg-slate-100 px-3 py-1.5 rounded-full border border-slate-200 text-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-slate-600 font-medium">Peran:</span>
                    <span class="font-bold text-blue-800">{{ Auth::user()->role_label }}</span>
                </div>

                <!-- Quick Switcher Dropdown (Seamless Testing of all 7 roles) -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-1.5 text-xs bg-blue-50 text-blue-700 hover:bg-blue-100 px-3 py-1.5 rounded-lg border border-blue-200 font-semibold transition">
                        <i class="fa-solid fa-shuffle text-blue-600"></i>
                        <span class="hidden sm:inline">Ganti Akun Demo</span>
                        <i class="fa-solid fa-chevron-down text-[10px]"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-slate-200 py-2 z-50">
                        <div class="px-3 py-1.5 border-b border-slate-100 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                            Pilih Akun Demo (7 Aktor):
                        </div>
                        <a href="{{ route('quick.login', 3) }}" class="flex items-center px-3 py-2 text-xs hover:bg-slate-50 transition {{ Auth::id() == 3 ? 'bg-blue-50 font-semibold' : '' }}">
                            <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center mr-2.5 font-bold">1</div>
                            <div>
                                <div class="text-slate-800">Dr. Ahmad Fauzi</div>
                                <div class="text-[11px] text-slate-500">Ketua Pengusul (Dosen)</div>
                            </div>
                        </a>
                        <a href="{{ route('quick.login', 4) }}" class="flex items-center px-3 py-2 text-xs hover:bg-slate-50 transition {{ Auth::id() == 4 ? 'bg-blue-50 font-semibold' : '' }}">
                            <div class="w-7 h-7 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center mr-2.5 font-bold">2</div>
                            <div>
                                <div class="text-slate-800">Siti Rahma, M.T.</div>
                                <div class="text-[11px] text-slate-500">Anggota Tim / Dosen</div>
                            </div>
                        </a>
                        <a href="{{ route('quick.login', 5) }}" class="flex items-center px-3 py-2 text-xs hover:bg-slate-50 transition {{ Auth::id() == 5 ? 'bg-blue-50 font-semibold' : '' }}">
                            <div class="w-7 h-7 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center mr-2.5 font-bold">3</div>
                            <div>
                                <div class="text-slate-800">Prof. Dr. Hendra Wijaya</div>
                                <div class="text-[11px] text-slate-500">Reviewer Internal 1</div>
                            </div>
                        </a>
                        <a href="{{ route('quick.login', 2) }}" class="flex items-center px-3 py-2 text-xs hover:bg-slate-50 transition {{ Auth::id() == 2 ? 'bg-blue-50 font-semibold' : '' }}">
                            <div class="w-7 h-7 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center mr-2.5 font-bold">4</div>
                            <div>
                                <div class="text-slate-800">LPPM USS</div>
                                <div class="text-[11px] text-slate-500">Admin Pengelola LPPM</div>
                            </div>
                        </a>
                        <a href="{{ route('quick.login', 7) }}" class="flex items-center px-3 py-2 text-xs hover:bg-slate-50 transition {{ Auth::id() == 7 ? 'bg-blue-50 font-semibold' : '' }}">
                            <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mr-2.5 font-bold">5</div>
                            <div>
                                <div class="text-slate-800">Bagian Keuangan</div>
                                <div class="text-[11px] text-slate-500">Pencairan Dana (70% & 30%)</div>
                            </div>
                        </a>
                        <a href="{{ route('quick.login', 8) }}" class="flex items-center px-3 py-2 text-xs hover:bg-slate-50 transition {{ Auth::id() == 8 ? 'bg-blue-50 font-semibold' : '' }}">
                            <div class="w-7 h-7 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center mr-2.5 font-bold">6</div>
                            <div>
                                <div class="text-slate-800">Prof. Dr. Ir. M. Yusuf (WR I)</div>
                                <div class="text-[11px] text-slate-500">Pimpinan Universitas</div>
                            </div>
                        </a>
                        <a href="{{ route('quick.login', 1) }}" class="flex items-center px-3 py-2 text-xs hover:bg-slate-50 transition {{ Auth::id() == 1 ? 'bg-blue-50 font-semibold' : '' }}">
                            <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center mr-2.5 font-bold">7</div>
                            <div>
                                <div class="text-slate-800">Administrator TI</div>
                                <div class="text-[11px] text-slate-500">Admin Sistem & TI</div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- User Profile & Logout -->
                <div class="relative" x-data="{ userMenu: false }">
                    <button @click="userMenu = !userMenu" class="flex items-center space-x-2 p-1.5 rounded-lg hover:bg-slate-100 transition">
                        <div class="w-8 h-8 rounded-full bg-blue-700 text-white flex items-center justify-center font-bold text-xs">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                        <span class="text-xs font-semibold text-slate-700 hidden lg:block">{{ Str::limit(Auth::user()->name, 18) }}</span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                    </button>
                    <div x-show="userMenu" @click.away="userMenu = false" x-cloak class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-200 py-2 z-50">
                        <div class="px-4 py-2 border-b border-slate-100">
                            <p class="text-xs font-bold text-slate-800">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-slate-500">{{ Auth::user()->email }}</p>
                            <p class="text-[10px] font-semibold text-blue-700 mt-1 uppercase">{{ Auth::user()->role_label }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="mt-1">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 flex items-center space-x-2 font-medium">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                <span>Keluar (Logout)</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container with Sidebar -->
    <div class="flex-1 flex overflow-hidden">
        <!-- Sidebar Navigation -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-64 bg-slate-900 text-slate-300 transform transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col no-print">
            <div class="p-4 border-b border-slate-800 flex items-center justify-between">
                <span class="text-xs font-bold tracking-wider uppercase text-slate-400">Menu Utama</span>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto text-sm">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg font-medium transition {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-gauge w-5 text-center mr-3 text-slate-400 {{ request()->routeIs('dashboard') ? 'text-white' : '' }}"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Tahap 1: Skema Hibah -->
                <a href="{{ route('schemes.index') }}" class="flex items-center px-3 py-2.5 rounded-lg font-medium transition {{ request()->routeIs('schemes.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-bullhorn w-5 text-center mr-3 text-slate-400 {{ request()->routeIs('schemes.*') ? 'text-white' : '' }}"></i>
                    <span>Skema Hibah</span>
                </a>

                <!-- Tahap 2: Pengusulan Proposal -->
                <a href="{{ route('proposals.index') }}" class="flex items-center px-3 py-2.5 rounded-lg font-medium transition {{ request()->routeIs('proposals.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-file-lines w-5 text-center mr-3 text-slate-400 {{ request()->routeIs('proposals.*') ? 'text-white' : '' }}"></i>
                    <span>Usulan Proposal</span>
                </a>

                @if(Auth::user()->isAdminLppm() || Auth::user()->isPimpinan())
                <!-- Tahap 3 & 5: Penetapan Pemenang & Ranking -->
                <a href="{{ route('lppm.ranking') }}" class="flex items-center px-3 py-2.5 rounded-lg font-medium transition {{ request()->routeIs('lppm.ranking') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-trophy w-5 text-center mr-3 text-slate-400 {{ request()->routeIs('lppm.ranking') ? 'text-white' : '' }}"></i>
                    <span>Penetapan Pemenang</span>
                </a>
                @endif

                @if(Auth::user()->isReviewer() || Auth::user()->isAdminLppm())
                <!-- Tahap 4: Review Substansi -->
                <a href="{{ route('reviews.index') }}" class="flex items-center px-3 py-2.5 rounded-lg font-medium transition {{ request()->routeIs('reviews.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-clipboard-check w-5 text-center mr-3 text-slate-400 {{ request()->routeIs('reviews.*') ? 'text-white' : '' }}"></i>
                    <span>Review Substansi</span>
                </a>
                @endif

                @if(Auth::user()->isKeuangan() || Auth::user()->isAdminLppm() || Auth::user()->isPimpinan())
                <!-- Tahap 7 & 12: Pencairan Dana -->
                <a href="{{ route('finance.index') }}" class="flex items-center px-3 py-2.5 rounded-lg font-medium transition {{ request()->routeIs('finance.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-money-bill-transfer w-5 text-center mr-3 text-slate-400 {{ request()->routeIs('finance.*') ? 'text-white' : '' }}"></i>
                    <span>Pencairan Dana</span>
                </a>
                @endif

                <!-- Tahap 9: Pelaksanaan & Monev -->
                <a href="{{ route('monitoring.index') }}" class="flex items-center px-3 py-2.5 rounded-lg font-medium transition {{ request()->routeIs('monitoring.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-line w-5 text-center mr-3 text-slate-400 {{ request()->routeIs('monitoring.*') ? 'text-white' : '' }}"></i>
                    <span>Monev Kemajuan</span>
                </a>

                <!-- Tahap 10 & 11: Laporan Akhir & Luaran -->
                <a href="{{ route('final-reports.index') }}" class="flex items-center px-3 py-2.5 rounded-lg font-medium transition {{ request()->routeIs('final-reports.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-box-archive w-5 text-center mr-3 text-slate-400 {{ request()->routeIs('final-reports.*') ? 'text-white' : '' }}"></i>
                    <span>Laporan Akhir & Luaran</span>
                </a>

                <!-- Tahap 13: Arsip & Rekapitulasi Data -->
                <a href="{{ route('reports.index') }}" class="flex items-center px-3 py-2.5 rounded-lg font-medium transition {{ request()->routeIs('reports.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-table-list w-5 text-center mr-3 text-slate-400 {{ request()->routeIs('reports.*') ? 'text-white' : '' }}"></i>
                    <span>Rekap & Laporan BKD</span>
                </a>

                @if(Auth::user()->isAdminTi() || Auth::user()->isAdminLppm())
                <div class="pt-3 pb-1">
                    <p class="px-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Administrasi Sistem</p>
                </div>
                <a href="{{ route('users.index') }}" class="flex items-center px-3 py-2.5 rounded-lg font-medium transition {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-users-gear w-5 text-center mr-3 text-slate-400 {{ request()->routeIs('users.*') ? 'text-white' : '' }}"></i>
                    <span>Manajemen Pengguna</span>
                </a>
                @endif
            </nav>

            <div class="p-4 border-t border-slate-800 text-xs text-slate-400">
                <div class="font-bold text-slate-300">ARIMA v1.0</div>
                <div>LPPM Universitas Sumatera Selatan</div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto">
                <!-- Flash Notification Messages -->
                @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start space-x-3 shadow-sm no-print">
                    <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-lg"></i>
                    <div class="flex-1 font-medium text-sm">{{ session('success') }}</div>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start space-x-3 shadow-sm no-print">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 text-lg"></i>
                    <div class="flex-1 font-medium text-sm">{{ session('error') }}</div>
                </div>
                @endif

                @if(session('warning'))
                <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 flex items-start space-x-3 shadow-sm no-print">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-0.5 text-lg"></i>
                    <div class="flex-1 font-medium text-sm">{{ session('warning') }}</div>
                </div>
                @endif

                @if(session('info'))
                <div class="mb-6 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 flex items-start space-x-3 shadow-sm no-print">
                    <i class="fa-solid fa-circle-info text-blue-600 mt-0.5 text-lg"></i>
                    <div class="flex-1 font-medium text-sm">{{ session('info') }}</div>
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
