<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk') — SewaIn</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50">
    <div class="grid min-h-screen lg:grid-cols-[1.05fr_1fr]">
        <aside class="relative hidden overflow-hidden bg-slate-950 p-12 text-slate-300 lg:flex lg:flex-col lg:justify-between">
            <div class="pointer-events-none absolute -left-24 top-10 h-72 w-72 rounded-full bg-indigo-600/30 blur-3xl"></div>
            <div class="pointer-events-none absolute -right-16 bottom-0 h-80 w-80 rounded-full bg-amber-400/20 blur-3xl"></div>

            <a href="{{ route('home') }}" class="relative flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-2xl bg-white/10 text-emerald-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 8.5h2l1.2-2.1A1.5 1.5 0 0 1 8.5 5.7h7a1.5 1.5 0 0 1 1.3.7L18 8.5h2A2 2 0 0 1 22 10.5v7A2 2 0 0 1 20 19.5H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Z"/>
                        <circle cx="12" cy="14" r="3.4"/>
                    </svg>
                </span>
                <span class="text-xl font-bold tracking-tight text-white">Sewa<span class="text-emerald-200">In</span></span>
            </a>

            <div class="relative max-w-md space-y-8">
                <div class="space-y-4">
                    <p class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-100">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                        Banjarmasin &amp; Banjarbaru
                    </p>
                    <h2 class="text-4xl font-bold leading-tight tracking-tight text-white">
                        Kamera siap pakai untuk momen yang tidak bisa diulang.
                    </h2>
                    <p class="text-sm leading-7 text-slate-400">
                        Pilih kamera, tentukan tanggal sewa, dan ajukan pesanan dalam hitungan menit. Semua proses pemesanan tercatat rapi di dashboard Anda.
                    </p>
                </div>

                <ul class="space-y-4 text-sm">
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-emerald-500/15 text-emerald-300">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                        </span>
                        <span class="text-slate-300">Stok dan harga harian ditampilkan secara transparan.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-emerald-500/15 text-emerald-300">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                        </span>
                        <span class="text-slate-300">Status pesanan dapat dipantau kapan saja.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-emerald-500/15 text-emerald-300">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                        </span>
                        <span class="text-slate-300">Pesanan diproses langsung oleh admin SewaIn.</span>
                    </li>
                </ul>
            </div>

            <p class="relative text-xs leading-6 text-slate-500">
                &copy; {{ date('Y') }} SewaIn. Tugas akhir mata kuliah Analisis dan Perancangan Sistem.
            </p>
        </aside>

        <main class="flex min-h-screen flex-col">
            <div class="flex items-center justify-between border-b border-slate-200 bg-white px-5 py-4 lg:hidden">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-slate-950 text-emerald-200">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 8.5h2l1.2-2.1A1.5 1.5 0 0 1 8.5 5.7h7a1.5 1.5 0 0 1 1.3.7L18 8.5h2A2 2 0 0 1 22 10.5v7A2 2 0 0 1 20 19.5H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Z"/>
                            <circle cx="12" cy="14" r="3.4"/>
                        </svg>
                    </span>
                    <span class="text-lg font-bold tracking-tight text-slate-950">Sewa<span class="text-indigo-600">In</span></span>
                </a>
                <a href="{{ route('home') }}" class="text-sm font-semibold text-slate-500 transition hover:text-slate-900">Kembali ke katalog</a>
            </div>

            <div class="flex flex-1 items-center justify-center px-4 py-10 sm:px-8">
                <div class="w-full max-w-md">
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
            </div>

            <div class="border-t border-slate-200 px-5 py-5 text-center text-xs text-slate-400">
                Butuh bantuan? Hubungi admin SewaIn di <span class="font-semibold text-slate-600">admin@sewain.test</span>
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
