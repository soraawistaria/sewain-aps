<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PengaturanController extends Controller
{
    public function edit(): View
    {
        return view('admin.pengaturan.edit');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $admin = $request->user('admin');

        if (! Hash::check($request->current_password, $admin->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password saat ini tidak sesuai.',
            ]);
        }

        $admin->update([
            'password' => $request->password,
        ]);

        return back()->with('success', 'Password admin berhasil diperbarui.');
    }
}
