<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthApiController extends Controller
{
    /** POST /api/login  — memverifikasi akun admin inven untuk web IT Support */
    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|max:100',
            'password' => 'required|string|max:255',
        ]);

        $key = 'api-login:' . strtolower($data['username']) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'message' => 'Terlalu banyak percobaan. Coba lagi dalam ' . RateLimiter::availableIn($key) . ' detik.',
            ], 429);
        }

        // Boleh login pakai username atau email
        $admin = DB::table('admin')
            ->where('username', $data['username'])
            ->orWhere('email', $data['username'])
            ->first();

        if (! $admin || ! Hash::check($data['password'], $admin->password)) {
            RateLimiter::hit($key, 60);

            return response()->json(['message' => 'Username atau password salah.'], 401);
        }

        RateLimiter::clear($key);

        return response()->json([
            'data' => [
                'id_admin' => $admin->id_admin,
                'nama'     => $admin->nama,
                'username' => $admin->username,
            ],
        ]);
    }
}