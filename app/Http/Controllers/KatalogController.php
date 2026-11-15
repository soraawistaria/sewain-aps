<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KatalogController extends Controller
{
    public function index(Request $request): View
    {
        $kataKunci = trim((string) $request->query('q', ''));
        $hanyaTersedia = $request->boolean('tersedia');

        $barang = Barang::query()
            ->cari($kataKunci)
            ->when($hanyaTersedia, fn ($query) => $query->tersedia())
            ->with('tarifTerbaru')
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('katalog.index', [
            'barang' => $barang,
            'kataKunci' => $kataKunci,
            'hanyaTersedia' => $hanyaTersedia,
            'totalBarang' => Barang::query()->count(),
            'totalStok' => (int) Barang::query()->sum('stok'),
        ]);
    }

    public function show(Barang $barang): View
    {
        $barang->load('tarifTerbaru');

        return view('katalog.show', [
            'barang' => $barang,
        ]);
    }
}
