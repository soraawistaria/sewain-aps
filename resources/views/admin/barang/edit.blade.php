@extends('layouts.admin')

@section('title', 'Ubah Barang')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.barang.index') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-slate-900">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7 7-7M3 12h18"/></svg>
            Kembali ke data barang
        </a>
        <div class="mt-3 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-950">Ubah data barang</h2>
                <p class="mt-1.5 text-sm text-slate-500">Perbarui informasi {{ $barang->nama }}.</p>
            </div>
            <a href="{{ route('katalog.show', $barang) }}" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 transition hover:text-indigo-700">
                Lihat di katalog
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 5h11v11M19 5 5 19"/></svg>
            </a>
        </div>
    </div>

    @include('admin.barang._form', [
        'barang' => $barang,
        'action' => route('admin.barang.update', $barang),
        'method' => 'PUT',
        'submitLabel' => 'Simpan perubahan',
    ])
@endsection
