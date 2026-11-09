<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-700 text-2xl font-extrabold text-white">K</div>
        <h1 class="text-2xl font-extrabold text-slate-800">KasirNih</h1>
        <p class="mt-1 text-sm text-slate-500">Masuk ke akun toko Anda</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" x-data="{ role: '{{ old('role', 'kasir') }}' }">
        @csrf

        <fieldset>
            <legend class="mb-2 block text-sm font-semibold text-slate-700">Masuk sebagai</legend>
            <div class="grid grid-cols-2 gap-3">
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="owner" x-model="role" class="peer sr-only">
                    <span class="flex flex-col items-center gap-2 rounded-xl border border-slate-200 bg-white p-4 text-slate-600 transition peer-checked:border-emerald-700 peer-checked:bg-emerald-50 peer-checked:text-emerald-800">
                        <i class="fa-solid fa-store text-xl"></i>
                        <span class="text-sm font-bold">Owner</span>
                    </span>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="kasir" x-model="role" class="peer sr-only">
                    <span class="flex flex-col items-center gap-2 rounded-xl border border-slate-200 bg-white p-4 text-slate-600 transition peer-checked:border-emerald-700 peer-checked:bg-emerald-50 peer-checked:text-emerald-800">
                        <i class="fa-solid fa-cash-register text-xl"></i>
                        <span class="text-sm font-bold">Kasir</span>
                    </span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </fieldset>

        <div class="mt-5">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@toko.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4 flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-emerald-700 shadow-sm focus:ring-emerald-600" name="remember">
                <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm text-emerald-700 underline hover:text-emerald-900" href="{{ route('password.request') }}">Lupa password?</a>
            @endif
        </div>

        <button type="submit" class="mt-6 flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-700 px-4 py-3 font-bold text-white transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">
            <i class="fa-solid fa-right-to-bracket"></i> Masuk
        </button>
    </form>
</x-guest-layout>
