@extends('mahasiswa.layouts.app')
@section('title', 'Detail Peminjaman')
@section('content')

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
        <div class="info-item"><span class="info-label">Nama Barang</span><span class="info-value" style="font-weight:500">{{ $peminjaman->nama_barang }}</span></div>
        <div class="info-item"><span class="info-label">Jumlah</span><span class="info-value mono">{{ $peminjaman->jumlah }} unit</span></div>
        <div class="info-item"><span class="info-label">Lab Asal</span><span class="info-value">{{ $peminjaman->nama_lab }}</span></div>
    @else
        <div class="info-item"><span class="info-label">Laboratorium</span><span class="info-value" style="font-weight:500">{{ $peminjaman->nama_lab }}</span></div>
        <div class="info-item"><span class="info-label">Nomor Kursi</span><span class="info-value mono">{{ $peminjaman->kursi ?? '-' }}</span></div>
    @endif

    <div class="info-item"><span class="info-label">Tanggal</span><span class="info-value mono">{{ \Carbon\Carbon::parse($peminjaman->tanggal)->format('l, d MMMM Y', 'id') }}</span></div>
    <div class="info-item"><span class="info-label">Jam</span><span class="info-value"><span class="time-range">{{ substr($peminjaman->jam_mulai,0,5) }} – {{ substr($peminjaman->jam_selesai,0,5) }}</span></span></div>
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
