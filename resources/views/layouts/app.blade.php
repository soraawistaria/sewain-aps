<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SewaIn — Sewa Kamera Digital')</title>
    <meta name="description" content="SewaIn, marketplace penyewaan kamera digital di Banjarmasin dan Banjarbaru. Cari kamera, cek stok, dan ajukan sewa dengan mudah.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50">
    @php($penggunaAktif = auth('web')->user())

    <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/85 backdrop-blur-xl">
        <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-950 text-emerald-200 shadow-lg shadow-slate-950/20">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 8.5h2l1.2-2.1A1.5 1.5 0 0 1 8.5 5.7h7a1.5 1.5 0 0 1 1.3.7L18 8.5h2A2 2 0 0 1 22 10.5v7A2 2 0 0 1 20 19.5H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Z"/>
                        <circle cx="12" cy="14" r="3.4"/>
                    </svg>
                </span>
                <span class="text-xl font-bold tracking-tight text-slate-950">
                    Sewa<span class="text-indigo-600">In</span>
                </span>
            </a>

            <nav class="hidden items-center gap-1 md:flex" aria-label="Navigasi utama">
                <a href="{{ route('home') }}"
                   class="rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ request()->routeIs('home', 'katalog.*') ? 'bg-slate-100 text-slate-950' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-950' }}">
                    Katalog
                </a>
                <a href="{{ route('home') }}#cara-sewa"
                   class="rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-slate-950">
                    Cara Sewa
                </a>
                @auth('web')
                    <a href="{{ route('dashboard') }}"
                       class="rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ request()->routeIs('dashboard', 'pesanan.*') ? 'bg-slate-100 text-slate-950' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-950' }}">
                        Pesanan Saya
                    </a>
                @endauth
            </nav>

            <div class="hidden items-center gap-3 md:flex">
                @auth('web')
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white py-1.5 pl-1.5 pr-4 shadow-sm transition hover:border-slate-300">
                        <span class="grid h-9 w-9 place-items-center rounded-xl bg-indigo-600 text-sm font-bold text-white">
                            {{ strtoupper(substr($penggunaAktif->username, 0, 1)) }}
                        </span>
                        <span class="text-left">
                            <span class="block text-[11px] font-medium uppercase tracking-wider text-slate-400">Penyewa</span>
                            <span class="block max-w-32 truncate text-sm font-semibold text-slate-900">{{ $penggunaAktif->username }}</span>
                        </span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-500 transition hover:bg-rose-50 hover:text-rose-600">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 hover:text-slate-950">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-slate-950/15 transition hover:bg-indigo-600">
                        Daftar
                    </a>
                @endauth
            </div>

            <button type="button" data-menu-toggle="#menu-pengguna" aria-expanded="false" aria-controls="menu-pengguna"
                    class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 md:hidden">
                <span class="sr-only">Buka menu</span>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <div id="menu-pengguna" class="hidden border-t border-slate-200 bg-white px-4 py-4 md:hidden">
            <nav class="space-y-1" aria-label="Navigasi mobile">
                <a href="{{ route('home') }}" class="block rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Katalog</a>
                <a href="{{ route('home') }}#cara-sewa" class="block rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cara Sewa</a>
                @auth('web')
                    <a href="{{ route('dashboard') }}" class="block rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Pesanan Saya</a>
                    <a href="{{ route('profil.edit') }}" class="block rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Ubah Password</a>
                    <form method="POST" action="{{ route('logout') }}" class="pt-2">
                        @csrf
                        <button type="submit" class="w-full rounded-xl bg-rose-50 px-4 py-3 text-left text-sm font-semibold text-rose-600">Keluar</button>
                    </form>
                @else
                    <div class="grid grid-cols-2 gap-2 pt-2">
                        <a href="{{ route('login') }}" class="rounded-xl border border-slate-200 px-4 py-3 text-center text-sm font-semibold text-slate-700">Masuk</a>
                        <a href="{{ route('register') }}" class="rounded-xl bg-slate-950 px-4 py-3 text-center text-sm font-semibold text-white">Daftar</a>
                    </div>
                @endauth
            </nav>
        </div>
    </header>

    @if (session('success') || session('error'))
        <div class="fixed right-4 top-24 z-50 w-[calc(100%-2rem)] max-w-sm" role="status">
            <div data-flash class="flex items-start gap-3 rounded-2xl border p-4 shadow-xl transition duration-200 {{ session('error') ? 'border-rose-200 bg-rose-50 text-rose-800' : 'border-emerald-200 bg-emerald-50 text-emerald-800' }}">
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

    <main>
        @yield('content')
    </main>

    <footer class="mt-20 bg-slate-950 text-slate-300">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 lg:grid-cols-[1.4fr_1fr_1fr] lg:px-8">
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-2xl bg-white/10 text-emerald-200">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 8.5h2l1.2-2.1A1.5 1.5 0 0 1 8.5 5.7h7a1.5 1.5 0 0 1 1.3.7L18 8.5h2A2 2 0 0 1 22 10.5v7A2 2 0 0 1 20 19.5H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Z"/>
                            <circle cx="12" cy="14" r="3.4"/>
                        </svg>
                    </span>
                    <span class="text-xl font-bold tracking-tight text-white">Sewa<span class="text-emerald-200">In</span></span>
                </div>
                <p class="max-w-sm text-sm leading-7 text-slate-400">
                    Platform penyewaan kamera digital untuk kebutuhan wisuda, konten, liburan, dan dokumentasi acara di Banjarmasin dan Banjarbaru.
                </p>
            </div>

            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Jelajahi</h3>
                <ul class="mt-4 space-y-3 text-sm">
                    <li><a href="{{ route('home') }}" class="transition hover:text-white">Katalog kamera</a></li>
                    <li><a href="{{ route('home') }}#cara-sewa" class="transition hover:text-white">Cara sewa</a></li>
                    @auth('web')
                        <li><a href="{{ route('dashboard') }}" class="transition hover:text-white">Pesanan saya</a></li>
                    @else
                        <li><a href="{{ route('register') }}" class="transition hover:text-white">Daftar akun</a></li>
                    @endauth
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Untuk Pengelola</h3>
                <ul class="mt-4 space-y-3 text-sm">
                    <li><a href="{{ route('admin.login') }}" class="transition hover:text-white">Masuk panel admin</a></li>
                    <li class="text-slate-500">Kelola barang, pesanan, dan laporan.</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-white/10 py-6">
            <p class="mx-auto max-w-7xl px-4 text-center text-xs text-slate-500 sm:px-6 lg:px-8">
                &copy; {{ date('Y') }} SewaIn. Dibuat untuk tugas akhir mata kuliah Analisis dan Perancangan Sistem.
            </p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
