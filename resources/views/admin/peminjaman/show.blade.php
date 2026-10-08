@extends('admin.layouts.app')
@section('title', 'Detail Peminjaman')
@section('content')

@php
    $kategoriPeminjam = $peminjaman->kategori_peminjam ?? 'internal';
    $labelPeminjam = [
        'internal' => 'Mahasiswa Internal',
        'eksternal_individu' => 'Eksternal Individu',
        'eksternal_instansi' => 'Eksternal Instansi',
    ][$kategoriPeminjam] ?? 'Peminjam';
    $tanggalMulai = \Carbon\Carbon::parse($peminjaman->tanggal);
    $tanggalSelesai = \Carbon\Carbon::parse($peminjaman->tanggal_selesai ?: $peminjaman->tanggal);
    $isExternalBooking = in_array($kategoriPeminjam, ['eksternal_individu', 'eksternal_instansi'], true);
    $jumlahHariPeminjaman = max(1, (int) $tanggalMulai->diffInDays($tanggalSelesai) + 1);
    $biayaPeminjaman = (int) ($peminjaman->biaya_peminjaman ?? 0);
    if ($isExternalBooking && $biayaPeminjaman <= 0) {
        $biayaPeminjaman = $jumlahHariPeminjaman * 75000;
    }
    $pembayaranLunas = $peminjaman->status_pembayaran === 'lunas';
    $statusPembayaranLabel = $pembayaranLunas
        ? 'Lunas'
        : ($peminjaman->status_pembayaran === 'belum_lunas' ? 'Belum lunas' : 'Belum diverifikasi');
    $statusPembayaranBadge = $pembayaranLunas
        ? 'badge-disetujui'
        : ($peminjaman->status_pembayaran === 'belum_lunas' ? 'badge-menunggu' : 'badge-default');
    $checkoutEnabled = !$isExternalBooking || $pembayaranLunas;
@endphp

<div class="breadcrumb">
    <a href="{{ route('admin.peminjaman.index') }}">Permintaan</a>
    <i class="bi bi-chevron-right"></i><span class="current">Detail #{{ $peminjaman->id_data }}</span>
</div>

