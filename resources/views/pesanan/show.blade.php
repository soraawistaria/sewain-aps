@extends('layouts.app')

@section('title', 'Pesanan '.$pesanan->nomor_pesanan.' — SewaIn')

@section('content')
    @php
        $langkah = [
            ['key' => 'menunggu', 'label' => 'Diajukan', 'deskripsi' => 'Pesanan diterima sistem'],
            ['key' => 'disetujui', 'label' => 'Disetujui', 'deskripsi' => 'Dikonfirmasi admin'],
            ['key' => 'disewa', 'label' => 'Disewa', 'deskripsi' => 'Barang sedang digunakan'],
            ['key' => 'selesai', 'label' => 'Selesai', 'deskripsi' => 'Barang sudah dikembalikan'],
        ];
        $posisi = array_search($pesanan->status, array_column($langkah, 'key'), true);
        $statusDalamAlur = $posisi !== false;
    @endphp

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-slate-900">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7 7-7M3 12h18"/></svg>
            Kembali ke pesanan saya
        </a>

        <div class="mt-5 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="flex flex-wrap items-center gap-2.5">
                    <p class="font-mono text-sm font-semibold text-slate-400">{{ $pesanan->nomor_pesanan }}</p>
                    <x-status-badge :status="$pesanan->status" />
                </div>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">Detail pesanan</h1>
                <p class="mt-2 text-sm text-slate-500">
                    Dibuat {{ $pesanan->created_at->translatedFormat('d F Y, H:i') }} WITA
                </p>
            </div>
            <p class="text-left sm:text-right">
                <span class="text-[11px] font-medium uppercase tracking-wider text-slate-400">Total biaya</span>
                <span class="block text-2xl font-bold text-slate-950">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
            </p>
        </div>

        @if ($pesanan->status === \App\Models\PesananSewa::STATUS_DITOLAK)
            <div class="mt-7 flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-5 text-rose-800">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-rose-600 text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 6l12 12M18 6 6 18"/></svg>
                </span>
                <div>
                    <p class="font-semibold">Pesanan ini ditolak oleh admin.</p>
                    <p class="mt-1 text-sm leading-6 text-rose-700">
                        {{ $pesanan->alasan_penolakan ?: 'Tidak ada alasan penolakan yang dicantumkan. Silakan hubungi admin untuk informasi lebih lanjut.' }}
                    </p>
                    <a href="{{ route('katalog.index') }}" class="mt-3 inline-flex text-sm font-semibold underline underline-offset-4 hover:text-rose-950">
                        Cari kamera lain
                    </a>
                </div>
            </div>
        @elseif ($pesanan->status === \App\Models\PesananSewa::STATUS_DIBATALKAN)
            <div class="mt-7 flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-100 p-5 text-slate-700">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-slate-500 text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 6l12 12M18 6 6 18"/></svg>
                </span>
                <div>
                    <p class="font-semibold">Pesanan dibatalkan.</p>
                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Stok barang sudah dikembalikan ke katalog. Anda dapat membuat pesanan baru kapan saja.
                    </p>
                </div>
            </div>
        @endif

        @if ($statusDalamAlur)
            <div class="mt-7 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">
                <div class="grid gap-6 sm:grid-cols-4">
                    @foreach ($langkah as $index => $tahap)
                        @php($selesai = $index <= $posisi)
                        <div class="relative flex items-start gap-3 sm:block">
                            @if ($index < count($langkah) - 1)
                                <span class="absolute left-[15px] top-9 h-[calc(100%-1rem)] w-0.5 {{ $index < $posisi ? 'bg-emerald-400' : 'bg-slate-200' }} sm:left-[calc(50%+1.25rem)] sm:top-[15px] sm:h-0.5 sm:w-[calc(100%-2.5rem)]"></span>
                            @endif

                            <span class="relative z-10 grid h-8 w-8 shrink-0 place-items-center rounded-full text-xs font-bold
                                {{ $selesai ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400 ring-1 ring-inset ring-slate-200' }}">
                                @if ($selesai)
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </span>

                            <div class="sm:mt-3">
                                <p class="text-sm font-semibold {{ $selesai ? 'text-slate-950' : 'text-slate-400' }}">{{ $tahap['label'] }}</p>
                                <p class="mt-0.5 text-xs leading-5 {{ $selesai ? 'text-slate-500' : 'text-slate-400' }}">{{ $tahap['deskripsi'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-7 grid items-start gap-6 lg:grid-cols-[1.35fr_0.65fr]">
            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold tracking-tight text-slate-950">Barang yang disewa</h2>

                    <div class="mt-5 flex flex-col gap-5 sm:flex-row">
                        <img src="{{ $pesanan->barang?->foto_url }}" alt="{{ $pesanan->barang?->nama }}"
                             class="h-40 w-full rounded-2xl border border-slate-200 object-cover sm:h-32 sm:w-44">

                        <div class="min-w-0 flex-1">
                            <a href="{{ $pesanan->barang ? route('katalog.show', $pesanan->barang) : '#' }}"
                               class="text-xl font-bold tracking-tight text-slate-950 transition hover:text-indigo-600">
                                {{ $pesanan->barang?->nama ?? 'Barang tidak tersedia' }}
                            </a>
                            <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-500">
                                {{ $pesanan->barang?->deskripsi ?: 'Tidak ada deskripsi barang.' }}
                            </p>
                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                    Rp {{ number_format($pesanan->barang?->harga ?? 0, 0, ',', '.') }} / hari
                                </span>
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                    {{ $pesanan->durasi_hari }} hari
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold tracking-tight text-slate-950">Rincian sewa</h2>

                    <dl class="mt-5 grid gap-5 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-slate-400">Tanggal mulai</dt>
                            <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $pesanan->tanggal_mulai->translatedFormat('l, d F Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-slate-400">Tanggal selesai</dt>
                            <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $pesanan->tanggal_selesai->translatedFormat('l, d F Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-slate-400">Durasi</dt>
                            <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $pesanan->durasi_hari }} hari</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-slate-400">Status pesanan</dt>
                            <dd class="mt-1"><x-status-badge :status="$pesanan->status" /></dd>
                        </div>
                    </dl>

                    <div class="mt-6 border-t border-slate-100 pt-5">
                        <dt class="text-xs font-medium uppercase tracking-wider text-slate-400">Catatan dari penyewa</dt>
                        <dd class="mt-2 rounded-2xl bg-slate-50 p-4 text-sm leading-7 text-slate-600">
                            {{ $pesanan->catatan ?: 'Tidak ada catatan tambahan.' }}
                        </dd>
                    </div>
                </div>
            </div>

            <div class="space-y-4 lg:sticky lg:top-28">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold tracking-tight text-slate-950">Ringkasan biaya</h2>

                    <dl class="mt-5 space-y-3 text-sm">
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-slate-500">Harga per hari</dt>
                            <dd class="font-semibold text-slate-900">Rp {{ number_format($pesanan->barang?->harga ?? 0, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-slate-500">Durasi</dt>
                            <dd class="font-semibold text-slate-900">{{ $pesanan->durasi_hari }} hari</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3 border-t border-dashed border-slate-200 pt-3">
                            <dt class="font-semibold text-slate-900">Total</dt>
                            <dd class="text-xl font-bold text-slate-950">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</dd>
                        </div>
                    </dl>

                    @if ($pesanan->bisaDibatalkan())
                        <form method="POST" action="{{ route('pesanan.cancel', $pesanan) }}" class="mt-6"
                              data-confirm="Batalkan pesanan {{ $pesanan->nomor_pesanan }}? Stok akan dikembalikan ke katalog.">
                            @csrf
                            @method('PATCH')
                            <x-danger-button class="w-full">
                                Batalkan pesanan
                            </x-danger-button>
                        </form>
                        <p class="mt-3 text-center text-xs leading-5 text-slate-400">
                            Pesanan hanya dapat dibatalkan selama statusnya masih menunggu konfirmasi.
                        </p>
                    @endif
                </div>

                <div class="rounded-3xl border border-indigo-100 bg-indigo-50/70 p-5 text-sm leading-6 text-indigo-900">
                    <p class="font-semibold">Butuh perubahan pesanan?</p>
                    <p class="mt-1.5 text-indigo-800">
                        Hubungi admin SewaIn. Perubahan tanggal atau pembatalan setelah disetujui diproses secara manual.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
