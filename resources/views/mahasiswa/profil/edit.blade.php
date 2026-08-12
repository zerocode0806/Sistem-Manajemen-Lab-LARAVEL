@extends('mahasiswa.layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('mahasiswa.dashboard') }}">Dashboard</a>
    <i class="bi bi-chevron-right"></i>
    <span class="current">Profil Saya</span>
</div>

<div class="page-header">
    <div>
        <h1>Profil Saya</h1>
        <p>Perbarui informasi pribadi dan password akun mahasiswa.</p>
    </div>
</div>

<form action="{{ route('mahasiswa.profil.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card" style="max-width: 760px; margin-bottom: 16px;">
        <div class="card-header">
            <div class="card-header-icon"><i class="bi bi-person-vcard"></i></div>
            <div>
                <h2>Informasi Pribadi</h2>
                <p style="font-size:12px;color:var(--muted);margin-top:2px;">Pastikan informasi kontak Anda selalu terbaru.</p>
            </div>
        </div>

        <div class="field-row">
            <div class="field-group">
                <label for="nim">NIM</label>
                <input id="nim" type="text" value="{{ $mahasiswa->nim }}" readonly style="background:var(--bg);color:var(--muted);">
                <span style="font-size:11.5px;color:var(--muted);">NIM tidak dapat diubah.</span>
            </div>
            <div class="field-group">
                <label for="nama">Nama Lengkap</label>
                <input id="nama" type="text" name="nama" value="{{ old('nama', $mahasiswa->nama) }}" maxlength="100" required>
                @error('nama')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="field-row">
            <div class="field-group">
                <label for="no_telepon">No. Telepon</label>
                <input id="no_telepon" type="text" name="no_telepon" value="{{ old('no_telepon', $mahasiswa->no_telepon) }}" maxlength="100" required>
                @error('no_telepon')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field-group">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat" maxlength="255" required>{{ old('alamat', $mahasiswa->alamat) }}</textarea>
                @error('alamat')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>

    <div class="card" style="max-width: 760px;">
        <div class="card-header">
            <div class="card-header-icon"><i class="bi bi-shield-lock"></i></div>
            <div>
                <h2>Ubah Password</h2>
                <p style="font-size:12px;color:var(--muted);margin-top:2px;">Kosongkan bagian ini jika password tidak ingin diubah.</p>
            </div>
        </div>

        <div class="field-row">
            <div class="field-group">
                <label for="current_password">Password Lama</label>
                <input id="current_password" type="password" name="current_password" autocomplete="current-password">
                @error('current_password')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field-group">
                <label for="password">Password Baru</label>
                <input id="password" type="password" name="password" minlength="6" autocomplete="new-password">
                @error('password')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="field-group" style="max-width: 50%;">
            <label for="password_confirmation">Konfirmasi Password Baru</label>
            <input id="password_confirmation" type="password" name="password_confirmation" minlength="6" autocomplete="new-password">
        </div>

        <div class="form-actions">
            <a href="{{ route('mahasiswa.dashboard') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary"><i class="bi bi-check2"></i> Simpan Perubahan</button>
        </div>
    </div>
</form>
@endsection