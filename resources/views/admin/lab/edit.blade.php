@extends('admin.layouts.app')
@section('title', 'Edit Lab')
@section('content')
<div class="breadcrumb"><a href="{{ route('admin.lab.index') }}">Laboratorium</a><i class="bi bi-chevron-right"></i><span class="current">Edit {{ $lab->nama_lab }}</span></div>
<div class="page-header"><div><h1>Edit Lab</h1><p>Kapasitas orang adalah daya tampung ruangan; jumlah PC menentukan workstation yang dikelola dan nomor kursi peminjaman.</p></div></div>
<div class="form-card">
    <form action="{{ route('admin.lab.update', $lab->id_lab) }}" method="POST" id="labUpdateForm">@csrf @method('PUT')
        <div class="field-group"><label for="nama_lab">Nama Lab <span style="color:var(--red)">*</span></label><input type="text" id="nama_lab" name="nama_lab" value="{{ old('nama_lab', $lab->nama_lab) }}" required maxlength="100">@error('nama_lab')<span class="field-error">{{ $message }}</span>@enderror</div>
        <div class="field-group"><label for="lokasi">Lokasi</label><input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $lab->lokasi) }}" maxlength="100">@error('lokasi')<span class="field-error">{{ $message }}</span>@enderror</div>
        <div class="field-row">
            <div class="field-group"><label for="stok">Kapasitas ruangan (orang)</label><input type="number" id="stok" name="stok" value="{{ old('stok', $lab->stok) }}" min="0" required><small style="display:block;margin-top:5px;color:var(--muted);font-size:12px">Daya tampung informasi ruangan; bukan jumlah PC atau jumlah kursi yang dapat dipilih.</small>@error('stok')<span class="field-error">{{ $message }}</span>@enderror</div>
            <div class="field-group"><label for="status">Status</label><select id="status" name="status" required><option value="availabel" @selected(old('status', $lab->status)==='availabel')>Tersedia</option><option value="not available" @selected(old('status', $lab->status)==='not available')>Tidak Tersedia</option></select>@error('status')<span class="field-error">{{ $message }}</span>@enderror</div>
        </div>
        <div class="field-row">
            <div class="field-group"><label for="jumlah_meja">Jumlah PC / meja</label><input type="number" id="jumlah_meja" name="jumlah_meja" value="{{ old('jumlah_meja', $lab->jumlah_meja) }}" min="0" max="10000" required><small style="display:block;margin-top:5px;color:var(--muted);font-size:12px">Jumlah unit PC yang disimpan di inventaris. Penambahan membuat unit baru otomatis; pengurangan menolak unit yang sedang dipakai pada peminjaman aktif.</small>@error('jumlah_meja')<span class="field-error">{{ $message }}</span>@enderror</div>
            <div class="field-group"><label for="jumlah_kursi_display">Nomor kursi maksimum di peminjaman</label><input type="number" id="jumlah_kursi_display" value="{{ $lab->jumlah_kursi }}" readonly><small style="display:block;margin-top:5px;color:var(--muted);font-size:12px">Dihitung setelah disimpan dari nomor PC tertinggi. PC yang tidak ada atau bermasalah tidak bisa dipilih.</small></div>
        </div>
        <div class="form-actions"><a href="{{ route('admin.lab.index') }}" class="btn-secondary">Batal</a><div class="form-actions-right"><button type="submit" class="btn-primary"><i class="bi bi-check2"></i> Simpan Perubahan</button></div></div>
    </form>
    <div class="form-actions" style="justify-content:flex-start;padding-top:0"><form action="{{ route('admin.lab.destroy', $lab->id_lab) }}" method="POST" style="margin:0">@csrf @method('DELETE')<button type="submit" class="btn-danger-outline" onclick="return confirm('Hapus lab {{ $lab->nama_lab }}? Semua data terkait akan ikut terhapus.')"><i class="bi bi-trash"></i> Hapus Lab</button></form></div>
</div>
@endsection
