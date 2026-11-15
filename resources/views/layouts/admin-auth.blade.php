<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin') — SewaIn</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100">
    <div class="grid min-h-screen lg:grid-cols-[1fr_1.05fr]">
        <main class="flex items-center justify-center px-4 py-10 sm:px-8">
            <div class="w-full max-w-md">
                <a href="{{ route('home') }}" class="mb-8 flex items-center gap-3 lg:hidden">
                    <span class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-950 text-emerald-200">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 8.5h2l1.2-2.1A1.5 1.5 0 0 1 8.5 5.7h7a1.5 1.5 0 0 1 1.3.7L18 8.5h2A2 2 0 0 1 22 10.5v7A2 2 0 0 1 20 19.5H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Z"/>
                            <circle cx="12" cy="14" r="3.4"/>
                        </svg>
                    </span>
                    <span>
                        <span class="block text-lg font-bold tracking-tight text-slate-950">Sewa<span class="text-indigo-600">In</span></span>
                        <span class="block text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Panel Admin</span>
                    </span>
                </a>

                @if (session('success') || session('error'))
                    <div class="mb-6 flex items-start gap-3 rounded-2xl border p-4 text-sm {{ session('error') ? 'border-rose-200 bg-rose-50 text-rose-800' : 'border-emerald-200 bg-emerald-50 text-emerald-800' }}">
                        <span class="mt-0.5 grid h-5 w-5 shrink-0 place-items-center rounded-full {{ session('error') ? 'bg-rose-600' : 'bg-emerald-600' }} text-white">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                @if (session('error'))
                                    <path stroke-linecap="round" d="M6 6l12 12M18 6 6 18"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/>
                                @endif
                            </svg>
                        </span>
                        <p class="font-medium leading-6">{{ session('error') ?? session('success') }}</p>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <aside class="relative hidden overflow-hidden bg-slate-950 p-12 lg:flex lg:flex-col lg:justify-between">
            <div class="pointer-events-none absolute -right-24 top-0 h-80 w-80 rounded-full bg-amber-400/15 blur-3xl"></div>
            <div class="pointer-events-none absolute -left-20 bottom-0 h-80 w-80 rounded-full bg-indigo-600/25 blur-3xl"></div>

            <div class="relative flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-2xl bg-white/10 text-emerald-200">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 8.5h2l1.2-2.1A1.5 1.5 0 0 1 8.5 5.7h7a1.5 1.5 0 0 1 1.3.7L18 8.5h2A2 2 0 0 1 22 10.5v7A2 2 0 0 1 20 19.5H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Z"/>
                            <circle cx="12" cy="14" r="3.4"/>
                        </svg>
                    </span>
                    <span class="text-xl font-bold tracking-tight text-white">Sewa<span class="text-emerald-200">In</span></span>
                </a>
                <span class="rounded-full border border-emerald-200/25 bg-emerald-200/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-emerald-100">
                    Administrator
                </span>
            </div>

            <div class="relative max-w-md space-y-7">
                <h2 class="text-4xl font-bold leading-tight tracking-tight text-white">
                    Satu panel untuk mengelola seluruh penyewaan.
                </h2>
                <p class="text-sm leading-7 text-slate-400">
                    Kelola data kamera, konfirmasi pesanan penyewa, pantau pengguna, dan cetak laporan sewa dari satu tempat.
                </p>

                <div class="grid gap-3">
                    <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 p-4">
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-emerald-200/15 text-emerald-200">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 13h6V4H4v9Zm10 7h6v-9h-6v9ZM4 20h6v-3H4v3Zm10-13h6V4h-6v3Z"/></svg>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-white">Data barang</p>
                            <p class="text-xs text-slate-500">Tambah, ubah, dan atur stok kamera.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 p-4">
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-emerald-200/15 text-emerald-200">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 3h8l3 4v14H5V7l3-4ZM9 11h6M9 15h6"/></svg>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-white">Pesanan masuk</p>
                            <p class="text-xs text-slate-500">Setujui, tolak, dan selesaikan pesanan.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 p-4">
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-emerald-200/15 text-emerald-200">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 20V10m7 10V4m7 16v-7"/></svg>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-white">Laporan sewa</p>
                            <p class="text-xs text-slate-500">Rekap transaksi dan pendapatan per periode.</p>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('home') }}" class="relative inline-flex items-center gap-2 text-sm font-semibold text-slate-400 transition hover:text-white">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7 7-7M3 12h18"/></svg>
                Kembali ke situs penyewa
            </a>
        </aside>
    </div>

    @stack('scripts')
</body>
</html>
