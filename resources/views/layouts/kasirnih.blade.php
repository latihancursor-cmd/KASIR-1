<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KasirNih - @yield('title', 'Sistem Kasir')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        kasir: {
                            green: '#006A4E',
                            darkGreen: '#004D38',
                            bg: '#FAF5EE',
                            card: '#FFFFFF',
                            orange: '#E6A15C',
                            darkOrange: '#D48F4A',
                            border: '#E8DEC9',
                            text: '#2D3748',
                            grayText: '#718096'
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FAF5EE;
            color: #2D3748;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #E8DEC9; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #E6A15C; }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between pb-20 md:pb-0">

    <!-- TOP HEADER MOBILE & DESKTOP HEADER -->
    <header class="bg-kasir-green text-white sticky top-0 z-30 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            
            <!-- Left Header: Back button if sub-page OR Logo & Greeting -->
            <div class="flex items-center gap-3">
                @hasSection('back_url')
                    <a href="@yield('back_url')" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                @endif

                <div>
                    @hasSection('page_title')
                        <h1 class="text-xl font-bold tracking-tight">@yield('page_title')</h1>
                    @else
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center font-bold text-lg">K</div>
                            <div>
                                <p class="text-[11px] text-emerald-100 font-medium">Selamat {{ now()->hour < 12 ? 'pagi' : (now()->hour < 18 ? 'siang' : 'sore') }}</p>
                                <h1 class="text-base font-extrabold leading-tight">{{ $settings->store_name ?? 'KasirNih' }}</h1>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Header Actions (Desktop Nav & Quick icons) -->
            <div class="flex items-center gap-4">
                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-1 bg-kasir-darkGreen/40 p-1 rounded-xl">
                    <a href="{{ route('kasir.beranda') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request()->routeIs('kasir.beranda') ? 'bg-white text-kasir-green shadow-xs' : 'text-emerald-100 hover:text-white' }}">
                        <i class="fa-solid fa-house mr-1"></i> Beranda
                    </a>
                    <a href="{{ route('kasir.penjualan') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request()->routeIs('kasir.penjualan') ? 'bg-white text-kasir-green shadow-xs' : 'text-emerald-100 hover:text-white' }}">
                        <i class="fa-solid fa-cart-shopping mr-1"></i> Penjualan
                    </a>
                    @if(auth()->user()?->hasRole('owner'))
                    <a href="{{ route('kasir.products') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request()->routeIs('kasir.products*') || request()->routeIs('kasir.stock*') ? 'bg-white text-kasir-green shadow-xs' : 'text-emerald-100 hover:text-white' }}"><i class="fa-solid fa-box mr-1"></i> Produk</a>
                    @else
                    <span title="Khusus owner" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-emerald-100/50 cursor-not-allowed"><i class="fa-solid fa-box mr-1"></i> Produk <i class="fa-solid fa-lock ml-1 text-[9px]"></i></span>
                    @endif
                    <a href="{{ route('kasir.history') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request()->routeIs('kasir.history') ? 'bg-white text-kasir-green shadow-xs' : 'text-emerald-100 hover:text-white' }}">
                        <i class="fa-solid fa-receipt mr-1"></i> Riwayat
                    </a>
                    @if(auth()->user()?->hasRole('owner'))
                    <a href="{{ route('kasir.reports') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request()->routeIs('kasir.reports') ? 'bg-white text-kasir-green shadow-xs' : 'text-emerald-100 hover:text-white' }}"><i class="fa-solid fa-chart-pie mr-1"></i> Laporan</a>
                    @else
                    <span title="Khusus owner" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-emerald-100/50 cursor-not-allowed"><i class="fa-solid fa-chart-pie mr-1"></i> Laporan <i class="fa-solid fa-lock ml-1 text-[9px]"></i></span>
                    @endif
                </nav>

                <!-- Quick Buttons: Settings & Notifications -->
                @if(auth()->user()?->hasRole('owner'))
                <a href="{{ route('kasir.settings') }}" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition relative" title="Pengaturan"><i class="fa-solid fa-gear"></i></a>
                @else
                <span class="w-9 h-9 rounded-full bg-white/5 flex items-center justify-center text-white/40 cursor-not-allowed" title="Pengaturan khusus owner"><i class="fa-solid fa-gear"></i><i class="fa-solid fa-lock absolute text-[8px] mt-5 ml-5"></i></span>
                @endif
                
                <!-- Reminder Closing Bell -->
                <button onclick="checkClosingStatus()" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition relative" title="Pengingat tutup kasir">
                    <i class="fa-solid fa-bell"></i>
                    @if(isset($settings) && $settings->closing_reminder)<span class="w-2.5 h-2.5 rounded-full bg-kasir-orange absolute top-1 right-1"></span>@endif
                </button>
                <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                    @csrf
                    <button type="submit" class="h-9 px-3 rounded-xl bg-white/10 hover:bg-rose-500 text-white text-xs font-semibold transition flex items-center gap-2" title="Keluar">
                        <i class="fa-solid fa-right-from-bracket"></i><span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- ALERT NOTIFIKASI SUKSES / ERROR -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-emerald-100 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-2xl flex items-center justify-between text-sm shadow-xs">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800"><i class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-rose-100 border border-rose-300 text-rose-800 px-4 py-3 rounded-2xl flex items-center justify-between text-sm shadow-xs">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800"><i class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
    @endif

    <!-- MAIN CONTENT -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-5">
        @yield('content')
    </main>

    <!-- BOTTOM NAVIGATION (khusus Tampilan Mobile) -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-kasir-border z-40 md:hidden shadow-lg">
        <div class="grid {{ auth()->user()?->hasRole('owner') ? 'grid-cols-6' : 'grid-cols-4' }} h-16">
            <a href="{{ route('kasir.beranda') }}" class="flex flex-col items-center justify-center transition {{ request()->routeIs('kasir.beranda') ? 'text-kasir-green font-bold' : 'text-gray-400 hover:text-gray-600' }}">
                <div class="{{ request()->routeIs('kasir.beranda') ? 'bg-kasir-green text-white w-10 h-10 rounded-2xl flex items-center justify-center shadow-xs' : '' }}">
                    <i class="fa-solid fa-house text-lg"></i>
                </div>
                <span class="text-[10px] mt-0.5">Beranda</span>
            </a>

            <a href="{{ route('kasir.penjualan') }}" class="flex flex-col items-center justify-center transition {{ request()->routeIs('kasir.penjualan') ? 'text-kasir-green font-bold' : 'text-gray-400 hover:text-gray-600' }}">
                <div class="{{ request()->routeIs('kasir.penjualan') ? 'bg-kasir-green text-white w-10 h-10 rounded-2xl flex items-center justify-center shadow-xs' : '' }}">
                    <i class="fa-solid fa-bag-shopping text-lg"></i>
                </div>
                <span class="text-[10px] mt-0.5">Penjualan</span>
            </a>

            @if(auth()->user()?->hasRole('owner'))
            <a href="{{ route('kasir.stock') }}" class="flex flex-col items-center justify-center transition {{ request()->routeIs('kasir.stock*') || request()->routeIs('kasir.products*') ? 'text-kasir-green font-bold' : 'text-gray-400 hover:text-gray-600' }}"><div class="{{ request()->routeIs('kasir.stock*') || request()->routeIs('kasir.products*') ? 'bg-kasir-green text-white w-10 h-10 rounded-2xl flex items-center justify-center shadow-xs' : '' }}"><i class="fa-solid fa-box text-lg"></i></div><span class="text-[10px] mt-0.5">Stok</span></a>
            @else
            <span class="flex flex-col items-center justify-center text-gray-300 cursor-not-allowed" title="Khusus owner"><i class="fa-solid fa-box text-lg"></i><span class="text-[10px] mt-0.5">Stok <i class="fa-solid fa-lock text-[8px]"></i></span></span>
            @endif

            <a href="{{ route('kasir.history') }}" class="flex flex-col items-center justify-center transition {{ request()->routeIs('kasir.history') ? 'text-kasir-green font-bold' : 'text-gray-400 hover:text-gray-600' }}">
                <div class="{{ request()->routeIs('kasir.history') ? 'bg-kasir-green text-white w-10 h-10 rounded-2xl flex items-center justify-center shadow-xs' : '' }}">
                    <i class="fa-solid fa-receipt text-lg"></i>
                </div>
                <span class="text-[10px] mt-0.5">Riwayat</span>
            </a>

            @if(auth()->user()?->hasRole('owner'))
            <a href="{{ route('kasir.reports') }}" class="flex flex-col items-center justify-center transition {{ request()->routeIs('kasir.reports') ? 'text-kasir-green font-bold' : 'text-gray-400 hover:text-gray-600' }}"><div class="{{ request()->routeIs('kasir.reports') ? 'bg-kasir-green text-white w-10 h-10 rounded-2xl flex items-center justify-center shadow-xs' : '' }}"><i class="fa-solid fa-chart-simple text-lg"></i></div><span class="text-[10px] mt-0.5">Laporan</span></a>
            @else
            <span class="flex flex-col items-center justify-center text-gray-300 cursor-not-allowed" title="Khusus owner"><i class="fa-solid fa-chart-simple text-lg"></i><span class="text-[10px] mt-0.5">Laporan <i class="fa-solid fa-lock text-[8px]"></i></span></span>
            @endif

            <form method="POST" action="{{ route('logout') }}" class="flex flex-col items-center justify-center">
                @csrf
                <button type="submit" class="flex flex-col items-center justify-center text-gray-400 hover:text-rose-600 transition" aria-label="Keluar">
                    <i class="fa-solid fa-right-from-bracket text-lg"></i>
                    <span class="text-[10px] mt-0.5">Keluar</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        function checkClosingStatus() {
            alert('Status Tutup Kasir Aktif.\nJam pengingat: {{ $settings->closing_time ?? "21:00" }}');
        }
    </script>
    @yield('scripts')
</body>
</html>