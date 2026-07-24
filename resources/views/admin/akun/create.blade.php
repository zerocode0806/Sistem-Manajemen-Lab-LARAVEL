@extends('admin.layouts.app')
@section('title', 'Tambah Admin')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.akun.index') }}">Data Admin</a>
    <i class="bi bi-chevron-right"></i><span class="current">Tambah Admin</span>
</div>

<div class="page-header">
    <div><h1>Tambah Admin Baru</h1><p>Buat akun administrator baru untuk mengakses sistem</p></div>
</div>

<div class="form-card">
    <form action="{{ route('admin.akun.store') }}" method="POST">
        @csrf

        <div class="field-group">
            <label for="nama">Nama Lengkap <span style="color:var(--red)">*</span></label>
            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Nama administrator">
            @error('nama')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <div class="field-row">
            <div class="field-group">
                <label for="email">Email <span style="color:var(--red)">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="admin@email.com">
                @error('email')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field-group">
                <label for="username">Username <span style="color:var(--red)">*</span></label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" required placeholder="Username untuk login">
                @error('username')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <hr class="section-divider">

        <div class="field-row">
            <div class="field-group">
                <label for="password">Password <span style="color:var(--red)">*</span></label>
                <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter">
                @error('password')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field-group">
                <label for="password_confirmation">Konfirmasi Password <span style="color:var(--red)">*</span></label>
                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password">
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.akun.index') }}" class="btn-secondary">Batal</a>
            <div class="form-actions-right">
                <button type="submit" class="btn-primary"><i class="bi bi-check2"></i> Simpan Admin</button>
            </div>
        </div>
    </form>
</div>
@endsection
