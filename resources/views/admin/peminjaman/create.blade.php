@extends('admin.layouts.app')

@section('title', 'Buat Peminjaman')

@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.peminjaman.index') }}">Permintaan</a>
    <i class="bi bi-chevron-right"></i>
    <span class="current">Buat Peminjaman</span>
</div>

<div class="page-header">
    <div>
        <h1>Buat Peminjaman</h1>
        <p>
            Buat pengajuan peminjaman lab atau barang atas nama mahasiswa
            maupun instansi eksternal
        </p>
    </div>
</div>

<div class="form-card" style="max-width:780px">
    <form
        action="{{ route('admin.peminjaman.store') }}"
        method="POST"
        id="peminjamanForm"
    >
        @csrf

        {{-- TIPE PEMOHON --}}
        <div class="field-group">
            <label>
                Tipe Pemohon
                <span style="color:var(--red)">*</span>
            </label>

            <div style="display:flex;gap:12px">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px;font-weight:400">
                    <input
                        type="radio"
                        name="tipe_pemohon"
                        value="internal"
                        @checked(old('tipe_pemohon', 'internal') === 'internal')
                        onchange="toggleTipe('internal')"
                    >
                    Internal (Mahasiswa)
                </label>

                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px;font-weight:400">
                    <input
                        type="radio"
                        name="tipe_pemohon"
                        value="eksternal"
                        @checked(old('tipe_pemohon') === 'eksternal')
                        onchange="toggleTipe('eksternal')"
                    >
                    Eksternal (Instansi / Perusahaan)
                </label>
            </div>

            @error('tipe_pemohon')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        {{-- ═══════════════════════════════════════════ --}}
        {{-- SECTION INTERNAL                           --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div id="section_internal">

            {{-- MAHASISWA --}}
            <div class="field-group">
                <label for="nim">
                    Mahasiswa
                    <span style="color:var(--red)">*</span>
                </label>

                <select id="nim" name="nim">
                    <option value="">— Pilih Mahasiswa —</option>

                    @foreach($mahasiswa as $mhs)
                        <option
                            value="{{ $mhs->nim }}"
                            @selected(old('nim') == $mhs->nim)
                        >
                            {{ $mhs->nama }} ({{ $mhs->nim }})
                        </option>
                    @endforeach
                </select>

                @error('nim')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- JENIS PEMINJAMAN --}}
            <div class="field-group">
                <label>
                    Jenis Peminjaman
                    <span style="color:var(--red)">*</span>
                </label>

                <div style="display:flex;gap:12px">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px;font-weight:400">
                        <input
                            type="radio"
                            name="jenis"
                            id="jenisLab"
                            value="lab"
                            @checked(old('jenis', 'lab') === 'lab')
                            onchange="toggleJenis('lab')"
                        >
                        Ruang Lab
                    </label>

                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px;font-weight:400">
                        <input
                            type="radio"
                            name="jenis"
                            id="jenisBarang"
                            value="barang"
                            @checked(old('jenis') === 'barang')
                            onchange="toggleJenis('barang')"
                        >
                        Barang / Alat
                    </label>
                </div>

                @error('jenis')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- LAB INTERNAL --}}
            <div id="section_lab_internal">
                <div class="field-group">
                    <label for="namaLabSelect">
                        Laboratorium
                        <span style="color:var(--red)">*</span>
                    </label>

                    <select
                        id="namaLabSelect"
                        name="nama_lab"
                        onchange="onLabChange()"
                    >
                        <option value="">— Pilih Lab —</option>

                        @foreach($labs as $lab)
                            <option
                                value="{{ $lab->nama_lab }}"
                                data-kursi="{{ $lab->jumlah_kursi }}"
                                @selected(old('nama_lab') == $lab->nama_lab)
                            >
                                {{ $lab->nama_lab }}
                                (stok: {{ $lab->stok }} |
                                {{ $lab->jumlah_kursi }} kursi)
                            </option>
                        @endforeach
                    </select>

                    @error('nama_lab')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- BARANG --}}
            <div id="section_barang" style="display:none">
                <div class="field-row">
                    <div class="field-group">
                        <label for="id_barang">Barang</label>

                        <select
                            id="id_barang"
                            name="id_barang"
                            onchange="setNamaBarang(this)"
                        >
                            <option value="">— Pilih Barang —</option>

                            @foreach($barang as $b)
                                <option
                                    value="{{ $b->id_barang }}"
                                    data-nama="{{ $b->nama_barang }}"
                                    data-lab="{{ $b->lab->nama_lab ?? '' }}"
                                    @selected(old('id_barang') == $b->id_barang)
                                >
                                    {{ $b->nama_barang }}
                                    (stok: {{ $b->stok }})
                                </option>
                            @endforeach
                        </select>

                        @error('id_barang')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label for="jumlah">Jumlah</label>

                        <input
                            type="number"
                            id="jumlah"
                            name="jumlah"
                            value="{{ old('jumlah', 1) }}"
                            min="1"
                        >

                        @error('jumlah')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <input
                    type="hidden"
                    name="nama_barang"
                    id="nama_barang_hidden"
                    value="{{ old('nama_barang') }}"
                >
            </div>
        </div>

        {{-- ═══════════════════════════════════════════ --}}
        {{-- SECTION EKSTERNAL                          --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div id="section_eksternal" style="display:none">

            {{-- Info biaya --}}
            <div style="
                background:var(--yellow-soft,#fef9ec);
                border:1px solid var(--yellow,#f5c518);
                border-radius:10px;
                padding:12px 16px;
                margin-bottom:16px;
                font-size:13px
            ">
                <i class="bi bi-info-circle" style="color:var(--yellow,#f5c518)"></i>
                Peminjaman lab oleh instansi/perusahaan dikenakan biaya
                <strong>Rp 75.000 / hari</strong>.
                Total biaya dihitung otomatis.
            </div>

            {{-- Baris 1: nama instansi + PIC --}}
            <div class="field-row">
                <div class="field-group">
                    <label for="nama_instansi">
                        Nama Instansi / Perusahaan
                        <span style="color:var(--red)">*</span>
                    </label>
                    <input
                        type="text"
                        id="nama_instansi"
                        name="nama_instansi"
                        value="{{ old('nama_instansi') }}"
                        placeholder="PT. Contoh Jaya"
                    >
                    @error('nama_instansi')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-group">
                    <label for="pic_instansi">
                        Nama PIC / Penanggung Jawab
                        <span style="color:var(--red)">*</span>
                    </label>
                    <input
                        type="text"
                        id="pic_instansi"
                        name="pic_instansi"
                        value="{{ old('pic_instansi') }}"
                        placeholder="Budi Santoso"
                    >
                    @error('pic_instansi')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Baris 2: kontak + lab --}}
            <div class="field-row">
                <div class="field-group">
                    <label for="kontak_instansi">
                        No. Telepon / Email PIC
                        <span style="color:var(--red)">*</span>
                    </label>
                    <input
                        type="text"
                        id="kontak_instansi"
                        name="kontak_instansi"
                        value="{{ old('kontak_instansi') }}"
                        placeholder="08xxxxxxxxxx"
                    >
                    @error('kontak_instansi')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-group">
                    <label for="namaLabEksternal">
                        Laboratorium
                        <span style="color:var(--red)">*</span>
                    </label>
                    <select
                        id="namaLabEksternal"
                        name="nama_lab"
                        onchange="hitungBiaya()"
                    >
                        <option value="">— Pilih Lab —</option>
                        @foreach($labs as $lab)
                            <option
                                value="{{ $lab->nama_lab }}"
                                @selected(old('nama_lab') == $lab->nama_lab)
                            >
                                {{ $lab->nama_lab }}
                            </option>
                        @endforeach
                    </select>
                    @error('nama_lab')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Baris 3: alamat instansi (full width) --}}
            <div class="field-group">
                <label for="alamat_instansi">
                    Alamat Instansi
                    <span style="color:var(--red)">*</span>
                </label>
                <input
                    type="text"
                    id="alamat_instansi"
                    name="alamat_instansi"
                    value="{{ old('alamat_instansi') }}"
                    placeholder="Jl. Contoh No. 1, Kota"
                >
                @error('alamat_instansi')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Baris 4: keperluan (full width) --}}
            <div class="field-group">
                <label for="keperluan">
                    Keperluan / Tujuan Peminjaman
                    <span style="color:var(--red)">*</span>
                </label>
                <textarea
                    id="keperluan"
                    name="keperluan"
                    rows="3"
                    placeholder="Jelaskan tujuan penggunaan laboratorium..."
                    style="
                        width:100%;
                        padding:8px 12px;
                        border:1px solid var(--border);
                        border-radius:8px;
                        font-family:DM Sans,sans-serif;
                        font-size:13.5px;
                        resize:vertical;
                        outline:none;
                        background:var(--surface-1);
                        color:var(--text)
                    "
                >{{ old('keperluan') }}</textarea>
                @error('keperluan')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Baris 5: no surat opsional --}}
            <div class="field-group">
                <label for="no_surat">
                    No. Surat / MOU
                    <span style="font-size:12px;color:var(--muted);font-weight:400">
                        (opsional)
                    </span>
                </label>
                <input
                    type="text"
                    id="no_surat"
                    name="no_surat"
                    value="{{ old('no_surat') }}"
                    placeholder="Contoh: 001/MOU/2024"
                >
                @error('no_surat')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

        </div>{{-- end section_eksternal --}}

        {{-- TANGGAL DAN JAM --}}
        <div class="field-row">
            <div class="field-group">
                <label for="tanggalInput">
                    Tanggal
                    <span id="labelTanggal"></span>
                    <span style="color:var(--red)">*</span>
                </label>

                <input
                    type="date"
                    id="tanggalInput"
                    name="tanggal"
                    value="{{ old('tanggal') }}"
                    required
                    onchange="onScheduleChange(); hitungBiaya()"
                >

                @error('tanggal')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div
                class="field-group"
                id="fieldTanggalSelesai"
                style="display:none"
            >
                <label for="tanggalSelesaiInput">
                    Tanggal Selesai
                    <span style="color:var(--red)">*</span>
                </label>

                <input
                    type="date"
                    id="tanggalSelesaiInput"
                    name="tanggal_selesai"
                    value="{{ old('tanggal_selesai') }}"
                    onchange="hitungBiaya()"
                >

                @error('tanggal_selesai')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="field-group">
                <label>Jam</label>

                <div style="display:flex;gap:8px;align-items:center">
                    <input
                        type="time"
                        id="jamMulaiInput"
                        name="jam_mulai"
                        value="{{ old('jam_mulai') }}"
                        required
                        onchange="onScheduleChange()"
                        style="
                            height:38px;
                            padding:0 12px;
                            border:1px solid var(--border);
                            border-radius:8px;
                            font-family:DM Sans,sans-serif;
                            font-size:13.5px;
                            flex:1;
                            outline:none
                        "
                    >

                    <span style="color:var(--muted);font-size:13px">–</span>

                    <input
                        type="time"
                        id="jamSelesaiInput"
                        name="jam_selesai"
                        value="{{ old('jam_selesai') }}"
                        required
                        onchange="onScheduleChange()"
                        style="
                            height:38px;
                            padding:0 12px;
                            border:1px solid var(--border);
                            border-radius:8px;
                            font-family:DM Sans,sans-serif;
                            font-size:13.5px;
                            flex:1;
                            outline:none
                        "
                    >
                </div>

                @error('jam_mulai')
                    <span class="field-error">{{ $message }}</span>
                @enderror

                @error('jam_selesai')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- KALKULASI BIAYA EKSTERNAL --}}
        <div
            id="biayaBox"
            style="
                display:none;
                background:var(--surface-2,#f8f9fa);
                border:1px solid var(--border);
                border-radius:10px;
                padding:14px 18px;
                margin-bottom:16px
            "
        >
            <div style="font-size:13px;color:var(--muted);margin-bottom:6px">
                Estimasi Biaya
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center">
                <span style="font-size:13.5px" id="biayaDetail">— hari × Rp 75.000</span>
                <span style="font-size:18px;font-weight:600;color:var(--primary)" id="biayaTotal">Rp 0</span>
            </div>
        </div>

        {{-- SEAT PICKER --}}
        <div id="seatPickerContainer">
            @include('components.seat-picker', [
                'seatCheckUrl' => route('admin.peminjaman.checkSeats'),
            ])
        </div>

        @error('kursi')
            <span
                class="field-error"
                style="display:block;margin-top:-12px;margin-bottom:12px"
            >
                {{ $message }}
            </span>
        @enderror

        <div class="form-actions">
            <a href="{{ route('admin.peminjaman.index') }}" class="btn-secondary">Batal</a>
            <div class="form-actions-right">
                <button type="submit" class="btn-primary">
                    <i class="bi bi-check2"></i> Buat Peminjaman
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function getTipePemohon() {
        return document.querySelector(
            'input[name="tipe_pemohon"]:checked'
        )?.value || 'internal';
    }

    function getJenisPeminjaman() {
        return document.querySelector(
            'input[name="jenis"]:checked'
        )?.value || 'lab';
    }

    function isInternalLab() {
        return getTipePemohon() === 'internal' && getJenisPeminjaman() === 'lab';
    }

    function updateLabSelectState() {
        const labInternal  = document.getElementById('namaLabSelect');
        const labEksternal = document.getElementById('namaLabEksternal');
        const isInternal   = getTipePemohon() === 'internal';
        const isLab        = getJenisPeminjaman() === 'lab';

        if (labInternal) {
            labInternal.disabled = !isInternal;
            labInternal.required  = isInternal && isLab;
        }
        if (labEksternal) {
            labEksternal.disabled = isInternal;
            labEksternal.required  = !isInternal;
        }
    }

    function updateSeatPickerVisibility() {
        const seatPickerSection = document.getElementById('seatPickerSection');
        const kursiInput        = document.getElementById('spKursiInput');

        if (!seatPickerSection || !kursiInput) return;

        const shouldShow = isInternalLab();

        seatPickerSection.style.display = shouldShow ? '' : 'none';
        kursiInput.required = shouldShow;

        if (!shouldShow) kursiInput.value = '';
        if (shouldShow && window.SeatPicker) window.SeatPicker.checkAndFetch();
    }

    function toggleTipe(tipe) {
        const isInternal = tipe === 'internal';

        document.getElementById('section_internal').style.display  = isInternal ? '' : 'none';
        document.getElementById('section_eksternal').style.display = isInternal ? 'none' : '';
        document.getElementById('fieldTanggalSelesai').style.display = isInternal ? 'none' : '';

        const biayaBox = document.getElementById('biayaBox');
        if (biayaBox && isInternal) biayaBox.style.display = 'none';

        const labelTanggal = document.getElementById('labelTanggal');
        if (labelTanggal) labelTanggal.textContent = isInternal ? '' : 'Mulai';

        // Required fields
        const fields = {
            'tanggalSelesaiInput' : !isInternal,
            'nim'                 : isInternal,
            'nama_instansi'       : !isInternal,
            'pic_instansi'        : !isInternal,
            'kontak_instansi'     : !isInternal,
            'alamat_instansi'     : !isInternal,  // ← baru
            'keperluan'           : !isInternal,  // ← baru
        };

        Object.entries(fields).forEach(([id, req]) => {
            const el = document.getElementById(id);
            if (el) el.required = req;
        });

        updateLabSelectState();
        updateSeatPickerVisibility();
    }

    function toggleJenis(jenis) {
        const sectionLabInternal = document.getElementById('section_lab_internal');
        const sectionBarang      = document.getElementById('section_barang');

        if (sectionLabInternal) sectionLabInternal.style.display = jenis === 'lab'    ? '' : 'none';
        if (sectionBarang)      sectionBarang.style.display      = jenis === 'barang' ? '' : 'none';

        updateLabSelectState();
        updateSeatPickerVisibility();

        if (jenis === 'lab' && isInternalLab() && window.SeatPicker) {
            window.SeatPicker.checkAndFetch();
        }
    }

    function onLabChange() {
        if (isInternalLab() && window.SeatPicker) window.SeatPicker.checkAndFetch();
    }

    function onScheduleChange() {
        if (isInternalLab() && window.SeatPicker) window.SeatPicker.checkAndFetch();
        if (!isInternalLab()) hitungBiaya();
    }

    function hitungBiaya() {
        const tgl1 = document.getElementById('tanggalInput')?.value;
        const tgl2 = document.getElementById('tanggalSelesaiInput')?.value;
        if (!tgl1 || !tgl2) return;

        const hari  = Math.max(1, Math.round((new Date(tgl2) - new Date(tgl1)) / 86400000) + 1);
        const total = hari * 75000;

        document.getElementById('biayaDetail').textContent = hari + ' hari × Rp 75.000';
        document.getElementById('biayaTotal').textContent  = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('biayaBox').style.display  = '';
    }

    function setNamaBarang(selectElement) {
        const opt = selectElement.options[selectElement.selectedIndex];
        const namaBarangInput = document.getElementById('nama_barang_hidden');
        if (namaBarangInput) namaBarangInput.value = opt.getAttribute('data-nama') || '';

        const labInternalSelect = document.getElementById('namaLabSelect');
        if (!labInternalSelect) return;

        const labName = opt.getAttribute('data-lab') || '';
        for (let i = 0; i < labInternalSelect.options.length; i++) {
            if (labInternalSelect.options[i].value === labName) {
                labInternalSelect.selectedIndex = i;
                break;
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const tipe  = @json(old('tipe_pemohon', 'internal'));
        const jenis = @json(old('jenis', 'lab'));

        toggleTipe(tipe);
        toggleJenis(jenis);
        updateLabSelectState();
        updateSeatPickerVisibility();

        if (isInternalLab() && window.SeatPicker) {
            setTimeout(() => window.SeatPicker.checkAndFetch(), 100);
        }

        if (tipe === 'eksternal') hitungBiaya();
    });
</script>
@endpush

@endsection