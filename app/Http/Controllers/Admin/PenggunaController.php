<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenggunaController extends Controller
{
    public function index(Request $request): View
    {
        $kataKunci = trim((string) $request->query('q', ''));

        $pengguna = Pengguna::query()
            ->when(filled($kataKunci), fn ($query) => $query->where('username', 'like', '%'.$kataKunci.'%'))
            ->withCount('pesananSewa')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pengguna.index', [
            'pengguna' => $pengguna,
            'kataKunci' => $kataKunci,
        ]);
    }
}
