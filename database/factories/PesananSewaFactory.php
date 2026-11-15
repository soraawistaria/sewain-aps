<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\Pengguna;
use App\Models\PesananSewa;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PesananSewa>
 */
class PesananSewaFactory extends Factory
{
    protected $model = PesananSewa::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tanggalMulai = now()->addDay();
        $durasi = fake()->numberBetween(1, 5);

        return [
            'nomor_pesanan' => 'SWI-'.now()->format('Ymd').'-'.Str::upper(Str::random(5)),
            'id_penyewa' => Pengguna::factory(),
            'id_barang' => Barang::factory(),
            'tanggal_mulai' => $tanggalMulai->toDateString(),
            'tanggal_selesai' => $tanggalMulai->copy()->addDays($durasi - 1)->toDateString(),
            'durasi_hari' => $durasi,
            'total_harga' => fake()->numberBetween(100_000, 1_000_000),
            'status' => PesananSewa::STATUS_MENUNGGU,
            'catatan' => fake()->optional()->sentence(),
        ];
    }
}
