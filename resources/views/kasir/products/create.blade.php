@extends('layouts.kasirnih')

@section('title', 'Tambah Produk')
@section('page_title', 'Tambah Produk')
@section('back_url', route('kasir.products'))

@section('content')
    <form method="POST" action="{{ route('kasir.products.store') }}" enctype="multipart/form-data" class="max-w-2xl mx-auto space-y-5" x-data="productCreateForm()">
        @csrf

        <!-- Product Identity -->
        <section>
            <h2 class="text-sm font-semibold text-gray-400 mb-3">Identitas</h2>
            
            <!-- Product Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-semibold text-kasir-text mb-2">Nama produk <span class="text-rose-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Beras Pandan Wangi 5 kg" class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm @error('name') border-rose-400 @enderror">
                @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- SKU & Barcode -->
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label for="sku" class="block text-sm font-semibold text-kasir-text mb-2">SKU <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="text" id="sku" name="sku" x-model="sku" value="{{ old('sku') }}" required placeholder="Otomatis dari kategori" class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm @error('sku') border-rose-400 @enderror">
                        <span x-show="loadingSku" class="absolute right-3 top-1/2 -translate-y-1/2 text-kasir-green"><i class="fa-solid fa-spinner animate-spin text-xs"></i></span>
                    </div>
                    <p class="text-[11px] text-gray-400 mt-1">Terisi otomatis sesuai kategori (bisa disesuaikan)</p>
                    @error('sku') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="barcode" class="block text-sm font-semibold text-kasir-text mb-2">Barcode</label>
                    <input type="text" id="barcode" name="barcode" value="{{ old('barcode') }}" placeholder="Opsional" class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm">
                </div>
            </div>

            <!-- Category -->
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <label for="category_id" class="block text-sm font-semibold text-kasir-text">Kategori <span class="text-rose-500">*</span></label>
                    <a href="{{ route('kasir.settings') }}#kategori" class="text-xs font-semibold text-kasir-green hover:text-kasir-darkGreen"><i class="fa-solid fa-pen-to-square mr-1"></i> Kelola</a>
                </div>
                <select id="category_id" name="category_id" x-model="categoryId" @change="generateSku()" required class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm">
                    <option value="">Pilih kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }} ({{ $category->code ?: strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $category->name), 0, 3)) }})</option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Product Image -->
            <div class="mb-4" x-data="{ preview: null }">
                <label for="image" class="block text-sm font-semibold text-kasir-text mb-2">Gambar produk</label>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp" @change="preview = URL.createObjectURL($event.target.files[0])" class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border text-sm">
                <p class="text-xs text-gray-400 mt-1">JPG, PNG, atau WebP. Maksimal 2 MB.</p>
                <img x-show="preview" :src="preview" alt="Preview gambar produk" class="mt-3 h-32 w-32 rounded-xl object-cover border border-kasir-border">
                @error('image') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-kasir-text mb-2">Deskripsi</label>
                <textarea id="description" name="description" rows="3" placeholder="Opsional" class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm resize-none">{{ old('description') }}</textarea>
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
                        <input type="number" id="cost_price" name="cost_price" value="{{ old('cost_price') }}" required min="0" step="0.01" placeholder="0" class="w-full pl-10 pr-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm">
                    </div>
                    @error('cost_price') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="price" class="block text-sm font-semibold text-kasir-text mb-2">Harga jual <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs text-gray-400">Rp</span>
                        <input type="number" id="price" name="price" value="{{ old('price') }}" required min="0" step="0.01" placeholder="0" class="w-full pl-10 pr-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm">
                    </div>
                    @error('price') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        <!-- Stock -->
        <section>
            <h2 class="text-sm font-semibold text-gray-400 mb-3">Stok</h2>
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label for="stock" class="block text-sm font-semibold text-kasir-text mb-2">Stok awal <span class="text-rose-500">*</span></label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" required min="0" class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm">
                </div>
                <div>
                    <label for="min_stock" class="block text-sm font-semibold text-kasir-text mb-2">Stok minimum <span class="text-rose-500">*</span></label>
                    <input type="number" id="min_stock" name="min_stock" value="{{ old('min_stock', 0) }}" required min="0" class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm">
                </div>
            </div>
            
            <!-- Unit -->
            <div>
                <label for="unit" class="block text-sm font-semibold text-kasir-text mb-2">Satuan <span class="text-rose-500">*</span></label>
                <select id="unit" name="unit" required class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm">
                    @foreach(['pcs', 'kg', 'gram', 'dus', 'botol', 'kaleng', 'karung', 'liter', 'pak', 'lembar', 'ikat', 'box'] as $unit)
                    <option value="{{ $unit }}" {{ old('unit', 'pcs') === $unit ? 'selected' : '' }}>{{ $unit }}</option>
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
                <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                <div class="w-12 h-7 bg-gray-200 rounded-full peer peer-checked:bg-kasir-green after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="flex gap-3 pt-2">
            <a href="{{ route('kasir.products') }}" class="flex-1 px-5 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold text-sm rounded-2xl text-center transition">Batal</a>
            <button type="submit" class="flex-[2] px-5 py-3.5 bg-kasir-orange hover:bg-kasir-darkOrange text-kasir-text font-bold text-sm rounded-2xl shadow-sm transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-check-square"></i> Simpan Produk
            </button>
        </div>
    </form>

    <script>
    function productCreateForm() {
        return {
            categoryId: '{{ old('category_id') }}',
            sku: '{{ old('sku') }}',
            loadingSku: false,
            init() {
                if (this.categoryId && !this.sku) {
                    this.generateSku();
                }
            },
            async generateSku() {
                if (!this.categoryId) {
                    return;
                }
                this.loadingSku = true;
                try {
                    const response = await fetch(`{{ route('kasir.products.sku') }}?category_id=${this.categoryId}`);
                    const data = await response.json();
                    if (data.sku) {
                        this.sku = data.sku;
                    }
                } catch (e) {
                    console.error(e);
                } finally {
                    this.loadingSku = false;
                }
            }
        }
    }
    </script>
@endsection