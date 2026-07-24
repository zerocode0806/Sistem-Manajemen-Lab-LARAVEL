@extends('admin.layouts.app')
@section('title', 'Tambah Mahasiswa')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.mahasiswa.index') }}">Mahasiswa</a>
    <i class="bi bi-chevron-right"></i><span class="current">Tambah Mahasiswa</span>
</div>

<div class="page-header">
    <div><h1>Tambah Mahasiswa Baru</h1><p>Daftarkan akun mahasiswa ke sistem</p></div>
</div>

<div class="form-card">
    <form action="{{ route('admin.mahasiswa.store') }}" method="POST">
        @csrf
        <div class="field-row">
            <div class="field-group">
                <label for="nim">NIM <span style="color:var(--red)">*</span></label>
                <input type="text" id="nim" name="nim" value="{{ old('nim') }}" required placeholder="Contoh: 2021001001">
                @error('nim')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field-group">
                <label for="nama">Nama Lengkap <span style="color:var(--red)">*</span></label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Nama sesuai KTP">
                @error('nama')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="field-group">
            <label for="no_telepon">No. Telepon <span style="color:var(--red)">*</span></label>
            <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" required placeholder="08xxxxxxxxxx">
            @error('no_telepon')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="field-group">
            <label for="alamat">Alamat <span style="color:var(--red)">*</span></label>
            <textarea id="alamat" name="alamat" required placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
            @error('alamat')<span class="field-error">{{ $message }}</span>@enderror
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
            <a href="{{ route('admin.mahasiswa.index') }}" class="btn-secondary">Batal</a>
            <div class="form-actions-right">
                <button type="submit" class="btn-primary"><i class="bi bi-check2"></i> Simpan Mahasiswa</button>
            </div>
        </div>
    </form>
</div>
@endsection
