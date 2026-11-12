@extends('admin.layouts.app')
@section('title', 'Tambah Lab')
@section('content')
<div class="breadcrumb"><a href="{{ route('admin.lab.index') }}">Laboratorium</a><i class="bi bi-chevron-right"></i><span class="current">Tambah Lab</span></div>
<div class="page-header"><div><h1>Tambah Lab Baru</h1><p>Kapasitas orang adalah daya tampung ruangan; jumlah PC menentukan workstation dan nomor kursi peminjaman.</p></div></div>
<div class="form-card">
    <form action="{{ route('admin.lab.store') }}" method="POST" id="labForm">@csrf
        <div class="field-group"><label for="nama_lab">Nama Lab <span style="color:var(--red)">*</span></label><input type="text" id="nama_lab" name="nama_lab" value="{{ old('nama_lab') }}" required maxlength="100" placeholder="Contoh: Lab Algoritma & Pemrograman">@error('nama_lab')<span class="field-error">{{ $message }}</span>@enderror</div>
        <div class="field-group"><label for="lokasi">Lokasi</label><input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi') }}" maxlength="100" placeholder="Contoh: Gedung Saintek Lt. 2">@error('lokasi')<span class="field-error">{{ $message }}</span>@enderror</div>
        <div class="field-row">
            <div class="field-group"><label for="stok">Kapasitas ruangan (orang) <span style="color:var(--red)">*</span></label><input type="number" id="stok" name="stok" value="{{ old('stok', 1) }}" min="0" required><small style="display:block;margin-top:5px;color:var(--muted);font-size:12px">Daya tampung informasi ruangan; bukan jumlah PC atau kursi yang dapat dipilih.</small>@error('stok')<span class="field-error">{{ $message }}</span>@enderror</div>
            <div class="field-group"><label for="status">Status <span style="color:var(--red)">*</span></label><select id="status" name="status" required><option value="">— Pilih Status —</option><option value="availabel" @selected(old('status')==='availabel')>Tersedia</option><option value="not available" @selected(old('status')==='not available')>Tidak Tersedia</option></select>@error('status')<span class="field-error">{{ $message }}</span>@enderror</div>
        </div>
        <div class="field-row">
            <div class="field-group"><label for="jumlah_meja">Jumlah PC / meja <span style="color:var(--red)">*</span></label><input type="number" id="jumlah_meja" name="jumlah_meja" value="{{ old('jumlah_meja', 0) }}" min="0" max="10000" required><small style="display:block;margin-top:5px;color:var(--muted);font-size:12px">Inventaris workstation akan dibuat otomatis sejumlah ini.</small>@error('jumlah_meja')<span class="field-error">{{ $message }}</span>@enderror</div>
            <div class="field-group"><label for="jumlah_kursi_display">Perkiraan nomor kursi peminjaman</label><input type="number" id="jumlah_kursi_display" value="{{ old('jumlah_meja', 0) }}" readonly aria-live="polite"><small style="display:block;margin-top:5px;color:var(--muted);font-size:12px">Nomor kursi mengikuti PC; PC dengan komponen bermasalah tidak dapat dipilih.</small></div>
        </div>
        <div class="form-actions"><a href="{{ route('admin.lab.index') }}" class="btn-secondary">Batal</a><div class="form-actions-right"><button type="submit" class="btn-primary"><i class="bi bi-check2"></i> Simpan Lab</button></div></div>
    </form>
</div>
@push('scripts')<script>
(()=>{const count=document.getElementById('jumlah_meja'),seats=document.getElementById('jumlah_kursi_display');count.addEventListener('input',()=>{seats.value=count.value||0;});})();
</script>@endpush
@endsection
