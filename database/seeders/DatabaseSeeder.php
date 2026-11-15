<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Barang;
use App\Models\Pengguna;
use App\Services\BarangService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Admin::query()->firstOrCreate(
            ['username' => 'admin'],
            ['password' => 'admin123'],
        );

        Pengguna::query()->firstOrCreate(
            ['username' => 'penyewa'],
            ['password' => 'penyewa123'],
        );

        $daftarBarang = [
            [
                'nama' => 'Canon EOS M50',
                'foto' => 'canon-eos-m50.svg',
                'harga' => 120000,
                'stok' => 3,
                'deskripsi' => 'Kamera mirrorless ringkas dengan hasil foto tajam dan video 4K. Cocok untuk wisuda, konten, dan dokumentasi acara.',
            ],
            [
                'nama' => 'Sony ZV-E10',
                'foto' => 'sony-zv-e10.svg',
                'harga' => 150000,
                'stok' => 2,
                'deskripsi' => 'Kamera vlog dengan autofokus cepat, layar lipat, dan kualitas video stabil. Pilihan favorit untuk pembuatan konten.',
            ],
            [
                'nama' => 'Fujifilm X-A7',
                'foto' => 'fujifilm-x-a7.svg',
                'harga' => 110000,
                'stok' => 2,
                'deskripsi' => 'Warna khas Fujifilm dengan tampilan retro. Ringan dibawa jalan-jalan dan mudah digunakan pemula.',
            ],
            [
                'nama' => 'Nikon D5600',
                'foto' => 'nikon-d5600.svg',
                'harga' => 100000,
                'stok' => 3,
                'deskripsi' => 'DSLR andal untuk dokumentasi acara dan sesi foto. Dilengkapi lensa kit yang serbaguna.',
            ],
            [
                'nama' => 'GoPro Hero 9',
                'foto' => 'gopro-hero-9.svg',
                'harga' => 85000,
                'stok' => 4,
                'deskripsi' => 'Kamera aksi tahan air untuk liburan, olahraga, dan konten outdoor. Rekam video hingga 5K.',
            ],
            [
                'nama' => 'DJI Osmo Pocket',
                'foto' => 'dji-osmo-pocket.svg',
                'harga' => 95000,
                'stok' => 2,
                'deskripsi' => 'Gimbal kamera saku dengan hasil video halus. Praktis untuk vlog dan dokumentasi perjalanan.',
            ],
            [
                'nama' => 'Canon EOS 200D',
                'foto' => 'canon-eos-200d.svg',
                'harga' => 105000,
                'stok' => 1,
                'deskripsi' => 'DSLR ringan dengan layar sentuh dan koneksi Wi-Fi. Nyaman untuk belajar fotografi dan dokumentasi keluarga.',
            ],
            [
                'nama' => 'Sony A6400',
                'foto' => 'sony-a6400.svg',
                'harga' => 175000,
                'stok' => 1,
                'deskripsi' => 'Kamera mirrorless kelas menengah dengan autofokus real-time dan video 4K. Cocok untuk proyek profesional.',
            ],
            [
                'nama' => 'Fujifilm Instax Mini 11',
                'foto' => 'fujifilm-instax-mini-11.svg',
                'harga' => 45000,
                'stok' => 5,
                'deskripsi' => 'Kamera instan praktis untuk acara seru dan kenang-kenangan langsung cetak. Termasuk satu paket film.',
            ],
        ];

        $barangService = app(BarangService::class);

        foreach ($daftarBarang as $data) {
            $barang = Barang::query()->updateOrCreate(
                ['nama' => $data['nama']],
                $data,
            );

            $barangService->sinkronkanTarif($barang);
        }
    }
}
