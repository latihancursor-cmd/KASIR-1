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
    <div class="space-y-3" x-data="historyReceipts()">
        @forelse($transactions as $trx)
        <button type="button" @click="openReceipt({{ Illuminate\Support\Js::from($trx) }})" class="w-full text-left bg-white rounded-2xl p-4 border border-kasir-border shadow-sm hover:shadow-md hover:border-kasir-green/40 transition flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-kasir-green/10 flex items-center justify-center text-kasir-green text-lg shrink-0"><i class="fa-solid fa-receipt"></i></div>
                <div class="min-w-0">
                    <h4 class="font-bold text-sm text-kasir-text"><span>{{ $trx['quantity'] }}</span> item • {{ $trx['payment_method'] }}</h4>
                    <p class="text-xs text-gray-400">{{ $trx['invoice_no'] }} • {{ $trx['created_at']->format('d M Y, H:i') }}</p>
                    <p class="text-xs text-kasir-text font-medium mt-0.5 truncate">{{ collect($trx['items'])->pluck('name')->join(', ') }}</p>
                </div>
            </div>
            <div class="text-right shrink-0">
                <p class="font-bold text-base text-kasir-green">Rp{{ number_format($trx['total'], 0, ',', '.') }}</p>
                <p class="text-[11px] text-emerald-600 font-semibold">+Rp{{ number_format($trx['profit'], 0, ',', '.') }} profit</p>
                <span class="text-[10px] text-kasir-green">Lihat bukti <i class="fa-solid fa-chevron-right"></i></span>
            </div>
        </button>
        @empty
        <div class="text-center py-16 px-4">
            <div class="w-16 h-16 rounded-2xl bg-gray-200 flex items-center justify-center mx-auto mb-4 text-gray-500 shadow-xs"><i class="fa-solid fa-receipt text-2xl"></i></div>
            <h3 class="text-base font-bold text-kasir-text mb-1">Belum ada transaksi</h3>
            <p class="text-xs text-gray-400 max-w-xs mx-auto">Penjualan yang selesai akan muncul di sini.</p>
        </div>
        @endforelse

        <!-- Receipt Modal -->
        <div x-show="showReceipt" x-cloak class="fixed inset-0 bg-black/50 z-50 flex items-end md:items-center justify-center p-4" @click.self="showReceipt = false">
            <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden" x-transition>
                <div class="flex justify-center pt-6 pb-3">
                    <div class="w-16 h-16 rounded-full bg-kasir-green/10 flex items-center justify-center"><i class="fa-solid fa-check text-3xl text-kasir-green"></i></div>
                </div>
                <div class="px-6 pb-6">
                    <h3 class="text-2xl font-bold text-center text-kasir-text mb-1">Transaksi berhasil</h3>
                    <p class="text-center text-sm text-gray-500 mb-5"><span x-text="receipt.invoice_no"></span> • <span x-text="receipt.payment_method"></span></p>
                    <div class="bg-kasir-bg rounded-2xl p-4 mb-4 max-h-48 overflow-y-auto">
                        <template x-for="(item, index) in receipt.items" :key="index">
                            <div class="flex items-start justify-between py-2 border-b border-kasir-border last:border-0">
                                <div class="flex-1"><p class="font-bold text-sm text-kasir-text" x-text="item.name"></p><p class="text-xs text-gray-400"><span x-text="item.qty"></span> <span x-text="item.unit"></span> × <span x-text="formatRupiah(item.price)"></span></p></div>
                                <p class="font-bold text-sm text-kasir-green ml-3" x-text="formatRupiah(item.subtotal)"></p>
                            </div>
                        </template>
                    </div>
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between text-sm"><span class="text-gray-500">Subtotal</span><span class="font-semibold" x-text="formatRupiah(receipt.total)"></span></div>
                        <div class="flex justify-between text-base font-bold pt-2 border-t border-kasir-border"><span>Total</span><span class="text-kasir-green" x-text="formatRupiah(receipt.total)"></span></div>
                        <div class="flex justify-between text-sm"><span class="text-gray-500">Dibayar</span><span class="font-semibold" x-text="formatRupiah(receipt.cash_paid)"></span></div>
                        <div class="flex justify-between text-sm font-bold text-kasir-orange"><span>Kembalian</span><span x-text="formatRupiah(receipt.change)"></span></div>
                    </div>
                    <button @click="showReceipt = false" class="w-full py-3 border border-kasir-border text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-50 transition">Selesai</button>
                </div>
            </div>
        </div>
    </div>
    <style>[x-cloak] { display: none !important; }</style>
    <script>
        function historyReceipts() {
            return {
                showReceipt: false,
                receipt: {},
                openReceipt(data) {
                    this.receipt = data;
                    this.showReceipt = true;
                },
                formatRupiah(amount) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount || 0);
                }
            };
        }
    </script>
@endsection