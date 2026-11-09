@extends('layouts.kasirnih')

@section('title', 'Riwayat Transaksi')
@section('page_title', 'Riwayat')

@section('content')
    <!-- SUMMARY CARD (Hijau / Krem sesuai screenshot) -->
    <div class="bg-kasir-green text-white rounded-3xl p-6 shadow-md mb-5">
        <div class="bg-[#FAF5EE] text-kasir-text rounded-2xl p-5 shadow-xs mb-4">
            <p class="text-xs text-gray-500 font-medium mb-1">Pendapatan • {{ str_replace('_', ' ', $period) }}</p>
            <p class="text-3xl font-extrabold text-kasir-green mb-4">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
            
            <div class="grid grid-cols-3 gap-2 border-t border-kasir-border/60 pt-3 text-center">
                <div>
                    <p class="text-[11px] text-gray-400 font-medium">Transaksi</p>
                    <p class="text-base font-bold text-kasir-text">{{ $totalTransactions }}</p>
                </div>
                <div>
                    <p class="text-[11px] text-gray-400 font-medium">Item terjual</p>
                    <p class="text-base font-bold text-kasir-text">{{ $totalItems }}</p>
                </div>
                <div>
                    <p class="text-[11px] text-gray-400 font-medium">Untung</p>
                    <p class="text-base font-bold text-kasir-green">Rp{{ number_format($totalProfit, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- PERIOD FILTER TABS -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('kasir.history') }}?period=hari_ini" class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ $period === 'hari_ini' ? 'bg-kasir-orange text-kasir-text font-bold shadow-xs' : 'bg-white/10 text-white hover:bg-white/20' }}">
                Hari ini
            </a>
            <a href="{{ route('kasir.history') }}?period=7_hari" class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ $period === '7_hari' ? 'bg-kasir-orange text-kasir-text font-bold shadow-xs' : 'bg-white/10 text-white hover:bg-white/20' }}">
                7 hari
            </a>
            <a href="{{ route('kasir.history') }}?period=bulan_ini" class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ $period === 'bulan_ini' ? 'bg-kasir-orange text-kasir-text font-bold shadow-xs' : 'bg-white/10 text-white hover:bg-white/20' }}">
                Bulan ini
            </a>
            <a href="{{ route('kasir.history') }}?period=semua" class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ $period === 'semua' ? 'bg-kasir-orange text-kasir-text font-bold shadow-xs' : 'bg-white/10 text-white hover:bg-white/20' }}">
                Semua
            </a>
        </div>
    </div>

    <!-- TRANSACTIONS LIST / EMPTY STATE -->
    <div class="space-y-3">
        @forelse($transactions as $trx)
        <div class="bg-white rounded-2xl p-4 border border-kasir-border shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-kasir-green/10 flex items-center justify-center text-kasir-green text-lg shrink-0">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-kasir-text">{{ $trx->quantity }} item • {{ $trx->payment_method }}</h4>
                    <p class="text-xs text-gray-400">{{ $trx->invoice_no ?? ('TRX-' . $trx->id) }} • {{ $trx->created_at->format('d M Y, H:i') }}</p>
                    <p class="text-xs text-kasir-text font-medium mt-0.5">{{ $trx->product->name ?? 'Produk' }}</p>
                </div>
            </div>
            <div class="text-right shrink-0">
                <p class="font-bold text-base text-kasir-green">Rp{{ number_format($trx->total_price, 0, ',', '.') }}</p>
                <p class="text-[11px] text-emerald-600 font-semibold">+Rp{{ number_format($trx->profit, 0, ',', '.') }} profit</p>
            </div>
        </div>
        @empty
        <!-- EMPTY STATE ACCORDING TO SCREENSHOT 2 -->
        <div class="text-center py-16 px-4">
            <div class="w-16 h-16 rounded-2xl bg-gray-200 flex items-center justify-center mx-auto mb-4 text-gray-500 shadow-xs">
                <i class="fa-solid fa-receipt text-2xl"></i>
            </div>
            <h3 class="text-base font-bold text-kasir-text mb-1">Belum ada transaksi</h3>
            <p class="text-xs text-gray-400 max-w-xs mx-auto">Penjualan yang selesai akan muncul di sini.</p>
        </div>
        @endforelse
    </div>
@endsection