<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PesananSewa;
use App\Services\PesananService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PesananController extends Controller
{
    public function index(Request $request): View
    {
        $status = (string) $request->query('status', '');
        $kataKunci = trim((string) $request->query('q', ''));

        $pesanan = PesananSewa::query()
            ->with(['penyewa', 'barang'])
            ->status($status)
            ->when(filled($kataKunci), function ($query) use ($kataKunci): void {
                $query->where(function ($query) use ($kataKunci): void {
                    $query->where('nomor_pesanan', 'like', '%'.$kataKunci.'%')
                        ->orWhereHas('penyewa', fn ($query) => $query->where('username', 'like', '%'.$kataKunci.'%'))
                        ->orWhereHas('barang', fn ($query) => $query->where('nama', 'like', '%'.$kataKunci.'%'));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $jumlahStatus = PesananSewa::query()
            ->selectRaw('status, count(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        return view('admin.pesanan.index', [
            'pesanan' => $pesanan,
            'status' => $status,
            'kataKunci' => $kataKunci,
            'jumlahStatus' => $jumlahStatus,
        ]);
    }

    public function show(PesananSewa $pesanan): View
    {
        $pesanan->load(['penyewa', 'barang.tarifTerbaru']);

        return view('admin.pesanan.show', [
            'pesanan' => $pesanan,
        ]);
    }

    public function approve(PesananSewa $pesanan, PesananService $pesananService): RedirectResponse
    {
        $pesananService->setujui($pesanan);

        return back()->with('success', 'Pesanan '.$pesanan->nomor_pesanan.' disetujui.');
    }

    public function reject(Request $request, PesananSewa $pesanan, PesananService $pesananService): RedirectResponse
    {
        $validated = $request->validate([
            'alasan_penolakan' => ['nullable', 'string', 'max:500'],
        ], [
            'alasan_penolakan.max' => 'Alasan penolakan maksimal 500 karakter.',
        ]);

        $pesananService->tolak($pesanan, $validated['alasan_penolakan'] ?? null);

        return back()->with('success', 'Pesanan '.$pesanan->nomor_pesanan.' ditolak.');
    }

    public function markRented(PesananSewa $pesanan, PesananService $pesananService): RedirectResponse
    {
        $pesananService->tandaiDisewa($pesanan);

        return back()->with('success', 'Pesanan '.$pesanan->nomor_pesanan.' ditandai sedang disewa.');
    }

    public function complete(PesananSewa $pesanan, PesananService $pesananService): RedirectResponse
    {
        $pesananService->selesaikan($pesanan);

        return back()->with('success', 'Pesanan '.$pesanan->nomor_pesanan.' selesai dan stok barang sudah dikembalikan.');
    }
}
