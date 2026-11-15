@extends('layouts.admin')

@section('title', 'Pengguna')

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Akun</p>
            <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Data pengguna</h2>
            <p class="mt-1.5 text-sm text-slate-500">Daftar penyewa yang terdaftar di SewaIn.</p>
        </div>
        <p class="text-xs text-slate-500">Total {{ $pengguna->total() }} pengguna</p>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4">
            <form method="GET" action="{{ route('admin.pengguna.index') }}" class="flex w-full max-w-md items-center gap-2">
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="6.5"/><path stroke-linecap="round" d="m16 16 4 4"/></svg>
                    </span>
                    <input type="search" name="q" value="{{ $kataKunci }}" placeholder="Cari username…"
                           class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                </div>
                <button type="submit" class="rounded-xl bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                    Cari
                </button>
                @if ($kataKunci !== '')
                    <a href="{{ route('admin.pengguna.index') }}" class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm font-semibold text-slate-500 transition hover:border-slate-300">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        @if ($pengguna->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50/70 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Pengguna</th>
                            <th class="px-5 py-3">Terdaftar</th>
                            <th class="px-5 py-3">Jumlah pesanan</th>
                            <th class="px-5 py-3">Jenis akun</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($pengguna as $item)
                            <tr class="transition hover:bg-slate-50/60">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-indigo-600 text-sm font-bold text-white">
                                            {{ strtoupper(substr($item->username, 0, 1)) }}
                                        </span>
                                        <div>
                                            <p class="font-semibold text-slate-900">{{ $item->username }}</p>
                                            <p class="text-xs text-slate-400">ID #{{ $item->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                    {{ $item->created_at->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                        {{ $item->pesanan_sewa_count }} pesanan
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                        Penyewa
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-5 py-4">
                {{ $pengguna->links() }}
            </div>
        @else
            <div class="p-5">
                <x-empty-state
                    title="Pengguna tidak ditemukan"
                    message="{{ $kataKunci !== '' ? 'Coba gunakan kata kunci lain.' : 'Belum ada penyewa yang mendaftar.' }}">
                    @if ($kataKunci !== '')
                        <a href="{{ route('admin.pengguna.index') }}"
                           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300">
                            Tampilkan semua
                        </a>
                    @endif
                </x-empty-state>
            </div>
        @endif
    </div>
@endsection
