<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\PesananSewa;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $pesananTerbaru = PesananSewa::query()
            ->with(['penyewa', 'barang'])
            ->latest()
            ->limit(6)
            ->get();

        $barangPopuler = Barang::query()
            ->withCount('pesananSewa')
            ->orderByDesc('pesanan_sewa_count')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'totalBarang' => Barang::query()->count(),
            'totalStok' => (int) Barang::query()->sum('stok'),
            'pesananMenunggu' => PesananSewa::query()->where('status', PesananSewa::STATUS_MENUNGGU)->count(),
            'pesananSelesai' => PesananSewa::query()->selesai()->count(),
            'pendapatan' => (int) PesananSewa::query()->selesai()->sum('total_harga'),
            'pesananTerbaru' => $pesananTerbaru,
            'barangPopuler' => $barangPopuler,
        ]);
    }
}
