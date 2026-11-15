@extends('layouts.admin-auth')

@section('title', 'Masuk Admin')

@section('content')
    <div class="space-y-8">
        <div class="space-y-3">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-600">Akses terbatas</p>
            <h1 class="text-3xl font-bold tracking-tight text-slate-950">Masuk panel admin</h1>
            <p class="text-sm leading-6 text-slate-500">
                Halaman ini khusus pengelola SewaIn. Gunakan akun admin untuk mengelola barang, pesanan, dan laporan.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="username" value="Username admin" />
                <x-text-input id="username" name="username" type="text" :value="old('username')"
                              placeholder="Masukkan username admin" autocomplete="username" autofocus required />
                <x-input-error :messages="$errors->get('username')" />
            </div>

            <div>
                <x-input-label for="password" value="Password" />
                <div class="relative">
                    <x-text-input id="password" name="password" type="password" placeholder="Masukkan password admin"
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

            <label class="inline-flex cursor-pointer items-center gap-2 text-sm font-medium text-slate-600">
                <input type="checkbox" name="remember" value="1"
                       class="h-4 w-4 rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                Ingat saya
            </label>

            <button type="submit"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-950/20 transition hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300">
                <svg class="h-4 w-4 text-emerald-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/>
                </svg>
                Masuk panel admin
            </button>
        </form>

        @if ($belumAdaAdmin)
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                <p class="text-sm font-semibold text-amber-900">Belum ada akun admin</p>
                <p class="mt-1.5 text-sm leading-6 text-amber-800">
                    Database admin masih kosong. Buat akun admin pertama melalui halaman setup sebelum masuk.
                </p>
                <a href="{{ route('admin.setup') }}"
                   class="mt-4 inline-flex items-center gap-2 rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-slate-950 transition hover:bg-amber-400">
                    Buat akun admin
                </a>
            </div>
        @endif

        <div class="border-t border-slate-200 pt-6 text-center">
            <p class="text-sm text-slate-500">
                Anda penyewa?
                <a href="{{ route('login') }}" class="font-semibold text-slate-900 underline decoration-slate-300 underline-offset-4 transition hover:text-indigo-600 hover:decoration-indigo-300">
                    Masuk di halaman penyewa
                </a>
            </p>
        </div>
    </div>
@endsection
