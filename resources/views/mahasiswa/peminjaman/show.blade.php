@extends('mahasiswa.layouts.app')
@section('title', 'Detail Peminjaman')
@section('content')

@php
    $tanggalMulai = \Carbon\Carbon::parse($peminjaman->tanggal);
    $tanggalSelesai = \Carbon\Carbon::parse($peminjaman->tanggal_selesai ?: $peminjaman->tanggal);
@endphp

<style>
    /* Preview gambar barang pada detail peminjaman */
.detail-barang-preview {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px;
    margin-bottom: 14px;
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 9px;
}

.detail-barang-preview img {
    width: 85px;
    height: 85px;
    object-fit: cover;
    border-radius: 7px;
    background: #f1f1f1;
    flex-shrink: 0;
}

.detail-barang-preview-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
    min-width: 0;
}

.detail-barang-preview-name {
    font-size: 13.5px;
    font-weight: 600;
    color: var(--text);
}

.detail-barang-preview-caption {
    font-size: 12px;
    color: var(--muted);
}

@media (max-width: 480px) {
    .detail-barang-preview img {
        width: 70px;
        height: 70px;
    }
}
</style>

<div class="breadcrumb">
    <a href="{{ route('mahasiswa.peminjaman.riwayat') }}">Riwayat</a>
    <i class="bi bi-chevron-right"></i><span class="current">Detail #{{ $peminjaman->id_data }}</span>
</div>

<div class="page-header">
    <div>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px">
            <h1>Detail Peminjaman</h1>
            @if($peminjaman->jenis === 'barang')
                <span class="badge badge-barang"><i class="bi bi-box-seam-fill"></i> Barang / Alat</span>
            @else
                <span class="badge badge-lab"><i class="bi bi-building"></i> Ruang Lab</span>
            @endif
        </div>
        @php $bc = ['menunggu'=>'badge-menunggu','disetujui'=>'badge-disetujui','ditolak'=>'badge-ditolak','selesai'=>'badge-selesai'][$peminjaman->status] ?? 'badge-default'; @endphp
        <span class="badge {{ $bc }}">{{ ucfirst($peminjaman->status) }}</span>
    </div>
    <a href="{{ url()->previous() }}" class="btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width:640px">
    <div class="card-header">
        <div class="card-header-icon">
            <i class="{{ $peminjaman->jenis === 'barang' ? 'bi bi-box-seam' : 'bi bi-building' }}"></i>
        </div>
        <h2>{{ $peminjaman->jenis === 'barang' ? 'Detail Peminjaman Barang' : 'Detail Peminjaman Lab' }}</h2>
    </div>

    @if($peminjaman->jenis === 'barang')
        {{-- Preview Gambar Barang --}}
        @if($peminjaman->barang?->gambar)
            <div class="detail-barang-preview">
                <img
                    src="{{ asset('storage/' . $peminjaman->barang->gambar) }}"
                    alt="{{ $peminjaman->nama_barang }}"
                    onerror="this.style.display='none'"
                >
                <div class="detail-barang-preview-info">
                    <span class="detail-barang-preview-name">
                        {{ $peminjaman->nama_barang ?? '-' }}
                    </span>
                    <span class="detail-barang-preview-caption">
                        Barang yang Anda pinjam
                    </span>
                </div>
            </div>
        @endif

        <div class="info-item">
            <span class="info-label">Nama Barang</span>
            <span class="info-value" style="font-weight:500">
                {{ $peminjaman->nama_barang ?? '-' }}
            </span>
        </div>

        <div class="info-item">
            <span class="info-label">Jumlah</span>
            <span class="info-value mono">{{ $peminjaman->jumlah }} unit</span>
        </div>

        <div class="info-item">
            <span class="info-label">Lab Asal</span>
            <span class="info-value">{{ $peminjaman->nama_lab ?? '-' }}</span>
        </div>
    @else
        <div class="info-item"><span class="info-label">Laboratorium</span><span class="info-value" style="font-weight:500">{{ $peminjaman->nama_lab }}</span></div>
        <div class="info-item"><span class="info-label">Nomor Kursi</span><span class="info-value mono">{{ $peminjaman->seluruh_lab ? 'Seluruh lab' : ($peminjaman->kursi ?? '-') }}</span></div>
    @endif

    <div class="info-item"><span class="info-label">Mulai</span><span class="info-value mono">{{ $tanggalMulai->format('l, d M Y') }} · {{ substr($peminjaman->jam_mulai,0,5) }}</span></div>
    <div class="info-item"><span class="info-label">Jadwal selesai</span><span class="info-value mono">{{ $tanggalSelesai->format('l, d M Y') }} · {{ substr($peminjaman->jam_selesai,0,5) }}</span></div>
    @if($peminjaman->tanggal_dikembalikan || $peminjaman->jam_dikembalikan)
        <div class="info-item"><span class="info-label">Dikembalikan aktual</span><span class="info-value mono">{{ $peminjaman->tanggal_dikembalikan ? \Carbon\Carbon::parse($peminjaman->tanggal_dikembalikan)->format('d M Y') : '-' }} · {{ $peminjaman->jam_dikembalikan ? substr($peminjaman->jam_dikembalikan, 0, 5) : '-' }}</span></div>
    @endif
    <div class="info-item"><span class="info-label">Status</span><span class="info-value"><span class="badge {{ $bc }}">{{ ucfirst($peminjaman->status) }}</span></span></div>
    <div class="info-item"><span class="info-label">Diajukan</span><span class="info-value mono">{{ \Carbon\Carbon::parse($peminjaman->created_at)->format('d M Y H:i') }}</span></div>

    @if($peminjaman->status === 'menunggu')
    <hr style="border:none;border-top:1px solid var(--border);margin:16px 0">
    <div style="display:flex;align-items:center;gap:8px">
        <i class="bi bi-hourglass-split" style="color:var(--muted)"></i>
        <span style="font-size:13px;color:var(--muted)">Permintaan Anda sedang menunggu persetujuan admin.</span>
    </div>
    @elseif($peminjaman->status === 'ditolak')
    <hr style="border:none;border-top:1px solid var(--border);margin:16px 0">
    <div style="display:flex;align-items:center;gap:8px">
        <i class="bi bi-x-circle" style="color:var(--red)"></i>
        <span style="font-size:13px;color:var(--red)">Permintaan Anda telah ditolak. Hubungi admin untuk informasi lebih lanjut.</span>
    </div>
    @elseif($peminjaman->status === 'disetujui')
    <hr style="border:none;border-top:1px solid var(--border);margin:16px 0">
    <div style="display:flex;align-items:center;gap:8px">
        <i class="bi bi-check2-circle" style="color:var(--green)"></i>
        <span style="font-size:13px;color:var(--green);font-weight:500">Peminjaman Anda telah disetujui. Silakan gunakan fasilitas sesuai jadwal.</span>
    </div>
    @endif
</div>
@endsection
