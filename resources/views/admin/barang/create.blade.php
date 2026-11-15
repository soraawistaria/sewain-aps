@extends('layouts.admin')

@section('title', 'Tambah Barang')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.barang.index') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-slate-900">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7 7-7M3 12h18"/></svg>
            Kembali ke data barang
        </a>
        <h2 class="mt-3 text-2xl font-bold tracking-tight text-slate-950">Tambah barang baru</h2>
        <p class="mt-1.5 text-sm text-slate-500">Lengkapi informasi kamera sebelum ditampilkan di katalog.</p>
    </div>

    @include('admin.barang._form', [
        'barang' => $barang,
        'action' => route('admin.barang.store'),
        'method' => 'POST',
        'submitLabel' => 'Simpan barang',
    ])
@endsection
