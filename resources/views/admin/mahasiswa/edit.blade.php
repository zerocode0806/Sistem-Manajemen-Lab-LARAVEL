@extends('admin.layouts.app')
@section('title', 'Edit Mahasiswa')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.mahasiswa.index') }}">Mahasiswa</a>
    <i class="bi bi-chevron-right"></i>
    <a href="{{ route('admin.mahasiswa.show', $mahasiswa->nim) }}">{{ $mahasiswa->nama }}</a>
    <i class="bi bi-chevron-right"></i><span class="current">Edit</span>
</div>

<div class="page-header">
    <div><h1>Edit Mahasiswa</h1><p>Perbarui data mahasiswa</p></div>
</div>

<div class="form-card">
    <form action="{{ route('admin.mahasiswa.update', $mahasiswa->nim) }}" method="POST">
        @csrf @method('PUT')
        <div class="field-row">
            <div class="field-group">
                <label for="nim">NIM</label>
                <input type="text" id="nim" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" required>
                @error('nim')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field-group">
                <label for="nama">Nama Lengkap <span style="color:var(--red)">*</span></label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $mahasiswa->nama) }}" required>
                @error('nama')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="field-group">
            <label for="no_telepon">No. Telepon <span style="color:var(--red)">*</span></label>
            <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $mahasiswa->no_telepon) }}" required>
            @error('no_telepon')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="field-group">
            <label for="alamat">Alamat <span style="color:var(--red)">*</span></label>
            <textarea id="alamat" name="alamat" required>{{ old('alamat', $mahasiswa->alamat) }}</textarea>
            @error('alamat')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <hr class="section-divider">
        <p style="font-size:12.5px;color:var(--muted);margin-bottom:12px">Kosongkan password jika tidak ingin mengubah password.</p>
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
            <form action="{{ route('admin.mahasiswa.destroy', $mahasiswa->nim) }}" method="POST" style="margin:0">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger-outline" onclick="return confirm('Hapus mahasiswa {{ $mahasiswa->nama }}?')"><i class="bi bi-trash"></i> Hapus</button>
            </form>
            <div class="form-actions-right">
                <a href="{{ route('admin.mahasiswa.show', $mahasiswa->nim) }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary"><i class="bi bi-check2"></i> Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>
@endsection
