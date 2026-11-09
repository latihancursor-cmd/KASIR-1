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
                    <div class="flex gap-3">
                        <button @click="showReceipt = false" class="flex-1 py-3 border border-kasir-border text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-50 transition">Selesai</button>
                        <button @click="printReceipt()" class="flex-1 py-3 bg-kasir-orange text-white font-bold text-sm rounded-xl hover:bg-kasir-darkOrange transition flex items-center justify-center gap-2"><i class="fa-solid fa-print"></i> Cetak Struk</button>
                    </div>
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
                },
                printReceipt() {
                    const receipt = this.receipt;
                    const printWindow = window.open('', '_blank', 'width=320,height=640');
                    const items = (receipt.items || []).map(item => `
                        <tr><td style="padding:4px 0;font-size:11px;">${item.name}</td></tr>
                        <tr>
                            <td style="padding:0 0 6px;font-size:10px;color:#666;">${item.qty} ${item.unit} × ${this.formatRupiah(item.price)}</td>
                            <td style="text-align:right;padding:0 0 6px;font-size:11px;font-weight:bold;">${this.formatRupiah(item.subtotal)}</td>
                        </tr>`).join('');
                    const cash = receipt.payment_method === 'Tunai' ? `
                        <tr><td style="padding:3px 0;font-size:11px;">Dibayar</td><td style="text-align:right;font-size:11px;font-weight:bold;">${this.formatRupiah(receipt.cash_paid)}</td></tr>
                        <tr><td style="padding-top:6px;font-size:12px;font-weight:bold;">Kembalian</td><td style="text-align:right;padding-top:6px;font-size:12px;font-weight:bold;">${this.formatRupiah(receipt.change)}</td></tr>` : '';
                    printWindow.document.write(`<!DOCTYPE html><html><head><title>Struk - ${receipt.invoice_no}</title>
                        <style>@media print{@page{margin:0;size:58mm auto}body{margin:0}}body{font-family:'Courier New',monospace;width:58mm;margin:0 auto;padding:10mm 5mm;color:#000}table{width:100%;border-collapse:collapse}.header{text-align:center;border-bottom:2px solid #000;padding-bottom:8px}.info{margin:8px 0;font-size:10px}.info div{display:flex;justify-content:space-between;margin:3px 0}.footer{text-align:center;border-top:1px dashed #999;margin-top:12px;padding-top:8px;font-size:9px}</style></head>
                        <body>
                            <div class="header"><h2 style="margin:0;font-size:16px;">{{ $settings->store_name ?? 'KasirNih' }}</h2><p style="font-size:9px;">Terima kasih atas kunjungan Anda</p></div>
                            <div class="info"><div><span>No. Transaksi</span><strong>${receipt.invoice_no}</strong></div><div><span>Tanggal</span><span>${receipt.created_at}</span></div><div><span>Metode</span><span>${receipt.payment_method}</span></div></div>
                            <table>${items}<tr style="border-top:2px solid #000"><td style="padding-top:8px;font-weight:bold;">TOTAL</td><td style="text-align:right;padding-top:8px;font-weight:bold;">${this.formatRupiah(receipt.total)}</td></tr>${cash}</table>
                            <div class="footer"><p>Barang yang sudah dibeli tidak dapat dikembalikan</p><p>Powered by KasirNih</p></div>
                        </body></html>`);
                    printWindow.document.close();
                    printWindow.focus();
                    setTimeout(() => { printWindow.print(); printWindow.close(); }, 250);
                }
            };
        }
    </script>
@endsection