@extends('layouts.app')

@section('title', 'Pengaturan Akun — SewaIn')

@section('content')
    @php($pengguna = auth('web')->user())

    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-slate-900">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7 7-7M3 12h18"/></svg>
            Kembali ke pesanan saya
        </a>

        <div class="mt-5">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-600">Akun</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">Pengaturan akun</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                Kelola informasi akun dan perbarui password Anda secara berkala untuk menjaga keamanan.
            </p>
        </div>

        <div class="mt-8 grid items-start gap-6 lg:grid-cols-[0.8fr_1.2fr]">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <span class="grid h-14 w-14 place-items-center rounded-2xl bg-indigo-600 text-xl font-bold text-white">
                        {{ strtoupper(substr($pengguna->username, 0, 1)) }}
                    </span>
                    <div class="min-w-0">
                        <p class="truncate text-lg font-bold text-slate-950">{{ $pengguna->username }}</p>
                        <p class="text-sm text-slate-500">Penyewa</p>
                    </div>
                </div>

                <dl class="mt-6 space-y-4 border-t border-slate-100 pt-5 text-sm">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-slate-400">Username</dt>
                        <dd class="mt-1 font-mono font-semibold text-slate-900">{{ $pengguna->username }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-slate-400">Terdaftar sejak</dt>
                        <dd class="mt-1 font-semibold text-slate-900">{{ $pengguna->created_at->translatedFormat('d F Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-slate-400">Jenis akun</dt>
                        <dd class="mt-1 font-semibold text-slate-900">Akun penyewa</dd>
                    </div>
                </dl>

                <p class="mt-6 rounded-2xl bg-slate-50 p-4 text-xs leading-6 text-slate-500">
                    Username tidak dapat diubah karena digunakan sebagai identitas masuk. Hubungi admin jika memerlukan bantuan.
                </p>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex items-start gap-3">
                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-slate-100 text-slate-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <rect x="4" y="10" width="16" height="10" rx="2"/>
                            <path stroke-linecap="round" d="M8 10V7a4 4 0 0 1 8 0v3"/>
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-lg font-bold tracking-tight text-slate-950">Ubah password</h2>
                        <p class="mt-1 text-sm leading-6 text-slate-500">Masukkan password saat ini untuk mengonfirmasi perubahan.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('profil.update') }}" class="mt-6 space-y-5">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="current_password" value="Password saat ini" />
                        <div class="relative">
                            <x-text-input id="current_password" name="current_password" type="password"
                                          placeholder="Password yang sedang digunakan" autocomplete="current-password" required
                                          style="padding-right: 2.75rem;" />
                            <button type="button" data-password-toggle="#current_password"
                                    class="absolute right-2 top-1/2 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                                    aria-label="Tampilkan password">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('current_password')" />
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <x-input-label for="password" value="Password baru" />
                            <x-text-input id="password" name="password" type="password" placeholder="Minimal 6 karakter"
                                          autocomplete="new-password" required />
                            <x-input-error :messages="$errors->get('password')" />
                        </div>

                        <div>
                            <x-input-label for="password_confirmation" value="Ulangi password baru" />
                            <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                                          placeholder="Ketik ulang password baru" autocomplete="new-password" required />
                            <x-input-error :messages="$errors->get('password_confirmation')" />
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs leading-5 text-slate-400">Gunakan kombinasi huruf dan angka agar password lebih aman.</p>
                        <x-primary-button>
                            Simpan password
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
