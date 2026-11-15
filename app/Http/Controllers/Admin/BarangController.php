<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBarangRequest;
use App\Http\Requests\UpdateBarangRequest;
use App\Models\Barang;
use App\Services\BarangService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BarangController extends Controller
{
    public function __construct(private BarangService $barangService) {}

    public function index(Request $request): View
    {
        $kataKunci = trim((string) $request->query('q', ''));

        $barang = Barang::query()
            ->cari($kataKunci)
            ->withCount('pesananSewa')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.barang.index', [
            'barang' => $barang,
            'kataKunci' => $kataKunci,
        ]);
    }

    public function create(): View
    {
        return view('admin.barang.create', [
            'barang' => new Barang,
        ]);
    }

    public function store(StoreBarangRequest $request): RedirectResponse
    {
        $this->barangService->simpan(
            new Barang,
            $request->safe()->only(['nama', 'harga', 'stok', 'deskripsi']),
            $request->file('foto'),
        );

        return redirect()
            ->route('admin.barang.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Barang $barang): View
    {
        return view('admin.barang.edit', [
            'barang' => $barang,
        ]);
    }

    public function update(UpdateBarangRequest $request, Barang $barang): RedirectResponse
    {
        $this->barangService->simpan(
            $barang,
            $request->safe()->only(['nama', 'harga', 'stok', 'deskripsi']),
            $request->file('foto'),
        );

        return redirect()
            ->route('admin.barang.index')
            ->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang): RedirectResponse
    {
        if ($barang->pesananSewa()->exists()) {
            return back()->with('error', 'Barang tidak dapat dihapus karena sudah memiliki riwayat pesanan.');
        }

        $this->barangService->hapus($barang);

        return redirect()
            ->route('admin.barang.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
}
