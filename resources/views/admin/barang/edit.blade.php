@extends('admin.layouts.app')
@section('title', 'Edit Barang')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.barang.index') }}">Data Barang</a>
    <i class="bi bi-chevron-right"></i>
    <span class="current">Edit {{ $barang->nama_barang }}</span>
</div>

<div class="page-header">
    <div><h1>Edit Barang</h1><p>Perbarui data inventaris barang</p></div>
</div>

<div class="form-card">
    <form id="barangDeleteForm" action="{{ route('admin.barang.destroy', $barang->id_barang) }}" method="POST" style="display:none">
        @csrf @method('DELETE')
    </form>
    <form action="{{ route('admin.barang.update', $barang->id_barang) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="field-group">
            <label for="id_lab">Laboratorium <span style="color:var(--red)">*</span></label>
            <select id="id_lab" name="id_lab" required>
                <option value="">— Pilih Lab —</option>
                @foreach($labs as $lab)
                    <option value="{{ $lab->id_lab }}" @selected(old('id_lab', $barang->id_lab) == $lab->id_lab)>{{ $lab->nama_lab }}</option>
                @endforeach
            </select>
            @error('id_lab')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <div class="field-row">
            <div class="field-group">
                <label for="kode_barang">Kode Barang <span style="color:var(--red)">*</span></label>
                <input type="text" id="kode_barang" name="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}" required>
                @error('kode_barang')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field-group">
                <label for="kategori">Kategori</label>
                <input type="text" id="kategori" name="kategori" value="{{ old('kategori', $barang->kategori) }}">
                @error('kategori')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="field-group">
            <label for="nama_barang">Nama Barang <span style="color:var(--red)">*</span></label>
            <input type="text" id="nama_barang" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" required>
            @error('nama_barang')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        
        <div class="field-group">
            <label for="gambar">Gambar Barang</label>

            @if($barang->gambar)
                <div style="margin-bottom:10px">
                    <img
                        src="{{ asset('storage/' . $barang->gambar) }}"
                        alt="{{ $barang->nama_barang }}"
                        style="width:160px;height:120px;object-fit:cover;border-radius:8px;border:1px solid var(--border)"
                    >
                </div>
            @endif

            <input
                type="file"
                id="gambar"
                name="gambar"
                accept="image/jpeg,image/png,image/webp"
            >

            <small style="color:var(--muted)">
                Kosongkan jika ingin mempertahankan gambar lama.
            </small>

            @error('gambar')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="field-row">
            <div class="field-group">
                <label for="stok">Stok <span style="color:var(--red)">*</span></label>
                <input type="number" id="stok" name="stok" value="{{ old('stok', $barang->stok) }}" min="0" required>
                @error('stok')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field-group">
                <label for="kondisi">Kondisi <span style="color:var(--red)">*</span></label>
                <select id="kondisi" name="kondisi" required>
                    <option value="baik" @selected(old('kondisi', $barang->kondisi) === 'baik')>Baik</option>
                    <option value="rusak" @selected(old('kondisi', $barang->kondisi) === 'rusak')>Rusak</option>
                    <option value="perbaikan" @selected(old('kondisi', $barang->kondisi) === 'perbaikan')>Dalam Perbaikan</option>
                </select>
                @error('kondisi')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="field-group">
            <label for="status">Status <span style="color:var(--red)">*</span></label>
            <select id="status" name="status" required>
                <option value="availabel" @selected(old('status', $barang->status) === 'availabel')>Tersedia</option>
                <option value="tidak availabel" @selected(old('status', $barang->status) === 'tidak availabel')>Tidak Tersedia</option>
            </select>
            @error('status')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <div class="field-group">
            <label for="keterangan">Keterangan (opsional)</label>
            <textarea id="keterangan" name="keterangan">{{ old('keterangan', $barang->keterangan) }}</textarea>
            @error('keterangan')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-actions">
            <button
                type="submit"
                form="barangDeleteForm"
                class="btn-danger-outline"
                onclick="return confirm('Hapus barang {{ $barang->nama_barang }}?')"
            >
                <i class="bi bi-trash"></i> Hapus
            </button>
            <div class="form-actions-right">
                <a href="{{ route('admin.barang.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary"><i class="bi bi-check2"></i> Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>
@endsection
