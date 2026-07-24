@extends('mahasiswa.layouts.app')
@section('title', 'Dashboard Mahasiswa')
@section('content')

<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p>Selamat datang, {{ Auth::guard('mahasiswa')->user()->nama }} · <span class="mono">{{ Auth::guard('mahasiswa')->user()->nim }}</span></p>
    </div>
    <a href="{{ route('mahasiswa.peminjaman.create') }}" class="btn-primary">
        <i class="bi bi-plus"></i> Ajukan Peminjaman
    </a>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-card-label">TOTAL PENGAJUAN</div>
        <div class="stat-card-value">{{ $total_pinjam }}</div>
        <div class="stat-card-sub">Semua peminjaman</div>
    </div>
    <div class="stat-card warn">
        <div class="stat-card-label">MENUNGGU</div>
        <div class="stat-card-value">{{ $total_menunggu }}</div>
        <div class="stat-card-sub">Belum diproses</div>
    </div>
    <div class="stat-card green">
        <div class="stat-card-label">DISETUJUI</div>
        <div class="stat-card-value">{{ $total_disetujui }}</div>
        <div class="stat-card-sub">Sedang aktif</div>
    </div>
    <div class="stat-card red">
        <div class="stat-card-label">DITOLAK</div>
        <div class="stat-card-value">{{ $total_ditolak }}</div>
        <div class="stat-card-sub">Tidak disetujui</div>
    </div>
</div>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
    <div>
        <h2 style="font-size:15px;font-weight:600">Peminjaman Terkini</h2>
        <p style="font-size:12.5px;color:var(--muted);margin-top:2px">5 data terbaru Anda</p>
    </div>
    <a href="{{ route('mahasiswa.peminjaman.riwayat') }}" class="btn-secondary" style="font-size:12.5px">
        Lihat Semua <i class="bi bi-arrow-right"></i>
    </a>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Jenis</th>
                <th>Lab / Barang</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $p)
            <tr>
                <td>
                    @if($p->jenis === 'barang')
                        <span class="badge badge-barang">Barang</span>
                    @else
                        <span class="badge badge-lab">Lab</span>
                    @endif
                </td>
                <td>{{ $p->jenis === 'barang' ? $p->nama_barang : $p->nama_lab }}</td>
                <td class="mono">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                <td><span class="time-range">{{ substr($p->jam_mulai,0,5) }} – {{ substr($p->jam_selesai,0,5) }}</span></td>
                <td>
                    @php
                        $badgeMap = ['menunggu'=>'badge-menunggu','disetujui'=>'badge-disetujui','ditolak'=>'badge-ditolak','selesai'=>'badge-selesai'];
                        $bc = $badgeMap[$p->status] ?? 'badge-default';
                    @endphp
                    <span class="badge {{ $bc }}">{{ ucfirst($p->status) }}</span>
                </td>
                <td>
                    <a href="{{ route('mahasiswa.peminjaman.show', $p->id_data) }}" class="btn-detail">
                        <i class="bi bi-eye"></i> Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                        <p>Belum ada pengajuan peminjaman. <a href="{{ route('mahasiswa.peminjaman.create') }}" style="color:var(--blue)">Ajukan sekarang</a></p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
