@extends('mahasiswa.layouts.app')
@section('title', 'Ajukan Peminjaman')
@section('content')

<div class="page-header">
    <div><h1>Ajukan Peminjaman</h1><p>Isi formulir berikut untuk mengajukan peminjaman</p></div>
</div>

<div class="form-card" style="max-width:680px">
    <form action="{{ route('mahasiswa.peminjaman.store') }}" method="POST">
        @csrf

        <div class="field-group">
            <label>Jenis Peminjaman <span style="color:var(--red)">*</span></label>
            <div style="display:flex;gap:16px">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px;font-weight:400">
                    <input type="radio" name="jenis" value="lab" @checked(old('jenis','lab') === 'lab') onchange="toggleJenis(this.value)"> Ruang Lab
                </label>
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px;font-weight:400">
                    <input type="radio" name="jenis" value="barang" @checked(old('jenis') === 'barang') onchange="toggleJenis(this.value)"> Barang / Alat
                </label>
            </div>
            @error('jenis')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <div id="section_lab">
            <div class="field-row">
                <div class="field-group">
                    <label for="nama_lab">Pilih Laboratorium <span style="color:var(--red)">*</span></label>
                    <select id="nama_lab" name="nama_lab">
                        <option value="">— Pilih Lab —</option>
                        @foreach($labs as $lab)
                            <option value="{{ $lab->nama_lab }}" @selected(old('nama_lab') === $lab->nama_lab) data-stok="{{ $lab->stok }}">
                                {{ $lab->nama_lab }} (Kapasitas: {{ $lab->stok }})
                            </option>
                        @endforeach
                    </select>
                    @error('nama_lab')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field-group">
                    <label for="kursi">Pilih Nomor Kursi <span style="color:var(--red)">*</span></label>
                    <input type="number" id="kursi" name="kursi" value="{{ old('kursi') }}" min="1" placeholder="Nomor kursi">
                    @error('kursi')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div id="section_barang" style="display:none">
            <div class="field-row">
                <div class="field-group">
                    <label for="id_barang">Pilih Barang <span style="color:var(--red)">*</span></label>
                    <select id="id_barang" name="id_barang" onchange="setBarangHidden(this)">
                        <option value="">— Pilih Barang —</option>
                        @foreach($barang as $b)
                            <option value="{{ $b->id_barang }}" data-nama="{{ $b->nama_barang }}" data-lab="{{ $b->lab->nama_lab ?? '' }}" @selected(old('id_barang') == $b->id_barang)>
                                {{ $b->nama_barang }} (stok: {{ $b->stok }}) — {{ $b->lab->nama_lab ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_barang')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field-group">
                    <label for="jumlah">Jumlah <span style="color:var(--red)">*</span></label>
                    <input type="number" id="jumlah" name="jumlah" value="{{ old('jumlah', 1) }}" min="1">
                    @error('jumlah')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>
            <input type="hidden" name="nama_barang" id="nama_barang_hidden" value="{{ old('nama_barang') }}">
        </div>

        <div class="field-row">
            <div class="field-group">
                <label for="tanggal">Tanggal Peminjaman <span style="color:var(--red)">*</span></label>
                <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal') }}" required min="{{ date('Y-m-d') }}">
                @error('tanggal')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="field-group">
            <label>Jam Peminjaman <span style="color:var(--red)">*</span></label>
            <div style="display:flex;gap:8px;align-items:center">
                <input type="time" name="jam_mulai" value="{{ old('jam_mulai') }}" required style="height:38px;padding:0 12px;border:1px solid var(--border);border-radius:8px;font-family:DM Sans,sans-serif;font-size:13.5px;flex:1;outline:none">
                <span style="color:var(--muted);font-size:13px">–</span>
                <input type="time" name="jam_selesai" value="{{ old('jam_selesai') }}" required style="height:38px;padding:0 12px;border:1px solid var(--border);border-radius:8px;font-family:DM Sans,sans-serif;font-size:13.5px;flex:1;outline:none">
            </div>
            @error('jam_mulai')<span class="field-error">{{ $message }}</span>@enderror
            @error('jam_selesai')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
        @endif

        <div class="form-actions">
            <a href="{{ route('mahasiswa.dashboard') }}" class="btn-secondary">Batal</a>
            <div class="form-actions-right">
                <button type="submit" class="btn-primary"><i class="bi bi-check2"></i> Ajukan Peminjaman</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function toggleJenis(val) {
    document.getElementById('section_lab').style.display = val === 'lab' ? '' : 'none';
    document.getElementById('section_barang').style.display = val === 'barang' ? '' : 'none';
}
function setBarangHidden(sel) {
    const opt = sel.options[sel.selectedIndex];
    document.getElementById('nama_barang_hidden').value = opt.getAttribute('data-nama') || '';
}
toggleJenis('{{ old("jenis","lab") }}');
</script>
@endpush
@endsection
