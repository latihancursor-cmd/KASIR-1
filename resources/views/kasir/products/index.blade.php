@extends('layouts.kasirnih')

@section('title', 'Manajemen Produk')
@section('page_title', 'Produk')

@section('content')
    <!-- Header with Add Button -->
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-lg font-bold text-kasir-text">Daftar Produk</h2>
            <p class="text-xs text-gray-500">{{ $products->count() }} item</p>
        </div>
        <a href="{{ route('kasir.products.create') }}" class="w-12 h-12 bg-kasir-orange hover:bg-kasir-darkOrange text-white rounded-2xl flex items-center justify-center shadow-sm transition">
            <i class="fa-solid fa-plus text-lg"></i>
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-2xl p-4 border border-kasir-border shadow-sm mb-5">
        <form method="GET" action="{{ route('kasir.products') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, SKU, atau barcode..." class="w-full pl-10 pr-4 py-2.5 bg-kasir-bg rounded-xl border border-kasir-border focus:ring-2 focus:ring-kasir-orange/30 focus:border-kasir-orange outline-none text-sm">
            </div>
            
            <select name="category" onchange="this.form.submit()" class="px-4 py-2.5 bg-kasir-bg rounded-xl border border-kasir-border focus:ring-2 focus:ring-kasir-orange/30 focus:border-kasir-orange outline-none text-sm">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Product List -->
    <div class="space-y-3">
        @forelse($products as $product)
        <div class="bg-white rounded-2xl p-4 border border-kasir-border shadow-sm hover:shadow-md transition">
            <div class="flex items-start justify-between gap-3">
                <!-- Product Icon & Info -->
                <div class="flex items-start gap-3 flex-1 min-w-0">
                    <div class="w-12 h-12 rounded-xl bg-kasir-green/10 flex items-center justify-center text-kasir-green shrink-0">
                        <i class="fa-solid fa-box"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-base text-kasir-text mb-0.5 truncate">{{ $product->name }}</h3>
                        <p class="text-xs text-gray-500 mb-1">{{ $product->sku }} • per {{ $product->unit }}</p>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-xs px-2 py-0.5 rounded-full bg-kasir-bg text-gray-600">Stok {{ $product->stock }}</span>
                            <span class="text-xs text-gray-500">Modal Rp{{ number_format($product->cost_price, 0, ',', '.') }}</span>
                            @if(!$product->is_active)
                            <span class="text-xs px-2 py-0.5 rounded-full bg-rose-100 text-rose-600">Nonaktif</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Price & Actions -->
                <div class="text-right shrink-0">
                    <p class="text-lg font-extrabold text-kasir-green">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                    @php
                        $margin = $product->cost_price > 0 ? (($product->price - $product->cost_price) / $product->cost_price) * 100 : 0;
                    @endphp
                    <span class="text-xs font-semibold {{ $margin > 0 ? 'text-emerald-600' : 'text-gray-400' }}">+{{ number_format($margin, 0) }}%</span>
                    
                    <div class="flex gap-2 mt-2">
                        <a href="{{ route('kasir.products.edit', $product) }}" class="w-8 h-8 rounded-lg bg-kasir-bg hover:bg-gray-200 flex items-center justify-center text-gray-600 transition">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </a>
                        <form method="POST" action="{{ route('kasir.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-100 flex items-center justify-center text-rose-500 transition">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl p-10 border border-kasir-border shadow-sm text-center">
            <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-3"></i>
            <p class="text-base font-semibold text-gray-500 mb-1">Belum ada produk</p>
            <p class="text-sm text-gray-400 mb-4">Tambahkan produk pertama Anda</p>
            <a href="{{ route('kasir.products.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-kasir-orange hover:bg-kasir-darkOrange text-kasir-text font-semibold text-sm rounded-xl transition">
                <i class="fa-solid fa-plus"></i> Tambah Produk
            </a>
        </div>
        @endforelse
    </div>
@endsection