<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        return view('mahasiswa.profil.edit', compact('mahasiswa'));
    }

    public function update(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        $validated = $request->validate([
            'nama'             => ['required', 'string', 'max:100'],
            'no_telepon'       => ['required', 'string', 'max:100'],
            'alamat'           => ['required', 'string', 'max:255'],
            'current_password' => ['nullable', 'string', 'required_with:password'],
            'password'         => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $mahasiswa->password)) {
                return back()
                    ->withErrors(['current_password' => 'Password lama tidak sesuai.'])
                    ->withInput();
            }

            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        unset($validated['current_password']);

        $mahasiswa->update($validated);

        return redirect()
            ->route('mahasiswa.profil.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}