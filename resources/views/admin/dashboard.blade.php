@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Ringkasan</p>
            <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Selamat datang, {{ auth('admin')->user()->username }}</h2>
            <p class="mt-1.5 text-sm text-slate-500">Pantau aktivitas penyewaan SewaIn hari ini.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.barang.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/></svg>
                Tambah barang
            </a>
            <a href="{{ route('admin.pesanan.index') }}"
               class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300">
                Lihat pesanan
            </a>
        </div>
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Data barang</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-slate-950">{{ $totalBarang }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ $totalStok }} unit stok tersedia</p>
                </div>
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-indigo-50 text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 8.5h2l1.2-2.1A1.5 1.5 0 0 1 8.5 5.7h7a1.5 1.5 0 0 1 1.3.7L18 8.5h2A2 2 0 0 1 22 10.5v7A2 2 0 0 1 20 19.5H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Z"/>
                        <circle cx="12" cy="14" r="3.4"/>
                    </svg>
                </span>
            </div>
        </div>

        <a href="{{ route('admin.pesanan.index', ['status' => 'menunggu']) }}"
           class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-emerald-300 hover:shadow-md">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Menunggu konfirmasi</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-amber-600">{{ $pesananMenunggu }}</p>
                    <p class="mt-1 text-xs text-slate-500">Perlu segera ditinjau</p>
                </div>
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round" d="M12 7v5l3 2"/>
                    </svg>
                </span>
            </div>
        </a>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Pesanan selesai</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-emerald-600">{{ $pesananSelesai }}</p>
                    <p class="mt-1 text-xs text-slate-500">Transaksi tuntas</p>
                </div>
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/>
                    </svg>
                </span>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-900 bg-slate-950 p-5 shadow-lg shadow-slate-950/10">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Pendapatan selesai</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-emerald-200">Rp {{ number_format($pendapatan, 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-slate-500">Dari pesanan berstatus selesai</p>
                </div>
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-white/10 text-emerald-200">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5m0 14h16M8 16v-5m4 5V8m4 8v-3"/>
                    </svg>
                </span>
            </div>
        </div>
    </div>

    <div class="mt-6 grid items-start gap-6 xl:grid-cols-[1.5fr_1fr]">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4">
                <div>
                    <h2 class="text-base font-bold tracking-tight text-slate-950">Pesanan terbaru</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Enam pesanan terakhir yang masuk.</p>
                </div>
                <a href="{{ route('admin.pesanan.index') }}" class="text-sm font-semibold text-indigo-600 transition hover:text-indigo-700">Semua</a>
            </div>

            @if ($pesananTerbaru->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50/70 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            <tr>
                                <th class="px-5 py-3">Pesanan</th>
                                <th class="px-5 py-3">Penyewa</th>
                                <th class="px-5 py-3">Total</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($pesananTerbaru as $item)
                                <tr class="transition hover:bg-slate-50/60">
                                    <td class="px-5 py-3.5">
                                        <p class="font-mono text-xs font-semibold text-slate-500">{{ $item->nomor_pesanan }}</p>
                                        <p class="mt-0.5 max-w-44 truncate font-semibold text-slate-900">{{ $item->barang?->nama ?? 'Barang tidak tersedia' }}</p>
                                    </td>
                                    <td class="px-5 py-3.5 text-slate-600">{{ $item->penyewa?->username ?? '—' }}</td>
                                    <td class="px-5 py-3.5 font-semibold text-slate-900">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                    <td class="px-5 py-3.5"><x-status-badge :status="$item->status" /></td>
                                    <td class="px-5 py-3.5 text-right">
                                        <a href="{{ route('admin.pesanan.show', $item) }}"
                                           class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 transition hover:text-indigo-700">
                                            Detail
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/></svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-5 py-10 text-center">
                    <p class="text-sm font-semibold text-slate-700">Belum ada pesanan masuk</p>
                    <p class="mt-1 text-xs text-slate-500">Pesanan dari penyewa akan muncul di sini.</p>
                </div>
            @endif
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-base font-bold tracking-tight text-slate-950">Barang paling sering disewa</h2>
                <p class="mt-0.5 text-xs text-slate-500">Berdasarkan jumlah pesanan.</p>
            </div>

            @if ($barangPopuler->isNotEmpty())
                @php($maks = max(1, (int) $barangPopuler->max('pesanan_sewa_count')))
                <ul class="space-y-5 px-5 py-5">
                    @foreach ($barangPopuler as $item)
                        <li>
                            <div class="flex items-center justify-between gap-3 text-sm">
                                <span class="min-w-0 truncate font-semibold text-slate-800">{{ $item->nama }}</span>
                                <span class="shrink-0 text-xs font-semibold text-slate-500">{{ $item->pesanan_sewa_count }} pesanan</span>
                            </div>
                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-indigo-500"
                                     style="width: {{ max(4, (int) round($item->pesanan_sewa_count / $maks * 100)) }}%"></div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="px-5 py-10 text-center">
                    <p class="text-sm font-semibold text-slate-700">Belum ada data</p>
                    <p class="mt-1 text-xs text-slate-500">Tambahkan barang agar statistik muncul.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
