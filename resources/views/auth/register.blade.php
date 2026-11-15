@extends('layouts.auth')

@section('title', 'Daftar')

@section('content')
    <div class="space-y-8">
        <div class="space-y-3">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-600">Akun penyewa</p>
            <h1 class="text-3xl font-bold tracking-tight text-slate-950">Daftar akun SewaIn</h1>
            <p class="text-sm leading-6 text-slate-500">
                Cukup tentukan username dan password. Setelah mendaftar, Anda langsung bisa menjelajahi katalog dan mengajukan sewa.
            </p>
        </div>

        <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="username" value="Username" />
                <x-text-input id="username" name="username" type="text" :value="old('username')"
                              placeholder="contoh: budi_kamera" autocomplete="username" autofocus required />
                <p class="mt-1.5 text-xs text-slate-400">3–25 karakter. Boleh huruf, angka, tanda hubung, dan garis bawah.</p>
                <x-input-error :messages="$errors->get('username')" />
            </div>

            <div>
                <x-input-label for="password" value="Password" />
                <div class="relative">
                    <x-text-input id="password" name="password" type="password" placeholder="Minimal 6 karakter"
                                  autocomplete="new-password" required style="padding-right: 2.75rem;" />
                    <button type="button" data-password-toggle="#password"
                            class="absolute right-2 top-1/2 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                            aria-label="Tampilkan password">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <div>
                <x-input-label for="password_confirmation" value="Ulangi password" />
                <div class="relative">
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                                  placeholder="Ketik ulang password" autocomplete="new-password" required
                                  style="padding-right: 2.75rem;" />
                    <button type="button" data-password-toggle="#password_confirmation"
                            class="absolute right-2 top-1/2 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                            aria-label="Tampilkan password">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" />
            </div>

            <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-white p-4 text-sm leading-6 text-slate-600">
                <input type="checkbox" required
                       class="mt-1 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <span>Saya menyetujui data yang saya isi digunakan untuk keperluan proses penyewaan di SewaIn.</span>
            </label>

            <x-primary-button class="w-full">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 19a4 4 0 0 0-8 0M12 11a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7ZM19 8v6m3-3h-6"/>
                </svg>
                Daftar sekarang
            </x-primary-button>
        </form>

        <p class="text-center text-sm text-slate-500">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-semibold text-indigo-600 transition hover:text-indigo-700">Masuk di sini</a>
        </p>
    </div>
@endsection
