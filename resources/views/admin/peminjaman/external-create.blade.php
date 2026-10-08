@extends('admin.layouts.app')
@php
    $isIndividual = $externalType === 'individual';
    $pageTitle = $isIndividual ? 'Peminjaman Eksternal Individu' : 'Peminjaman Eksternal Instansi';
    $submitRoute = $isIndividual
        ? route('admin.peminjaman.externalIndividual.store')
        : route('admin.peminjaman.externalInstitution.store');
@endphp
@section('title', $pageTitle)
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.peminjaman.index') }}">Permintaan Peminjaman</a>
    <i class="bi bi-chevron-right"></i><span class="current">{{ $pageTitle }}</span>
</div>

<div class="page-header">
    <div>
        <h1>{{ $pageTitle }}</h1>
        <p>{{ $isIndividual ? 'Catat pengajuan mahasiswa atau peminjam perseorangan yang tidak memiliki akun internal.' : 'Catat penggunaan seluruh ruang lab atas nama organisasi atau instansi.' }}</p>
    </div>
</div>

<div class="form-card" style="max-width:820px">
    <form action="{{ $submitRoute }}" method="POST" id="externalBookingForm">
        @csrf

        @if($isIndividual)
            <div class="field-row">
                <div class="field-group">
                    <label for="nama_peminjam">Nama peminjam <span style="color:var(--red)">*</span></label>
                    <input id="nama_peminjam" name="nama_peminjam" value="{{ old('nama_peminjam') }}" maxlength="150" required>
                    @error('nama_peminjam')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field-group">
                    <label for="nim_eksternal">NIM / nomor identitas <span style="color:var(--red)">*</span></label>
                    <input id="nim_eksternal" name="nim_eksternal" value="{{ old('nim_eksternal') }}" maxlength="32" required>
                    @error('nim_eksternal')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="field-row">
                <div class="field-group">
                    <label for="prodi_peminjam">Program studi / asal <span style="color:var(--red)">*</span></label>
                    <input id="prodi_peminjam" name="prodi_peminjam" value="{{ old('prodi_peminjam') }}" maxlength="150" required>
                    @error('prodi_peminjam')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field-group">
                    <label for="kontak_peminjam">Nomor kontak <span style="color:var(--red)">*</span></label>
                    <input id="kontak_peminjam" name="kontak_peminjam" value="{{ old('kontak_peminjam') }}" maxlength="50" required>
                    @error('kontak_peminjam')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>
        @else
            <div class="field-row">
                <div class="field-group">
                    <label for="nama_instansi">Nama instansi / organisasi <span style="color:var(--red)">*</span></label>
                    <input id="nama_instansi" name="nama_instansi" value="{{ old('nama_instansi') }}" maxlength="150" required>
                    @error('nama_instansi')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field-group">
                    <label for="nama_peminjam">Nama penanggung jawab <span style="color:var(--red)">*</span></label>
                    <input id="nama_peminjam" name="nama_peminjam" value="{{ old('nama_peminjam') }}" maxlength="150" required>
                    @error('nama_peminjam')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="field-group">
                <label for="kontak_peminjam">Nomor kontak penanggung jawab <span style="color:var(--red)">*</span></label>
                <input id="kontak_peminjam" name="kontak_peminjam" value="{{ old('kontak_peminjam') }}" maxlength="50" required>
                @error('kontak_peminjam')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        @endif

        <div class="field-group">
            <label for="tujuan_peminjaman">Tujuan / kegiatan</label>
            <textarea id="tujuan_peminjaman" name="tujuan_peminjaman" rows="3" maxlength="5000">{{ old('tujuan_peminjaman') }}</textarea>
            @error('tujuan_peminjaman')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <div class="field-group">
            <label for="namaLabSelect">Laboratorium <span style="color:var(--red)">*</span></label>
            <select id="namaLabSelect" name="nama_lab" required onchange="onLabChange()">
                <option value="">— Pilih Lab —</option>
                @foreach($labs as $lab)
                    <option value="{{ $lab->nama_lab }}" data-kursi="{{ $lab->jumlah_kursi }}" @selected(old('nama_lab') === $lab->nama_lab)>
                        {{ $lab->nama_lab }} (kapasitas {{ $lab->jumlah_kursi }} kursi)
                    </option>
                @endforeach
            </select>
            @error('nama_lab')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        @if(!$isIndividual)
            <div class="flash flash-warn">
                <i class="bi bi-info-circle"></i>
                <span>Pengajuan instansi memakai seluruh ruang lab. Semua kursi akan dinyatakan tidak tersedia selama jadwal ini.</span>
            </div>
        @endif

        <div class="field-row">
            <div class="field-group">
                <label for="tanggalInput">Tanggal mulai <span style="color:var(--red)">*</span></label>
                <input type="date" id="tanggalInput" name="tanggal" value="{{ old('tanggal') }}" min="{{ date('Y-m-d') }}" required onchange="onScheduleChange()">
                @error('tanggal')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field-group">
                <label for="tanggalSelesaiInput">Tanggal selesai <span style="color:var(--red)">*</span></label>
                <input type="date" id="tanggalSelesaiInput" name="tanggal_selesai" value="{{ old('tanggal_selesai', old('tanggal')) }}" min="{{ old('tanggal', date('Y-m-d')) }}" required onchange="onScheduleChange()">
                @error('tanggal_selesai')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="flash flash-warn" style="align-items:flex-start">
            <i class="bi bi-cash-coin" style="margin-top:2px"></i>
            <div>
                <strong>Biaya sewa lab</strong>
                <div>Tarif Rp75.000 per hari. Tanggal mulai dan selesai dihitung sebagai hari sewa.</div>
                <div style="margin-top:4px">
                    Perkiraan total: <strong id="feeEstimate">Pilih tanggal</strong>
                    <span id="billableDays" style="color:var(--muted)"></span>
                </div>
            </div>
        </div>

        <div class="field-group">
            <label>Jam peminjaman <span style="color:var(--red)">*</span></label>
            <div style="display:flex;gap:8px;align-items:center">
                <input type="time" id="jamMulaiInput" name="jam_mulai" value="{{ old('jam_mulai') }}" required onchange="onScheduleChange()" style="height:38px;padding:0 12px;border:1px solid var(--border);border-radius:8px;font:13.5px 'DM Sans',sans-serif;flex:1;min-width:0">
                <span style="color:var(--muted)">–</span>
                <input type="time" id="jamSelesaiInput" name="jam_selesai" value="{{ old('jam_selesai') }}" required onchange="onScheduleChange()" style="height:38px;padding:0 12px;border:1px solid var(--border);border-radius:8px;font:13.5px 'DM Sans',sans-serif;flex:1;min-width:0">
            </div>
            @error('jam_mulai')<span class="field-error">{{ $message }}</span>@enderror
            @error('jam_selesai')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        @if($isIndividual)
            <input type="hidden" id="jenisLab" value="lab">
            @include('components.seat-picker', ['seatCheckUrl' => route('admin.peminjaman.checkSeats')])
            @error('kursi')<span class="field-error" style="display:block">{{ $message }}</span>@enderror
        @endif

        @if($errors->any())
            <div class="alert-error" style="margin-top:14px">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        <div class="form-actions">
            <a href="{{ route('admin.peminjaman.index') }}" class="btn-secondary">Batal</a>
            <div class="form-actions-right">
                <button type="submit" class="btn-primary"><i class="bi bi-check2"></i> Simpan Pengajuan</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function updateFeeEstimate() {
    const startValue = document.getElementById('tanggalInput').value;
    const endValue = document.getElementById('tanggalSelesaiInput').value;
    const totalElement = document.getElementById('feeEstimate');
    const daysElement = document.getElementById('billableDays');

    if (!startValue || !endValue) {
        totalElement.textContent = 'Pilih tanggal';
        daysElement.textContent = '';
        return;
    }

    const toUtcDate = (value) => {
        const [year, month, day] = value.split('-').map(Number);
        return Date.UTC(year, month - 1, day);
    };
    const days = Math.max(1, Math.round((toUtcDate(endValue) - toUtcDate(startValue)) / 86400000) + 1);
    const total = days * 75000;

    totalElement.textContent = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0
    }).format(total);
    daysElement.textContent = `(${days} hari)`;
}

function onLabChange() {
    window.SeatPicker && window.SeatPicker.checkAndFetch();
}
function onScheduleChange() {
    const start = document.getElementById('tanggalInput').value;
    const end = document.getElementById('tanggalSelesaiInput');
    end.min = start || '{{ date('Y-m-d') }}';
    if (start && (!end.value || end.value < start)) end.value = start;
    updateFeeEstimate();
    const seatInput = document.getElementById('spKursiInput');
    if (seatInput) seatInput.required = true;
    window.SeatPicker && window.SeatPicker.checkAndFetch();
}
document.addEventListener('DOMContentLoaded', () => {
    onScheduleChange();
    window.SeatPicker && window.SeatPicker.checkAndFetch();
});
</script>
@endpush
@endsection
