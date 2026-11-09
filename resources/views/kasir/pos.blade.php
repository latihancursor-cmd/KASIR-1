@extends('layouts.kasirnih')

@section('title', 'Penjualan')
@section('page_title', 'Penjualan')

@section('content')
<div x-data="posApp()" class="h-full flex flex-col">
    <!-- Search & Category Filter -->
    <div class="bg-white rounded-2xl border border-kasir-border shadow-sm p-4 mb-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" x-model="searchQuery" placeholder="Cari produk..." class="w-full pl-10 pr-4 py-2.5 bg-kasir-bg rounded-xl border border-kasir-border focus:ring-2 focus:ring-kasir-orange/30 focus:border-kasir-orange outline-none text-sm">
            </div>
            <div class="flex gap-2 overflow-x-auto pb-1">
                <button @click="selectedCategory = null" :class="!selectedCategory ? 'bg-kasir-orange text-white' : 'bg-kasir-bg text-gray-600 hover:bg-gray-100'" class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition">Semua</button>
                <template x-for="cat in categories" :key="cat.id">
                    <button @click="selectedCategory = cat.id" :class="selectedCategory === cat.id ? 'bg-kasir-orange text-white' : 'bg-kasir-bg text-gray-600 hover:bg-gray-100'" class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition" x-text="cat.name"></button>
                </template>
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="flex-1 overflow-y-auto" :class="cart.length > 0 ? 'pb-24' : 'pb-4'">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            <template x-for="product in filteredProducts" :key="product.id">
                <button @click="addToCart(product)" :disabled="product.stock <= 0" :class="product.stock <= 0 ? 'opacity-50 cursor-not-allowed' : 'hover:shadow-md active:scale-[0.98]'" class="bg-white rounded-2xl border border-kasir-border shadow-sm p-3 text-left transition">
                    <div class="w-full h-20 bg-kasir-bg rounded-xl mb-2 flex items-center justify-center text-kasir-green text-3xl">
                        <i class="fa-solid fa-box"></i>
                    </div>
                    <h3 class="font-bold text-xs text-kasir-text truncate mb-0.5" x-text="product.name"></h3>
                    <p class="text-[10px] text-gray-400 mb-1" x-text="product.sku"></p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-extrabold text-kasir-green" x-text="formatRupiah(product.price)"></span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full" :class="product.stock <= 0 ? 'bg-rose-100 text-rose-600' : (product.stock <= product.min_stock ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-600')" x-text="product.stock <= 0 ? 'Habis' : product.stock + ' ' + product.unit"></span>
                    </div>
                </button>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="filteredProducts.length === 0" class="text-center py-12 text-gray-400">
            <i class="fa-solid fa-box-open text-4xl mb-3 text-gray-300"></i>
            <p class="text-sm">Tidak ada produk ditemukan</p>
        </div>
    </div>

    <!-- Toast Notification -->
    <div x-show="showToast" x-transition x-cloak class="fixed top-4 left-1/2 -translate-x-1/2 bg-kasir-green text-white px-4 py-2.5 rounded-xl shadow-lg text-sm font-medium flex items-center gap-2 z-50">
        <i class="fa-solid fa-check-circle"></i>
        <span x-text="toastMessage"></span>
    </div>

    <!-- Receipt Success Modal -->
    <div x-show="showReceipt" x-cloak class="fixed inset-0 bg-black/50 z-50 flex items-end md:items-center justify-center p-4" @click.self="closeReceipt()">
        <div @click.away="closeReceipt()" class="bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden" x-transition>
            <!-- Success Icon -->
            <div class="flex justify-center pt-8 pb-4">
                <div class="w-20 h-20 rounded-full bg-kasir-green/10 flex items-center justify-center">
                    <i class="fa-solid fa-check text-4xl text-kasir-green"></i>
                </div>
            </div>

            <!-- Receipt Content -->
            <div class="px-6 pb-6">
                <h3 class="text-2xl font-bold text-center text-kasir-text mb-1">Transaksi berhasil</h3>
                <p class="text-center text-sm text-gray-500 mb-6">
                    <span x-text="receiptData.invoice_no"></span> • <span x-text="receiptData.payment_method"></span>
                </p>

                <!-- Items List -->
                <div class="bg-kasir-bg rounded-2xl p-4 mb-4 max-h-48 overflow-y-auto">
                    <template x-for="item in receiptData.items" :key="item.name">
                        <div class="flex items-start justify-between py-2 border-b border-kasir-border last:border-0">
                            <div class="flex-1">
                                <p class="font-bold text-sm text-kasir-text" x-text="item.name"></p>
                                <p class="text-xs text-gray-400">
                                    <span x-text="item.qty"></span> <span x-text="item.unit"></span> × <span x-text="formatRupiah(item.price)"></span>
                                </p>
                            </div>
                            <p class="font-bold text-sm text-kasir-green ml-3" x-text="formatRupiah(item.subtotal)"></p>
                        </div>
                    </template>
                </div>

                <!-- Summary -->
                <div class="space-y-2 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="font-semibold" x-text="formatRupiah(receiptData.total)"></span>
                    </div>
                    <div class="flex justify-between text-base font-bold pt-2 border-t border-kasir-border">
                        <span class="text-gray-700">Total</span>
                        <span class="text-kasir-green" x-text="formatRupiah(receiptData.total)"></span>
                    </div>
                    <template x-if="receiptData.payment_method === 'Tunai'">
                        <div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Dibayar</span>
                                <span class="font-semibold" x-text="formatRupiah(receiptData.cash_paid)"></span>
                            </div>
                            <div class="flex justify-between text-sm font-bold text-kasir-orange">
                                <span>Kembalian</span>
                                <span x-text="formatRupiah(receiptData.change)"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Actions -->
                <div class="flex gap-3">
                    <button @click="closeReceipt()" class="flex-1 py-3 border border-kasir-border text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-50 transition">
                        Selesai
                    </button>
                    <button @click="printReceipt()" class="flex-1 py-3 bg-kasir-orange text-white font-bold text-sm rounded-xl hover:bg-kasir-darkOrange transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-print"></i> Cetak Struk
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- CART BOTTOM SHEET (Muncul saat ada item) -->
    <div x-show="cart.length > 0" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="translate-y-full"
         class="fixed bottom-0 left-0 right-0 bg-white border-t-2 border-kasir-orange shadow-2xl z-40 rounded-t-3xl flex flex-col">
        
        <!-- Handle Bar -->
        <div class="flex justify-center py-1.5">
            <div class="w-12 h-1 bg-gray-300 rounded-full"></div>
        </div>

        <!-- Cart Header -->
        <div class="px-4 pb-2 border-b border-kasir-border flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-kasir-text">Keranjang</h3>
                <p class="text-[10px] text-gray-500"><span x-text="cartCount"></span> item • Total: <span class="font-bold text-kasir-green" x-text="formatRupiah(cartTotal)"></span></p>
            </div>
            <button @click="clearCart()" class="text-[10px] text-rose-500 hover:text-rose-700 font-semibold flex items-center gap-1">
                <i class="fa-solid fa-trash text-xs"></i> Hapus
            </button>
        </div>

        <!-- Content Section: Left (Items List) & Right (Payment Method + Total + Checkout) -->
        <div class="px-4 py-2.5 flex flex-col md:flex-row gap-3 items-start">
            <!-- Left Side: Cart Items List Only -->
            <div class="flex-1 min-w-0 w-full">
                <div class="overflow-y-auto max-h-[75px] space-y-1 pr-1">
                    <template x-for="(item, index) in cart" :key="item.id">
                        <div class="bg-kasir-bg rounded-lg px-2.5 py-1.5 flex items-center gap-2 text-xs">
                            <span class="font-bold text-kasir-text truncate flex-1 min-w-0" x-text="item.name"></span>
                            <span class="text-[10px] text-gray-400 shrink-0" x-text="formatRupiah(item.price)"></span>
                            <div class="flex items-center bg-white rounded border border-kasir-border shrink-0">
                                <button type="button" @click="updateQty(index, -1)" class="w-5 h-5 hover:bg-gray-100 flex items-center justify-center text-gray-600">
                                    <i class="fa-solid fa-minus text-[9px]"></i>
                                </button>
                                <span class="w-5 text-center font-bold text-[11px]" x-text="item.qty"></span>
                                <button type="button" @click="updateQty(index, 1)" class="w-5 h-5 hover:bg-gray-100 flex items-center justify-center text-gray-600">
                                    <i class="fa-solid fa-plus text-[9px]"></i>
                                </button>
                            </div>
                            <span class="font-bold text-kasir-green text-[11px] w-14 text-right shrink-0" x-text="formatRupiah(item.price * item.qty)"></span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Right Side: Payment Method + Total + Checkout Button -->
            <div class="w-full md:w-60 shrink-0 bg-kasir-green/5 p-2.5 rounded-xl border border-kasir-border flex flex-col gap-2">
                <!-- Payment Method -->
                <div>
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Metode Pembayaran</span>
                    <div class="flex gap-1.5">
                        <button @click="paymentMethod = 'Tunai'" :class="paymentMethod === 'Tunai' ? 'bg-kasir-green text-white border-kasir-green' : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-300'" class="flex-1 py-1.5 rounded-lg text-[11px] font-semibold transition border flex items-center justify-center gap-1">
                            <i class="fa-solid fa-money-bill-wave text-[10px]"></i> Tunai
                        </button>
                        <button @click="paymentMethod = 'QRIS'" :class="paymentMethod === 'QRIS' ? 'bg-kasir-green text-white border-kasir-green' : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-300'" class="flex-1 py-1.5 rounded-lg text-[11px] font-semibold transition border flex items-center justify-center gap-1">
                            <i class="fa-solid fa-qrcode text-[10px]"></i> QRIS
                        </button>
                    </div>
                </div>

                <!-- Total Price -->
                <div class="flex items-center justify-between py-1.5 border-t border-kasir-border/40">
                    <span class="text-[10px] font-semibold text-gray-500">Total (<span x-text="cartCount"></span> item)</span>
                    <span class="text-base font-extrabold text-kasir-green" x-text="formatRupiah(cartTotal)"></span>
                </div>
                
                <!-- Checkout Button -->
                <button @click="checkout()" :disabled="isProcessing" :class="isProcessing ? 'bg-gray-300 cursor-not-allowed' : 'bg-kasir-orange hover:bg-kasir-darkOrange active:scale-[0.98]'" class="w-full py-2 text-white font-bold text-xs rounded-lg shadow-sm transition flex items-center justify-center gap-1.5">
                    <template x-if="isProcessing">
                        <i class="fa-solid fa-spinner animate-spin text-sm"></i>
                    </template>
                    <template x-if="!isProcessing">
                        <i class="fa-solid fa-check-circle text-sm"></i>
                    </template>
                    <span x-text="isProcessing ? 'Memproses...' : 'Bayar Sekarang'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
