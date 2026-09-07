@extends('admin.layouts.app')
@section('title', 'Tambah Barang')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.barang.index') }}">Data Barang</a>
    <i class="bi bi-chevron-right"></i>
    <span class="current">Tambah Barang</span>
</div>

<div class="page-header">
    <div><h1>Tambah Barang Baru</h1><p>Isi data barang yang akan ditambahkan ke inventaris</p></div>
</div>

<div class="form-card">
    <form action="{{ route('admin.barang.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="field-group">
            <label for="id_lab">Laboratorium <span style="color:var(--red)">*</span></label>
            <select id="id_lab" name="id_lab" required>
                <option value="">— Pilih Lab —</option>
                @foreach($labs as $lab)
                    <option value="{{ $lab->id_lab }}" @selected(old('id_lab') == $lab->id_lab)>{{ $lab->nama_lab }}</option>
                @endforeach
            </select>
            @error('id_lab')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <div class="field-row">
            <div class="field-group">
                <label for="kode_barang">Kode Barang <span style="color:var(--red)">*</span></label>
                <input type="text" id="kode_barang" name="kode_barang" value="{{ old('kode_barang') }}" required placeholder="Contoh: BRG-001">
                @error('kode_barang')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field-group">
                <label for="kategori">Kategori</label>
                <input type="text" id="kategori" name="kategori" value="{{ old('kategori') }}" placeholder="Contoh: Elektronik">
                @error('kategori')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="field-group">
            <label for="nama_barang">Nama Barang <span style="color:var(--red)">*</span></label>
            <input type="text" id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}" required placeholder="Nama lengkap barang">
            @error('nama_barang')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <div class="field-group">
            <label for="gambar">Gambar Barang</label>

            <input
                type="file"
                id="gambar"
                name="gambar"
                accept="image/jpeg,image/png,image/webp"
            >

            <small style="color:var(--muted)">
                Format JPG, PNG, atau WEBP. Maksimal 4 MB.
            </small>

            @error('gambar')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <img
            id="gambarPreview"
            src=""
            alt="Preview gambar"
            style="display:none;width:120px;height:90px;object-fit:cover;border-radius:8px;margin-top:10px;border:1px solid var(--border)"
        >

        @push('scripts')
        <script>
        document.getElementById('gambar')?.addEventListener('change', function (event) {
            const file = event.target.files[0];
            const preview = document.getElementById('gambarPreview');

            if (!file) {
                preview.style.display = 'none';
                return;
            }

            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        });
        </script>
        @endpush

        <div class="field-row">
            <div class="field-group">
                <label for="stok">Stok <span style="color:var(--red)">*</span></label>
                <input type="number" id="stok" name="stok" value="{{ old('stok', 0) }}" min="0" required>
                @error('stok')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field-group">
                <label for="kondisi">Kondisi <span style="color:var(--red)">*</span></label>
                <select id="kondisi" name="kondisi" required>
                    <option value="baik" @selected(old('kondisi', 'baik') === 'baik')>Baik</option>
                    <option value="rusak" @selected(old('kondisi') === 'rusak')>Rusak</option>
                    <option value="perbaikan" @selected(old('kondisi') === 'perbaikan')>Dalam Perbaikan</option>
                </select>
                @error('kondisi')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="field-group">
            <label for="status">Status <span style="color:var(--red)">*</span></label>
            <select id="status" name="status" required>
                <option value="availabel" @selected(old('status', 'availabel') === 'availabel')>Tersedia</option>
                <option value="tidak availabel" @selected(old('status') === 'tidak availabel')>Tidak Tersedia</option>
            </select>
            @error('status')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <div class="field-group">
            <label for="keterangan">Keterangan (opsional)</label>
            <textarea id="keterangan" name="keterangan" placeholder="Catatan tambahan…">{{ old('keterangan') }}</textarea>
            @error('keterangan')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.barang.index') }}" class="btn-secondary">Batal</a>
            <div class="form-actions-right">
                <button type="submit" class="btn-primary"><i class="bi bi-check2"></i> Simpan Barang</button>
            </div>
        </div>
    </form>
</div>
@endsection
