@extends('layouts.admin')

@section('title', 'Pesanan')

@section('content')
    @php
        $tabs = [
            ['key' => '', 'label' => 'Semua', 'jumlah' => $jumlahStatus->sum()],
            ['key' => 'menunggu', 'label' => 'Menunggu', 'jumlah' => $jumlahStatus->get('menunggu', 0)],
            ['key' => 'disetujui', 'label' => 'Disetujui', 'jumlah' => $jumlahStatus->get('disetujui', 0)],
            ['key' => 'disewa', 'label' => 'Disewa', 'jumlah' => $jumlahStatus->get('disewa', 0)],
            ['key' => 'selesai', 'label' => 'Selesai', 'jumlah' => $jumlahStatus->get('selesai', 0)],
            ['key' => 'ditolak', 'label' => 'Ditolak', 'jumlah' => $jumlahStatus->get('ditolak', 0)],
            ['key' => 'dibatalkan', 'label' => 'Dibatalkan', 'jumlah' => $jumlahStatus->get('dibatalkan', 0)],
        ];
    @endphp

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Transaksi</p>
            <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Pesanan sewa</h2>
            <p class="mt-1.5 text-sm text-slate-500">Tinjau pengajuan penyewa dan perbarui status pesanan.</p>
        </div>
        <p class="text-xs text-slate-500">{{ $pesanan->total() }} pesanan pada filter ini</p>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-4 pt-4 sm:px-5">
            <div class="flex gap-1 overflow-x-auto pb-1">
                @foreach ($tabs as $tab)
                    <a href="{{ route('admin.pesanan.index', array_filter(['status' => $tab['key'], 'q' => $kataKunci])) }}"
                       class="inline-flex shrink-0 items-center gap-2 rounded-lg px-3.5 py-2.5 text-sm font-semibold transition {{ $status === $tab['key'] ? 'bg-slate-950 text-white' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-900' }}">
                        {{ $tab['label'] }}
                        <span class="rounded-full px-1.5 py-0.5 text-[10px] font-bold {{ $status === $tab['key'] ? 'bg-white/15 text-white' : 'bg-slate-100 text-slate-500' }}">
                            {{ $tab['jumlah'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="border-b border-slate-100 px-4 py-4 sm:px-5">
            <form method="GET" action="{{ route('admin.pesanan.index') }}" class="flex w-full max-w-xl items-center gap-2">
                @if ($status !== '')
                    <input type="hidden" name="status" value="{{ $status }}">
                @endif
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="6.5"/><path stroke-linecap="round" d="m16 16 4 4"/></svg>
                    </span>
                    <input type="search" name="q" value="{{ $kataKunci }}" placeholder="Cari nomor pesanan, penyewa, atau barang…"
                           class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                </div>
                <button type="submit" class="rounded-xl bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                    Cari
                </button>
                @if ($kataKunci !== '')
                    <a href="{{ route('admin.pesanan.index', array_filter(['status' => $status])) }}"
                       class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm font-semibold text-slate-500 transition hover:border-slate-300">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        @if ($pesanan->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50/70 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Pesanan</th>
                            <th class="px-5 py-3">Penyewa</th>
                            <th class="px-5 py-3">Periode sewa</th>
                            <th class="px-5 py-3">Total</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($pesanan as $item)
                            <tr class="transition hover:bg-slate-50/60">
                                <td class="px-5 py-4">
                                    <p class="font-mono text-xs font-semibold text-slate-500">{{ $item->nomor_pesanan }}</p>
                                    <p class="mt-1 max-w-52 truncate font-semibold text-slate-900">{{ $item->barang?->nama ?? 'Barang tidak tersedia' }}</p>
                                    <p class="mt-0.5 text-xs text-slate-400">{{ $item->created_at->translatedFormat('d M Y, H:i') }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-800">{{ $item->penyewa?->username ?? '—' }}</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                    <p>{{ $item->tanggal_mulai->translatedFormat('d M Y') }}</p>
                                    <p class="text-xs text-slate-400">s.d. {{ $item->tanggal_selesai->translatedFormat('d M Y') }} · {{ $item->durasi_hari }} hari</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-900">
                                    Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4"><x-status-badge :status="$item->status" /></td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('admin.pesanan.show', $item) }}"
                                       class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-slate-300 hover:text-slate-950">
                                        Detail
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-5 py-4">
                {{ $pesanan->links() }}
            </div>
        @else
            <div class="p-5">
                <x-empty-state
                    title="Tidak ada pesanan"
                    message="Belum ada pesanan yang sesuai dengan filter atau kata kunci saat ini.">
                    @if ($status !== '' || $kataKunci !== '')
                        <a href="{{ route('admin.pesanan.index') }}"
                           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300">
                            Tampilkan semua pesanan
                        </a>
                    @endif
                </x-empty-state>
            </div>
        @endif
    </div>
@endsection
