<?php

namespace Database\Factories;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Barang>
 */
class BarangFactory extends Factory
{
    protected $model = Barang::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->words(3, true),
            'foto' => null,
            'harga' => fake()->numberBetween(50_000, 250_000),
            'deskripsi' => fake()->sentence(12),
            'stok' => fake()->numberBetween(1, 5),
        ];
    }
}
