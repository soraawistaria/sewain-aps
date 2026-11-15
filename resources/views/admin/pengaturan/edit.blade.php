@extends('layouts.admin')

@section('title', 'Pengaturan')

@section('content')
    @php($admin = auth('admin')->user())

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Akun</p>
            <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Pengaturan admin</h2>
            <p class="mt-1.5 text-sm text-slate-500">Kelola kredensial akun administrator Anda.</p>
        </div>
    </div>

    <div class="mt-6 grid items-start gap-6 xl:grid-cols-[0.8fr_1.2fr]">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-center gap-4">
                <span class="grid h-14 w-14 place-items-center rounded-2xl bg-slate-950 text-lg font-bold text-emerald-200">
                    {{ strtoupper(substr($admin->username, 0, 1)) }}
                </span>
                <div class="min-w-0">
                    <p class="truncate text-lg font-bold text-slate-950">{{ $admin->username }}</p>
                    <p class="text-sm text-slate-500">Administrator</p>
                </div>
            </div>

            <dl class="mt-6 space-y-4 border-t border-slate-100 pt-5 text-sm">
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wider text-slate-400">Username</dt>
                    <dd class="mt-1 font-mono font-semibold text-slate-900">{{ $admin->username }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wider text-slate-400">Terdaftar sejak</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $admin->created_at->translatedFormat('d F Y') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wider text-slate-400">Hak akses</dt>
                    <dd class="mt-1 font-semibold text-slate-900">Administrator penuh</dd>
                </div>
            </dl>

            <div class="mt-6 rounded-xl bg-amber-50 p-4 text-xs leading-6 text-amber-900 ring-1 ring-inset ring-amber-600/10">
                Akun admin dapat mengelola barang, memproses pesanan, melihat data pengguna, dan mencetak laporan.
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-start gap-3">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-slate-100 text-slate-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <rect x="4" y="10" width="16" height="10" rx="2"/>
                        <path stroke-linecap="round" d="M8 10V7a4 4 0 0 1 8 0v3"/>
                    </svg>
                </span>
                <div>
                    <h3 class="text-base font-bold tracking-tight text-slate-950">Ubah password admin</h3>
                    <p class="mt-0.5 text-sm leading-6 text-slate-500">Masukkan password saat ini untuk mengonfirmasi perubahan.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.pengaturan.password') }}" class="mt-6 space-y-5">
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
                    <p class="text-xs leading-5 text-slate-400">Setelah password diubah, gunakan password baru pada proses masuk berikutnya.</p>
                    <x-primary-button>
                        Simpan password
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
@endsection
