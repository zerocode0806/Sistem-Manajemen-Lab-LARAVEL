@extends('admin.layouts.app')
@section('title', 'Buat Peminjaman')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.peminjaman.index') }}">Permintaan</a>
    <i class="bi bi-chevron-right"></i><span class="current">Buat Peminjaman</span>
</div>

<div class="page-header">
    <div><h1>Buat Peminjaman</h1><p>Buat pengajuan peminjaman lab atau barang atas nama mahasiswa</p></div>
</div>

<div class="form-card" style="max-width:780px">
    <form action="{{ route('admin.peminjaman.store') }}" method="POST" id="peminjamanForm">
        @csrf

        {{-- MAHASISWA --}}
        <div class="field-group">
            <label for="nim">Mahasiswa <span style="color:var(--red)">*</span></label>
            <select id="nim" name="nim" required>
                <option value="">— Pilih Mahasiswa —</option>
                @foreach($mahasiswa as $mhs)
                    <option value="{{ $mhs->nim }}" @selected(old('nim') === $mhs->nim)>
                        {{ $mhs->nama }} ({{ $mhs->nim }})
                    </option>
                @endforeach
            </select>
            @error('nim')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        {{-- JENIS --}}
        <div class="field-group">
            <label>Jenis Peminjaman <span style="color:var(--red)">*</span></label>
            <div style="display:flex;gap:12px">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px;font-weight:400">
                    <input type="radio" name="jenis" id="jenisLab" value="lab"
                           @checked(old('jenis','lab') === 'lab') onchange="toggleJenis(this.value)"> Ruang Lab
                </label>
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px;font-weight:400">
                    <input type="radio" name="jenis" value="barang"
                           @checked(old('jenis') === 'barang') onchange="toggleJenis(this.value)"> Barang / Alat
                </label>
            </div>
            @error('jenis')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        {{-- LAB SECTION --}}
        <div id="section_lab">
            <div class="field-group">
                <label for="namaLabSelect">Laboratorium <span style="color:var(--red)">*</span></label>
                <select id="namaLabSelect" name="nama_lab" onchange="onLabChange()">
                    <option value="">— Pilih Lab —</option>
                    @foreach($labs as $lab)
                        <option value="{{ $lab->nama_lab }}"
                                data-kursi="{{ $lab->jumlah_kursi }}"
                                @selected(old('nama_lab') === $lab->nama_lab)>
                            {{ $lab->nama_lab }} (stok: {{ $lab->stok }} | {{ $lab->jumlah_kursi }} kursi)
                        </option>
                    @endforeach
                </select>
                @error('nama_lab')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>

        {{-- BARANG SECTION --}}
        <div id="section_barang" style="display:none">
            <div class="field-row">
                <div class="field-group">
                    <label for="id_barang">Barang</label>
                    <select id="id_barang" name="id_barang" onchange="setNamaBarang(this)">
                        <option value="">— Pilih Barang —</option>
                        @foreach($barang as $b)
                            <option value="{{ $b->id_barang }}"
                                    data-nama="{{ $b->nama_barang }}"
                                    data-lab="{{ $b->lab->nama_lab ?? '' }}"
                                    @selected(old('id_barang') == $b->id_barang)>
                                {{ $b->nama_barang }} (stok: {{ $b->stok }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_barang')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field-group">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" id="jumlah" name="jumlah" value="{{ old('jumlah', 1) }}" min="1">
                    @error('jumlah')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>
            <input type="hidden" name="nama_barang" id="nama_barang_hidden" value="{{ old('nama_barang') }}">
        </div>

        {{-- DATE & TIME --}}
        <div class="field-row">
            <div class="field-group">
                <label for="tanggalInput">Tanggal <span style="color:var(--red)">*</span></label>
                <input type="date" id="tanggalInput" name="tanggal"
                       value="{{ old('tanggal') }}" required onchange="onScheduleChange()">
                @error('tanggal')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field-group">
                <label>Jam</label>
                <div style="display:flex;gap:8px;align-items:center">
                    <input type="time" id="jamMulaiInput" name="jam_mulai"
                           value="{{ old('jam_mulai') }}" required
                           onchange="onScheduleChange()"
                           style="height:38px;padding:0 12px;border:1px solid var(--border);border-radius:8px;font-family:DM Sans,sans-serif;font-size:13.5px;flex:1;outline:none">
                    <span style="color:var(--muted);font-size:13px">–</span>
                    <input type="time" id="jamSelesaiInput" name="jam_selesai"
                           value="{{ old('jam_selesai') }}" required
                           onchange="onScheduleChange()"
                           style="height:38px;padding:0 12px;border:1px solid var(--border);border-radius:8px;font-family:DM Sans,sans-serif;font-size:13.5px;flex:1;outline:none">
                </div>
                @error('jam_mulai')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>

        {{-- SEAT PICKER --}}
        <div id="seatPickerContainer">
            @include('components.seat-picker', [
                'seatCheckUrl' => route('admin.peminjaman.checkSeats'),
            ])
        </div>

        @error('kursi')<span class="field-error" style="display:block;margin-top:-12px;margin-bottom:12px">{{ $message }}</span>@enderror

        <div class="form-actions">
            <a href="{{ route('admin.peminjaman.index') }}" class="btn-secondary">Batal</a>
            <div class="form-actions-right">
                <button type="submit" class="btn-primary"><i class="bi bi-check2"></i> Buat Peminjaman</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function toggleJenis(val) {
    document.getElementById('section_lab').style.display    = val === 'lab'    ? '' : 'none';
    document.getElementById('section_barang').style.display = val === 'barang' ? '' : 'none';
    document.getElementById('seatPickerSection').style.display = val === 'lab' ? '' : 'none';
    document.getElementById('spKursiInput').required = (val === 'lab');
    if (val === 'lab') window.SeatPicker && window.SeatPicker.checkAndFetch();
}

function onLabChange() {
    window.SeatPicker && window.SeatPicker.checkAndFetch();
}

function onScheduleChange() {
    window.SeatPicker && window.SeatPicker.checkAndFetch();
}

function setNamaBarang(sel) {
    const opt = sel.options[sel.selectedIndex];
    document.getElementById('nama_barang_hidden').value = opt.getAttribute('data-nama') || '';
    const labEl = document.getElementById('namaLabSelect');
    if (labEl) {
        const labName = opt.getAttribute('data-lab') || '';
        for (let i = 0; i < labEl.options.length; i++) {
            if (labEl.options[i].value === labName) { labEl.selectedIndex = i; break; }
        }
    }
}

(function () {
    const jenis = '{{ old("jenis", "lab") }}';
    toggleJenis(jenis);
    if (jenis === 'lab') {
        setTimeout(function () { window.SeatPicker && window.SeatPicker.checkAndFetch(); }, 100);
    }
})();
</script>
@endpush
@endsection
