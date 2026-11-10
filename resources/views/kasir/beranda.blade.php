<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Purr'Coffee - Beranda Kasir</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            bg: '#E3F2FD',
                            sidebar: '#E3F2FD',
                            card: '#FFFFFF',
                            accent: '#54B5C1',
                            accentHover: '#3A9EAA',
                            brown: '#1B0D80',
                            darkBrown: '#14095F',
                            lightBg: '#8ACBD0',
                            tag: '#D9EFF0',
                            border: '#8ACBD0'
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js for Statistics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #8ACBD0; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #54B5C1; }
        
        /* Shimmer Loading Animation */
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        .shimmer {
            animation: shimmer 2s infinite linear;
            background: linear-gradient(to right, #E3F2FD 4%, #E8DEC9 25%, #E3F2FD 36%);
            background-size: 1000px 100%;
        }
    </style>
</head>
<body class="bg-[#F6EFE6] text-[#4A3B32] min-h-screen flex items-center justify-center p-3 md:p-6">

    <!-- Container Utama Beranda -->
    <div class="w-full max-w-[1440px] h-[92vh] min-h-[720px] bg-[#E3F2FD] rounded-3xl shadow-2xl border border-brand-border flex overflow-hidden" x-data="berandaApp()">
        
        <!-- SIDEBAR NAVIGATION (Sama seperti Menu) -->
        <aside class="w-64 bg-[#F5ECE1] border-r border-brand-border flex flex-col justify-between p-5 shrink-0 hidden md:flex">
            <div>
                <!-- Brand Logo -->
                <div class="flex items-center gap-3 px-2 mb-8">
                    <div class="w-10 h-10 rounded-2xl bg-brand-accent/20 flex items-center justify-center text-brand-accent text-xl shadow-sm">
                        <i class="fa-solid fa-mug-hot"></i>
                    </div>
                    <div>
                        <span class="font-bold text-xl text-[#3A2B22] block leading-tight">Purr'Coffee</span>
                        <span class="text-[10px] text-gray-500 tracking-wider uppercase font-semibold">Smart POS System</span>
                    </div>
                </div>

                <!-- Nav Menu Items -->
                <nav class="space-y-1.5">
                    <a href="{{ route('kasir.beranda') }}" class="flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-semibold bg-[#8ACBD0] text-[#1B0D80] shadow-sm">
                        <div class="flex items-center gap-3.5">
                            <i class="fa-solid fa-house w-5 text-center text-brand-darkBrown"></i> Beranda
                        </div>
                    </a>
                    <a href="{{ route('kasir.index') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-medium text-gray-600 hover:bg-brand-tag transition">
                        <i class="fa-solid fa-utensils w-5 text-center text-gray-400"></i> Menu Utama
                    </a>
                    <a href="#" class="flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-medium text-gray-600 hover:bg-brand-tag transition">
                        <div class="flex items-center gap-3.5">
                            <i class="fa-solid fa-cart-shopping w-5 text-center text-gray-400"></i> Pesanan Active
                        </div>
                        <span class="w-5 h-5 rounded-full bg-brand-accent text-white text-xs flex items-center justify-center font-bold shadow-xs">{{ $todayCount ?? 0 }}</span>
                    </a>
                    <a href="#" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-medium text-gray-600 hover:bg-brand-tag transition">
                        <i class="fa-solid fa-clock-rotate-left w-5 text-center text-gray-400"></i> Riwayat
                    </a>
                    <a href="#" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-medium text-gray-600 hover:bg-brand-tag transition">
                        <i class="fa-solid fa-chart-line w-5 text-center text-gray-400"></i> Laporan
                    </a>
                    <a href="#" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-medium text-gray-600 hover:bg-brand-tag transition">
                        <i class="fa-solid fa-gear w-5 text-center text-gray-400"></i> Pengaturan
                    </a>
                </nav>
            </div>

            <!-- Footer Sidebar -->
            <div class="space-y-2 pt-4 border-t border-brand-border/60">
                <a href="#" class="flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-sm font-medium text-gray-600 hover:bg-brand-tag transition">
                    <i class="fa-regular fa-heart w-5 text-center text-rose-500"></i> Donasi Shelter
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-sm font-medium text-gray-600 hover:bg-rose-50 hover:text-rose-600 transition">
                        <i class="fa-solid fa-right-from-bracket w-5 text-center"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT BERANDA -->
        <main class="flex-1 flex flex-col min-w-0 bg-[#E3F2FD] overflow-hidden">
            
            <!-- HEADER GREETING -->
            <header class="p-6 pb-4 border-b border-brand-border/40">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-[#3A2B22] mb-1">
                            Selamat {{ now()->setTimezone('Asia/Jakarta')->hour < 11 ? 'Pagi' : (now()->setTimezone('Asia/Jakarta')->hour < 15 ? 'Siang' : (now()->setTimezone('Asia/Jakarta')->hour < 18 ? 'Sore' : 'Malam')) }}, {{ Auth::user()->name }}!
                        </h1>
                        <p class="text-sm text-gray-500">Dashboard Kasir Purr'Coffee - {{ now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs text-gray-500">Shift Aktif</p>
                            <p class="text-sm font-bold text-brand-darkBrown" x-text="currentTime"></p>
                        </div>
                        <a href="{{ route('kasir.index') }}" class="px-5 py-2.5 bg-brand-accent hover:bg-brand-accentHover text-white font-semibold text-sm rounded-full shadow-sm transition flex items-center gap-2">
                            <i class="fa-solid fa-plus"></i> Transaksi Baru
                        </a>
                    </div>
                </div>
            </header>

            <!-- DASHBOARD CONTENT SCROLLABLE -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6">
                
                <!-- STATISTICS CARDS ROW -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Card 1: Pendapatan Hari Ini -->
                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl p-5 border border-emerald-200/60 shadow-sm">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-12 h-12 rounded-xl bg-emerald-500 flex items-center justify-center text-white text-xl shadow-sm">
                                <i class="fa-solid fa-sack-dollar"></i>
                            </div>
                            <span class="text-xs font-semibold text-emerald-700 bg-emerald-200/60 px-2.5 py-1 rounded-full">Hari Ini</span>
                        </div>
                        <h3 class="text-xs font-medium text-emerald-700 mb-1 uppercase tracking-wide">Pendapatan</h3>
                        <p class="text-2xl font-extrabold text-emerald-900">Rp{{ number_format($todayRevenue ?? 0, 0, ',', '.') }}</p>
                        <p class="text-xs text-emerald-600 mt-2">
                            <i class="fa-solid fa-arrow-trend-up"></i> +12.5% vs kemarin
                        </p>
                    </div>

                    <!-- Card 2: Total Transaksi -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-5 border border-blue-200/60 shadow-sm">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-12 h-12 rounded-xl bg-blue-500 flex items-center justify-center text-white text-xl shadow-sm">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <span class="text-xs font-semibold text-blue-700 bg-blue-200/60 px-2.5 py-1 rounded-full">Transaksi</span>
                        </div>
                        <h3 class="text-xs font-medium text-blue-700 mb-1 uppercase tracking-wide">Total Order</h3>
                        <p class="text-2xl font-extrabold text-blue-900">{{ $todayCount ?? 0 }}</p>
                        <p class="text-xs text-blue-600 mt-2">
                            <i class="fa-solid fa-clock"></i> Rata-rata Rp{{ number_format($todayCount > 0 ? ($todayRevenue ?? 0) / $todayCount : 0, 0, ',', '.') }}/transaksi
                        </p>
                    </div>

                    <!-- Card 3: Item Terjual -->
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-2xl p-5 border border-amber-200/60 shadow-sm">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-12 h-12 rounded-xl bg-amber-500 flex items-center justify-center text-white text-xl shadow-sm">
                                <i class="fa-solid fa-box-open"></i>
                            </div>
                            <span class="text-xs font-semibold text-amber-700 bg-amber-200/60 px-2.5 py-1 rounded-full">Items</span>
                        </div>
                        <h3 class="text-xs font-medium text-amber-700 mb-1 uppercase tracking-wide">Produk Terjual</h3>
                        <p class="text-2xl font-extrabold text-amber-900">{{ $itemsSold ?? 0 }}</p>
                        <p class="text-xs text-amber-600 mt-2">
                            <i class="fa-solid fa-chart-simple"></i> {{ $todayCount > 0 ? number_format(($itemsSold ?? 0) / $todayCount, 1) : 0 }} item/order
                        </p>
                    </div>

                    <!-- Card 4: Target Harian -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-5 border border-purple-200/60 shadow-sm">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-12 h-12 rounded-xl bg-purple-500 flex items-center justify-center text-white text-xl shadow-sm">
                                <i class="fa-solid fa-bullseye"></i>
                            </div>
                            <span class="text-xs font-semibold text-purple-700 bg-purple-200/60 px-2.5 py-1 rounded-full">Target</span>
                        </div>
                        <h3 class="text-xs font-medium text-purple-700 mb-1 uppercase tracking-wide">Progress Target</h3>
                        <p class="text-2xl font-extrabold text-purple-900">{{ min(100, round((($todayRevenue ?? 0) / 1000000) * 100)) }}%</p>
                        <div class="w-full bg-purple-200 rounded-full h-2 mt-2">
                            <div class="bg-purple-600 h-2 rounded-full transition-all duration-500" style="width: {{ min(100, round((($todayRevenue ?? 0) / 1000000) * 100)) }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- QUICK ACTIONS ROW -->
                <div class="bg-white rounded-2xl border border-brand-border shadow-sm p-5">
                    <h2 class="text-base font-bold text-[#3A2B22] mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-brand-accent"></i> Quick Actions
                    </h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <a href="{{ route('kasir.index') }}" class="flex flex-col items-center justify-center p-4 bg-[#F7F1E8] hover:bg-brand-tag rounded-2xl transition text-center group">
                            <div class="w-12 h-12 rounded-xl bg-brand-accent/10 group-hover:bg-brand-accent/20 flex items-center justify-center mb-2 text-brand-accent text-xl transition">
                                <i class="fa-solid fa-cash-register"></i>
                            </div>
                            <span class="text-xs font-semibold text-gray-700">Buat Transaksi</span>
                        </a>
                        <button @click="alert('Fitur dalam pengembangan')" class="flex flex-col items-center justify-center p-4 bg-[#F7F1E8] hover:bg-brand-tag rounded-2xl transition text-center group">
                            <div class="w-12 h-12 rounded-xl bg-blue-500/10 group-hover:bg-blue-500/20 flex items-center justify-center mb-2 text-blue-600 text-xl transition">
                                <i class="fa-solid fa-file-invoice"></i>
                            </div>
                            <span class="text-xs font-semibold text-gray-700">Cetak Laporan</span>
                        </button>
                        <button @click="alert('Fitur dalam pengembangan')" class="flex flex-col items-center justify-center p-4 bg-[#F7F1E8] hover:bg-brand-tag rounded-2xl transition text-center group">
                            <div class="w-12 h-12 rounded-xl bg-purple-500/10 group-hover:bg-purple-500/20 flex items-center justify-center mb-2 text-purple-600 text-xl transition">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <span class="text-xs font-semibold text-gray-700">Cek Stok</span>
                        </button>
                        <button @click="alert('Fitur dalam pengembangan')" class="flex flex-col items-center justify-center p-4 bg-[#F7F1E8] hover:bg-brand-tag rounded-2xl transition text-center group">
                            <div class="w-12 h-12 rounded-xl bg-rose-500/10 group-hover:bg-rose-500/20 flex items-center justify-center mb-2 text-rose-600 text-xl transition">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <span class="text-xs font-semibold text-gray-700">Kelola Member</span>
                        </button>
                    </div>
                </div>

                <!-- TWO COLUMNS: TRANSAKSI TERBARU & PRODUK TERLARIS -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    
                    <!-- TRANSAKSI TERBARU -->
                    <div class="bg-white rounded-2xl border border-brand-border shadow-sm p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-base font-bold text-[#3A2B22] flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-brand-accent"></i> Transaksi Terbaru
                            </h2>
                            <a href="#" class="text-xs font-semibold text-brand-accent hover:text-brand-accentHover transition">Lihat Semua</a>
                        </div>
                        <div class="space-y-2.5 max-h-[320px] overflow-y-auto">
                            @forelse($lastTransactions ?? [] as $trx)
                            <div class="flex items-center justify-between p-3 bg-[#FFFFFF] rounded-xl border border-brand-border/40 hover:border-brand-accent/40 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-[#F7F1E8] flex items-center justify-center text-brand-brown">
                                        <i class="fa-solid fa-receipt"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm text-[#3A2B22]">{{ $trx->product->name ?? 'Produk' }}</p>
                                        <p class="text-xs text-gray-500">{{ $trx->created_at->format('H:i') }} • {{ $trx->quantity }}x item</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-sm text-brand-brown">Rp{{ number_format($trx->total_price, 0, ',', '.') }}</p>
                                    <span class="text-xs text-emerald-600 font-medium">
                                        <i class="fa-solid fa-circle-check"></i> Selesai
                                    </span>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-10 text-gray-400">
                                <i class="fa-solid fa-inbox text-3xl mb-2 text-gray-300"></i>
                                <p class="text-sm">Belum ada transaksi hari ini</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- PRODUK TERLARIS -->
                    <div class="bg-white rounded-2xl border border-brand-border shadow-sm p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-base font-bold text-[#3A2B22] flex items-center gap-2">
                                <i class="fa-solid fa-fire text-orange-500"></i> Produk Terlaris
                            </h2>
                            <span class="text-xs font-semibold text-gray-500">Top 5</span>
                        </div>
                        <div class="space-y-2.5">
                            @foreach($topProducts ?? [] as $index => $prod)
                            <div class="flex items-center justify-between p-3 bg-[#FFFFFF] rounded-xl border border-brand-border/40">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg {{ $index === 0 ? 'bg-amber-500' : ($index === 1 ? 'bg-gray-400' : ($index === 2 ? 'bg-amber-700' : 'bg-[#F7F1E8]')) }} flex items-center justify-center text-white font-bold text-sm">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm text-[#3A2B22]">{{ $prod->product->name ?? 'Produk' }}</p>
                                        <p class="text-xs text-gray-500">{{ $prod->total_qty }} terjual • Rp{{ number_format($prod->product->price ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-sm text-emerald-600">Rp{{ number_format($prod->total_revenue, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>

            </div>
        </main>

    </div>

    <!-- Alpine.js Logic -->
    <script>
        function berandaApp() {
            return {
                currentTime: '',
                
                init() {
                    this.updateClock();
                    setInterval(() => this.updateClock(), 1000);
                },

                updateClock() {
                    const now = new Date();
                    this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                }
            }
        }
    </script>
</body>
</html>