@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <header class="flex items-center justify-between bg-indigo-800 text-white px-4 py-3 rounded-md">
        <div class="flex items-center space-x-2">
            <h1 class="text-lg font-semibold">
                Selamat {{ now()->hour < 12 ? 'pagi' : (now()->hour < 18 ? 'siang' : 'sore') }},
            </h1>
            <span class="font-bold">Kasir Otomatis</span>
        </div>
        <span class="px-2 py-0.5 rounded-full text-xs bg-amber-100 text-amber-800 font-semibold">Auto</span>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow-sm p-4 text-center">
            <p class="text-xs text-gray-500">Pendapatan hari ini</p>
            <p class="text-lg font-bold text-indigo-600">Rp{{ number_format($todayRevenue ?? 0, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 text-center">
            <p class="text-xs text-gray-500">Transaksi</p>
            <p class="text-lg font-bold text-indigo-600">{{ $todayCount ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 text-center">
            <p class="text-xs text-gray-500">Item terjual</p>
            <p class="text-lg font-bold text-indigo-600">{{ $itemsSold ?? 0 }}</p>
        </div>
    </div>

    <div class="text-center">
        <a href="#" class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-md font-medium" @click="checkout()">
            <span>Transaksi Baru</span>
        </a>
    </div>

    {{-- -------------------------------------------------------------
        Produk Grid & Keranjang (Alpine.js)
    ------------------------------------------------------------- --}}
    <section x-data="kasirPOS()" class="mt-8">
        {{-- Grid produk ------------------------------------------------ --}}
        <h3 class="text-sm font-medium mb-2">Produk</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($products as $product)
                <div class="border rounded-md p-3 flex flex-col justify-between">
                    <div>
                        <p class="font-semibold">{{ $product->name }}</p>
                        <p class="text-xs text-gray-600">{{ $product->sku }}</p>
                        <p class="text-sm mt-1">{{ rupiah($product->price) }}</p>
                    </div>
                    <div class="mt-3 flex items-center space-x-2">
                        <input type="number"
                               min="0"
                               max="{{ $product->stock }}"
                               x-model.number="cart[{{ $product->id }}] ?? 0"
                               class="w-16 border rounded px-1 py-0.5 text-sm" />
                        <button type="button"
                                @click="addToCart({{ $product->id }})"
                                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs py-1 rounded">
                            Tambah
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Ringkasan keranjang ----------------------------------------- --}}
        <div class="mt-6 border-t pt-4">
            <h3 class="text-sm font-medium mb-2">Keranjang</h3>
            <template x-if="Object.keys(cartItems()).length">
                <div>
                    <ul class="space-y-2">
                        <template x-for="[id, qty] in Object.entries(cart)" :key="id">
                            <li class="flex justify-between text-sm" x-show="qty > 0">
                                <span x-text="productName(id)"></span>
                                <span>x <span x-text="qty"></span> = <span x-text="formatRupiah(productSubtotal(id))"></span></span>
                            </li>
                        </template>
                    </ul>
                    <div class="flex justify-between font-semibold mt-2">
                        <span>Total</span>
                        <span x-text="formatRupiah(cartTotal())"></span>
                    </div>
                    <button type="button"
                            @click="checkout()"
                            class="mt-4 w-full bg-amber-500 hover:bg-amber-600 text-white py-2 rounded">
                        Bayar
                    </button>
                </div>
            </template>
            <template x-if="!Object.keys(cartItems()).length">
                <p class="text-sm text-gray-500">Keranjang kosong.</p>
            </template>
        </div>
    </section>

    <script>
    function kasirPOS() {
        return {
            // cart[id] = qty
            cart: {},
            // -----------------------------------------------------
            // utilities
            // -----------------------------------------------------
            productById(id) {
                return this.$root.__x.$data.products.find(p => p.id === Number(id));
            },
            productName(id) {
                const p = this.productById(id);
                return p ? p.name : '';
            },
            productPrice(id) {
                const p = this.productById(id);
                return p ? Number(p.price) : 0;
            },
            productSubtotal(id) {
                return this.productPrice(id) * (this.cart[id] ?? 0);
            },
            formatRupiah(num) {
                return new Intl.NumberFormat('id-ID', {style: 'currency', currency: 'IDR', minimumFractionDigits: 0}).format(num);
            },
            cartItems() {
                const out = {};
                for (const [k, v] of Object.entries(this.cart)) {
                    if (v > 0) out[k] = v;
                }
                return out;
            },
            cartTotal() {
                return Object.entries(this.cart).reduce((sum, [id, qty]) => sum + this.productPrice(id) * qty, 0);
            },
            // -----------------------------------------------------
            // actions
            // -----------------------------------------------------
            addToCart(id) {
                const qty = Number(this.cart[id] ?? 0);
                if (qty > 0) this.cart[id] = qty;
            },
            async checkout() {
                const items = Object.entries(this.cart)
                    .filter(([, q]) => q > 0)
                    .map(([id, qty]) => ({product_id: Number(id), quantity: qty}));
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('{{ route('kasir.checkout') }}', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
                    body: JSON.stringify({items})
                });
                if (response.redirected) {
                    window.location = response.url;
                } else {
                    const data = await response.json();
                    alert(data.message || 'Checkout gagal');
                }
            }
        }
    }
    </script>

    <section class="mt-6">
        <div class="flex justify-between items-center mb-2">
            <h2 class="text-sm font-medium">Transaksi Terakhir</h2>
        </div>
        @forelse ($lastTransactions ?? [] as $trx)
            <div class="bg-white rounded-md shadow-sm p-3 flex justify-between items-center mb-2">
                <div>
                    <p class="text-sm font-medium">{{ $trx->quantity ?? 1 }} item • Tunai</p>
                    <p class="text-xs text-gray-500">{{ $trx->created_at->format('H:i') }}</p>
                </div>
                <p class="font-bold text-indigo-600">Rp{{ number_format($trx->total_price ?? 0, 0, ',', '.') }}</p>
            </div>
        @empty
            <p class="text-sm text-gray-500">Tidak ada transaksi.</p>
        @endforelse
    </section>
</div>
@endsection
