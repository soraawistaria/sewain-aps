@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
    @php($labelStatus = \App\Models\PesananSewa::STATUS_LABELS)

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between print:hidden">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Rekap</p>
            <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Laporan penyewaan</h2>
            <p class="mt-1.5 text-sm text-slate-500">Rekap transaksi dan pendapatan berdasarkan periode.</p>
        </div>
        <button type="button" onclick="window.print()"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8V3h10v5M7 17H5a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-2M7 14h10v7H7v-7Z"/></svg>
            Cetak laporan
        </button>
    </div>

    <div class="mt-6 hidden print:block">
        <h2 class="text-xl font-bold text-slate-950">Laporan Penyewaan SewaIn</h2>
        <p class="mt-1 text-sm text-slate-600">Periode {{ $dari->translatedFormat('d F Y') }} – {{ $sampai->translatedFormat('d F Y') }}</p>
        <p class="mt-0.5 text-xs text-slate-500">Dicetak {{ now()->translatedFormat('d F Y, H:i') }} WITA</p>
    </div>

    <form method="GET" action="{{ route('admin.laporan.index') }}"
          class="mt-6 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-end print:hidden">
        <div class="flex-1">
            <label for="dari" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-400">Dari tanggal</label>
            <input type="date" id="dari" name="dari" value="{{ $dari->toDateString() }}"
                   class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
        </div>
        <div class="flex-1">
            <label for="sampai" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-400">Sampai tanggal</label>
            <input type="date" id="sampai" name="sampai" value="{{ $sampai->toDateString() }}"
                   class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
        </div>
        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16l-6 7v6l-4 2v-8L4 5Z"/></svg>
            Terapkan
        </button>
        <a href="{{ route('admin.laporan.index') }}"
           class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-500 transition hover:border-slate-300">
            Reset
        </a>
    </form>

    <div class="mt-6 grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total pesanan</p>
            <p class="mt-2 text-3xl font-bold tracking-tight text-slate-950">{{ $totalPesanan }}</p>
            <p class="mt-1 text-xs text-slate-500">Pesanan dibuat pada periode ini</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Pendapatan selesai</p>
            <p class="mt-2 text-2xl font-bold tracking-tight text-emerald-600">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-500">Dari pesanan berstatus selesai</p>
        </div>
        <div class="rounded-2xl border border-slate-950 bg-slate-950 p-5 shadow-lg shadow-slate-950/10">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Nilai pesanan disetujui</p>
            <p class="mt-2 text-2xl font-bold tracking-tight text-emerald-200">Rp {{ number_format($totalNilaiDisetujui, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-500">Disetujui, disewa, dan selesai</p>
        </div>
    </div>

    <div class="mt-6 grid items-start gap-6 xl:grid-cols-2">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h3 class="text-base font-bold tracking-tight text-slate-950">Rekap per status</h3>
                <p class="mt-0.5 text-xs text-slate-500">Jumlah dan nilai pesanan pada periode terpilih.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50/70 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Jumlah</th>
                            <th class="px-5 py-3 text-right">Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($labelStatus as $key => $label)
                            @php($baris = $ringkasanStatus->get($key))
                            <tr>
                                <td class="px-5 py-3.5">
                                    <x-status-badge :status="$key" />
                                </td>
                                <td class="px-5 py-3.5 text-right font-semibold text-slate-900">{{ $baris->jumlah ?? 0 }}</td>
                                <td class="px-5 py-3.5 text-right text-slate-600">Rp {{ number_format($baris->total ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50/70">
                        <tr>
                            <td class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Total pesanan</td>
                            <td class="px-5 py-3 text-right font-bold text-slate-950">{{ $totalPesanan }}</td>
                            <td class="px-5 py-3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h3 class="text-base font-bold tracking-tight text-slate-950">Barang terpopuler</h3>
                <p class="mt-0.5 text-xs text-slate-500">Diurutkan berdasarkan jumlah pesanan.</p>
            </div>

            @if ($barangTerpopuler->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50/70 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            <tr>
                                <th class="px-5 py-3">Barang</th>
                                <th class="px-5 py-3 text-right">Pesanan</th>
                                <th class="px-5 py-3 text-right">Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($barangTerpopuler as $item)
                                <tr>
                                    <td class="px-5 py-3.5 font-semibold text-slate-900">{{ $item->barang?->nama ?? 'Barang tidak tersedia' }}</td>
                                    <td class="px-5 py-3.5 text-right font-semibold text-slate-900">{{ $item->jumlah }}×</td>
                                    <td class="px-5 py-3.5 text-right text-slate-600">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-5 py-10 text-center">
                    <p class="text-sm font-semibold text-slate-700">Belum ada data pada periode ini</p>
                    <p class="mt-1 text-xs text-slate-500">Ubah rentang tanggal untuk melihat data lain.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm print:border-0 print:shadow-none">
        <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4">
            <div>
                <h3 class="text-base font-bold tracking-tight text-slate-950">Rincian pesanan</h3>
                <p class="mt-0.5 text-xs text-slate-500">
                    {{ $dari->translatedFormat('d M Y') }} – {{ $sampai->translatedFormat('d M Y') }}
                </p>
            </div>
            <span class="text-xs text-slate-500 print:hidden">{{ $pesanan->total() }} baris</span>
        </div>

        @if ($pesanan->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50/70 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Nomor</th>
                            <th class="px-5 py-3">Penyewa</th>
                            <th class="px-5 py-3">Barang</th>
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3 text-right">Total</th>
                            <th class="px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($pesanan as $item)
                            <tr>
                                <td class="whitespace-nowrap px-5 py-3.5 font-mono text-xs font-semibold text-slate-500">{{ $item->nomor_pesanan }}</td>
                                <td class="px-5 py-3.5 text-slate-700">{{ $item->penyewa?->username ?? '—' }}</td>
                                <td class="px-5 py-3.5 font-semibold text-slate-900">{{ $item->barang?->nama ?? '—' }}</td>
                                <td class="whitespace-nowrap px-5 py-3.5 text-slate-600">
                                    {{ $item->tanggal_mulai->translatedFormat('d M Y') }} – {{ $item->tanggal_selesai->translatedFormat('d M Y') }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-3.5 text-right font-semibold text-slate-900">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                <td class="px-5 py-3.5"><x-status-badge :status="$item->status" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-5 py-4 print:hidden">
                {{ $pesanan->links() }}
            </div>
        @else
            <div class="p-5">
                <x-empty-state
                    title="Belum ada pesanan pada periode ini"
                    message="Coba pilih rentang tanggal yang lebih luas atau pastikan pesanan sudah dibuat." />
            </div>
        @endif
    </div>
@endsection
