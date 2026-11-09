<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Purr'Coffee POS - Kasir Pintar</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            bg: '#E3F2FD',
                            sidebar: '#E3F2FD',
                            card: '#FFFFFF',
                            accent: '#54B5C1',
                            accentHover: '#3A9EAA',
                            brown: '#1B0D80',
                            darkBrown: '#14095F',
                            lightBg: '#8ACBD0',
                            tag: '#D9EFF0',
                            border: '#8ACBD0'
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #8ACBD0; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #54B5C1; }
    </style>
</head>
<body class="bg-[#F6EFE6] text-[#4A3B32] min-h-screen flex items-center justify-center p-3 md:p-6 select-none">

    <!-- Container Utama Kasir Purr'Coffee (Responsive Mockup Container) -->
    <div class="w-full max-w-[1440px] h-[92vh] min-h-[720px] bg-[#E3F2FD] rounded-3xl shadow-2xl border border-brand-border flex overflow-hidden relative" x-data="purrPosApp()">
        
        <!-- 1. SIDEBAR NAVIGATION -->
        <aside class="w-64 bg-[#F5ECE1] border-r border-brand-border flex flex-col justify-between p-5 shrink-0 hidden md:flex">
            <div>
                <!-- Brand Logo & Header -->
                <div class="flex items-center gap-3 px-2 mb-8">
                    <div class="w-10 h-10 rounded-2xl bg-brand-accent/20 flex items-center justify-center text-brand-accent text-xl shadow-sm">
                        <i class="fa-solid fa-mug-hot"></i>
                    </div>
                    <div>
                        <span class="font-bold text-xl text-[#3A2B22] block leading-tight">Purr'Coffee</span>
                        <span class="text-[10px] text-gray-500 tracking-wider uppercase font-semibold">Smart POS System</span>
                    </div>
                </div>

                <!-- Nav Menu Items -->
                <nav class="space-y-1.5">
                    <a href="{{ route('kasir.beranda') }}" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-medium text-gray-600 hover:bg-brand-tag transition">
                        <i class="fa-solid fa-house w-5 text-center text-gray-400"></i> Beranda
                    </a>
                    <a href="#" class="flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-semibold bg-[#8ACBD0] text-[#1B0D80] shadow-sm">
                        <div class="flex items-center gap-3.5">
                            <i class="fa-solid fa-utensils w-5 text-center text-brand-darkBrown"></i> Menu Utama
                        </div>
                    </a>
                    <a href="#" class="flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-medium text-gray-600 hover:bg-brand-tag transition">
                        <div class="flex items-center gap-3.5">
                            <i class="fa-solid fa-cart-shopping w-5 text-center text-gray-400"></i> Pesanan Active
                        </div>
                        <span class="w-5 h-5 rounded-full bg-brand-accent text-white text-xs flex items-center justify-center font-bold shadow-xs" x-text="cartTotalCount()">0</span>
                    </a>
                    <a href="#" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-medium text-gray-600 hover:bg-brand-tag transition">
                        <i class="fa-solid fa-clock-rotate-left w-5 text-center text-gray-400"></i> Riwayat
                    </a>
                    <a href="#" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-medium text-gray-600 hover:bg-brand-tag transition">
                        <i class="fa-solid fa-handshake w-5 text-center text-gray-400"></i> Mitra POS
                    </a>
                    <a href="#" class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-medium text-gray-600 hover:bg-brand-tag transition">
                        <i class="fa-solid fa-gear w-5 text-center text-gray-400"></i> Pengaturan
                    </a>
                </nav>
            </div>

            <!-- Footer Sidebar Buttons -->
            <div class="space-y-2 pt-4 border-t border-brand-border/60">
                <a href="#" class="flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-sm font-medium text-gray-600 hover:bg-brand-tag transition">
                    <i class="fa-regular fa-heart w-5 text-center text-rose-500"></i> Donasi Shelter
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3.5 px-4 py-2.5 rounded-2xl text-sm font-medium text-gray-600 hover:bg-rose-50 hover:text-rose-600 transition">
                        <i class="fa-solid fa-right-from-bracket w-5 text-center"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- 2. MAIN CONTENT AREA (Search & Menu Grid) -->
        <main class="flex-1 flex flex-col min-w-0 bg-[#E3F2FD] overflow-hidden">
            
            <!-- HEADER (Search & Filter) -->
            <header class="p-5 pb-2 flex items-center justify-between gap-4">
                <div class="relative flex-1 max-w-xl">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" x-model="searchQuery" placeholder="Cari kopi, non-kopi, makanan..." class="w-full pl-11 pr-4 py-3 bg-white rounded-full border-none shadow-sm focus:ring-2 focus:ring-brand-accent/40 text-sm outline-none placeholder-gray-400">
                    <button x-show="searchQuery" @click="searchQuery = ''" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xs">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <button @click="selectedCategory = 'Semua'" class="flex items-center gap-2 px-5 py-3 bg-brand-accent hover:bg-brand-accentHover text-white text-sm font-semibold rounded-full shadow-sm transition">
                    <i class="fa-solid fa-sliders"></i> Filter
                </button>
            </header>

            <!-- MENU AREA CONTAINER -->
            <div class="flex-1 overflow-y-auto p-5 pt-3 space-y-5">
                <!-- Title & Category Tabs -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h1 class="text-2xl font-bold text-[#3A2B22]">Coffee Menu</h1>
                        <span class="text-xs text-gray-500 font-medium" x-text="filteredProducts.length + ' item ditemukan'"></span>
                    </div>
                    
                    <!-- Category Tabs Carousel -->
                    <div class="flex flex-wrap gap-2 pb-1">
                        <template x-for="cat in categories" :key="cat">
                            <button @click="selectedCategory = cat"
                                    :class="selectedCategory === cat ? 'bg-brand-accent text-white font-semibold shadow-sm' : 'bg-white text-gray-600 hover:bg-brand-tag'"
                                    class="px-5 py-2.5 rounded-full text-xs md:text-sm transition duration-150" x-text="cat">
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div class="bg-white rounded-3xl p-4 shadow-sm border border-brand-border/40 flex flex-col justify-between hover:shadow-md transition duration-200">
                            <div>
                                <!-- Image Card Thumbnail -->
                                <div class="w-full h-36 bg-[#E3F2FD] rounded-2xl overflow-hidden mb-3 relative flex items-center justify-center group">
                                    <img :src="product.image" :alt="product.name" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    <span class="absolute top-2 right-2 bg-white/90 backdrop-blur-md px-2.5 py-0.5 rounded-full text-[10px] font-bold text-brand-darkBrown shadow-xs" x-text="product.category"></span>
                                </div>

                                <div class="flex items-start justify-between gap-2 mb-1">
                                    <h3 class="font-bold text-[#3A2B22] text-base leading-snug" x-text="product.name"></h3>
                                    <span class="font-extrabold text-[#3A2B22] text-base" x-text="formatRupiah(product.selectedSize === 'Besar' ? product.price + 5000 : product.price)"></span>
                                </div>
                                <p class="text-xs text-gray-500 line-clamp-2 mb-3 min-h-[32px]" x-text="product.description"></p>

                                <!-- Size Toggle Selector -->
                                <div class="mb-4">
                                    <label class="text-[10px] font-bold text-gray-400 block mb-1 uppercase tracking-wider">Pilih Ukuran</label>
                                    <div class="grid grid-cols-2 gap-1.5 bg-[#F7F1E8] p-1 rounded-xl">
                                        <button @click="product.selectedSize = 'Kecil'" 
                                                :class="product.selectedSize === 'Kecil' ? 'bg-white font-semibold text-brand-darkBrown shadow-xs' : 'text-gray-500 hover:text-gray-700'"
                                                class="py-1 text-xs rounded-lg transition">Kecil</button>
                                        <button @click="product.selectedSize = 'Besar'" 
                                                :class="product.selectedSize === 'Besar' ? 'bg-white font-semibold text-brand-darkBrown shadow-xs' : 'text-gray-500 hover:text-gray-700'"
                                                class="py-1 text-xs rounded-lg transition">Besar (+5k)</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Qty Controls & Action -->
                            <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
                                <div class="flex items-center bg-[#F7F1E8] rounded-xl p-1 shrink-0">
                                    <button @click="product.tempQty = Math.max(1, (product.tempQty || 1) - 1)" class="w-7 h-7 flex items-center justify-center text-gray-600 hover:bg-white rounded-lg transition">
                                        <i class="fa-solid fa-minus text-xs"></i>
                                    </button>
                                    <span class="w-6 text-center font-bold text-xs" x-text="product.tempQty || 1"></span>
                                    <button @click="product.tempQty = (product.tempQty || 1) + 1" class="w-7 h-7 flex items-center justify-center text-gray-600 hover:bg-white rounded-lg transition">
                                        <i class="fa-solid fa-plus text-xs"></i>
                                    </button>
                                </div>

                                <button @click="addToCart(product)" class="flex-1 py-2.5 bg-brand-brown hover:bg-[#8A5C36] active:scale-95 text-white font-semibold text-xs rounded-xl shadow-sm transition flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-plus"></i> Tambah
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </main>

        <!-- 3. RIGHT SIDE CART SUMMARY -->
        <aside class="w-80 md:w-90 bg-white border-l border-brand-border flex flex-col justify-between p-5 shrink-0 shadow-lg">
            <div>
                <h2 class="text-xl font-bold text-[#3A2B22] mb-4">Cart Summary</h2>

                <!-- Fulfillment Modes Tabs (Delivery / Dine in / Take away) -->
                <div class="grid grid-cols-3 gap-1.5 bg-[#F7F1E8] p-1.5 rounded-2xl mb-5">
                    <button @click="fulfillmentMode = 'Delivery'" 
                            :class="fulfillmentMode === 'Delivery' ? 'bg-white text-brand-darkBrown font-semibold shadow-xs' : 'text-gray-500 hover:text-gray-700'"
                            class="flex flex-col items-center py-2 px-1 rounded-xl text-xs transition">
                        <i class="fa-solid fa-truck-fast text-sm mb-1"></i> Delivery
                    </button>
                    <button @click="fulfillmentMode = 'Dine in'" 
                            :class="fulfillmentMode === 'Dine in' ? 'bg-white text-brand-darkBrown font-semibold shadow-xs' : 'text-gray-500 hover:text-gray-700'"
                            class="flex flex-col items-center py-2 px-1 rounded-xl text-xs transition">
                        <i class="fa-solid fa-utensils text-sm mb-1"></i> Dine in
                    </button>
                    <button @click="fulfillmentMode = 'Take away'" 
                            :class="fulfillmentMode === 'Take away' ? 'bg-white text-brand-darkBrown font-semibold shadow-xs' : 'text-gray-500 hover:text-gray-700'"
                            class="flex flex-col items-center py-2 px-1 rounded-xl text-xs transition">
                        <i class="fa-solid fa-bag-shopping text-sm mb-1"></i> Take away
                    </button>
                </div>

                <!-- Cart Items List Container -->
                <div class="space-y-3 max-h-[320px] overflow-y-auto pr-1">
                    <template x-for="(item, index) in cart" :key="index">
                        <div class="flex items-center justify-between gap-3 p-2.5 bg-[#FFFFFF] rounded-2xl border border-brand-border/40 hover:border-brand-accent/40 transition">
                            <div class="w-12 h-12 bg-[#EFE6DC] rounded-xl overflow-hidden shrink-0">
                                <img :src="item.image" :alt="item.name" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-xs text-[#3A2B22] truncate" x-text="item.name"></h4>
                                <span class="text-[10px] text-gray-500 block" x-text="'Ukuran: ' + item.size"></span>
                                <span class="font-semibold text-xs text-brand-brown" x-text="formatRupiah(item.price * item.qty)"></span>
                            </div>
                            
                            <!-- Quantity Controls in Cart -->
                            <div class="flex items-center bg-[#F7F1E8] rounded-lg p-0.5 shrink-0">
                                <button @click="updateCartQty(index, -1)" class="w-5 h-5 flex items-center justify-center text-gray-600 hover:bg-white rounded transition text-[10px]">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                                <span class="w-5 text-center font-bold text-xs" x-text="item.qty"></span>
                                <button @click="updateCartQty(index, 1)" class="w-5 h-5 flex items-center justify-center text-gray-600 hover:bg-white rounded transition text-[10px]">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- Cart Empty State -->
                    <template x-if="cart.length === 0">
                        <div class="text-center py-12 text-gray-400 text-xs">
                            <div class="w-12 h-12 rounded-full bg-[#F7F1E8] flex items-center justify-center mx-auto mb-3 text-gray-400">
                                <i class="fa-solid fa-basket-shopping text-xl"></i>
                            </div>
                            <p class="font-medium text-gray-500">Keranjang masih kosong</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">Pilih produk dari menu untuk menambahkan</p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Price Breakdown & Place Order Action -->
            <div class="pt-4 border-t border-brand-border/80 space-y-2.5">
                <div class="flex justify-between text-xs text-gray-500">
                    <span>Subtotal</span>
                    <span class="font-semibold text-gray-700" x-text="formatRupiah(calculateSubtotal())"></span>
                </div>
                <div class="flex justify-between text-xs text-gray-500">
                    <span>Diskon Diskon (10%)</span>
                    <span class="font-semibold text-emerald-600" x-text="'-' + formatRupiah(calculateDiscount())"></span>
                </div>
                <div class="flex justify-between text-base font-extrabold text-[#3A2B22] pt-2 border-t border-dashed border-gray-200">
                    <span>Grand Total</span>
                    <span x-text="formatRupiah(calculateGrandTotal())"></span>
                </div>

                <!-- Submit Button -->
                <button @click="checkoutProcess()" 
                        :disabled="cart.length === 0 || isLoading"
                        :class="cart.length === 0 || isLoading ? 'bg-gray-300 cursor-not-allowed' : 'bg-brand-brown hover:bg-[#8A5C36] active:scale-[0.99]'"
                        class="w-full py-3.5 text-white font-bold text-sm rounded-2xl shadow-md transition duration-150 mt-2 flex items-center justify-center gap-2">
                    <template x-if="isLoading">
                        <i class="fa-solid fa-spinner animate-spin"></i>
                    </template>
                    <span x-text="isLoading ? 'Memproses...' : 'Place an Order'"></span>
                </button>
            </div>
        </aside>

    </div>

    <!-- Alpine.js Core Logic -->
    <script>
        function purrPosApp() {
            return {
                searchQuery: '',
                selectedCategory: 'Semua',
                fulfillmentMode: 'Dine in',
                isLoading: false,
                categories: ['Semua', 'Kopi', 'Non-Kopi', 'Makanan', 'Cemilan', 'Dessert'],
                cart: [],

                // Dynamic Products dari Database Laravel Backend
                products: [
                    @foreach($products as $product)
                    {
                        id: {{ $product->id }},
                        name: '{{ addslashes($product->name) }}',
                        category: '{{ $product->category->name ?? 'Kopi' }}',
                        price: {{ $product->price }},
                        description: '{{ addslashes($product->description ?? 'Nikmati racikan kopi khas Purr Coffee.') }}',
                        selectedSize: 'Kecil',
                        tempQty: 1,
                        image: '{{ 
                            $product->category && $product->category->name === 'Non-Kopi' ? 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?w=400&auto=format&fit=crop&q=60' :
                            ($product->category && $product->category->name === 'Makanan' ? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&auto=format&fit=crop&q=60' :
                            ($product->category && $product->category->name === 'Cemilan' ? 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=400&auto=format&fit=crop&q=60' :
                            ($product->category && $product->category->name === 'Dessert' ? 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?w=400&auto=format&fit=crop&q=60' :
                            'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=400&auto=format&fit=crop&q=60')))
                        }}'
                    },
                    @endforeach
                ],

                get filteredProducts() {
                    return this.products.filter(p => {
                        const matchCat = this.selectedCategory === 'Semua' || p.category.toLowerCase() === this.selectedCategory.toLowerCase();
                        const matchSearch = p.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                        return matchCat && matchSearch;
                    });
                },

                addToCart(product) {
                    const finalPrice = product.selectedSize === 'Besar' ? product.price + 5000 : product.price;
                    const existingIndex = this.cart.findIndex(item => item.id === product.id && item.size === product.selectedSize);

                    if (existingIndex > -1) {
                        this.cart[existingIndex].qty += (product.tempQty || 1);
                    } else {
                        this.cart.push({
                            id: product.id,
                            name: product.name,
                            price: finalPrice,
                            size: product.selectedSize,
                            qty: product.tempQty || 1,
                            image: product.image
                        });
                    }

                    // Reset Temp Input
                    product.tempQty = 1;
                },

                updateCartQty(index, delta) {
                    this.cart[index].qty += delta;
                    if (this.cart[index].qty <= 0) {
                        this.cart.splice(index, 1);
                    }
                },

                cartTotalCount() {
                    return this.cart.reduce((total, item) => total + item.qty, 0);
                },

                calculateSubtotal() {
                    return this.cart.reduce((total, item) => total + (item.price * item.qty), 0);
                },

                calculateDiscount() {
                    return this.calculateSubtotal() * 0.10; // Diskon 10%
                },

                calculateGrandTotal() {
                    return this.calculateSubtotal() - this.calculateDiscount();
                },

                formatRupiah(amount) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
                },

                async checkoutProcess() {
                    if (this.cart.length === 0) return;
                    this.isLoading = true;

                    const items = this.cart.map(item => ({
                        product_id: item.id,
                        quantity: item.qty
                    }));

                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    try {
                        const response = await fetch('{{ route('kasir.checkout') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                            body: JSON.stringify({ items })
                        });

                        if (response.ok || response.redirected) {
                            alert('🎉 Transaksi Purr\'Coffee Berhasil!\nTipe: ' + this.fulfillmentMode + '\nTotal: ' + this.formatRupiah(this.calculateGrandTotal()));
                            this.cart = [];
                            window.location.reload();
                        } else {
                            const data = await response.json();
                            alert(data.message || 'Gagal memproses transaksi.');
                        }
                    } catch (e) {
                        alert('Terjadi kesalahan jaringan.');
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
</body>
</html>