<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePesananRequest;
use App\Models\Barang;
use App\Models\PesananSewa;
use App\Services\PesananService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PesananController extends Controller
{
    public function index(Request $request): View
    {
        $pengguna = $request->user('web');

        $pesanan = $pengguna->pesananSewa()
            ->with('barang')
            ->latest()
            ->paginate(10);

        $ringkasan = $pengguna->pesananSewa()
            ->selectRaw('status, count(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        return view('pesanan.index', [
            'pesanan' => $pesanan,
            'ringkasan' => $ringkasan,
        ]);
    }

    public function show(Request $request, PesananSewa $pesanan): View
    {
        abort_unless($pesanan->id_penyewa === $request->user('web')->id, 403);

        $pesanan->load(['barang', 'penyewa']);

        return view('pesanan.show', [
            'pesanan' => $pesanan,
        ]);
    }

    public function store(
        StorePesananRequest $request,
        Barang $barang,
        PesananService $pesananService,
    ): RedirectResponse {
        $pesanan = $pesananService->buat($request->user('web'), $barang, $request->validated());

        return redirect()
            ->route('pesanan.show', $pesanan)
            ->with('success', 'Pesanan '.$pesanan->nomor_pesanan.' berhasil dibuat. Menunggu konfirmasi admin.');
    }

    public function cancel(
        Request $request,
        PesananSewa $pesanan,
        PesananService $pesananService,
    ): RedirectResponse {
        abort_unless($pesanan->id_penyewa === $request->user('web')->id, 403);

        $pesananService->batalkan($pesanan);

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
