@extends('layouts.kasirnih')

@section('title', 'Pengaturan')
@section('page_title', 'Pengaturan')

@section('content')
    <!-- TOKO SECTION -->
    <section class="mb-6">
        <h2 class="text-sm font-semibold text-gray-400 mb-3">Toko</h2>
        <div class="bg-white rounded-2xl border border-kasir-border p-4 shadow-sm" x-data="{ openModal: false }">
            <button @click="openModal = true" class="w-full flex items-center justify-between text-left">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-kasir-green/10 flex items-center justify-center text-kasir-green shrink-0">
                        <i class="fa-solid fa-store"></i>
                    </div>
                    <div>
                        <p class="font-bold text-sm text-kasir-text">{{ $settings->store_name }}</p>
                        <p class="text-xs text-gray-400">{{ $settings->address ?: 'Alamat dan telepon belum diisi' }}</p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 text-sm"></i>
            </button>

            <!-- Modal Edit Profil Toko (Alpine.js Modal) -->
            <div x-show="openModal" x-cloak class="fixed inset-0 bg-black/40 z-50 flex items-end sm:items-center justify-center p-4" @click.self="openModal = false">
                <div @click.away="openModal = false" class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-lg p-6 shadow-2xl" x-transition>
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-lg font-bold text-kasir-text">Profil Toko</h3>
                        <button @click="openModal = false" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('kasir.settings.update') }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="store_name" class="block text-sm font-semibold text-kasir-text mb-2">Nama toko</label>
                            <input type="text" id="store_name" name="store_name" value="{{ old('store_name', $settings->store_name) }}" required class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm">
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-semibold text-kasir-text mb-2">Alamat</label>
                            <textarea id="address" name="address" rows="2" placeholder="Opsional" class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm resize-none">{{ old('address', $settings->address) }}</textarea>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-semibold text-kasir-text mb-2">Telepon / WhatsApp</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $settings->phone) }}" placeholder="Opsional" class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm">
                        </div>

                        <button type="submit" class="w-full py-3.5 bg-kasir-orange hover:bg-kasir-darkOrange text-kasir-text font-bold text-sm rounded-2xl shadow-sm transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-check"></i> Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- OPERASIONAL SECTION -->
    <section class="mb-6">
        <h2 class="text-sm font-semibold text-gray-400 mb-3">Operasional</h2>
        
        <!-- Pengingat Tutup Kasir -->
        <div class="bg-white rounded-2xl border border-kasir-border p-4 shadow-sm mb-3" x-data="{ openTime: false }">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3 flex-1">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-sm text-kasir-text">Pengingat tutup kasir</p>
                        <p class="text-xs text-gray-400">{{ $settings->closing_reminder ? ('Aktif • Jam ' . $settings->closing_time) : 'Nonaktif' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('kasir.settings.update') }}" class="flex items-center gap-2">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="store_name" value="{{ $settings->store_name }}">
                    <input type="hidden" name="address" value="{{ $settings->address }}">
                    <input type="hidden" name="phone" value="{{ $settings->phone }}">
                    <input type="hidden" name="closing_time" value="{{ $settings->closing_time }}">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="closing_reminder" value="1" {{ $settings->closing_reminder ? 'checked' : '' }} onchange="this.form.submit()" class="sr-only peer">
                        <div class="w-12 h-7 bg-gray-200 rounded-full peer peer-checked:bg-kasir-green after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                    </label>
                </form>
            </div>
            
            @if($settings->closing_reminder)
            <button @click="openTime = true" class="mt-3 text-xs text-kasir-orange hover:text-kasir-darkOrange font-semibold flex items-center gap-1">
                <i class="fa-solid fa-clock"></i> Ubah jam pengingat
            </button>
            @endif

            <!-- Time Picker Modal -->
            <div x-show="openTime" x-cloak class="fixed inset-0 bg-black/40 z-50 flex items-end sm:items-center justify-center p-4" @click.self="openTime = false">
                <div @click.away="openTime = false" class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-sm p-6 shadow-2xl" x-transition>
                    <h3 class="text-lg font-bold text-kasir-text mb-4">Pengingat tutup kasir</h3>
                    <form method="POST" action="{{ route('kasir.settings.update') }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="store_name" value="{{ $settings->store_name }}">
                        <input type="hidden" name="address" value="{{ $settings->address }}">
                        <input type="hidden" name="phone" value="{{ $settings->phone }}">
                        <input type="hidden" name="closing_reminder" value="1">
                        
                        <label for="closing_time" class="block text-sm font-semibold text-kasir-text mb-2">Jam tutup kasir</label>
                        <input type="time" id="closing_time" name="closing_time" value="{{ $settings->closing_time }}" required class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm mb-4">
                        
                        <div class="flex gap-2">
                            <button type="button" @click="openTime = false" class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold text-sm rounded-xl transition">Batal</button>
                            <button type="submit" class="flex-1 py-2.5 bg-kasir-orange hover:bg-kasir-darkOrange text-kasir-text font-bold text-sm rounded-xl transition">OK</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kategori Produk -->
        <a href="#kategori" class="bg-white rounded-2xl border border-kasir-border p-4 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600 shrink-0">
                    <i class="fa-solid fa-border-all"></i>
                </div>
                <div>
                    <p class="font-bold text-sm text-kasir-text">Kategori produk</p>
                    <p class="text-xs text-gray-400">Tambah, ubah nama, hapus</p>
                </div>
            </div>
            <i class="fa-solid fa-chevron-right text-gray-400 text-sm"></i>
        </a>
    </section>

    <!-- KATEGORI PRODUK SECTION (Meniru Screenshot 5) -->
    <section id="kategori" class="mb-6" x-data="{ openAddCategory: false, editCategory: null }">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-semibold text-gray-400">Kelola Kategori</h2>
            <button @click="openAddCategory = true" class="text-xs font-semibold text-kasir-green hover:text-kasir-darkGreen flex items-center gap-1">
                <i class="fa-solid fa-plus-circle"></i> Tambah
            </button>
        </div>

        <div class="space-y-3">
            @forelse($categories as $category)
            <div class="bg-white rounded-2xl border border-kasir-border p-4 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-kasir-green/10 flex items-center justify-center text-kasir-green font-bold text-xs shrink-0">
                        {{ $category->code ?? strtoupper(substr($category->name, 0, 3)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-sm text-kasir-text truncate">{{ $category->name }}</p>
                        <p class="text-xs text-gray-400">{{ $category->products_count }} produk</p>
                    </div>
                </div>
                <div class="flex gap-2 shrink-0">
                    <button @click="editCategory = {{ $category->id }}" class="w-8 h-8 rounded-lg bg-kasir-bg hover:bg-gray-200 flex items-center justify-center text-gray-600 transition">
                        <i class="fa-solid fa-pen text-xs"></i>
                    </button>
                    <form method="POST" action="{{ route('kasir.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini? Produk dalam kategori ini tidak akan dihapus.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-100 flex items-center justify-center text-rose-500 transition">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>

                <!-- Edit Modal for this category -->
                <div x-show="editCategory === {{ $category->id }}" x-cloak class="fixed inset-0 bg-black/40 z-50 flex items-end sm:items-center justify-center p-4" @click.self="editCategory = null">
                    <div @click.away="editCategory = null" class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-sm p-6 shadow-2xl" x-transition>
                        <h3 class="text-lg font-bold text-kasir-text mb-4">Edit Kategori</h3>
                        <form method="POST" action="{{ route('kasir.categories.update', $category) }}">
                            @csrf
                            @method('PUT')
                            <label for="name_{{ $category->id }}" class="block text-sm font-semibold text-kasir-text mb-2">Nama kategori</label>
                            <input type="text" id="name_{{ $category->id }}" name="name" value="{{ $category->name }}" required class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm mb-4">
                            <div class="flex gap-2">
                                <button type="button" @click="editCategory = null" class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold text-sm rounded-xl transition">Batal</button>
                                <button type="submit" class="flex-1 py-2.5 bg-kasir-orange hover:bg-kasir-darkOrange text-kasir-text font-bold text-sm rounded-xl transition">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-center text-sm text-gray-400 py-5">Belum ada kategori</p>
            @endforelse
        </div>

        <!-- Add Category Modal -->
        <div x-show="openAddCategory" x-cloak class="fixed inset-0 bg-black/40 z-50 flex items-end sm:items-center justify-center p-4" @click.self="openAddCategory = false">
            <div @click.away="openAddCategory = false" class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-sm p-6 shadow-2xl" x-transition>
                <h3 class="text-lg font-bold text-kasir-text mb-4">Tambah Kategori</h3>
                <form method="POST" action="{{ route('kasir.categories.store') }}">
                    @csrf
                    <label for="new_category_name" class="block text-sm font-semibold text-kasir-text mb-2">Nama kategori</label>
                    <input type="text" id="new_category_name" name="name" required placeholder="Contoh: Sembako" class="w-full px-4 py-3 bg-white rounded-2xl border border-kasir-border focus:ring-2 focus:ring-kasir-green/20 focus:border-kasir-green outline-none text-sm mb-4">
                    <div class="flex gap-2">
                        <button type="button" @click="openAddCategory = false" class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold text-sm rounded-xl transition">Batal</button>
                        <button type="submit" class="flex-1 py-2.5 bg-kasir-orange hover:bg-kasir-darkOrange text-kasir-text font-bold text-sm rounded-xl transition">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <style>
        [x-cloak] { display: none !important; }
    </style>
@endsection