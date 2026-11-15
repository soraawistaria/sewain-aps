<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PesananSewa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $dari = Carbon::parse($request->query('dari', now()->startOfMonth()->toDateString()))->startOfDay();
        $sampai = Carbon::parse($request->query('sampai', now()->toDateString()))->endOfDay();

        if ($sampai->lessThan($dari)) {
            [$dari, $sampai] = [$sampai->copy()->startOfDay(), $dari->copy()->endOfDay()];
        }

        $dasar = PesananSewa::query()->whereBetween('created_at', [$dari, $sampai]);

        $ringkasanStatus = (clone $dasar)
            ->selectRaw('status, count(*) as jumlah, coalesce(sum(total_harga), 0) as total')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $barangTerpopuler = (clone $dasar)
            ->selectRaw('id_barang, count(*) as jumlah, coalesce(sum(total_harga), 0) as total')
            ->with('barang:id,nama')
            ->groupBy('id_barang')
            ->orderByDesc('jumlah')
            ->limit(5)
            ->get();

        $pesanan = (clone $dasar)
            ->with(['penyewa', 'barang'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.laporan.index', [
            'dari' => $dari,
            'sampai' => $sampai,
            'ringkasanStatus' => $ringkasanStatus,
            'barangTerpopuler' => $barangTerpopuler,
            'pesanan' => $pesanan,
            'totalPesanan' => (clone $dasar)->count(),
            'totalPendapatan' => (int) (clone $dasar)->where('status', PesananSewa::STATUS_SELESAI)->sum('total_harga'),
            'totalNilaiDisetujui' => (int) (clone $dasar)
                ->whereIn('status', [PesananSewa::STATUS_DISETUJUI, PesananSewa::STATUS_DISEWA, PesananSewa::STATUS_SELESAI])
                ->sum('total_harga'),
        ]);
    }
}
