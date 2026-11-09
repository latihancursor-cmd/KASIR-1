@extends('layouts.kasirnih')

@section('title', 'Laporan Penjualan')
@section('page_title', 'Laporan')

@section('content')
    <!-- SUMMARY CARD + PERIOD FILTER -->
    <div class="bg-kasir-green text-white rounded-3xl p-5 shadow-md mb-5">
        <div class="bg-[#FAF5EE] text-kasir-text rounded-2xl p-4 shadow-xs mb-4">
            <p class="text-xs text-gray-500 font-medium mb-1">Pendapatan • {{ str_replace('_', ' ', $period) }}</p>
            <p class="text-2xl font-extrabold text-kasir-green mb-3">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <div class="grid grid-cols-3 gap-2 border-t border-kasir-border/60 pt-3">
                <div>
                    <p class="text-[10px] text-gray-400">Untung</p>
                    <p class="text-sm font-bold text-kasir-green">Rp{{ number_format($totalProfit, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400">Transaksi</p>
                    <p class="text-sm font-bold text-kasir-text">{{ $totalTransactions }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400">Rata-rata</p>
                    <p class="text-sm font-bold text-kasir-text">Rp{{ number_format($averageTransaction, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Period Tabs -->
        <div class="flex gap-2">
            <a href="{{ route('kasir.reports') }}?period=7_hari" class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ $period === '7_hari' ? 'bg-kasir-orange text-kasir-text font-bold shadow-xs' : 'bg-white/10 text-white hover:bg-white/20' }}">7 hari</a>
            <a href="{{ route('kasir.reports') }}?period=30_hari" class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ $period === '30_hari' ? 'bg-kasir-orange text-kasir-text font-bold shadow-xs' : 'bg-white/10 text-white hover:bg-white/20' }}">30 hari</a>
            <a href="{{ route('kasir.reports') }}?period=bulan_ini" class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ $period === 'bulan_ini' ? 'bg-kasir-orange text-kasir-text font-bold shadow-xs' : 'bg-white/10 text-white hover:bg-white/20' }}">Bulan ini</a>
        </div>
    </div>

    <!-- REVENUE CHART -->
    <div class="bg-white rounded-2xl border border-kasir-border shadow-sm p-5 mb-5">
        <h2 class="text-sm font-bold text-kasir-text mb-4">Pendapatan harian</h2>
        <canvas id="revenueChart" height="120"></canvas>
        @if($bestDay->isNotEmpty())
        @php $bestDate = $bestDay->keys()->first(); $bestAmount = $bestDay->first(); @endphp
        <p class="text-xs text-gray-500 mt-3 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-kasir-orange"></span>
            Terbaik {{ \Carbon\Carbon::parse($bestDate)->translatedFormat('l, d M Y') }} · Rp{{ number_format($bestAmount, 0, ',', '.') }}
        </p>
        @endif
    </div>

    <!-- CATEGORY PIE CHART -->
    <div class="bg-white rounded-2xl border border-kasir-border shadow-sm p-5 mb-5">
        <h2 class="text-sm font-bold text-kasir-text mb-4">Penjualan per kategori</h2>
        @if($salesByCategory->isNotEmpty())
        <div class="max-w-xs mx-auto">
            <canvas id="categoryChart"></canvas>
        </div>
        <div class="space-y-2 mt-4">
            @foreach($salesByCategory as $category => $amount)
            @php $percentage = $totalRevenue > 0 ? round(($amount / $totalRevenue) * 100) : 0; @endphp
            <div class="flex items-center justify-between text-xs">
                <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ ['#006A4E', '#E6A15C', '#3B82F6', '#8B5CF6', '#EF4444'][$loop->index % 5] }}"></span>{{ $category }}</span>
                <span class="font-semibold">{{ $percentage }}% · Rp{{ number_format($amount, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8 text-gray-400">
            <i class="fa-solid fa-chart-pie text-3xl mb-2 text-gray-300"></i>
            <p class="text-sm">Belum ada data penjualan kategori</p>
        </div>
        @endif
    </div>

    <!-- TOP PRODUCTS -->
    <div class="bg-white rounded-2xl border border-kasir-border shadow-sm p-5 mb-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-bold text-kasir-text">Produk terlaris</h2>
            <span class="text-[10px] text-gray-400">Berdasarkan jumlah terjual</span>
        </div>
        @forelse($topProducts as $index => $item)
        <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <span class="w-6 text-sm font-bold {{ $index === 0 ? 'text-kasir-orange' : 'text-gray-400' }}">{{ $index + 1 }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-kasir-text truncate">{{ $item['product']->name ?? 'Produk dihapus' }}</p>
                    <div class="w-full bg-gray-100 h-1.5 rounded-full mt-1.5">
                        <div class="bg-kasir-orange h-1.5 rounded-full" style="width: {{ $topProducts->first()['quantity'] > 0 ? ($item['quantity'] / $topProducts->first()['quantity']) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
            <span class="text-xs font-bold text-kasir-green ml-3">{{ $item['quantity'] }} {{ $item['product']->unit ?? 'item' }}</span>
        </div>
        @empty
        <div class="text-center py-8 text-gray-400">
            <i class="fa-solid fa-mug-hot text-3xl mb-2 text-gray-300"></i>
            <p class="text-sm">Belum ada data produk terjual</p>
        </div>
        @endforelse
    </div>

    <!-- STOCK CONDITION -->
    <div class="bg-white rounded-2xl border border-kasir-border shadow-sm p-5 mb-5">
        <h2 class="text-sm font-bold text-kasir-text mb-4">Kondisi stok</h2>
        <div class="flex flex-wrap gap-2 mb-4">
            <span class="px-3 py-1.5 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-bold">Rp{{ number_format($totalStockValue, 0, ',', '.') }} nilai stok</span>
            <span class="px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700 text-xs font-bold">{{ $safeStock }} aman</span>
        </div>
        <div class="flex gap-4 text-xs mb-4">
            <span class="flex items-center gap-1.5"><i class="fa-solid fa-circle text-amber-500 text-[8px]"></i> Menipis {{ $lowStock }}</span>
            <span class="flex items-center gap-1.5"><i class="fa-solid fa-circle text-rose-500 text-[8px]"></i> Habis {{ $outOfStock }}</span>
        </div>

        <h3 class="text-xs font-semibold text-gray-500 mb-2">Perlu restok</h3>
        @forelse($needRestock as $product)
        <div class="flex items-center justify-between py-2 border-t border-gray-100">
            <span class="text-xs text-kasir-text flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-amber-500"></i> {{ $product->name }}
            </span>
            <span class="text-xs font-semibold text-amber-600">Sisa {{ $product->stock }} {{ $product->unit }}</span>
        </div>
        @empty
        <p class="text-xs text-emerald-600 py-2">Semua stok aman.</p>
        @endforelse
    </div>
@endsection

@section('scripts')
<script>
    const revenueLabels = @json($dailyRevenue->keys()->map(fn($date) => \Carbon\Carbon::parse($date)->format('d M')));
    const revenueData = @json($dailyRevenue->values());
    
    if (revenueLabels.length > 0 && document.getElementById('revenueChart')) {
        new Chart(document.getElementById('revenueChart'), {
            type: 'bar',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Pendapatan',
                    data: revenueData,
                    backgroundColor: '#E6A15C',
                    borderRadius: 8,
                    maxBarThickness: 28
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#F3F4F6' }, ticks: { callback: value => 'Rp' + new Intl.NumberFormat('id-ID').format(value) } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    const categoryLabels = @json($salesByCategory->keys());
    const categoryData = @json($salesByCategory->values());
    
    if (categoryLabels.length > 0 && document.getElementById('categoryChart')) {
        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryData,
                    backgroundColor: ['#006A4E', '#E6A15C', '#3B82F6', '#8B5CF6', '#EF4444'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                cutout: '68%',
                plugins: { legend: { display: false } }
            }
        });
    }
</script>
@endsection