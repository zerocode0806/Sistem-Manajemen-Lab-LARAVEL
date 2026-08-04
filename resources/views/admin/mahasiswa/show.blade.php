@extends('admin.layouts.app')
@section('title', 'Detail Mahasiswa')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.mahasiswa.index') }}">Mahasiswa</a>
    <i class="bi bi-chevron-right"></i><span class="current">{{ $mahasiswa->nama }}</span>
</div>

<div class="page-header">
    <div><h1>Detail Mahasiswa</h1></div>
    <div style="display:flex;gap:8px">
        <a href="{{ route('admin.mahasiswa.edit', $mahasiswa->nim) }}" class="btn-primary"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('admin.mahasiswa.index') }}" class="btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div style="display:grid;grid-template-rows:1fr 100%;gap:20px;align-items:start">
    <div class="card">
        <div class="card-header">
            <div class="card-header-icon"><i class="bi bi-person"></i></div>
            <h2>Profil Mahasiswa</h2>
        </div>
        <div class="info-item"><span class="info-label">NIM</span><span class="info-value mono">{{ $mahasiswa->nim }}</span></div>
        <div class="info-item"><span class="info-label">Nama</span><span class="info-value" style="font-weight:500">{{ $mahasiswa->nama }}</span></div>
        <div class="info-item"><span class="info-label">No. Telepon</span><span class="info-value mono">{{ $mahasiswa->no_telepon }}</span></div>
        <div class="info-item"><span class="info-label">Alamat</span><span class="info-value">{{ $mahasiswa->alamat }}</span></div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-header-icon"><i class="bi bi-clock-history"></i></div>
            <h2>Riwayat Peminjaman</h2>
        </div>
        <div style="overflow:auto;border-radius:6px;border:1px solid var(--border)">
            <table>
                <thead><tr><th>Jenis</th><th>Tanggal</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($mahasiswa->pinjaman()->latest('tanggal')->get() as $p)
                    <tr>
                        <td>{{ $p->jenis === 'barang' ? $p->nama_barang : $p->nama_lab }}</td>
                        <td class="mono">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                        <td>
                            @php $bc = ['menunggu'=>'badge-menunggu','disetujui'=>'badge-disetujui','ditolak'=>'badge-ditolak','selesai'=>'badge-selesai'][$p->status] ?? 'badge-default'; @endphp
                            <span class="badge {{ $bc }}">{{ ucfirst($p->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;padding:24px;color:var(--muted);font-size:13px">Belum ada riwayat peminjaman</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