<div class="page-header">
    <div>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;flex-wrap:wrap">
            <h1>Detail Peminjaman</h1>
            @if($peminjaman->jenis === 'barang')
                <span class="badge badge-barang"><i class="bi bi-box-seam-fill"></i> Barang / Alat</span>
            @else
                <span class="badge badge-lab"><i class="bi bi-building"></i> Ruang Lab</span>
            @endif
            @if($kategoriPeminjam !== 'internal')
                <span class="badge badge-default">{{ $labelPeminjam }}</span>
            @endif
        </div>
        @php $bc = ['menunggu'=>'badge-menunggu','disetujui'=>'badge-disetujui','ditolak'=>'badge-ditolak','selesai'=>'badge-selesai'][$peminjaman->status] ?? 'badge-default'; @endphp
        <span class="badge {{ $bc }}">{{ ucfirst($peminjaman->status) }}</span>
    </div>
    <a href="{{ url()->previous() }}" class="btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="detail-grid">
    <div class="card">
        <div class="card-header">
            <div class="card-header-icon"><i class="bi bi-person"></i></div>
            <h2>{{ $labelPeminjam }}</h2>
        </div>
        <div class="info-item"><span class="info-label">{{ $kategoriPeminjam === 'eksternal_instansi' ? 'Penanggung jawab' : 'Nama' }}</span><span class="info-value" style="font-weight:500">{{ $peminjaman->mahasiswa->nama ?? $peminjaman->nama_peminjam ?? '-' }}</span></div>
        @if($kategoriPeminjam === 'eksternal_instansi')
            <div class="info-item"><span class="info-label">Instansi / Organisasi</span><span class="info-value">{{ $peminjaman->nama_instansi ?? '-' }}</span></div>
        @elseif($kategoriPeminjam === 'eksternal_individu')
            <div class="info-item"><span class="info-label">NIM / Identitas</span><span class="info-value mono">{{ $peminjaman->nim_eksternal ?: '-' }}</span></div>
            <div class="info-item"><span class="info-label">Program studi / asal</span><span class="info-value">{{ $peminjaman->prodi_peminjam ?? '-' }}</span></div>
        @else
            <div class="info-item"><span class="info-label">NIM</span><span class="info-value mono">{{ $peminjaman->nim ?? '-' }}</span></div>
            <div class="info-item"><span class="info-label">Alamat</span><span class="info-value">{{ $peminjaman->mahasiswa->alamat ?? '-' }}</span></div>
        @endif
        <div class="info-item"><span class="info-label">No. Telepon</span><span class="info-value mono">{{ $peminjaman->mahasiswa->no_telepon ?? $peminjaman->kontak_peminjam ?? '-' }}</span></div>
        @if($peminjaman->tujuan_peminjaman)
            <div class="info-item"><span class="info-label">Tujuan / kegiatan</span><span class="info-value" style="white-space:pre-wrap;overflow-wrap:anywhere">{{ $peminjaman->tujuan_peminjaman }}</span></div>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-header-icon">
                <i class="{{ $peminjaman->jenis === 'barang' ? 'bi bi-box-seam' : 'bi bi-building' }}"></i>
            </div>
            <h2>{{ $peminjaman->jenis === 'barang' ? 'Detail Barang Dipinjam' : 'Detail Peminjaman Lab' }}</h2>
        </div>
        @if($peminjaman->jenis === 'barang')
            <div class="info-item"><span class="info-label">Barang</span><span class="info-value" style="font-weight:500">{{ $peminjaman->nama_barang ?? '-' }}</span></div>
            <div class="info-item"><span class="info-label">Jumlah</span><span class="info-value mono">{{ $peminjaman->jumlah }} unit</span></div>
            <div class="info-item"><span class="info-label">Lab Asal</span><span class="info-value">{{ $peminjaman->nama_lab ?? '-' }}</span></div>
        @else
            <div class="info-item"><span class="info-label">Laboratorium</span><span class="info-value" style="font-weight:500">{{ $peminjaman->nama_lab ?? '-' }}</span></div>
            <div class="info-item"><span class="info-label">Area</span><span class="info-value">{{ $peminjaman->seluruh_lab ? 'Seluruh lab' : 'Kursi ' . ($peminjaman->kursi ?? '-') }}</span></div>
        @endif
        <div class="info-item"><span class="info-label">Mulai</span><span class="info-value mono">{{ $tanggalMulai->format('d M Y') }} · {{ substr($peminjaman->jam_mulai,0,5) }}</span></div>
        <div class="info-item"><span class="info-label">Jadwal selesai</span><span class="info-value mono">{{ $tanggalSelesai->format('d M Y') }} · {{ substr($peminjaman->jam_selesai,0,5) }}</span></div>
        @if($peminjaman->tanggal_dikembalikan || $peminjaman->jam_dikembalikan)
            <div class="info-item"><span class="info-label">Dikembalikan aktual</span><span class="info-value mono">{{ $peminjaman->tanggal_dikembalikan ? \Carbon\Carbon::parse($peminjaman->tanggal_dikembalikan)->format('d M Y') : '-' }} · {{ $peminjaman->jam_dikembalikan ? substr($peminjaman->jam_dikembalikan, 0, 5) : '-' }}</span></div>
        @endif
    </div>
</div>

@if($isExternalBooking)
<div class="card">
    <div class="card-header">
        <div class="card-header-icon"><i class="bi bi-cash-coin"></i></div>
        <h2>Pembayaran</h2>
    </div>
    <div class="info-item"><span class="info-label">Tarif</span><span class="info-value mono">Rp75.000 / hari</span></div>
    <div class="info-item"><span class="info-label">Lama peminjaman</span><span class="info-value">{{ $jumlahHariPeminjaman }} hari</span></div>
    <div class="info-item"><span class="info-label">Total tagihan</span><span class="info-value mono" style="font-weight:600">Rp{{ number_format($biayaPeminjaman, 0, ',', '.') }}</span></div>
    <div class="info-item">
        <span class="info-label">Status pembayaran</span>
        <span class="info-value">
            <span class="badge {{ $statusPembayaranBadge }}">
                {{ $statusPembayaranLabel }}
            </span>
        </span>
    </div>
    @if($peminjaman->pembayaran_dikonfirmasi_pada)
        <div class="info-item">
            <span class="info-label">Dikonfirmasi pada</span>
            <span class="info-value mono">{{ \Carbon\Carbon::parse($peminjaman->pembayaran_dikonfirmasi_pada)->format('d M Y · H:i') }}</span>
        </div>
    @endif
    @if($peminjaman->status === 'disetujui' && !$pembayaranLunas)
        <form action="{{ route('admin.peminjaman.confirmPayment', $peminjaman->id_data) }}" method="POST" style="margin-top:14px">
            @csrf
            <button type="submit" class="btn-success" onclick="return confirm('Konfirmasi bahwa pembayaran sebesar Rp{{ number_format($biayaPeminjaman, 0, ',', '.') }} telah lunas?')">
                <i class="bi bi-check2-circle"></i> Konfirmasi Pelunasan
            </button>
        </form>
    @endif
