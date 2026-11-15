<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\Pengguna;
use App\Models\PesananSewa;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PesananService
{
    /**
     * @param  array{tanggal_mulai: string, tanggal_selesai: string, catatan?: string|null}  $data
     */
    public function buat(Pengguna $pengguna, Barang $barang, array $data): PesananSewa
    {
        $tanggalMulai = Carbon::parse($data['tanggal_mulai'])->startOfDay();
        $tanggalSelesai = Carbon::parse($data['tanggal_selesai'])->startOfDay();
        $durasiHari = (int) $tanggalMulai->diffInDays($tanggalSelesai) + 1;

        return DB::transaction(function () use ($pengguna, $barang, $data, $tanggalMulai, $tanggalSelesai, $durasiHari): PesananSewa {
            $barang = Barang::query()->lockForUpdate()->findOrFail($barang->id);

            if ($barang->stok < 1) {
                throw ValidationException::withMessages([
                    'tanggal_mulai' => 'Stok barang ini sedang tidak tersedia.',
                ]);
            }

            $pesanan = PesananSewa::create([
                'nomor_pesanan' => $this->nomorPesananBaru(),
                'id_penyewa' => $pengguna->id,
                'id_barang' => $barang->id,
                'tanggal_mulai' => $tanggalMulai->toDateString(),
                'tanggal_selesai' => $tanggalSelesai->toDateString(),
                'durasi_hari' => $durasiHari,
                'total_harga' => $barang->harga * $durasiHari,
                'status' => PesananSewa::STATUS_MENUNGGU,
                'catatan' => $data['catatan'] ?? null,
            ]);

            $barang->decrement('stok');

            return $pesanan;
        });
    }

    public function setujui(PesananSewa $pesanan): void
    {
        $this->ubahStatus($pesanan, PesananSewa::STATUS_DISETUJUI);
    }

    public function tolak(PesananSewa $pesanan, ?string $alasan = null): void
    {
        $this->ubahStatus(
            $pesanan,
            PesananSewa::STATUS_DITOLAK,
            ['alasan_penolakan' => $alasan],
            kembalikanStok: true,
        );
    }

    public function tandaiDisewa(PesananSewa $pesanan): void
    {
        $this->ubahStatus($pesanan, PesananSewa::STATUS_DISEWA);
    }

    public function selesaikan(PesananSewa $pesanan): void
    {
        $this->ubahStatus($pesanan, PesananSewa::STATUS_SELESAI, kembalikanStok: true);
    }

    public function batalkan(PesananSewa $pesanan): void
    {
        $this->ubahStatus(
            $pesanan,
            PesananSewa::STATUS_DIBATALKAN,
            kembalikanStok: true,
            statusYangDiizinkan: [PesananSewa::STATUS_MENUNGGU],
        );
    }

    /**
     * @param  array<string, mixed>  $atributTambahan
     * @param  array<int, string>|null  $statusYangDiizinkan
     */
    private function ubahStatus(
        PesananSewa $pesanan,
        string $statusBaru,
        array $atributTambahan = [],
        bool $kembalikanStok = false,
        ?array $statusYangDiizinkan = null,
    ): void {
        DB::transaction(function () use ($pesanan, $statusBaru, $atributTambahan, $kembalikanStok, $statusYangDiizinkan): void {
            $pesanan = PesananSewa::query()->lockForUpdate()->findOrFail($pesanan->id);

            $statusYangDiizinkan ??= $this->statusSebelumnya($statusBaru);

            if (! in_array($pesanan->status, $statusYangDiizinkan, true)) {
                $label = PesananSewa::STATUS_LABELS[$pesanan->status] ?? $pesanan->status;

                throw ValidationException::withMessages([
                    'status' => "Status pesanan saat ini adalah {$label} sehingga tidak dapat diubah.",
                ]);
            }

            $pesanan->update(['status' => $statusBaru, ...$atributTambahan]);

            if ($kembalikanStok) {
                Barang::query()
                    ->lockForUpdate()
                    ->find($pesanan->id_barang)
                    ?->increment('stok');
            }
        });
    }

    /**
     * @return array<int, string>
     */
    private function statusSebelumnya(string $statusBaru): array
    {
        return match ($statusBaru) {
            PesananSewa::STATUS_DISETUJUI => [PesananSewa::STATUS_MENUNGGU],
            PesananSewa::STATUS_DITOLAK => [PesananSewa::STATUS_MENUNGGU],
            PesananSewa::STATUS_DISEWA => [PesananSewa::STATUS_DISETUJUI],
            PesananSewa::STATUS_SELESAI => [PesananSewa::STATUS_DISEWA],
            default => [],
        };
    }

    private function nomorPesananBaru(): string
    {
        $tanggal = now()->format('Ymd');
        $urutan = PesananSewa::query()
            ->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
            ->count() + 1;

        do {
            $nomor = 'SWI-'.$tanggal.'-'.str_pad((string) $urutan, 4, '0', STR_PAD_LEFT);
            $urutan++;
        } while (PesananSewa::query()->where('nomor_pesanan', $nomor)->exists());

        return $nomor;
    }
}
