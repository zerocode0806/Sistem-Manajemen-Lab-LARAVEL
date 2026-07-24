@extends('admin.layouts.app')
@section('title', 'Edit Admin')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.akun.index') }}">Data Admin</a>
    <i class="bi bi-chevron-right"></i><span class="current">Edit {{ $admin->nama }}</span>
</div>

<div class="page-header">
    <div><h1>Edit Admin</h1><p>Perbarui data akun administrator</p></div>
</div>

<div class="form-card">
    <form action="{{ route('admin.akun.update', $admin->id_admin) }}" method="POST">
        @csrf @method('PUT')

        <div class="field-group">
            <label for="nama">Nama Lengkap <span style="color:var(--red)">*</span></label>
            <input type="text" id="nama" name="nama" value="{{ old('nama', $admin->nama) }}" required>
            @error('nama')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <div class="field-row">
            <div class="field-group">
                <label for="email">Email <span style="color:var(--red)">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email', $admin->email) }}" required>
                @error('email')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field-group">
                <label for="username">Username <span style="color:var(--red)">*</span></label>
                <input type="text" id="username" name="username" value="{{ old('username', $admin->username) }}" required>
                @error('username')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <hr class="section-divider">
        <p style="font-size:12.5px;color:var(--muted);margin-bottom:12px">Kosongkan password jika tidak ingin mengubah password admin ini.</p>

        <div class="field-row">
            <div class="field-group">
                <label for="password">Password Baru</label>
                <input type="password" id="password" name="password" placeholder="Biarkan kosong jika tidak diubah">
                @error('password')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field-group">
                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
            </div>
        </div>

        <div class="form-actions">
            @if($admin->id_admin !== Auth::guard('admin')->user()->id_admin)
            <form action="{{ route('admin.akun.destroy', $admin->id_admin) }}" method="POST" style="margin:0">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger-outline" onclick="return confirm('Hapus admin {{ $admin->nama }}?')"><i class="bi bi-trash"></i> Hapus</button>
            </form>
            @else
            <div></div>
            @endif
            <div class="form-actions-right">
                <a href="{{ route('admin.akun.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary"><i class="bi bi-check2"></i> Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>
@endsection
