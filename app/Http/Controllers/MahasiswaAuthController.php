<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MahasiswaAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.mahasiswa-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nim' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::guard('mahasiswa')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('mahasiswa.dashboard'));
        }

        return back()->withErrors([
            'nim' => 'NIM atau password salah.',
        ])->onlyInput('nim');
    }

    public function logout(Request $request)
    {
        Auth::guard('mahasiswa')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
