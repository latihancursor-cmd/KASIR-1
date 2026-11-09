@extends('layouts.kasirnih')

@section('title', 'Manajemen Stok')
@section('page_title', 'Stok')

@section('content')
    <!-- STATISTICS CARDS -->
    <div class="bg-white rounded-2xl p-5 border border-kasir-border shadow-sm mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <p class="text-xs font-medium text-gray-400 mb-1">Nilai stok (modal)</p>
                <p class="text-2xl font-extrabold text-kasir-green">Rp{{ number_format($totalStockValue, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400 mb-1">Produk</p>
                <p class="text-2xl font-extrabold text-gray-700">{{ $totalProducts }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400 mb-1">Menipis</p>
                <p class="text-2xl font-extrabold text-amber-600">{{ $lowStockCount }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400 mb-1">Habis</p>
                <p class="text-2xl font-extrabold text-rose-600">{{ $outOfStockCount }}</p>
            </div>
        </div>
    </div>

    <!-- SEARCH & FILTER -->
    <div class="bg-white rounded-2xl p-4 border border-kasir-border shadow-sm mb-5">
        <form method="GET" action="{{ route('kasir.stock') }}" class="flex flex-col sm:flex-row gap-3">
            <!-- Search Input -->
            <div class="flex-1 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="w-full pl-10 pr-4 py-2.5 bg-kasir-bg rounded-xl border border-kasir-border focus:ring-2 focus:ring-kasir-orange/30 focus:border-kasir-orange outline-none text-sm">
            </div>
            
            <!-- Filter Buttons -->
            <div class="flex gap-2">
                <a href="{{ route('kasir.stock') }}?filter=semua" class="px-4 py-2.5 rounded-xl text-xs font-semibold transition {{ $filter === 'semua' ? 'bg-kasir-orange text-white shadow-sm' : 'bg-kasir-bg text-gray-600 hover:bg-gray-100' }}">
                    Semua
                </a>
                <a href="{{ route('kasir.stock') }}?filter=menipis" class="px-4 py-2.5 rounded-xl text-xs font-semibold transition {{ $filter === 'menipis' ? 'bg-kasir-orange text-white shadow-sm' : 'bg-kasir-bg text-gray-600 hover:bg-gray-100' }}">
                    Menipis
                </a>
                <a href="{{ route('kasir.stock') }}?filter=habis" class="px-4 py-2.5 rounded-xl text-xs font-semibold transition {{ $filter === 'habis' ? 'bg-kasir-orange text-white shadow-sm' : 'bg-kasir-bg text-gray-600 hover:bg-gray-100' }}">
                    Habis
                </a>
            </div>
        </form>
    </div>

    <!-- PRODUCT STOCK LIST -->
    <div class="space-y-3">
        @forelse($products as $product)
        <div class="bg-white rounded-2xl p-4 border border-kasir-border shadow-sm hover:shadow-md transition">
            <div class="flex items-start justify-between gap-3">
                <!-- Product Info -->
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-base text-kasir-text mb-0.5 truncate">{{ $product->name }}</h3>
                    <p class="text-xs text-gray-500 mb-2">{{ $product->sku }} • min. {{ $product->min_stock }} {{ $product->unit }}</p>
                    
                    <!-- Stock Progress Bar -->
                    @php
                        $stockPercentage = $product->min_stock > 0 ? ($product->stock / $product->min_stock) * 100 : 100;
                        $stockStatus = $product->stock == 0 ? 'habis' : ($product->stock <= $product->min_stock ? 'menipis' : 'aman');
                        $barColor = $stockStatus === 'aman' ? 'bg-kasir-green' : ($stockStatus === 'menipis' ? 'bg-amber-500' : 'bg-rose-500');
                    @endphp
                    
                    <div class="w-full bg-gray-100 rounded-full h-2 mb-2">
                        <div class="{{ $barColor }} h-2 rounded-full transition-all duration-300" style="width: {{ min(100, $stockPercentage) }}%"></div>
                    </div>
                </div>

                <!-- Stock Info & Action -->
                <div class="text-right shrink-0">
                    <p class="text-2xl font-extrabold {{ $stockStatus === 'aman' ? 'text-kasir-green' : ($stockStatus === 'menipis' ? 'text-amber-600' : 'text-rose-600') }}">
                        {{ $product->stock }} <span class="text-sm font-normal">{{ $product->unit }}</span>
                    </p>
                    <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-full mt-1 {{ $stockStatus === 'aman' ? 'bg-emerald-100 text-emerald-700' : ($stockStatus === 'menipis' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                        {{ ucfirst($stockStatus) }}
                    </span>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl p-10 border border-kasir-border shadow-sm text-center">
            <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-3"></i>
            <p class="text-base font-semibold text-gray-500 mb-1">Tidak ada produk ditemukan</p>
            <p class="text-sm text-gray-400">Coba ubah filter atau kata kunci pencarian</p>
        </div>
        @endforelse
    </div>
@endsection