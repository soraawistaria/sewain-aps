@extends('layouts.app')

@section('title', 'Sewa Kamera Digital — SewaIn')

@section('content')
    <section class="relative overflow-hidden bg-slate-950">
        <div class="pointer-events-none absolute -left-32 top-0 h-96 w-96 rounded-full bg-indigo-600/30 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-24 bottom-0 h-96 w-96 rounded-full bg-amber-400/15 blur-3xl"></div>
        <div class="pointer-events-none absolute inset-0 opacity-[0.07]"
             style="background-image: linear-gradient(to right, #fff 1px, transparent 1px), linear-gradient(to bottom, #fff 1px, transparent 1px); background-size: 56px 56px;"></div>

        <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-24">
            <div class="mx-auto max-w-3xl text-center">
                <p class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-100">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                    Penyewaan kamera digital
                </p>

                <h1 class="mt-6 text-4xl font-bold leading-[1.1] tracking-tight text-white sm:text-5xl lg:text-6xl">
                    Sewa kamera tanpa ribet,
                    <span class="text-emerald-200">hasil tetap maksimal.</span>
                </h1>

                <p class="mx-auto mt-5 max-w-2xl text-base leading-8 text-slate-400">
                    Temukan kamera yang cocok untuk wisuda, konten, liburan, atau dokumentasi acara. Cek stok, tentukan tanggal, lalu ajukan pesanan langsung dari halaman ini.
                </p>

                <form method="GET" action="{{ route('katalog.index') }}"
                      class="mx-auto mt-9 flex max-w-2xl flex-col gap-3 rounded-2xl border border-white/10 bg-white p-2 shadow-2xl shadow-slate-950/40 sm:flex-row sm:items-center">
                    <div class="relative flex-1">
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="6.5"/>
                                <path stroke-linecap="round" d="m16 16 4 4"/>
                            </svg>
                        </span>
                        <input type="search" name="q" value="{{ $kataKunci }}"
                               placeholder="Cari kamera, misalnya Canon atau vlog…"
                               class="w-full rounded-xl border-0 bg-transparent py-3.5 pl-12 pr-4 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:ring-0">
                    </div>

                    <label class="flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-3 text-xs font-semibold text-slate-600 sm:border-0 sm:bg-slate-50">
                        <input type="checkbox" name="tersedia" value="1" @checked($hanyaTersedia)
                               class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        Stok tersedia
                    </label>

                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-indigo-600/30 transition hover:bg-indigo-700">
                        Cari kamera
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                        </svg>
                    </button>
                </form>

                <div class="mt-10 flex flex-wrap items-center justify-center gap-x-10 gap-y-5 text-sm">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-white">{{ $totalBarang }}</p>
                        <p class="mt-1 text-xs uppercase tracking-wider text-slate-500">Jenis kamera</p>
                    </div>
                    <span class="hidden h-10 w-px bg-white/10 sm:block"></span>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-white">{{ $totalStok }}</p>
                        <p class="mt-1 text-xs uppercase tracking-wider text-slate-500">Unit siap disewa</p>
                    </div>
                    <span class="hidden h-10 w-px bg-white/10 sm:block"></span>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-white">24/7</p>
                        <p class="mt-1 text-xs uppercase tracking-wider text-slate-500">Ajukan kapan saja</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="katalog" class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 border-b border-slate-200 pb-7 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-600">Katalog</p>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">Pilih kamera Anda</h2>
                <p class="mt-2 text-sm text-slate-500">
                    @if ($kataKunci !== '')
                        Hasil pencarian untuk <span class="font-semibold text-slate-800">“{{ $kataKunci }}”</span> —
                    @endif
                    {{ $barang->total() }} barang ditemukan.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if ($hanyaTersedia)
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Hanya stok tersedia
                    </span>
                @endif

                @if ($kataKunci !== '' || $hanyaTersedia)
                    <a href="{{ route('katalog.index') }}"
                       class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-slate-300 hover:text-slate-900">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 6l12 12M18 6 6 18"/></svg>
                        Reset filter
                    </a>
                @endif
            </div>
        </div>

        @if ($barang->isNotEmpty())
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($barang as $item)
                    <x-barang-card :barang="$item" />
                @endforeach
            </div>

            <div class="mt-10">
                {{ $barang->links() }}
            </div>
        @else
            <div class="mt-8">
                <x-empty-state
                    title="Kamera tidak ditemukan"
                    message="Coba gunakan kata kunci lain atau tampilkan seluruh katalog tanpa filter.">
                    <a href="{{ route('katalog.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-600">
                        Lihat semua kamera
                    </a>
                </x-empty-state>
            </div>
        @endif
    </section>

    <section id="cara-sewa" class="border-y border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-600">Cara sewa</p>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">Tiga langkah, kamera siap dipakai</h2>
                <p class="mt-3 text-sm leading-7 text-slate-500">
                    Tidak perlu datang ke toko untuk mengecek ketersediaan. Semua informasi stok dan harga sudah tampil di katalog.
                </p>
            </div>

            <div class="mt-10 grid gap-5 md:grid-cols-3">
                @php
                    $langkah = [
                        ['nomor' => '01', 'judul' => 'Pilih kamera', 'teks' => 'Buka detail barang, cek stok, harga harian, dan tarif per jam sebagai acuan.'],
                        ['nomor' => '02', 'judul' => 'Tentukan tanggal', 'teks' => 'Isi tanggal mulai dan selesai sewa. Total biaya dihitung otomatis sesuai durasi.'],
                        ['nomor' => '03', 'judul' => 'Tunggu konfirmasi', 'teks' => 'Admin memeriksa pesanan Anda. Status terbaru selalu bisa dilihat di halaman pesanan.'],
                    ];
                @endphp

                @foreach ($langkah as $item)
                    <div class="relative rounded-3xl border border-slate-200 bg-slate-50/70 p-7">
                        <span class="text-3xl font-bold tracking-tight text-slate-200">{{ $item['nomor'] }}</span>
                        <h3 class="mt-4 text-lg font-bold text-slate-950">{{ $item['judul'] }}</h3>
                        <p class="mt-2 text-sm leading-7 text-slate-500">{{ $item['teks'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="relative mt-10 overflow-hidden rounded-3xl bg-slate-950 px-6 py-10 sm:px-10">
                <div class="pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full bg-indigo-600/30 blur-3xl"></div>
                <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-2xl font-bold tracking-tight text-white">Siap menyewa kamera?</h3>
                        <p class="mt-2 max-w-xl text-sm leading-7 text-slate-400">
                            Buat akun penyewa, lalu ajukan pesanan pertama Anda. Prosesnya cepat dan tanpa biaya pendaftaran.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        @auth('web')
                            <a href="{{ route('dashboard') }}"
                               class="inline-flex items-center gap-2 rounded-xl bg-emerald-200 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-emerald-100">
                                Buka pesanan saya
                            </a>
                        @else
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center gap-2 rounded-xl bg-emerald-200 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-emerald-100">
                                Daftar akun
                            </a>
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center gap-2 rounded-xl border border-white/15 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/5">
                                Masuk
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
