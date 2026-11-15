@extends('layouts.admin-auth')

@section('title', 'Setup Admin')

@section('content')
    <div class="space-y-8">
        <div class="space-y-3">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-600">Setup pertama</p>
            <h1 class="text-3xl font-bold tracking-tight text-slate-950">Buat akun admin</h1>
            <p class="text-sm leading-6 text-slate-500">
                Halaman ini hanya muncul ketika belum ada akun admin di database. Setelah akun pertama dibuat, halaman setup akan ditutup otomatis.
            </p>
        </div>

        <div class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-900">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 4.2 2.8 17.5A1.8 1.8 0 0 0 4.4 20h15.2a1.8 1.8 0 0 0 1.6-2.5L13.7 4.2a2 2 0 0 0-3.4 0Z"/>
            </svg>
            <p>Simpan username dan password admin di tempat aman. Akun ini memiliki akses penuh ke panel pengelolaan.</p>
        </div>

        <form method="POST" action="{{ route('admin.setup.store') }}" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="username" value="Username admin" />
                <x-text-input id="username" name="username" type="text" :value="old('username')"
                              placeholder="contoh: admin_sewain" autocomplete="username" autofocus required />
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
                <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                              placeholder="Ketik ulang password" autocomplete="new-password" required />
                <x-input-error :messages="$errors->get('password_confirmation')" />
            </div>

            <button type="submit"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-950/20 transition hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300">
                <svg class="h-4 w-4 text-emerald-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/>
                </svg>
                Buat akun admin
            </button>
        </form>

        <p class="text-center text-sm text-slate-500">
            Sudah punya akun admin?
            <a href="{{ route('admin.login') }}" class="font-semibold text-slate-900 underline decoration-slate-300 underline-offset-4 transition hover:text-indigo-600 hover:decoration-indigo-300">
                Masuk di sini
            </a>
        </p>
    </div>
@endsection
