<?php

namespace App\Services;

use App\Models\Barang;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BarangService
{
    /**
     * @param  array{nama: string, harga: int, deskripsi?: string|null, stok: int}  $data
     */
    public function simpan(Barang $barang, array $data, ?UploadedFile $foto = null): Barang
    {
        if ($foto) {
            $this->hapusFoto($barang->foto);
            $data['foto'] = $this->simpanFoto($foto);
        }

        $barang->fill($data)->save();

        $this->sinkronkanTarif($barang);

        return $barang->refresh();
    }

    public function hapus(Barang $barang): void
    {
        $this->hapusFoto($barang->foto);

        $barang->delete();
    }

    public function simpanFoto(UploadedFile $foto): string
    {
        $folder = public_path('foto_barang');

        File::ensureDirectoryExists($folder);

        $namaFile = Str::uuid()->toString().'.'.$foto->extension();

        $foto->move($folder, $namaFile);

        return $namaFile;
    }

    public function hapusFoto(?string $namaFile): void
    {
        if (! $namaFile || $namaFile === 'placeholder.svg') {
            return;
        }

        $path = public_path('foto_barang/'.$namaFile);

        if (File::exists($path)) {
            File::delete($path);
        }
    }

    /**
     * Tarif per jam disimpan sebagai acuan tampilan, sedangkan perhitungan
     * total sewa menggunakan harga harian pada tabel barang.
     */
    public function sinkronkanTarif(Barang $barang): void
    {
        $hargaPerJam = max(1000, (int) round($barang->harga / 24 / 100) * 100);

        $tarifAktif = $barang->tarifSewa()
            ->whereNull('tanggal_selesai')
            ->latest('tanggal_mulai')
            ->first();

        if ($tarifAktif) {
            $tarifAktif->update(['harga_per_jam' => $hargaPerJam]);

            return;
        }

        $barang->tarifSewa()->create([
            'harga_per_jam' => $hargaPerJam,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => null,
        ]);
    }
}
