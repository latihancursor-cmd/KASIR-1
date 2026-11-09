@extends('layouts.kasirnih')

@section('title', 'Dashboard KasirNih')

@section('content')
    <!-- STATISTICS CARDS GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Card: Total Pendapatan Hari Ini -->
        <div class="bg-white rounded-2xl p-5 border border-kasir-border shadow-sm">
            <div class="flex items-start justify-between mb-3">
                <div class="w-12 h-12 rounded-xl bg-kasir-green flex items-center justify-center text-white text-xl">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
                <span class="text-xs font-semibold text-kasir-green bg-emerald-100 px-2.5 py-1 rounded-full">Hari Ini</span>
            </div>
            <p class="text-xs font-medium text-gray-400 mb-1 uppercase tracking-wide">Total Pendapatan</p>
            <p class="text-2xl font-extrabold text-kasir-green">Rp{{ number_format($todayRevenue ?? 0, 0, ',', '.') }}</p>
            <p class="text-xs text-emerald-600 mt-2">
                <i class="fa-solid fa-arrow-trend-up mr-1"></i> +Rp{{ number_format($todayProfit ?? 0, 0, ',', '.') }} profit
            </p>
        </div>

        <!-- Card: Jumlah Transaksi -->
        <div class="bg-white rounded-2xl p-5 border border-kasir-border shadow-sm">
            <div class="flex items-start justify-between mb-3">
                <div class="w-12 h-12 rounded-xl bg-blue-500 flex items-center justify-center text-white text-xl">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <span class="text-xs font-semibold text-blue-600 bg-blue-100 px-2.5 py-1 rounded-full">Transaksi</span>
            </div>
            <p class="text-xs font-medium text-gray-400 mb-1 uppercase tracking-wide">Jumlah Transaksi</p>
            <p class="text-2xl font-extrabold text-blue-600">{{ $todayCount ?? 0 }}</p>
            <p class="text-xs text-blue-600 mt-2">
                <i class="fa-solid fa-clock mr-1"></i> Rata-rata Rp{{ number_format($todayCount > 0 ? ($todayRevenue ?? 0) / $todayCount : 0, 0, ',', '.') }}/transaksi
            </p>
        </div>

        <!-- Card: Produk Terjual -->
        <div class="bg-white rounded-2xl p-5 border border-kasir-border shadow-sm">
            <div class="flex items-start justify-between mb-3">
                <div class="w-12 h-12 rounded-xl bg-amber-500 flex items-center justify-center text-white text-xl">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <span class="text-xs font-semibold text-amber-600 bg-amber-100 px-2.5 py-1 rounded-full">Produk</span>
            </div>
            <p class="text-xs font-medium text-gray-400 mb-1 uppercase tracking-wide">Jumlah Item Terjual</p>
            <p class="text-2xl font-extrabold text-amber-600">{{ $itemsSold ?? 0 }}</p>
            <p class="text-xs text-amber-600 mt-2">
                <i class="fa-solid fa-chart-simple mr-1"></i> {{ $todayCount > 0 ? number_format(($itemsSold ?? 0) / $todayCount, 1) : 0 }} item/order
            </p>
        </div>

        <!-- Card: Status Stok -->
        <div class="bg-white rounded-2xl p-5 border border-kasir-border shadow-sm">
            <div class="flex items-start justify-between mb-3">
                <div class="w-12 h-12 rounded-xl bg-purple-500 flex items-center justify-center text-white text-xl">
                    <i class="fa-solid fa-warehouse"></i>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-xs font-semibold text-purple-600 bg-purple-100 px-2.5 py-1 rounded-full mb-1">Stok</span>
                    <span class="text-xs font-semibold {{ $outOfStockProducts > 0 ? 'text-rose-600 bg-rose-100' : 'text-emerald-600 bg-emerald-100' }} px-2 py-0.5 rounded-full">
                        {{ $outOfStockProducts }} habis
                    </span>
                </div>
            </div>
            <p class="text-xs font-medium text-gray-400 mb-1 uppercase tracking-wide">Kontrol Stok</p>
            <p class="text-2xl font-extrabold text-purple-600">{{ $lowStockProducts ?? 0 }}</p>
            <p class="text-xs text-purple-600 mt-2">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ $lowStockProducts ?? 0 }} produk menipis
            </p>
        </div>
    </div>

    <!-- QUICK ACTIONS PANEL -->
    <div class="bg-white rounded-2xl p-5 border border-kasir-border shadow-sm mb-6">
        <h2 class="text-base font-bold text-kasir-text mb-4 flex items-center gap-2">
            <i class="fa-solid fa-bolt text-kasir-orange"></i> Quick Actions
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            <button type="button" onclick="document.getElementById('dailyCapitalModal').showModal()" class="flex flex-col items-center justify-center p-4 bg-kasir-bg hover:bg-gray-50 rounded-2xl transition text-center group border border-kasir-border">
                <div class="w-12 h-12 rounded-xl bg-kasir-green/10 group-hover:bg-kasir-green/20 flex items-center justify-center mb-2 text-kasir-green text-xl transition"><i class="fa-solid fa-vault"></i></div>
                <span class="text-xs font-semibold text-kasir-text">Modal Harian</span>
            </button>
            <a href="{{ route('kasir.penjualan') }}" class="flex flex-col items-center justify-center p-4 bg-kasir-bg hover:bg-gray-50 rounded-2xl transition text-center group border border-kasir-border">
                <div class="w-12 h-12 rounded-xl bg-kasir-green/10 group-hover:bg-kasir-green/20 flex items-center justify-center mb-2 text-kasir-green text-xl transition">
                    <i class="fa-solid fa-cart-plus"></i>
                </div>
                <span class="text-xs font-semibold text-kasir-text">Buat Transaksi</span>
            </a>
            <a href="{{ route('kasir.products.create') }}" class="flex flex-col items-center justify-center p-4 bg-kasir-bg hover:bg-gray-50 rounded-2xl transition text-center group border border-kasir-border">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 group-hover:bg-blue-500/20 flex items-center justify-center mb-2 text-blue-600 text-xl transition">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <span class="text-xs font-semibold text-kasir-text">Tambah Produk</span>
            </a>
            <a href="{{ route('kasir.stock') }}" class="flex flex-col items-center justify-center p-4 bg-kasir-bg hover:bg-gray-50 rounded-2xl transition text-center group border border-kasir-border">
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 group-hover:bg-amber-500/20 flex items-center justify-center mb-2 text-amber-600 text-xl transition">
                    <i class="fa-solid fa-arrow-up-arrow-down"></i>
                </div>
                <span class="text-xs font-semibold text-kasir-text">Atur Stok</span>
            </a>
            <a href="{{ route('kasir.reports') }}" class="flex flex-col items-center justify-center p-4 bg-kasir-bg hover:bg-gray-50 rounded-2xl transition text-center group border border-kasir-border">
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 group-hover:bg-purple-500/20 flex items-center justify-center mb-2 text-purple-600 text-xl transition">
                    <i class="fa-solid fa-chart-simple"></i>
                </div>
                <span class="text-xs font-semibold text-kasir-text">Lihat Laporan</span>
            </a>
        </div>
    </div>

    <!-- DAILY CAPITAL / CASH CLOSING MODAL -->
    <dialog id="dailyCapitalModal" class="w-full max-w-xl rounded-3xl p-0 shadow-2xl backdrop:bg-black/50">
        <div class="bg-white rounded-3xl overflow-hidden">
            <div class="bg-kasir-green text-white px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold">Modal Harian</h2>
                    <p class="text-xs text-white/70">{{ now()->format('d M Y') }}</p>
                </div>
                <button type="button" onclick="document.getElementById('dailyCapitalModal').close()" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <div class="p-5 space-y-4 max-h-[75vh] overflow-y-auto">
                <section class="rounded-2xl border border-kasir-border p-4">
                    <h3 class="text-xs font-bold uppercase text-kasir-green mb-3"><i class="fa-solid fa-building-columns mr-2"></i>Kas Awal</h3>
                    <p class="text-xl font-extrabold text-kasir-text">Rp{{ number_format($dailyCapital->initial_capital, 0, ',', '.') }}</p>
                </section>

                <section class="rounded-2xl border border-kasir-border p-4">
                    <h3 class="text-xs font-bold uppercase text-kasir-green mb-3"><i class="fa-solid fa-receipt mr-2"></i>Penjualan Hari Ini</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between text-gray-500"><span>Total transaksi</span><span>{{ $todayCount }}x</span></div>
                        <div class="flex justify-between text-gray-500"><span>Tunai</span><span>Rp{{ number_format($todayCashSales, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between text-gray-500"><span>QRIS</span><span>Rp{{ number_format($todayQrisSales, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between font-bold text-kasir-text border-t border-kasir-border pt-2"><span>Total penjualan</span><span>Rp{{ number_format($todayRevenue, 0, ',', '.') }}</span></div>
                    </div>
                </section>

                <section class="rounded-2xl border border-kasir-border p-4">
                    <h3 class="text-xs font-bold uppercase text-rose-600 mb-2"><i class="fa-solid fa-money-bill-transfer mr-2"></i>Pengeluaran</h3>
                    <p class="text-lg font-bold text-rose-600">Rp{{ number_format($todayExpenses, 0, ',', '.') }}</p>
                </section>

                <form method="POST" action="{{ route('kasir.daily-capital.update') }}" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <section class="rounded-2xl border border-kasir-border p-4 space-y-3">
                        <h3 class="text-xs font-bold uppercase text-kasir-green"><i class="fa-solid fa-calculator mr-2"></i>Hitung Fisik Uang</h3>
                        <p class="text-xs text-gray-500">Hitung uang tunai di laci kasir, lalu masukkan jumlahnya.</p>
                        <label class="block text-xs font-semibold text-kasir-text" for="initial_capital">Kas awal (Rp)</label>
                        <input id="initial_capital" name="initial_capital" type="number" min="0" step="1" required value="{{ old('initial_capital', $dailyCapital->initial_capital) }}" class="w-full rounded-xl border border-kasir-border px-3 py-2.5 text-sm focus:ring-2 focus:ring-kasir-green/20 outline-none">
                        <label class="block text-xs font-semibold text-kasir-text" for="expenses">Pengeluaran hari ini (Rp)</label>
                        <input id="expenses" name="expenses" type="number" min="0" step="1" required value="{{ old('expenses', $dailyCapital->expenses) }}" class="w-full rounded-xl border border-kasir-border px-3 py-2.5 text-sm focus:ring-2 focus:ring-kasir-green/20 outline-none">
                        <label class="block text-xs font-semibold text-kasir-text" for="counted_cash">Jumlah uang aktual (Rp)</label>
                        <input id="counted_cash" name="counted_cash" type="number" min="0" step="1" value="{{ old('counted_cash', $dailyCapital->counted_cash) }}" placeholder="0" class="w-full rounded-xl border border-kasir-border px-3 py-2.5 text-sm focus:ring-2 focus:ring-kasir-green/20 outline-none">
                        <label class="block text-xs font-semibold text-kasir-text" for="daily_notes">Catatan penutupan (opsional)</label>
                        <textarea id="daily_notes" name="notes" maxlength="500" rows="3" placeholder="Catatan" class="w-full rounded-xl border border-kasir-border px-3 py-2.5 text-sm focus:ring-2 focus:ring-kasir-green/20 outline-none">{{ old('notes', $dailyCapital->notes) }}</textarea>
                    </section>
                    <div class="flex gap-3">
                        <button type="button" onclick="document.getElementById('dailyCapitalModal').close()" class="flex-1 py-3 rounded-xl border border-kasir-border font-semibold text-sm">Tutup</button>
                        <button type="submit" class="flex-1 py-3 rounded-xl bg-kasir-orange text-white font-bold text-sm"><i class="fa-solid fa-floppy-disk mr-1"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </dialog>

    <!-- TWO COLUMNS: TRANSAKSI TERBARU & STATUS PRODUK -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <!-- TRANSAKSI TERBARU -->
        <div class="bg-white rounded-2xl border border-kasir-border shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold text-kasir-text flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-kasir-orange"></i> Transaksi Terbaru
                </h2>
                <a href="{{ route('kasir.history') }}" class="text-xs font-semibold text-kasir-orange hover:text-kasir-darkOrange transition flex items-center gap-1">
                    Lihat Semua <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
            <div class="space-y-3 max-h-[340px] overflow-y-auto pr-1">
                @forelse($lastTransactions as $trx)
                <div class="flex items-center justify-between p-3 bg-kasir-bg rounded-xl border border-kasir-border/40 hover:border-kasir-orange/40 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-kasir-green/10 flex items-center justify-center text-kasir-green">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-kasir-text">{{ $trx->product->name ?? 'Produk' }}</p>
                            <p class="text-xs text-gray-500">{{ $trx->created_at->format('H:i') }} • {{ $trx->quantity }}x item • {{ $trx->payment_method }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-sm text-kasir-green">Rp{{ number_format($trx->total_price, 0, ',', '.') }}</p>
                        <span class="text-xs text-emerald-600 font-medium">
                            <i class="fa-solid fa-circle-check"></i> Berhasil
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 text-gray-400">
                    <i class="fa-solid fa-receipt text-3xl mb-2 text-gray-300"></i>
                    <p class="text-sm">Belum ada transaksi hari ini</p>
                    <p class="text-xs mt-1 text-gray-400">Mulai transaksi pertama Anda!</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- NOTIFIKASI & STATUS STOK -->
        <div class="bg-white rounded-2xl border border-kasir-border shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold text-kasir-text flex items-center gap-2">
                    <i class="fa-solid fa-bell text-kasir-orange"></i> Notifikasi & Status
                </h2>
                <span class="text-xs font-semibold text-gray-500">{{ now()->format('d M Y') }}</span>
            </div>
            <div class="space-y-3">
                <!-- Closing Reminder -->
                @if($settings->closing_reminder)
                <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-sm text-kasir-text mb-0.5">Pengingat Tutup Kasir</p>
                            <p class="text-xs text-gray-600">Toko akan tutup pada jam {{ $settings->closing_time }}.</p>
                        </div>
                        <button class="text-xs font-semibold text-amber-600 hover:text-amber-800 shrink-0">
                            Atur
                        </button>
                    </div>
                </div>
                @endif

                <!-- Low Stock Warning -->
                @if($lowStockProducts > 0)
                <div class="p-3 bg-orange-50 border border-orange-200 rounded-xl">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 shrink-0">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-sm text-kasir-text mb-0.5">Stok Menipis</p>
                            <p class="text-xs text-gray-600">Ada {{ $lowStockProducts }} produk yang stoknya menipis.</p>
                        </div>
                        <a href="{{ route('kasir.stock') }}?filter=menipis" class="text-xs font-semibold text-orange-600 hover:text-orange-800 shrink-0">
                            Cek
                        </a>
                    </div>
                </div>
                @endif

                <!-- Out of Stock Warning -->
                @if($outOfStockProducts > 0)
                <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 shrink-0">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-sm text-kasir-text mb-0.5">Stok Habis</p>
                            <p class="text-xs text-gray-600">Ada {{ $outOfStockProducts }} produk yang stoknya habis.</p>
                        </div>
                        <a href="{{ route('kasir.stock') }}?filter=habis" class="text-xs font-semibold text-rose-600 hover:text-rose-800 shrink-0">
                            Cek
                        </a>
                    </div>
                </div>
                @endif

                <!-- No Notification Placeholder -->
                @if($lowStockProducts == 0 && $outOfStockProducts == 0 && !$settings->closing_reminder)
                <div class="text-center py-8 text-gray-400">
                    <i class="fa-solid fa-bell-slash text-2xl mb-2 text-gray-300"></i>
                    <p class="text-sm">Tidak ada notifikasi</p>
                    <p class="text-xs mt-1 text-gray-400">Semua baik-baik saja!</p>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection