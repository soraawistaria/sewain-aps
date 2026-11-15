<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View
    {
        return view('admin.auth.login', [
            'belumAdaAdmin' => ! Admin::query()->exists(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username admin wajib diisi.',
            'password.required' => 'Password admin wajib diisi.',
        ]);

        if (! Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'username' => 'Username atau password admin salah.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()
            ->intended(route('admin.dashboard'))
            ->with('success', 'Selamat datang di panel admin SewaIn.');
    }

    public function setup(): View
    {
        abort_if(Admin::query()->exists(), 404);

        return view('admin.auth.setup');
    }

    public function storeSetup(Request $request): RedirectResponse
    {
        abort_if(Admin::query()->exists(), 404);

        $data = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:25', 'alpha_dash', 'unique:admin,username'],
            'password' => ['required', 'string', 'min:6', 'max:100', 'confirmed'],
        ], [
            'username.required' => 'Username admin wajib diisi.',
            'username.min' => 'Username admin minimal 3 karakter.',
            'username.max' => 'Username admin maksimal 25 karakter.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
            'username.unique' => 'Username admin sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        Admin::create($data);

        return redirect()
            ->route('admin.login')
            ->with('success', 'Akun admin pertama berhasil dibuat. Silakan masuk.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('success', 'Anda berhasil keluar dari panel admin.');
    }
}
