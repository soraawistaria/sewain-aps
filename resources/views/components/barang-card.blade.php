@props(['barang'])

<article class="group flex h-full flex-col overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/70">
    <a href="{{ route('katalog.show', $barang) }}" class="relative block aspect-[4/3] overflow-hidden bg-slate-100">
        <img src="{{ $barang->foto_url }}" alt="{{ $barang->nama }}"
             class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
             loading="lazy">

        <div class="absolute left-3 top-3">
            @if ($barang->stok > 0)
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/90 px-3 py-1.5 text-xs font-semibold text-emerald-700 shadow-sm backdrop-blur">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    Tersedia {{ $barang->stok }} unit
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/90 px-3 py-1.5 text-xs font-semibold text-rose-700 shadow-sm backdrop-blur">
                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                    Stok habis
                </span>
            @endif
        </div>
    </a>

    <div class="flex flex-1 flex-col gap-4 p-5">
        <div class="space-y-2">
            <h3 class="text-lg font-bold tracking-tight text-slate-950">
                <a href="{{ route('katalog.show', $barang) }}" class="transition hover:text-indigo-600">
                    {{ $barang->nama }}
                </a>
            </h3>
            <p class="line-clamp-2 text-sm leading-6 text-slate-500">
                {{ $barang->deskripsi ?: 'Kamera siap pakai untuk kebutuhan dokumentasi dan konten Anda.' }}
            </p>
        </div>

        <div class="mt-auto flex items-end justify-between gap-3">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Mulai dari</p>
                <p class="text-xl font-bold text-slate-950">
                    Rp {{ number_format($barang->harga, 0, ',', '.') }}
                    <span class="text-sm font-medium text-slate-500">/ hari</span>
                </p>
                @if ($barang->tarifTerbaru)
                    <p class="text-xs text-slate-400">
                        ± Rp {{ number_format($barang->tarifTerbaru->harga_per_jam, 0, ',', '.') }} / jam
                    </p>
                @endif
            </div>

            <a href="{{ route('katalog.show', $barang) }}"
               class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-600">
                Sewa
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        </div>
    </div>
</article>
