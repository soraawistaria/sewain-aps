@extends('layouts.admin')

@section('title', 'Data Barang')

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Inventaris</p>
            <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Data barang</h2>
            <p class="mt-1.5 text-sm text-slate-500">Kelola kamera yang tersedia untuk disewakan.</p>
        </div>
        <a href="{{ route('admin.barang.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/></svg>
            Tambah barang
        </a>
    </div>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" action="{{ route('admin.barang.index') }}" class="flex w-full max-w-md items-center gap-2">
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="6.5"/><path stroke-linecap="round" d="m16 16 4 4"/></svg>
                    </span>
                    <input type="search" name="q" value="{{ $kataKunci }}" placeholder="Cari nama barang…"
                           class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                </div>
                <button type="submit" class="rounded-xl bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                    Cari
                </button>
                @if ($kataKunci !== '')
                    <a href="{{ route('admin.barang.index') }}" class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm font-semibold text-slate-500 transition hover:border-slate-300">
                        Reset
                    </a>
                @endif
            </form>

            <p class="text-xs text-slate-500">Total {{ $barang->total() }} barang</p>
        </div>

        @if ($barang->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50/70 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Barang</th>
                            <th class="px-5 py-3">Harga / hari</th>
                            <th class="px-5 py-3">Stok</th>
                            <th class="px-5 py-3">Pesanan</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($barang as $item)
                            <tr class="transition hover:bg-slate-50/60">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $item->foto_url }}" alt="{{ $item->nama }}"
                                             class="h-12 w-12 shrink-0 rounded-xl border border-slate-200 object-cover" loading="lazy">
                                        <div class="min-w-0">
                                            <p class="max-w-72 truncate font-semibold text-slate-900">{{ $item->nama }}</p>
                                            <p class="mt-0.5 max-w-72 truncate text-xs text-slate-500">{{ $item->deskripsi ?: 'Tanpa deskripsi' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-900">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4">
                                    @if ($item->stok > 0)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            {{ $item->stok }} unit
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 ring-1 ring-inset ring-rose-600/20">
                                            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                            Habis
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ $item->pesanan_sewa_count }}×</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.barang.edit', $item) }}"
                                           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-slate-300 hover:text-slate-950">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4 20 4.5-1 10-10a2.1 2.1 0 0 0-3-3l-10 10L4 20Z"/></svg>
                                            Ubah
                                        </a>
                                        <form method="POST" action="{{ route('admin.barang.destroy', $item) }}"
                                              data-confirm="Hapus barang {{ $item->nama }}? Tindakan ini tidak dapat dibatalkan.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-600 transition hover:bg-rose-100">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M9 7V5h6v2m-8 0 1 13h8l1-13"/></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-5 py-4">
                {{ $barang->links() }}
            </div>
        @else
            <div class="p-5">
                <x-empty-state
                    title="{{ $kataKunci !== '' ? 'Barang tidak ditemukan' : 'Belum ada barang' }}"
                    message="{{ $kataKunci !== '' ? 'Coba kata kunci lain atau tampilkan seluruh data barang.' : 'Tambahkan kamera pertama agar penyewa bisa mulai memesan.' }}">
                    @if ($kataKunci !== '')
                        <a href="{{ route('admin.barang.index') }}"
                           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300">
                            Tampilkan semua
                        </a>
                    @else
                        <a href="{{ route('admin.barang.create') }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                            Tambah barang
                        </a>
                    @endif
                </x-empty-state>
            </div>
        @endif
    </div>
@endsection
