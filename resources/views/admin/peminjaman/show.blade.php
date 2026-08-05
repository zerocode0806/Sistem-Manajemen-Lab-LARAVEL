@extends('admin.layouts.app')
@section('title', 'Detail Peminjaman')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.peminjaman.index') }}">Permintaan</a>
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

<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(350px,1fr));gap:20px;margin-bottom:20px">
    <div class="card">
        <div class="card-header">
            <div class="card-header-icon"><i class="bi bi-person"></i></div>
            <h2>Data Mahasiswa</h2>
        </div>
        <div class="info-item"><span class="info-label">Nama</span><span class="info-value" style="font-weight:500">{{ $peminjaman->mahasiswa->nama ?? '-' }}</span></div>
        <div class="info-item"><span class="info-label">NIM</span><span class="info-value mono">{{ $peminjaman->nim }}</span></div>
        <div class="info-item"><span class="info-label">No. Telepon</span><span class="info-value mono">{{ $peminjaman->mahasiswa->no_telepon ?? '-' }}</span></div>
        <div class="info-item"><span class="info-label">Alamat</span><span class="info-value">{{ $peminjaman->mahasiswa->alamat ?? '-' }}</span></div>
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
            <div class="info-item"><span class="info-label">Kursi</span><span class="info-value mono">{{ $peminjaman->kursi ?? '-' }}</span></div>
        @endif
        <div class="info-item"><span class="info-label">Tanggal</span><span class="info-value mono">{{ \Carbon\Carbon::parse($peminjaman->tanggal)->format('d M Y') }}</span></div>
        <div class="info-item"><span class="info-label">Jam</span><span class="info-value"><span class="time-range">{{ substr($peminjaman->jam_mulai,0,5) }} – {{ substr($peminjaman->jam_selesai,0,5) }}</span></span></div>
    </div>
</div>

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
    <p style="font-size:13.5px;color:var(--muted);margin-bottom:14px">Klik tombol di bawah untuk menandai peminjaman selesai dan mengembalikan stok.</p>
    <form action="{{ route('admin.peminjaman.checkout', $peminjaman->id_data) }}" method="POST" style="margin:0">
        @csrf
        <button type="submit" class="btn-blue" onclick="return confirm('Tandai selesai & kembalikan stok?')">
            <i class="bi bi-check2-circle"></i> Tandai Selesai & Kembalikan Stok
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