</div>
@endif

@if($peminjaman->status === 'menunggu')
<div class="card">
    <div class="card-header"><div class="card-header-icon"><i class="bi bi-sliders"></i></div><h2>Tindakan</h2></div>
    <div style="display:flex;gap:10px">
        <form action="{{ route('admin.peminjaman.approve', $peminjaman->id_data) }}" method="POST" style="margin:0">
            @csrf
            <button type="submit" class="btn-success" onclick="return confirm('Setujui peminjaman ini?')">
                <i class="bi bi-check2-circle"></i> Setujui Peminjaman
            </button>
        </form>
        <form action="{{ route('admin.peminjaman.reject', $peminjaman->id_data) }}" method="POST" style="margin:0">
            @csrf
            <button type="submit" class="btn-danger" onclick="return confirm('Tolak peminjaman ini?')">
                <i class="bi bi-x-circle"></i> Tolak Peminjaman
            </button>
        </form>
    </div>
</div>
@elseif($peminjaman->status === 'disetujui')
<div class="card">
    <div class="card-header"><div class="card-header-icon"><i class="bi bi-check2-circle"></i></div><h2>Tandai Selesai</h2></div>
    @if(!$checkoutEnabled)
        <div class="flash flash-warn">
            <i class="bi bi-lock-fill"></i>
            <span>Checkout terkunci. Admin harus mengonfirmasi pelunasan pembayaran terlebih dahulu.</span>
        </div>
    @else
        <p style="font-size:13.5px;color:var(--muted);margin-bottom:14px">Masukkan tanggal dan jam pengembalian aktual sebelum menandai peminjaman selesai.</p>
    @endif
    <form action="{{ route('admin.peminjaman.checkout', $peminjaman->id_data) }}" method="POST" style="margin:0">
        @csrf
        <div class="field-row" style="max-width:600px">
            <div class="field-group">
                <label for="tanggal_dikembalikan">Tanggal pengembalian aktual</label>
                <input type="date" id="tanggal_dikembalikan" name="tanggal_dikembalikan" value="{{ old('tanggal_dikembalikan', now()->format('Y-m-d')) }}" required @if(!$checkoutEnabled) disabled @endif>
                @error('tanggal_dikembalikan')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field-group">
                <label for="jam_dikembalikan">Jam pengembalian aktual</label>
                <input type="time" id="jam_dikembalikan" name="jam_dikembalikan" value="{{ old('jam_dikembalikan', now()->format('H:i')) }}" required @if(!$checkoutEnabled) disabled @endif>
                @error('jam_dikembalikan')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>
        <button type="submit" class="btn-blue" @if(!$checkoutEnabled) disabled aria-disabled="true" style="opacity:.55;cursor:not-allowed" title="Konfirmasi pelunasan pembayaran terlebih dahulu" @else onclick="return confirm('Simpan waktu pengembalian dan tandai peminjaman selesai?')" @endif>
            <i class="bi bi-check2-circle"></i> Simpan Pengembalian & Tandai Selesai
        </button>
    </form>
</div>
@else
<div class="card" style="background:var(--bg)">
    <div style="display:flex;align-items:center;gap:10px;color:var(--muted)">
        <i class="bi bi-lock-fill"></i>
        <span style="font-size:13.5px">Peminjaman ini berstatus <strong style="color:var(--text)">{{ ucfirst($peminjaman->status) }}</strong> dan tidak dapat diubah.</span>
    </div>
</div>
@endif

@endsection