</style>

<script>
function posApp() {
    return {
        searchQuery: '',
        selectedCategory: null,
        paymentMethod: 'Tunai',
        isProcessing: false,
        showToast: false,
        showReceipt: false,
        receiptData: { invoice_no: '', payment_method: '', total: 0, cash_paid: 0, change: 0, items: [] },
        toastMessage: '',
        toastTimer: null,
        cart: [],
        categories: {{ Illuminate\Support\Js::from($categories) }},
        products: {{ Illuminate\Support\Js::from($products) }},

        get filteredProducts() {
            return this.products.filter(p => {
                const matchSearch = p.name.toLowerCase().includes(this.searchQuery.toLowerCase()) || p.sku.toLowerCase().includes(this.searchQuery.toLowerCase());
                const matchCategory = !this.selectedCategory || p.category_id === this.selectedCategory;
                const isActive = p.is_active;
                return matchSearch && matchCategory && isActive;
            });
        },

        get cartCount() {
            return this.cart.reduce((sum, item) => sum + item.qty, 0);
        },

        get cartTotal() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        },

        addToCart(product) {
            if (product.stock <= 0) return;
            
            const existingIndex = this.cart.findIndex(item => item.id === product.id);
            if (existingIndex >= 0) {
                if (this.cart[existingIndex].qty < product.stock) {
                    this.cart[existingIndex].qty++;
                    this.showNotification('Ditambahkan: ' + product.name + ' (' + this.cart[existingIndex].qty + 'x)');
                } else {
                    this.showNotification('Stok produk ' + product.name + ' tidak cukup!');
                }
            } else {
                this.cart.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    unit: product.unit,
                    stock: product.stock,
                    qty: 1
                });
                this.showNotification('Ditambahkan: ' + product.name);
            }
        },

        showNotification(message) {
            this.toastMessage = message;
            this.showToast = true;
            
            if (this.toastTimer) clearTimeout(this.toastTimer);
            this.toastTimer = setTimeout(() => {
                this.showToast = false;
            }, 2500);
        },

        updateQty(index, delta) {
            const newQty = this.cart[index].qty + delta;
            if (newQty <= 0) {
                this.removeItem(index);
            } else if (newQty <= this.cart[index].stock) {
                this.cart[index].qty = newQty;
            } else {
                this.showNotification('Stok tidak mencukupi!');
            }
        },

        removeItem(index) {
            const productName = this.cart[index].name;
            this.cart.splice(index, 1);
            this.showNotification(productName + ' dihapus');
        },

        clearCart() {
            if (confirm('Hapus semua item dari keranjang?')) {
                this.cart = [];
                this.showNotification('Keranjang dikosongkan');
            }
        },

        closeReceipt() {
            this.showReceipt = false;
        },

        printReceipt() {
            const printWindow = window.open('', '_blank', 'width=300,height=600');
            const receipt = this.receiptData;
            const storeName = '{{ $settings->store_name ?? "KasirNih" }}';
            const date = new Date().toLocaleString('id-ID');
            
            let itemsHTML = '';
            receipt.items.forEach(item => {
                itemsHTML += `
                    <tr>
                        <td style="padding: 4px 0; font-size: 11px;">${item.name}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0 0 6px 0; font-size: 10px; color: #666;">
                            ${item.qty} ${item.unit} × ${this.formatRupiah(item.price)}
                        </td>
                        <td style="text-align: right; padding: 0 0 6px 0; font-size: 11px; font-weight: bold;">
                            ${this.formatRupiah(item.subtotal)}
                        </td>
                    </tr>
                `;
            });

            let changeHTML = '';
            if (receipt.payment_method === 'Tunai') {
                changeHTML = `
                    <tr>
                        <td style="padding: 3px 0; font-size: 11px;">Dibayar</td>
                        <td style="text-align: right; font-size: 11px; font-weight: bold;">${this.formatRupiah(receipt.cash_paid)}</td>
                    </tr>
                    <tr style="border-top: 1px dashed #ddd;">
                        <td style="padding: 6px 0 0 0; font-size: 12px; font-weight: bold;">Kembalian</td>
                        <td style="text-align: right; padding: 6px 0 0 0; font-size: 12px; font-weight: bold; color: #E6A15C;">${this.formatRupiah(receipt.change)}</td>
                    </tr>
                `;
            }

            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Struk - ${receipt.invoice_no}</title>
                    <style>
                        @media print {
                            @page { margin: 0; size: 58mm auto; }
                            body { margin: 0; padding: 0; }
                        }
                        body {
                            font-family: 'Courier New', monospace;
                            width: 58mm;
                            margin: 0 auto;
                            padding: 10mm 5mm;
                            font-size: 11px;
                            color: #000;
                        }
                        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #000; padding-bottom: 10px; }
                        .header h2 { margin: 0 0 5px 0; font-size: 16px; font-weight: bold; }
                        .header p { margin: 2px 0; font-size: 9px; color: #555; }
                        .info { margin: 10px 0; font-size: 10px; }
                        .info div { display: flex; justify-content: space-between; margin: 3px 0; }
                        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
                        .divider { border-top: 1px dashed #999; margin: 10px 0; }
                        .total-row { border-top: 2px solid #000; padding-top: 8px; }
                        .footer { text-align: center; margin-top: 15px; padding-top: 10px; border-top: 1px dashed #999; font-size: 9px; color: #666; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h2>${storeName}</h2>
                        <p>Terima kasih atas kunjungan Anda</p>
                    </div>
                    
                    <div class="info">
                        <div><span>No. Transaksi</span><span><strong>${receipt.invoice_no}</strong></span></div>
                        <div><span>Tanggal</span><span>${date}</span></div>
                        <div><span>Kasir</span><span>{{ Auth::user()->name ?? 'Admin' }}</span></div>
                        <div><span>Metode</span><span>${receipt.payment_method}</span></div>
                    </div>

                    <div class="divider"></div>

                    <table>
                        ${itemsHTML}
                        <tr class="total-row">
                            <td style="padding: 8px 0 3px 0; font-size: 13px; font-weight: bold;">TOTAL</td>
                            <td style="text-align: right; padding: 8px 0 3px 0; font-size: 13px; font-weight: bold;">${this.formatRupiah(receipt.total)}</td>
                        </tr>
                        ${changeHTML}
                    </table>

                    <div class="footer">
                        <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
                        <p style="margin-top: 8px;">Powered by KasirNih</p>
                    </div>
                </body>
                </html>
            `);
            
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
        },

        formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
        },

        async checkout() {
            if (this.cart.length === 0) return;
            this.isProcessing = true;

            const items = this.cart.map(item => ({
                product_id: item.id,
                quantity: item.qty
            }));

            try {
                const response = await fetch('{{ route('kasir.checkout') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        items: items,
                        payment_method: this.paymentMethod
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.receiptData = {
                        invoice_no: data.invoice_no,
                        payment_method: data.payment_method,
                        total: data.total,
                        cash_paid: data.cash_paid,
                        change: data.change,
                        items: data.items
                    };
                    this.showReceipt = true;
                    this.cart = [];
                } else {
                    this.showNotification(data.message || 'Gagal memproses transaksi.');
                }
            } catch (error) {
                this.showNotification('Gagal terhubung ke server.');
            } finally {
                this.isProcessing = false;
            }
        }
    }
}
</script>
@endsection