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

        {{-- SECTION INTERNAL --}}
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

        {{-- SECTION EKSTERNAL --}}
        <div id="section_eksternal" style="display:none">

            <div style="
                background:var(--yellow-soft,#fef9ec);
                border:1px solid var(--yellow,#f5c518);
                border-radius:10px;
                padding:12px 16px;
                margin-bottom:16px;
                font-size:13px
            ">
                <i
                    class="bi bi-info-circle"
                    style="color:var(--yellow,#f5c518)"
                ></i>

                Peminjaman lab oleh instansi/perusahaan dikenakan biaya
                <strong>Rp 75.000 / hari</strong>.
                Total biaya dihitung otomatis.
            </div>

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
        </div>

        {{-- TANGGAL DAN JAM --}}
        <div class="field-row">
            <div class="field-group">
                <label for="tanggalInput">
                    Tanggal
                    <span id="labelTanggal">Mulai</span>
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
            <div style="
                font-size:13px;
                color:var(--muted);
                margin-bottom:6px
            ">
                Estimasi Biaya
            </div>

            <div style="
                display:flex;
                justify-content:space-between;
                align-items:center
            ">
                <span
                    style="font-size:13.5px"
                    id="biayaDetail"
                >
                    — hari × Rp 75.000
                </span>

                <span
                    style="font-size:18px;font-weight:600;color:var(--primary)"
                    id="biayaTotal"
                >
                    Rp 0
                </span>
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
                style="
                    display:block;
                    margin-top:-12px;
                    margin-bottom:12px
                "
            >
                {{ $message }}
            </span>
        @enderror

        <div class="form-actions">
            <a
                href="{{ route('admin.peminjaman.index') }}"
                class="btn-secondary"
            >
                Batal
            </a>

            <div class="form-actions-right">
                <button
                    type="submit"
                    class="btn-primary"
                >
                    <i class="bi bi-check2"></i>
                    Buat Peminjaman
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
        return (
            getTipePemohon() === 'internal' &&
            getJenisPeminjaman() === 'lab'
        );
    }

    /*
     * Penting:
     * Hanya select yang aktif yang boleh dikirim ke server.
     *
     * Jika dua select sama-sama memakai name="nama_lab"
     * tetapi tidak di-disable, browser akan mengirim dua value.
     * Value dari select terakhir dapat menimpa value internal.
     */
    function updateLabSelectState() {
        const labInternal =
            document.getElementById('namaLabSelect');

        const labEksternal =
            document.getElementById('namaLabEksternal');

        const isInternal =
            getTipePemohon() === 'internal';

        const isLab =
            getJenisPeminjaman() === 'lab';

        if (labInternal) {
            labInternal.disabled = !isInternal;
            labInternal.required = isInternal && isLab;
        }

        if (labEksternal) {
            labEksternal.disabled = isInternal;
            labEksternal.required = !isInternal;
        }
    }

    function updateSeatPickerVisibility() {
        const seatPickerSection =
            document.getElementById('seatPickerSection');

        const kursiInput =
            document.getElementById('spKursiInput');

        if (!seatPickerSection || !kursiInput) {
            return;
        }

        const shouldShow =
            isInternalLab();

        seatPickerSection.style.display =
            shouldShow ? '' : 'none';

        kursiInput.required =
            shouldShow;

        if (!shouldShow) {
            kursiInput.value = '';
        }

        if (shouldShow && window.SeatPicker) {
            window.SeatPicker.checkAndFetch();
        }
    }

    function toggleTipe(tipe) {
        const isInternal =
            tipe === 'internal';

        const sectionInternal =
            document.getElementById('section_internal');

        const sectionEksternal =
            document.getElementById('section_eksternal');

        const fieldTanggalSelesai =
            document.getElementById('fieldTanggalSelesai');

        const biayaBox =
            document.getElementById('biayaBox');

        if (sectionInternal) {
            sectionInternal.style.display =
                isInternal ? '' : 'none';
        }

        if (sectionEksternal) {
            sectionEksternal.style.display =
                isInternal ? 'none' : '';
        }

        if (fieldTanggalSelesai) {
            fieldTanggalSelesai.style.display =
                isInternal ? 'none' : '';
        }

        if (biayaBox && isInternal) {
            biayaBox.style.display = 'none';
        }

        const tanggalSelesaiInput =
            document.getElementById('tanggalSelesaiInput');

        const nimInput =
            document.getElementById('nim');

        const namaInstansiInput =
            document.getElementById('nama_instansi');

        const picInstansiInput =
            document.getElementById('pic_instansi');

        const kontakInstansiInput =
            document.getElementById('kontak_instansi');

        if (tanggalSelesaiInput) {
            tanggalSelesaiInput.required =
                !isInternal;
        }

        if (nimInput) {
            nimInput.required =
                isInternal;
        }

        if (namaInstansiInput) {
            namaInstansiInput.required =
                !isInternal;
        }

        if (picInstansiInput) {
            picInstansiInput.required =
                !isInternal;
        }

        if (kontakInstansiInput) {
            kontakInstansiInput.required =
                !isInternal;
        }

        const labelTanggal =
            document.getElementById('labelTanggal');

        if (labelTanggal) {
            labelTanggal.textContent =
                isInternal ? '' : 'Mulai';
        }

        updateLabSelectState();
        updateSeatPickerVisibility();
    }

    function toggleJenis(jenis) {
        const sectionLabInternal =
            document.getElementById('section_lab_internal');

        const sectionBarang =
            document.getElementById('section_barang');

        if (sectionLabInternal) {
            sectionLabInternal.style.display =
                jenis === 'lab' ? '' : 'none';
        }

        if (sectionBarang) {
            sectionBarang.style.display =
                jenis === 'barang' ? '' : 'none';
        }

        updateLabSelectState();
        updateSeatPickerVisibility();

        if (
            jenis === 'lab' &&
            isInternalLab() &&
            window.SeatPicker
        ) {
            window.SeatPicker.checkAndFetch();
        }
    }

    function onLabChange() {
        if (isInternalLab() && window.SeatPicker) {
            window.SeatPicker.checkAndFetch();
        }
    }

    function onScheduleChange() {
        if (isInternalLab() && window.SeatPicker) {
            window.SeatPicker.checkAndFetch();
        }

        if (!isInternalLab()) {
            hitungBiaya();
        }
    }

    function hitungBiaya() {
        const tanggalMulai =
            document.getElementById('tanggalInput')?.value;

        const tanggalSelesai =
            document.getElementById('tanggalSelesaiInput')?.value;

        if (!tanggalMulai || !tanggalSelesai) {
            return;
        }

        const startDate =
            new Date(tanggalMulai);

        const endDate =
            new Date(tanggalSelesai);

        const difference =
            Math.round(
                (endDate - startDate) /
                (1000 * 60 * 60 * 24)
            );

        const jumlahHari =
            Math.max(1, difference + 1);

        const totalBiaya =
            jumlahHari * 75000;

        const biayaDetail =
            document.getElementById('biayaDetail');

        const biayaTotal =
            document.getElementById('biayaTotal');

        const biayaBox =
            document.getElementById('biayaBox');

        if (biayaDetail) {
            biayaDetail.textContent =
                jumlahHari + ' hari × Rp 75.000';
        }

        if (biayaTotal) {
            biayaTotal.textContent =
                'Rp ' + totalBiaya.toLocaleString('id-ID');
        }

        if (biayaBox) {
            biayaBox.style.display = '';
        }
    }

    function setNamaBarang(selectElement) {
        const option =
            selectElement.options[selectElement.selectedIndex];

        const namaBarangInput =
            document.getElementById('nama_barang_hidden');

        if (namaBarangInput) {
            namaBarangInput.value =
                option.getAttribute('data-nama') || '';
        }

        const labInternalSelect =
            document.getElementById('namaLabSelect');

        if (!labInternalSelect) {
            return;
        }

        const labName =
            option.getAttribute('data-lab') || '';

        for (
            let index = 0;
            index < labInternalSelect.options.length;
            index++
        ) {
            if (
                labInternalSelect.options[index].value === labName
            ) {
                labInternalSelect.selectedIndex = index;
                break;
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const tipePemohon =
            @json(old('tipe_pemohon', 'internal'));

        const jenisPeminjaman =
            @json(old('jenis', 'lab'));

        /*
         * Urutan penting:
         * 1. Tipe pemohon.
         * 2. Jenis peminjaman.
         * 3. Status select lab.
         * 4. Status seat picker.
         */
        toggleTipe(tipePemohon);
        toggleJenis(jenisPeminjaman);
        updateLabSelectState();
        updateSeatPickerVisibility();

        if (isInternalLab() && window.SeatPicker) {
            setTimeout(function () {
                window.SeatPicker.checkAndFetch();
            }, 100);
        }

        if (tipePemohon === 'eksternal') {
            hitungBiaya();
        }
    });
</script>
@endpush

@endsection