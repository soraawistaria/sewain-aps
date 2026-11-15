@extends('layouts.app')

@section('title', $barang->nama.' — SewaIn')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-sm text-slate-500" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="font-medium transition hover:text-slate-900">Katalog</a>
            <svg class="h-4 w-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/></svg>
            <span class="truncate font-semibold text-slate-800">{{ $barang->nama }}</span>
        </nav>

        <div class="mt-6 grid items-start gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="space-y-6">
                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div class="relative aspect-[16/10] bg-slate-100">
                        <img src="{{ $barang->foto_url }}" alt="{{ $barang->nama }}" class="h-full w-full object-cover">

                        <div class="absolute left-4 top-4">
                            @if ($barang->stok > 0)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/90 px-3 py-1.5 text-xs font-semibold text-emerald-700 shadow-sm backdrop-blur">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Stok tersedia {{ $barang->stok }} unit
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/90 px-3 py-1.5 text-xs font-semibold text-rose-700 shadow-sm backdrop-blur">
                                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                    Stok habis
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-indigo-600">Kamera digital</p>
                            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">{{ $barang->nama }}</h1>
                        </div>
                        <div class="shrink-0 rounded-2xl bg-slate-50 px-5 py-3 text-right">
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Harga sewa</p>
                            <p class="text-2xl font-bold text-slate-950">
                                Rp {{ number_format($barang->harga, 0, ',', '.') }}
                                <span class="text-sm font-medium text-slate-500">/ hari</span>
                            </p>
                        </div>
                    </div>

                    <p class="mt-5 text-sm leading-8 text-slate-600">
                        {{ $barang->deskripsi ?: 'Kamera siap pakai untuk kebutuhan dokumentasi dan konten Anda. Hubungi admin bila membutuhkan informasi lebih lanjut.' }}
                    </p>

                    <div class="mt-7 grid gap-4 border-t border-slate-100 pt-7 sm:grid-cols-3">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Stok tersedia</p>
                            <p class="mt-1.5 text-lg font-bold text-slate-950">{{ $barang->stok }} unit</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Tarif per jam</p>
                            <p class="mt-1.5 text-lg font-bold text-slate-950">
                                @if ($barang->tarifTerbaru)
                                    Rp {{ number_format($barang->tarifTerbaru->harga_per_jam, 0, ',', '.') }}
                                @else
                                    —
                                @endif
                            </p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Status</p>
                            <p class="mt-1.5 text-lg font-bold {{ $barang->stok > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $barang->stok > 0 ? 'Tersedia' : 'Tidak tersedia' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-7 flex items-start gap-3 rounded-2xl border border-indigo-100 bg-indigo-50/70 p-4 text-sm leading-6 text-indigo-900">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-indigo-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9"/>
                            <path stroke-linecap="round" d="M12 11v5m0-8.5v.5"/>
                        </svg>
                        <p>Tarif per jam ditampilkan sebagai acuan. Perhitungan total pesanan menggunakan harga sewa harian dan jumlah hari yang dipilih.</p>
                    </div>
                </div>
            </div>

            <div class="lg:sticky lg:top-28">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/50 sm:p-7">
                    <h2 class="text-lg font-bold tracking-tight text-slate-950">Ajukan penyewaan</h2>
                    <p class="mt-1.5 text-sm leading-6 text-slate-500">Lengkapi tanggal sewa, lalu kirim pesanan ke admin.</p>

                    @auth('web')
                        @if ($barang->stok > 0)
                            <form method="POST" action="{{ route('pesanan.store', $barang) }}" class="mt-6 space-y-4"
                                  data-order-form
                                  data-harga="{{ $barang->harga }}"
                                  data-nama="{{ $barang->nama }}"
                                  data-foto="{{ $barang->foto_url }}">
                                @csrf

                                <div>
                                    <x-input-label for="tanggal_mulai" value="Tanggal mulai" />
                                    <x-text-input id="tanggal_mulai" name="tanggal_mulai" type="date"
                                                  :value="old('tanggal_mulai', now()->toDateString())"
                                                  min="{{ now()->toDateString() }}" required />
                                    <x-input-error :messages="$errors->get('tanggal_mulai')" />
                                </div>

                                <div>
                                    <x-input-label for="tanggal_selesai" value="Tanggal selesai" />
                                    <x-text-input id="tanggal_selesai" name="tanggal_selesai" type="date"
                                                  :value="old('tanggal_selesai', now()->addDay()->toDateString())"
                                                  min="{{ now()->toDateString() }}" required />
                                    <x-input-error :messages="$errors->get('tanggal_selesai')" />
                                </div>

                                <div class="rounded-2xl bg-slate-950 p-4 text-white">
                                    <div class="flex items-center justify-between text-xs text-slate-400">
                                        <span>Durasi sewa</span>
                                        <span data-durasi class="font-semibold text-slate-200">—</span>
                                    </div>
                                    <div class="mt-2 flex items-end justify-between gap-3">
                                        <span class="text-sm font-medium text-slate-300">Total estimasi</span>
                                        <span data-total class="text-xl font-bold text-emerald-200">—</span>
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="catatan" value="Catatan (opsional)" />
                                    <textarea id="catatan" name="catatan" rows="3" maxlength="500"
                                              placeholder="Contoh: butuh lensa kit dan tripod."
                                              class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">{{ old('catatan') }}</textarea>
                                    <x-input-error :messages="$errors->get('catatan')" />
                                </div>

                                <x-primary-button class="w-full">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16v10H4V7Zm0 4h16M8 15h2"/>
                                    </svg>
                                    Sewa sekarang
                                </x-primary-button>

                                <p class="text-center text-xs leading-5 text-slate-400">
                                    Klik tombol sewa untuk membuka pembayaran QRIS. Stok dikurangi setelah pesanan dibuat dan dikembalikan bila pesanan ditolak atau dibatalkan.
                                </p>
                            </form>
                        @else
                            <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 p-5">
                                <p class="text-sm font-semibold text-rose-800">Stok sedang habis</p>
                                <p class="mt-1.5 text-sm leading-6 text-rose-700">
                                    Barang ini sedang disewa seluruhnya. Silakan pilih kamera lain atau cek kembali beberapa waktu ke depan.
                                </p>
                                <a href="{{ route('katalog.index') }}"
                                   class="mt-4 inline-flex items-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700">
                                    Lihat kamera lain
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <span class="grid h-11 w-11 place-items-center rounded-xl bg-white text-slate-500 shadow-sm">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <rect x="4" y="10" width="16" height="10" rx="2"/>
                                    <path stroke-linecap="round" d="M8 10V7a4 4 0 0 1 8 0v3"/>
                                </svg>
                            </span>
                            <p class="mt-4 text-sm font-semibold text-slate-900">Masuk untuk mengajukan sewa</p>
                            <p class="mt-1.5 text-sm leading-6 text-slate-500">
                                Anda perlu memiliki akun penyewa agar pesanan tercatat dan statusnya bisa dipantau.
                            </p>
                            <div class="mt-4 grid grid-cols-2 gap-2">
                                <a href="{{ route('login') }}"
                                   class="rounded-xl bg-slate-950 px-4 py-2.5 text-center text-sm font-semibold text-white transition hover:bg-indigo-600">
                                    Masuk
                                </a>
                                <a href="{{ route('register') }}"
                                   class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:border-slate-400">
                                    Daftar
                                </a>
                            </div>
                        </div>
                    @endauth

                    <div class="mt-6 space-y-3 border-t border-slate-100 pt-5 text-sm text-slate-500">
                        <div class="flex items-center gap-3">
                            <svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                            <span>Konfirmasi langsung oleh admin</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                            <span>Total biaya dihitung otomatis</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                            <span>Status pesanan dapat dipantau</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('katalog.index') }}"
                   class="mt-4 flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-3.5 text-sm font-semibold text-slate-600 transition hover:border-slate-300 hover:text-slate-900">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7 7-7M3 12h18"/></svg>
                    Kembali ke katalog
                </a>
            </div>
        </div>

        {{-- Modal pembayaran QRIS (placeholder, tidak terhubung payment gateway) --}}
        <div data-payment-modal class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="judul-modal-pembayaran">
            <div data-payment-close class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"></div>

            <div class="relative flex min-h-full items-end justify-center p-4 sm:items-center sm:p-6">
                <div class="w-full max-w-lg overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl">
                    <div class="bg-slate-950 px-6 py-5 text-white">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-300">Pembayaran QRIS</p>
                                <h2 id="judul-modal-pembayaran" class="mt-1 text-lg font-bold tracking-tight">Selesaikan pembayaran</h2>
                            </div>
                            <button type="button" data-payment-close
                                    class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-white/10 text-white/80 transition hover:bg-white/20 hover:text-white"
                                    aria-label="Tutup modal pembayaran">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" d="M6 6l12 12M18 6 6 18"/>
                                </svg>
                            </button>
                        </div>
                        <p class="mt-2 text-xs leading-5 text-slate-400">
                            Pindai kode QR di bawah, lalu tekan <span class="font-semibold text-white">Sudah bayar</span> agar pesanan dikirim ke admin.
                        </p>
                    </div>

                    <div class="space-y-5 px-6 py-6">
                        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-3">
                            <img data-modal-foto src="{{ $barang->foto_url }}" alt="{{ $barang->nama }}"
                                 class="h-16 w-16 shrink-0 rounded-xl border border-slate-200 object-cover">
                            <div class="min-w-0 flex-1">
                                <p data-modal-barang class="truncate text-sm font-bold text-slate-950">{{ $barang->nama }}</p>
                                <p data-modal-tanggal class="mt-1 text-xs leading-5 text-slate-500">—</p>
                                <p class="text-xs leading-5 text-slate-500">
                                    <span data-modal-durasi>—</span>
                                    <span class="text-slate-300">·</span>
                                    <span data-modal-harga>Rp {{ number_format($barang->harga, 0, ',', '.') }}</span>/hari
                                </p>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-slate-950 p-4 text-white">
                            <div class="flex items-center justify-between text-xs text-slate-400">
                                <span>Total pembayaran</span>
                                <span class="rounded-full bg-white/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-emerald-300">QRIS</span>
                            </div>
                            <p data-modal-total class="mt-1.5 text-2xl font-bold text-emerald-300">—</p>
                        </div>

                        <div class="grid place-items-center rounded-2xl border border-dashed border-slate-300 bg-white p-4">
                            <img src="{{ asset('images/qris-placeholder.svg') }}" alt="Contoh kode QRIS pembayaran"
                                 class="h-44 w-44" loading="lazy">
                            <p class="mt-3 text-center text-xs leading-5 text-slate-400">
                                QR ini hanya simulasi untuk tugas akhir. Tidak ada transaksi nyata yang diproses.
                            </p>
                        </div>

                        <div class="flex gap-3">
                            <button type="button" data-payment-close
                                    class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:border-slate-400 hover:text-slate-900">
                                Batal
                            </button>
                            <button type="button" data-payment-confirm
                                    class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-950/20 transition hover:bg-indigo-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/>
                                </svg>
                                Sudah bayar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
