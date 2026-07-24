@extends('admin.layouts.app')
@section('title', 'Dashboard Admin')
@section('content')

<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p>Selamat datang, {{ Auth::guard('admin')->user()->nama }}</p>
    </div>
    <a href="{{ route('admin.peminjaman.create') }}" class="btn-primary">
        <i class="bi bi-plus"></i> Buat Peminjaman
    </a>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-card-label">TOTAL LAB</div>
        <div class="stat-card-value">{{ $total_lab }}</div>
        <div class="stat-card-sub">Laboratorium terdaftar</div>
    </div>
    <div class="stat-card warn">
        <div class="stat-card-label">MENUNGGU</div>
        <div class="stat-card-value">{{ $total_menunggu }}</div>
        <div class="stat-card-sub">Perlu persetujuan</div>
    </div>
    <div class="stat-card green">
        <div class="stat-card-label">DISETUJUI</div>
        <div class="stat-card-value">{{ $total_disetujui }}</div>
        <div class="stat-card-sub">Sedang berlangsung</div>
    </div>
    <div class="stat-card red">
        <div class="stat-card-label">DITOLAK</div>
        <div class="stat-card-value">{{ $total_ditolak }}</div>
        <div class="stat-card-sub">Permintaan ditolak</div>
    </div>
</div>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
    <div>
        <h2 style="font-size:15px;font-weight:600">Permintaan Menunggu Persetujuan</h2>
        <p style="font-size:12.5px;color:var(--muted);margin-top:2px">Refresh otomatis setiap 5 detik</p>
    </div>
    <a href="{{ route('admin.peminjaman.index') }}" class="btn-secondary" style="font-size:12.5px">
        Lihat Semua <i class="bi bi-arrow-right"></i>
    </a>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Jenis</th>
                <th>Lab / Barang</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="dataTableBody">
            @forelse($peminjaman as $p)
            <tr>
                <td class="mono">{{ $p->nim }}</td>
                <td>{{ $p->mahasiswa->nama ?? '-' }}</td>
                <td>
                    @if($p->jenis === 'barang')
                        <span class="badge badge-barang"><i class="bi bi-box-seam-fill"></i> Barang</span>
                    @else
                        <span class="badge badge-lab"><i class="bi bi-building"></i> Lab</span>
                    @endif
                </td>
                <td>{{ $p->jenis === 'barang' ? $p->nama_barang : $p->nama_lab }}</td>
                <td class="mono">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                <td><span class="time-range">{{ substr($p->jam_mulai,0,5) }} – {{ substr($p->jam_selesai,0,5) }}</span></td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('admin.peminjaman.show', $p->id_data) }}" class="btn-action btn-view" title="Detail"><i class="bi bi-eye"></i></a>
                        <form action="{{ route('admin.peminjaman.approve', $p->id_data) }}" method="POST" style="margin:0">
                            @csrf
                            <button type="submit" class="btn-action btn-approve" title="Setujui"><i class="bi bi-check-lg"></i> Setujui</button>
                        </form>
                        <form action="{{ route('admin.peminjaman.reject', $p->id_data) }}" method="POST" style="margin:0">
                            @csrf
                            <button type="submit" class="btn-action btn-reject" title="Tolak"><i class="bi bi-x-lg"></i> Tolak</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                        <p>Tidak ada permintaan yang menunggu persetujuan.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
function loadData() {
    fetch('{{ route('admin.dashboard.refresh') }}')
        .then(r => r.text())
        .then(html => { document.getElementById('dataTableBody').innerHTML = html; })
        .catch(() => {});
}
setInterval(loadData, 5000);
</script>
@endpush
@endsection
