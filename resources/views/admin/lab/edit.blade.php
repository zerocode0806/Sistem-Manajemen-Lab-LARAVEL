@extends('admin.layouts.app')
@section('title', 'Edit Lab')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.lab.index') }}">Laboratorium</a>
    <i class="bi bi-chevron-right"></i>
    <span class="current">Edit {{ $lab->nama_lab }}</span>
</div>

<div class="page-header">
    <div>
        <h1>Edit Lab</h1>
        <p>Perbarui data laboratorium</p>
    </div>
</div>

<div class="form-card">
    <form action="{{ route('admin.lab.update', $lab->id_lab) }}" method="POST">
        @csrf @method('PUT')

        <div class="field-group">
            <label for="nama_lab">Nama Lab <span style="color:var(--red)">*</span></label>
            <input type="text" id="nama_lab" name="nama_lab" value="{{ old('nama_lab', $lab->nama_lab) }}" required>
            @error('nama_lab')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <div class="field-group">
            <label for="lokasi">Lokasi</label>
            <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $lab->lokasi) }}">
            @error('lokasi')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <div class="field-row">
            <div class="field-group">
                <label for="stok">Stok (Kapasitas)</label>
                <input type="number" id="stok" name="stok" value="{{ old('stok', $lab->stok) }}" min="0" required>
                @error('stok')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="availabel" @selected(old('status', $lab->status) === 'availabel')>Tersedia</option>
                    <option value="not available" @selected(old('status', $lab->status) === 'not available')>Tidak Tersedia</option>
                </select>
                @error('status')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="field-row">
            <div class="field-group">
                <label for="jumlah_kursi">Jumlah Kursi</label>
                <input type="number" id="jumlah_kursi" name="jumlah_kursi" value="{{ old('jumlah_kursi', $lab->jumlah_kursi) }}" min="0" required>
                @error('jumlah_kursi')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field-group">
                <label for="jumlah_meja">Jumlah Meja</label>
                <input type="number" id="jumlah_meja" name="jumlah_meja" value="{{ old('jumlah_meja', $lab->jumlah_meja) }}" min="0" required>
                @error('jumlah_meja')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-actions">
            <form action="{{ route('admin.lab.destroy', $lab->id_lab) }}" method="POST" style="margin:0">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger-outline" onclick="return confirm('Hapus lab {{ $lab->nama_lab }}? Semua data terkait akan ikut terhapus.')">
                    <i class="bi bi-trash"></i> Hapus Lab
                </button>
            </form>
            <div class="form-actions-right">
                <a href="{{ route('admin.lab.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary"><i class="bi bi-check2"></i> Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>
@endsection
