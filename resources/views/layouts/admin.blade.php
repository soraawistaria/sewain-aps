<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Admin') — SewaIn</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100">
    @php($adminAktif = auth('admin')->user())

    <aside data-admin-sidebar
           class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col bg-slate-950 text-slate-300 transition-transform duration-300 lg:translate-x-0 print:hidden">
        <div class="flex h-20 items-center gap-3 border-b border-white/10 px-6">
            <span class="grid h-11 w-11 place-items-center rounded-2xl bg-white/10 text-emerald-200">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 8.5h2l1.2-2.1A1.5 1.5 0 0 1 8.5 5.7h7a1.5 1.5 0 0 1 1.3.7L18 8.5h2A2 2 0 0 1 22 10.5v7A2 2 0 0 1 20 19.5H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Z"/>
                    <circle cx="12" cy="14" r="3.4"/>
                </svg>
            </span>
            <div>
                <p class="text-lg font-bold tracking-tight text-white">Sewa<span class="text-emerald-200">In</span></p>
                <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-slate-500">Panel Admin</p>
            </div>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6" aria-label="Navigasi admin">
            <p class="px-3 pb-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-600">Menu utama</p>

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 13h6V4H4v9Zm10 7h6v-9h-6v9ZM4 20h6v-3H4v3Zm10-13h6V4h-6v3Z"/>
                </svg>
                Dashboard
                @if (request()->routeIs('admin.dashboard'))
                    <span class="ml-auto h-1.5 w-1.5 rounded-full bg-emerald-200"></span>
                @endif
            </a>

            <a href="{{ route('admin.barang.index') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition {{ request()->routeIs('admin.barang.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 8.5h2l1.2-2.1A1.5 1.5 0 0 1 8.5 5.7h7a1.5 1.5 0 0 1 1.3.7L18 8.5h2A2 2 0 0 1 22 10.5v7A2 2 0 0 1 20 19.5H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Z"/>
                    <circle cx="12" cy="14" r="3.4"/>
                </svg>
                Data Barang
                @if (request()->routeIs('admin.barang.*'))
                    <span class="ml-auto h-1.5 w-1.5 rounded-full bg-emerald-200"></span>
                @endif
            </a>

            <a href="{{ route('admin.pesanan.index') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition {{ request()->routeIs('admin.pesanan.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 3h8l3 4v14H5V7l3-4Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 11h6M9 15h6"/>
                </svg>
                Pesanan
                @if (request()->routeIs('admin.pesanan.*'))
                    <span class="ml-auto h-1.5 w-1.5 rounded-full bg-emerald-200"></span>
                @endif
            </a>

            <a href="{{ route('admin.pengguna.index') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition {{ request()->routeIs('admin.pengguna.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 19a4 4 0 0 0-8 0M12 11a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z"/>
                </svg>
                Pengguna
                @if (request()->routeIs('admin.pengguna.*'))
                    <span class="ml-auto h-1.5 w-1.5 rounded-full bg-emerald-200"></span>
                @endif
            </a>

            <a href="{{ route('admin.laporan.index') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition {{ request()->routeIs('admin.laporan.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 20V10m7 10V4m7 16v-7"/>
                </svg>
                Laporan
                @if (request()->routeIs('admin.laporan.*'))
                    <span class="ml-auto h-1.5 w-1.5 rounded-full bg-emerald-200"></span>
                @endif
            </a>

            <p class="px-3 pb-2 pt-6 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-600">Akun</p>

            <a href="{{ route('admin.pengaturan.edit') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition {{ request()->routeIs('admin.pengaturan.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06-1.86 1.86-.06-.06a1.7 1.7 0 0 0-1.88-.34 1.7 1.7 0 0 0-1 1.56V20h-2.63v-.08a1.7 1.7 0 0 0-1-1.56 1.7 1.7 0 0 0-1.88.34l-.06.06-1.86-1.86.06-.06A1.7 1.7 0 0 0 7.4 15a1.7 1.7 0 0 0-1.56-1H5.8v-2.63h.08a1.7 1.7 0 0 0 1.56-1 1.7 1.7 0 0 0-.34-1.88l-.06-.06L8.9 6.6l.06.06a1.7 1.7 0 0 0 1.88.34 1.7 1.7 0 0 0 1-1.56V5.4h2.63v.08a1.7 1.7 0 0 0 1 1.56 1.7 1.7 0 0 0 1.88-.34l.06-.06 1.86 1.86-.06.06a1.7 1.7 0 0 0-.34 1.88 1.7 1.7 0 0 0 1.56 1h.08v2.63h-.08a1.7 1.7 0 0 0-1.56 1Z"/>
                </svg>
                Pengaturan
            </a>
        </nav>

        <div class="border-t border-white/10 p-4">
            <div class="flex items-center gap-3 rounded-2xl bg-white/5 p-3">
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-emerald-200 text-sm font-bold text-slate-950">
                    {{ strtoupper(substr($adminAktif->username, 0, 1)) }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-white">{{ $adminAktif->username }}</p>
                    <p class="text-[11px] text-slate-500">Administrator</p>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="rounded-lg p-2 text-slate-400 transition hover:bg-rose-500/10 hover:text-rose-300" aria-label="Keluar">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 8V5a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2v-3M10 12h11m0 0-3-3m3 3-3 3"/></svg>
                    </button>
                </form>
            </div>
            <a href="{{ route('home') }}" class="mt-3 flex items-center justify-center gap-2 rounded-xl border border-white/10 px-3 py-2.5 text-xs font-semibold text-slate-400 transition hover:bg-white/5 hover:text-white">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7 7-7M3 12h18"/></svg>
                Lihat situs penyewa
            </a>
        </div>
    </aside>

    <div data-admin-overlay class="fixed inset-0 z-40 hidden bg-slate-950/50 backdrop-blur-sm lg:hidden"></div>

    <div class="lg:pl-72">
        <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/85 backdrop-blur-xl print:hidden">
            <div class="flex h-20 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <button type="button" data-admin-sidebar-toggle
                            class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 lg:hidden">
                        <span class="sr-only">Buka menu admin</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                    </button>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Panel Admin</p>
                        <h1 class="text-lg font-bold tracking-tight text-slate-950">@yield('title', 'Dashboard')</h1>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden rounded-xl border border-slate-200 bg-white px-4 py-2 text-right sm:block">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Hari ini</p>
                        <p class="text-sm font-semibold text-slate-700">{{ now()->translatedFormat('d M Y') }}</p>
                    </div>
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-slate-950 text-sm font-bold text-emerald-200">
                        {{ strtoupper(substr($adminAktif->username, 0, 1)) }}
                    </span>
                </div>
            </div>
        </header>

        @if (session('success') || session('error'))
            <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
                <div data-flash class="flex items-start gap-3 rounded-2xl border p-4 shadow-sm transition duration-200 {{ session('error') ? 'border-rose-200 bg-rose-50 text-rose-800' : 'border-emerald-200 bg-emerald-50 text-emerald-800' }}">
                    <span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full {{ session('error') ? 'bg-rose-600' : 'bg-emerald-600' }} text-white">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                            @if (session('error'))
                                <path stroke-linecap="round" d="M6 6l12 12M18 6 6 18"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/>
                            @endif
                        </svg>
                    </span>
                    <p class="flex-1 text-sm font-medium leading-6">{{ session('error') ?? session('success') }}</p>
                    <button type="button" data-flash-close class="rounded-lg p-1 opacity-60 transition hover:bg-black/5 hover:opacity-100" aria-label="Tutup notifikasi">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 6l12 12M18 6 6 18"/></svg>
                    </button>
                </div>
            </div>
        @endif

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
