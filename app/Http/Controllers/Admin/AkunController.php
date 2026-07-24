<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AkunController extends Controller
{
    public function index()
    {
        $admins = Admin::orderBy('id_admin')->get();
        return view('admin.akun.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.akun.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'                  => 'required|string|max:100',
            'email'                 => 'required|email|max:150|unique:admin,email',
            'username'              => 'required|string|max:50|unique:admin,username',
            'password'              => ['required', 'confirmed', Password::min(6)],
        ]);

        Admin::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.akun.index')
                         ->with('success', 'Akun admin berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.akun.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|max:150|unique:admin,email,' . $id . ',id_admin',
            'username' => 'required|string|max:50|unique:admin,username,' . $id . ',id_admin',
            'password' => ['nullable', 'confirmed', Password::min(6)],
        ]);

        $data = [
            'nama'     => $request->nama,
            'email'    => $request->email,
            'username' => $request->username,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admin.akun.index')
                         ->with('success', 'Akun admin berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Admin::findOrFail($id)->delete();

        return redirect()->route('admin.akun.index')
                         ->with('success', 'Akun admin berhasil dihapus.');
    }
}
