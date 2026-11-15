@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
    <div class="space-y-8">
        <div class="space-y-3">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-600">Akun penyewa</p>
            <h1 class="text-3xl font-bold tracking-tight text-slate-950">Masuk ke SewaIn</h1>
            <p class="text-sm leading-6 text-slate-500">
                Gunakan username dan password yang Anda daftarkan untuk melihat katalog dan memantau pesanan sewa.
            </p>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="username" value="Username" />
                <x-text-input id="username" name="username" type="text" :value="old('username')"
                              placeholder="contoh: penyewa" autocomplete="username" autofocus required />
                <x-input-error :messages="$errors->get('username')" />
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <x-input-label for="password" value="Password" />
                    <span class="mb-2 text-xs text-slate-400">Minimal 6 karakter</span>
                </div>
                <div class="relative">
                    <x-text-input id="password" name="password" type="password" placeholder="Masukkan password"
                                  autocomplete="current-password" required style="padding-right: 2.75rem;" />
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

            <div class="flex items-center justify-between gap-3">
                <label class="inline-flex cursor-pointer items-center gap-2 text-sm font-medium text-slate-600">
                    <input type="checkbox" name="remember" value="1"
                           class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    Ingat saya
                </label>
                <a href="{{ route('register') }}" class="text-sm font-semibold text-indigo-600 transition hover:text-indigo-700">
                    Buat akun baru
                </a>
            </div>

            <x-primary-button class="w-full">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/>
                </svg>
                Masuk
            </x-primary-button>
        </form>

        <div class="border-t border-slate-200 pt-6 text-center">
            <p class="text-sm text-slate-500">
                Anda pengelola?
                <a href="{{ route('admin.login') }}" class="font-semibold text-slate-900 underline decoration-slate-300 underline-offset-4 transition hover:text-indigo-600 hover:decoration-indigo-300">
                    Masuk panel admin
                </a>
            </p>
        </div>
    </div>
@endsection
