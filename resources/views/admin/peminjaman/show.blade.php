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

            {{-- Badge tipe pemohon --}}
            @if($peminjaman->tipe_pemohon === 'eksternal')
                <span class="badge badge-barang"><i class="bi bi-building-fill"></i> Eksternal</span>
            @else
                <span class="badge" style="background:var(--surface-2);color:var(--muted)">
                    <i class="bi bi-person-fill"></i> Internal
                </span>
            @endif

            {{-- Badge jenis --}}
            @if($peminjaman->jenis === 'barang')
                <span class="badge badge-barang"><i class="bi bi-box-seam-fill"></i> Barang / Alat</span>
            @else
                <span class="badge badge-lab"><i class="bi bi-building"></i> Ruang Lab</span>
            @endif
        </div>

        {{-- Badge status --}}
        @php
            $bc = [
                'menunggu'  => 'badge-menunggu',
                'disetujui' => 'badge-disetujui',
                'ditolak'   => 'badge-ditolak',
                'selesai'   => 'badge-selesai',
            ][$peminjaman->status] ?? 'badge-default';
        @endphp
        <span class="badge {{ $bc }}">{{ ucfirst($peminjaman->status) }}</span>

        {{-- Badge status pembayaran (hanya eksternal) --}}
        @if($peminjaman->tipe_pemohon === 'eksternal')
            @if($peminjaman->status_pembayaran === 'lunas')
                <span class="badge" style="background:#d1fae5;color:#065f46;margin-left:4px">
                    <i class="bi bi-check-circle-fill"></i> Lunas
                </span>
            @else
                <span class="badge" style="background:#fef3c7;color:#92400e;margin-left:4px">
                    <i class="bi bi-clock-fill"></i> Belum Bayar
                </span>
            @endif
        @endif
    </div>
    <a href="{{ url()->previous() }}" class="btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(350px,1fr));gap:20px;margin-bottom:20px">

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- CARD KIRI: Data pemohon (berbeda antara internal/eksternal) --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="card">
        <div class="card-header">
            <div class="card-header-icon">
                <i class="{{ $peminjaman->tipe_pemohon === 'eksternal' ? 'bi bi-building' : 'bi bi-person' }}"></i>
            </div>
            <h2>{{ $peminjaman->tipe_pemohon === 'eksternal' ? 'Data Instansi' : 'Data Mahasiswa' }}</h2>
        </div>

        @if($peminjaman->tipe_pemohon === 'eksternal')
            <div class="info-item">
                <span class="info-label">Nama Instansi</span>
                <span class="info-value" style="font-weight:500">{{ $peminjaman->nama_instansi }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">PIC / Penanggung Jawab</span>
                <span class="info-value">{{ $peminjaman->pic_instansi }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Kontak PIC</span>
                <span class="info-value mono">{{ $peminjaman->kontak_instansi }}</span>
            </div>
        @else
            <div class="info-item">
                <span class="info-label">Nama</span>
                <span class="info-value" style="font-weight:500">{{ $peminjaman->mahasiswa->nama ?? '-' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">NIM</span>
                <span class="info-value mono">{{ $peminjaman->nim }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">No. Telepon</span>
                <span class="info-value mono">{{ $peminjaman->mahasiswa->no_telepon ?? '-' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Alamat</span>
                <span class="info-value">{{ $peminjaman->mahasiswa->alamat ?? '-' }}</span>
            </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- CARD KANAN: Detail peminjaman                         --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="card">
        <div class="card-header">
            <div class="card-header-icon">
                <i class="{{ $peminjaman->jenis === 'barang' ? 'bi bi-box-seam' : 'bi bi-building' }}"></i>
            </div>
            <h2>{{ $peminjaman->jenis === 'barang' ? 'Detail Barang Dipinjam' : 'Detail Peminjaman Lab' }}</h2>
        </div>

        @if($peminjaman->jenis === 'barang')
            <div class="info-item">
                <span class="info-label">Barang</span>
                <span class="info-value" style="font-weight:500">{{ $peminjaman->nama_barang ?? '-' }}</span>
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
            <div class="info-item">
                <span class="info-label">Laboratorium</span>
                <span class="info-value" style="font-weight:500">{{ $peminjaman->nama_lab ?? '-' }}</span>
            </div>
            {{-- Kursi hanya untuk internal --}}
            @if($peminjaman->tipe_pemohon === 'internal')
                <div class="info-item">
                    <span class="info-label">Kursi</span>
                    <span class="info-value mono">{{ $peminjaman->kursi ?? '-' }}</span>
                </div>
            @endif
        @endif

        <div class="info-item">
            <span class="info-label">Tanggal Mulai</span>
            <span class="info-value mono">
                {{ \Carbon\Carbon::parse($peminjaman->tanggal)->format('d M Y') }}
            </span>
        </div>

        {{-- Tanggal selesai + durasi hanya untuk eksternal --}}
        @if($peminjaman->tipe_pemohon === 'eksternal')
            <div class="info-item">
                <span class="info-label">Tanggal Selesai</span>
                <span class="info-value mono">
                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->format('d M Y') }}
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Durasi</span>
                <span class="info-value mono">{{ $peminjaman->durasi_hari }} hari</span>
            </div>
        @endif

        <div class="info-item">
            <span class="info-label">Jam</span>
            <span class="info-value">
                <span class="time-range">
                    {{ substr($peminjaman->jam_mulai, 0, 5) }} – {{ substr($peminjaman->jam_selesai, 0, 5) }}
                </span>
            </span>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- CARD BIAYA — hanya eksternal                              --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if($peminjaman->tipe_pemohon === 'eksternal')
<div class="card" style="margin-bottom:20px">
    <div class="card-header">
        <div class="card-header-icon"><i class="bi bi-cash-coin"></i></div>
        <h2>Informasi Biaya</h2>
    </div>
    <div class="info-item">
        <span class="info-label">Biaya per Hari</span>
        <span class="info-value mono">Rp {{ number_format($peminjaman->biaya_per_hari, 0, ',', '.') }}</span>
    </div>
    <div class="info-item">
        <span class="info-label">Durasi</span>
        <span class="info-value mono">{{ $peminjaman->durasi_hari }} hari</span>
    </div>
    <div class="info-item">
        <span class="info-label">Total Biaya</span>
        <span class="info-value" style="font-size:18px;font-weight:600;color:var(--primary)">
            Rp {{ number_format($peminjaman->total_biaya, 0, ',', '.') }}
        </span>
    </div>
    <div class="info-item">
        <span class="info-label">Status Pembayaran</span>
        <span class="info-value">
            @if($peminjaman->status_pembayaran === 'lunas')
                <span style="display:inline-flex;align-items:center;gap:6px;color:#065f46;font-weight:500">
                    <i class="bi bi-check-circle-fill"></i> Lunas
                </span>
            @else
                <span style="display:inline-flex;align-items:center;gap:6px;color:#92400e;font-weight:500">
                    <i class="bi bi-clock-fill"></i> Belum Bayar
                </span>
            @endif
        </span>
    </div>
</div>
@endif

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- CARD TINDAKAN                                             --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if($peminjaman->status === 'menunggu')
<div class="card">
    <div class="card-header">
        <div class="card-header-icon"><i class="bi bi-sliders"></i></div>
        <h2>Tindakan</h2>
    </div>
    <div style="display:flex;gap:10px">
        <form action="{{ route('admin.peminjaman.approve', $peminjaman->id_data) }}" method="POST" style="margin:0">
            @csrf
            <button type="submit" class="btn-success"
                    onclick="return confirm('Setujui peminjaman ini?')">
                <i class="bi bi-check2-circle"></i> Setujui Peminjaman
            </button>
        </form>
        <form action="{{ route('admin.peminjaman.reject', $peminjaman->id_data) }}" method="POST" style="margin:0">
            @csrf
            <button type="submit" class="btn-danger"
                    onclick="return confirm('Tolak peminjaman ini?')">
                <i class="bi bi-x-circle"></i> Tolak Peminjaman
            </button>
        </form>
    </div>
</div>

@elseif($peminjaman->status === 'disetujui')
<div class="card">
    <div class="card-header">
        <div class="card-header-icon"><i class="bi bi-check2-circle"></i></div>
        <h2>Tindakan</h2>
    </div>

    <div style="display:flex;flex-wrap:wrap;gap:10px;align-items:flex-start">

        {{-- Konfirmasi pembayaran — hanya eksternal belum bayar --}}
        @if($peminjaman->tipe_pemohon === 'eksternal' && $peminjaman->status_pembayaran === 'belum_bayar')
            <form action="{{ route('admin.peminjaman.updatePembayaran', $peminjaman->id_data) }}"
                  method="POST" style="margin:0">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-primary"
                        onclick="return confirm('Konfirmasi pembayaran sudah lunas?')">
                    <i class="bi bi-cash-coin"></i> Konfirmasi Pembayaran Lunas
                </button>
            </form>
        @endif

        {{-- Checkout / tandai selesai --}}
        {{-- Eksternal: baru bisa checkout setelah lunas --}}
        @if($peminjaman->tipe_pemohon === 'internal' || $peminjaman->status_pembayaran === 'lunas')
            <form action="{{ route('admin.peminjaman.checkout', $peminjaman->id_data) }}"
                  method="POST" style="margin:0">
                @csrf
                <button type="submit" class="btn-blue"
                        onclick="return confirm('Tandai selesai & kembalikan stok?')">
                    <i class="bi bi-check2-circle"></i> Tandai Selesai & Kembalikan Stok
                </button>
            </form>
        @else
            {{-- Eksternal belum lunas: tombol checkout di-disable --}}
            <button class="btn-blue" disabled
                    title="Selesaikan pembayaran terlebih dahulu"
                    style="opacity:0.5;cursor:not-allowed">
                <i class="bi bi-lock"></i> Tandai Selesai (Belum Lunas)
            </button>
        @endif

    </div>

    {{-- Hint untuk eksternal yang belum bayar --}}
    @if($peminjaman->tipe_pemohon === 'eksternal' && $peminjaman->status_pembayaran === 'belum_bayar')
        <p style="font-size:12.5px;color:var(--muted);margin-top:12px">
            <i class="bi bi-info-circle"></i>
            Peminjaman eksternal harus dikonfirmasi pembayarannya sebelum bisa ditandai selesai.
        </p>
    @endif
</div>

@else
<div class="card" style="background:var(--bg)">
    <div style="display:flex;align-items:center;gap:10px;color:var(--muted)">
        <i class="bi bi-lock-fill"></i>
        <span style="font-size:13.5px">
            Peminjaman ini berstatus
            <strong style="color:var(--text)">{{ ucfirst($peminjaman->status) }}</strong>
            dan tidak dapat diubah.
        </span>
    </div>
</div>
@endif

@endsection