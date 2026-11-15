@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
    @php
        $langkah = [
            ['key' => 'menunggu', 'label' => 'Diajukan'],
            ['key' => 'disetujui', 'label' => 'Disetujui'],
            ['key' => 'disewa', 'label' => 'Disewa'],
            ['key' => 'selesai', 'label' => 'Selesai'],
        ];
        $posisi = array_search($pesanan->status, array_column($langkah, 'key'), true);
        $statusDalamAlur = $posisi !== false;
    @endphp

    <div class="mb-6">
        <a href="{{ route('admin.pesanan.index') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-slate-900">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7 7-7M3 12h18"/></svg>
            Kembali ke daftar pesanan
        </a>

        <div class="mt-3 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="flex flex-wrap items-center gap-2.5">
                    <p class="font-mono text-sm font-semibold text-slate-400">{{ $pesanan->nomor_pesanan }}</p>
                    <x-status-badge :status="$pesanan->status" />
                </div>
                <h2 class="mt-2 text-2xl font-bold tracking-tight text-slate-950">Detail pesanan</h2>
                <p class="mt-1.5 text-sm text-slate-500">
                    Dibuat {{ $pesanan->created_at->translatedFormat('d F Y, H:i') }} WITA oleh
                    <span class="font-semibold text-slate-700">{{ $pesanan->penyewa?->username ?? 'pengguna tidak ditemukan' }}</span>
                </p>
            </div>
            <p class="text-left sm:text-right">
                <span class="text-[11px] font-medium uppercase tracking-wider text-slate-400">Total pesanan</span>
                <span class="block text-2xl font-bold text-slate-950">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
            </p>
        </div>
    </div>

    @if ($statusDalamAlur)
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="grid gap-4 sm:grid-cols-4">
                @foreach ($langkah as $index => $tahap)
                    @php($selesai = $index <= $posisi)
                    <div class="flex items-center gap-3 sm:block">
                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full text-xs font-bold {{ $selesai ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400 ring-1 ring-inset ring-slate-200' }}">
                            @if ($selesai)
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                            @else
                                {{ $index + 1 }}
                            @endif
                        </span>
                        <p class="mt-0 text-sm font-semibold sm:mt-2 {{ $selesai ? 'text-slate-900' : 'text-slate-400' }}">{{ $tahap['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif ($pesanan->status === \App\Models\PesananSewa::STATUS_DITOLAK)
        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-5">
            <p class="text-sm font-semibold text-rose-800">Pesanan ditolak</p>
            <p class="mt-1.5 text-sm leading-6 text-rose-700">
                {{ $pesanan->alasan_penolakan ?: 'Tidak ada alasan penolakan yang dicatat.' }}
            </p>
        </div>
    @elseif ($pesanan->status === \App\Models\PesananSewa::STATUS_DIBATALKAN)
        <div class="mb-6 rounded-2xl border border-slate-200 bg-slate-100 p-5">
            <p class="text-sm font-semibold text-slate-800">Pesanan dibatalkan oleh penyewa</p>
            <p class="mt-1.5 text-sm leading-6 text-slate-600">Stok barang sudah dikembalikan ke katalog.</p>
        </div>
    @endif

    <div class="grid items-start gap-6 xl:grid-cols-[1.35fr_0.65fr]">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <h3 class="text-base font-bold tracking-tight text-slate-950">Barang yang disewa</h3>

                <div class="mt-4 flex flex-col gap-4 sm:flex-row">
                    <img src="{{ $pesanan->barang?->foto_url }}" alt="{{ $pesanan->barang?->nama }}"
                         class="h-36 w-full rounded-xl border border-slate-200 object-cover sm:h-28 sm:w-40">
                    <div class="min-w-0 flex-1">
                        <a href="{{ $pesanan->barang ? route('katalog.show', $pesanan->barang) : '#' }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-1.5 text-lg font-bold text-slate-950 transition hover:text-indigo-600">
                            {{ $pesanan->barang?->nama ?? 'Barang tidak tersedia' }}
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 5h11v11M19 5 5 19"/></svg>
                        </a>
                        <p class="mt-1.5 line-clamp-2 text-sm leading-6 text-slate-500">
                            {{ $pesanan->barang?->deskripsi ?: 'Tanpa deskripsi.' }}
                        </p>
                        <div class="mt-3 flex flex-wrap gap-2 text-xs">
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 font-semibold text-slate-600">
                                Rp {{ number_format($pesanan->barang?->harga ?? 0, 0, ',', '.') }} / hari
                            </span>
                            @if ($pesanan->barang?->tarifTerbaru)
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 font-semibold text-slate-600">
                                    ± Rp {{ number_format($pesanan->barang->tarifTerbaru->harga_per_jam, 0, ',', '.') }} / jam
                                </span>
                            @endif
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 font-semibold text-slate-600">
                                Stok sekarang: {{ $pesanan->barang?->stok ?? 0 }} unit
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <h3 class="text-base font-bold tracking-tight text-slate-950">Rincian penyewa</h3>

                <div class="mt-4 flex items-center gap-3 rounded-xl bg-slate-50 p-4">
                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-indigo-600 text-sm font-bold text-white">
                        {{ strtoupper(substr($pesanan->penyewa?->username ?? '?', 0, 1)) }}
                    </span>
                    <div>
                        <p class="font-semibold text-slate-900">{{ $pesanan->penyewa?->username ?? 'Pengguna tidak ditemukan' }}</p>
                        <p class="text-xs text-slate-500">
                            Terdaftar {{ $pesanan->penyewa?->created_at?->translatedFormat('d F Y') ?? '—' }}
                        </p>
                    </div>
                </div>

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
                        <dt class="text-xs font-medium uppercase tracking-wider text-slate-400">Status</dt>
                        <dd class="mt-1"><x-status-badge :status="$pesanan->status" /></dd>
                    </div>
                </dl>

                <div class="mt-5 border-t border-slate-100 pt-4">
                    <dt class="text-xs font-medium uppercase tracking-wider text-slate-400">Catatan penyewa</dt>
                    <dd class="mt-2 rounded-xl bg-slate-50 p-4 text-sm leading-7 text-slate-600">
                        {{ $pesanan->catatan ?: 'Tidak ada catatan tambahan.' }}
                    </dd>
                </div>
            </div>
        </div>

        <div class="space-y-4 xl:sticky xl:top-28">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-base font-bold tracking-tight text-slate-950">Tindakan</h3>

                @if ($pesanan->status === \App\Models\PesananSewa::STATUS_MENUNGGU)
                    <p class="mt-1.5 text-sm leading-6 text-slate-500">
                        Periksa ketersediaan barang, lalu setujui atau tolak pengajuan penyewa.
                    </p>

                    <form method="POST" action="{{ route('admin.pesanan.approve', $pesanan) }}" class="mt-4"
                          data-confirm="Setujui pesanan {{ $pesanan->nomor_pesanan }}?">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                            Setujui pesanan
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.pesanan.reject', $pesanan) }}" class="mt-4 space-y-3 border-t border-slate-100 pt-4"
                          data-confirm="Tolak pesanan {{ $pesanan->nomor_pesanan }}? Stok akan dikembalikan.">
                        @csrf
                        @method('PATCH')
                        <div>
                            <x-input-label for="alasan_penolakan" value="Alasan penolakan (opsional)" />
                            <textarea id="alasan_penolakan" name="alasan_penolakan" rows="3" maxlength="500"
                                      placeholder="Contoh: barang sedang dalam perawatan."
                                      class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10"></textarea>
                            <x-input-error :messages="$errors->get('alasan_penolakan')" />
                        </div>
                        <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-600 transition hover:bg-rose-100">
                            Tolak pesanan
                        </button>
                    </form>
                @elseif ($pesanan->status === \App\Models\PesananSewa::STATUS_DISETUJUI)
                    <p class="mt-1.5 text-sm leading-6 text-slate-500">
                        Pesanan sudah disetujui. Tandai sedang disewa setelah barang diserahkan kepada penyewa.
                    </p>
                    <form method="POST" action="{{ route('admin.pesanan.mark-rented', $pesanan) }}" class="mt-4"
                          data-confirm="Tandai pesanan {{ $pesanan->nomor_pesanan }} sebagai sedang disewa?">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8.5h2l1.2-2.1A1.5 1.5 0 0 1 8.5 5.7h7a1.5 1.5 0 0 1 1.3.7L18 8.5h2A2 2 0 0 1 22 10.5v7A2 2 0 0 1 20 19.5H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Z"/><circle cx="12" cy="14" r="3.4"/></svg>
                            Tandai sedang disewa
                        </button>
                    </form>
                @elseif ($pesanan->status === \App\Models\PesananSewa::STATUS_DISEWA)
                    <p class="mt-1.5 text-sm leading-6 text-slate-500">
                        Barang sedang digunakan penyewa. Tandai selesai setelah barang dikembalikan; stok akan bertambah otomatis.
                    </p>
                    <form method="POST" action="{{ route('admin.pesanan.complete', $pesanan) }}" class="mt-4"
                          data-confirm="Selesaikan pesanan {{ $pesanan->nomor_pesanan }} dan kembalikan stok barang?">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                            Selesaikan pesanan
                        </button>
                    </form>
                @elseif ($pesanan->status === \App\Models\PesananSewa::STATUS_SELESAI)
                    <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm leading-6 text-emerald-800">
                        <p class="font-semibold">Pesanan selesai</p>
                        <p class="mt-1 text-emerald-700">Transaksi telah tuntas dan stok barang sudah dikembalikan.</p>
                    </div>
                @elseif ($pesanan->status === \App\Models\PesananSewa::STATUS_DITOLAK)
                    <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm leading-6 text-rose-800">
                        <p class="font-semibold">Pesanan ditolak</p>
                        <p class="mt-1 text-rose-700">Stok barang sudah dikembalikan ke katalog.</p>
                    </div>
                @else
                    <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm leading-6 text-slate-700">
                        <p class="font-semibold">Pesanan dibatalkan</p>
                        <p class="mt-1 text-slate-600">Tidak ada tindakan lanjutan yang diperlukan.</p>
                    </div>
                @endif
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-base font-bold tracking-tight text-slate-950">Ringkasan biaya</h3>
                <dl class="mt-4 space-y-3 text-sm">
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
                        <dd class="text-lg font-bold text-slate-950">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
@endsection
