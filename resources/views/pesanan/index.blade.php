@extends('layouts.app')

@section('title', 'Pesanan Saya — SewaIn')

@section('content')
    @php
        $pengguna = auth('web')->user();
    @endphp

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-600">Dashboard penyewa</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">Halo, {{ $pengguna->username }}</h1>
                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Pantau status pesanan sewa Anda dan lihat detail setiap transaksi di satu tempat.
                </p>
            </div>
            <a href="{{ route('katalog.index') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-950/15 transition hover:bg-indigo-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/></svg>
                Sewa kamera baru
            </a>
        </div>

        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @php
                $statistik = [
                    ['key' => 'menunggu', 'label' => 'Menunggu konfirmasi', 'class' => 'bg-amber-50 text-amber-700 ring-amber-600/15', 'dot' => 'bg-amber-500'],
                    ['key' => 'disetujui', 'label' => 'Disetujui', 'class' => 'bg-sky-50 text-sky-700 ring-sky-600/15', 'dot' => 'bg-sky-500'],
                    ['key' => 'disewa', 'label' => 'Sedang disewa', 'class' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/15', 'dot' => 'bg-indigo-500'],
                    ['key' => 'selesai', 'label' => 'Selesai', 'class' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/15', 'dot' => 'bg-emerald-500'],
                ];
            @endphp

            @foreach ($statistik as $item)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $item['class'] }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $item['dot'] }}"></span>
                            {{ $item['label'] }}
                        </span>
                    </div>
                    <p class="mt-4 text-3xl font-bold tracking-tight text-slate-950">{{ $ringkasan->get($item['key'], 0) }}</p>
                    <p class="mt-1 text-xs text-slate-400">pesanan</p>
                </div>
            @endforeach
        </div>

        <div class="mt-8 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h2 class="text-lg font-bold tracking-tight text-slate-950">Riwayat pesanan</h2>
                    <p class="mt-1 text-sm text-slate-500">Total {{ $pesanan->total() }} pesanan tercatat.</p>
                </div>
                <a href="{{ route('profil.edit') }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-slate-900">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06-1.86 1.86-.06-.06a1.7 1.7 0 0 0-1.88-.34 1.7 1.7 0 0 0-1 1.56V20h-2.63v-.08a1.7 1.7 0 0 0-1-1.56 1.7 1.7 0 0 0-1.88.34l-.06.06-1.86-1.86.06-.06A1.7 1.7 0 0 0 7.4 15a1.7 1.7 0 0 0-1.56-1H5.8v-2.63h.08a1.7 1.7 0 0 0 1.56-1 1.7 1.7 0 0 0-.34-1.88l-.06-.06L8.9 6.6l.06.06a1.7 1.7 0 0 0 1.88.34 1.7 1.7 0 0 0 1-1.56V5.4h2.63v.08a1.7 1.7 0 0 0 1 1.56 1.7 1.7 0 0 0 1.88-.34l.06-.06 1.86 1.86-.06.06a1.7 1.7 0 0 0-.34 1.88 1.7 1.7 0 0 0 1.56 1h.08v2.63h-.08a1.7 1.7 0 0 0-1.56 1Z"/>
                    </svg>
                    Ubah password
                </a>
            </div>

            @if ($pesanan->isNotEmpty())
                <div class="divide-y divide-slate-100">
                    @foreach ($pesanan as $item)
                        <div class="flex flex-col gap-4 px-5 py-5 transition hover:bg-slate-50/60 sm:px-6 lg:flex-row lg:items-center">
                            <div class="flex min-w-0 flex-1 items-center gap-4">
                                <img src="{{ $item->barang?->foto_url }}" alt="{{ $item->barang?->nama }}"
                                     class="h-16 w-16 shrink-0 rounded-2xl border border-slate-200 object-cover"
                                     loading="lazy">

                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="font-mono text-xs font-semibold text-slate-400">{{ $item->nomor_pesanan }}</p>
                                        <x-status-badge :status="$item->status" />
                                    </div>
                                    <p class="mt-1.5 truncate text-base font-bold text-slate-950">
                                        {{ $item->barang?->nama ?? 'Barang tidak tersedia' }}
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $item->tanggal_mulai->translatedFormat('d M Y') }}
                                        <span class="text-slate-300">→</span>
                                        {{ $item->tanggal_selesai->translatedFormat('d M Y') }}
                                        <span class="text-slate-300">·</span>
                                        {{ $item->durasi_hari }} hari
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between gap-5 sm:justify-end lg:w-72">
                                <div class="text-left sm:text-right">
                                    <p class="text-[11px] font-medium uppercase tracking-wider text-slate-400">Total</p>
                                    <p class="text-lg font-bold text-slate-950">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</p>
                                </div>
                                <a href="{{ route('pesanan.show', $item) }}"
                                   class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:text-slate-950">
                                    Detail
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-slate-100 px-5 py-5 sm:px-6">
                    {{ $pesanan->links() }}
                </div>
            @else
                <div class="p-6">
                    <x-empty-state
                        title="Belum ada pesanan"
                        message="Anda belum pernah menyewa kamera. Mulai dengan menjelajahi katalog dan pilih kamera yang sesuai kebutuhan.">
                        <a href="{{ route('katalog.index') }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-600">
                            Jelajahi katalog
                        </a>
                    </x-empty-state>
                </div>
            @endif
        </div>
    </div>
@endsection
