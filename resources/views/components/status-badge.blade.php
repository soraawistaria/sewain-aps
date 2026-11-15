@props(['status'])

@php
    $classes = match ($status) {
        'menunggu' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'disetujui' => 'bg-sky-50 text-sky-700 ring-sky-600/20',
        'disewa' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
        'selesai' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'ditolak' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
        'dibatalkan' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
        default => 'bg-slate-100 text-slate-600 ring-slate-500/20',
    };
@endphp

<span {{ $attributes->merge([
    'class' => 'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset '.$classes,
]) }}>
    {{ \App\Models\PesananSewa::STATUS_LABELS[$status] ?? ucfirst($status) }}
</span>
