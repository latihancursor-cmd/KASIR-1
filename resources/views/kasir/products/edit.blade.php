@extends('layouts.kasirnih')

@section('title', 'Edit Produk')
@section('page_title', 'Edit Produk')
@section('back_url', route('kasir.products'))

@section('content')
    <form method="POST" action="{{ route('kasir.products.update', $product) }}" class="max-w-2xl mx-auto space-y-5">
        @csrf
        @method('PUT')

        <!-- Product Identity -->
        <section>
            <h2 class="text-sm font-semibold text-gray-400 mb-3">Identitas</h2>
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-semibold text-kasir-text mb-2">Nama produk <span class="text-rose-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm @error('name') border-rose-400 @enderror">
                @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label for="sku" class="block text-sm font-semibold text-kasir-text mb-2">SKU <span class="text-rose-500">*</span></label>
                    <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm @error('sku') border-rose-400 @enderror">
                    @error('sku') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="barcode" class="block text-sm font-semibold text-kasir-text mb-2">Barcode</label>
                    <input type="text" id="barcode" name="barcode" value="{{ old('barcode', $product->barcode) }}" class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm">
                </div>
            </div>

            <div class="mb-4">
                <label for="category_id" class="block text-sm font-semibold text-kasir-text mb-2">Kategori <span class="text-rose-500">*</span></label>
                <select id="category_id" name="category_id" required class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm">
                    <option value="">Pilih kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-kasir-text mb-2">Deskripsi</label>
                <textarea id="description" name="description" rows="3" class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm resize-none">{{ old('description', $product->description) }}</textarea>
            </div>
        </section>

        <!-- Pricing -->
        <section>
            <h2 class="text-sm font-semibold text-gray-400 mb-3">Harga</h2>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="cost_price" class="block text-sm font-semibold text-kasir-text mb-2">Harga modal <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs text-gray-400">Rp</span>
                        <input type="number" id="cost_price" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" required min="0" step="0.01" class="w-full pl-10 pr-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm">
                    </div>
                </div>
                <div>
                    <label for="price" class="block text-sm font-semibold text-kasir-text mb-2">Harga jual <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs text-gray-400">Rp</span>
                        <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" required min="0" step="0.01" class="w-full pl-10 pr-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm">
                    </div>
                </div>
            </div>
        </section>

        <!-- Stock -->
        <section>
            <h2 class="text-sm font-semibold text-gray-400 mb-3">Stok</h2>
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label for="stock" class="block text-sm font-semibold text-kasir-text mb-2">Stok saat ini <span class="text-rose-500">*</span></label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required min="0" class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm">
                </div>
                <div>
                    <label for="min_stock" class="block text-sm font-semibold text-kasir-text mb-2">Stok minimum <span class="text-rose-500">*</span></label>
                    <input type="number" id="min_stock" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" required min="0" class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm">
                </div>
            </div>
            
            <div>
                <label for="unit" class="block text-sm font-semibold text-kasir-text mb-2">Satuan <span class="text-rose-500">*</span></label>
                <select id="unit" name="unit" required class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm">
                    @foreach(['pcs', 'kg', 'gram', 'dus', 'botol', 'kaleng', 'karung', 'liter', 'pak', 'lembar', 'ikat', 'box'] as $unit)
                    <option value="{{ $unit }}" {{ old('unit', $product->unit) === $unit ? 'selected' : '' }}>{{ $unit }}</option>
                    @endforeach
                </select>
            </div>
        </section>

        <!-- Active Toggle -->
        <div class="bg-white rounded-2xl border border-kasir-border px-4 py-4 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-kasir-text">Tampilkan di daftar jual</p>
                <p class="text-xs text-gray-400 mt-0.5">Produk aktif bisa dipilih saat transaksi</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="sr-only peer">
                <div class="w-12 h-7 bg-gray-200 rounded-full peer peer-checked:bg-kasir-green after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="flex gap-3 pt-2">
            <a href="{{ route('kasir.products') }}" class="flex-1 px-5 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold text-sm rounded-2xl text-center transition">Batal</a>
            <button type="submit" class="flex-[2] px-5 py-3.5 bg-kasir-orange hover:bg-kasir-darkOrange text-kasir-text font-bold text-sm rounded-2xl shadow-sm transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-check-square"></i> Simpan Perubahan
            </button>
        </div>
    </form>
@endsection