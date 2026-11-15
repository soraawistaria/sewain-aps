<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
            <h2 class="text-base font-bold tracking-tight text-slate-950">Informasi barang</h2>
            <p class="mt-0.5 text-xs text-slate-500">Nama dan deskripsi akan tampil di katalog penyewa.</p>
        </div>

        <div class="space-y-5 p-5 sm:p-6">
            <div>
                <x-input-label for="nama" value="Nama barang" />
                <x-text-input id="nama" name="nama" type="text" :value="old('nama', $barang->nama)"
                              placeholder="Contoh: Canon EOS M50" required />
                <x-input-error :messages="$errors->get('nama')" />
            </div>

            <div>
                <x-input-label for="deskripsi" value="Deskripsi" />
                <textarea id="deskripsi" name="deskripsi" rows="4" maxlength="2000"
                          placeholder="Jelaskan kondisi barang, kelengkapan, dan kegunaan utamanya."
                          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                <x-input-error :messages="$errors->get('deskripsi')" />
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
            <h2 class="text-base font-bold tracking-tight text-slate-950">Harga dan stok</h2>
            <p class="mt-0.5 text-xs text-slate-500">Tarif per jam dihitung otomatis dari harga harian sebagai acuan.</p>
        </div>

        <div class="grid gap-5 p-5 sm:grid-cols-2 sm:p-6">
            <div>
                <x-input-label for="harga" value="Harga sewa per hari (Rp)" />
                <x-text-input id="harga" name="harga" type="number" min="1000" step="1000"
                              :value="old('harga', $barang->harga)" placeholder="100000" required />
                <p class="mt-1.5 text-xs text-slate-400">Minimal Rp 1.000 per hari.</p>
                <x-input-error :messages="$errors->get('harga')" />
            </div>

            <div>
                <x-input-label for="stok" value="Stok tersedia (unit)" />
                <x-text-input id="stok" name="stok" type="number" min="0" max="1000"
                              :value="old('stok', $barang->stok ?? 0)" placeholder="1" required />
                <p class="mt-1.5 text-xs text-slate-400">Stok berkurang otomatis saat pesanan dibuat.</p>
                <x-input-error :messages="$errors->get('stok')" />
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
            <h2 class="text-base font-bold tracking-tight text-slate-950">Foto barang</h2>
            <p class="mt-0.5 text-xs text-slate-500">Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.</p>
        </div>

        <div class="p-5 sm:p-6">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-start">
                <div class="w-full sm:w-48">
                    <p class="mb-2 text-xs font-medium uppercase tracking-wider text-slate-400">
                        {{ $barang->foto ? 'Foto saat ini' : 'Belum ada foto' }}
                    </p>
                    <img src="{{ $barang->foto_url }}" alt="Foto {{ $barang->nama ?: 'barang' }}"
                         class="aspect-[4/3] w-full rounded-xl border border-slate-200 object-cover">
                </div>

                <div class="flex-1">
                    <x-input-label for="foto" value="Unggah foto baru" />
                    <input id="foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp"
                           class="block w-full cursor-pointer rounded-xl border border-slate-200 bg-white text-sm text-slate-600 shadow-sm file:mr-4 file:border-0 file:bg-slate-950 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-white transition hover:file:bg-slate-800">
                    <p class="mt-2 text-xs leading-5 text-slate-400">
                        @if ($barang->foto)
                            Biarkan kosong bila tidak ingin mengganti foto. Foto lama akan dihapus saat foto baru diunggah.
                        @else
                            Jika tidak diunggah, katalog akan menampilkan gambar placeholder.
                        @endif
                    </p>
                    <x-input-error :messages="$errors->get('foto')" />
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
        <a href="{{ route('admin.barang.index') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">
            Batal
        </a>
        <x-primary-button>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 4h11l3 3v13H5V4Zm3 0v5h7V4M8 20v-6h8v6"/>
            </svg>
            {{ $submitLabel }}
        </x-primary-button>
    </div>
</form>
