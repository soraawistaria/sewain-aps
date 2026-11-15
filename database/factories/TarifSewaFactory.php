<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\TarifSewa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TarifSewa>
 */
class TarifSewaFactory extends Factory
{
    protected $model = TarifSewa::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_barang' => Barang::factory(),
            'harga_per_jam' => fake()->numberBetween(5_000, 20_000),
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => null,
        ];
    }
}
