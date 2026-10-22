@extends('admin.layouts.app')
@section('title', 'Arsip Peminjaman')
@section('content')

<style>
.archive-filters {
    display: grid;
    grid-template-columns: minmax(220px, 1.5fr) repeat(3, minmax(150px, 1fr)) auto auto;
    align-items: center;
    gap: 10px;
    width: 100%;
}
.archive-filters .search-wrap { max-width: none; min-width: 0; }
.archive-filter-select {
    width: 100%;
    min-width: 0;
    height: 36px;
    padding: 0 30px 0 11px;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    background: var(--surface);
    color: var(--text);
    font: 13px 'DM Sans', sans-serif;
}
.archive-filter-button,
.archive-reset-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    min-height: 36px;
    padding: 0 12px;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    background: var(--surface);
    color: var(--text);
    font: 500 13px 'DM Sans', sans-serif;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}
.archive-filter-button { background: var(--accent); border-color: var(--accent); color: #fff; }
.archive-reset-button:hover { background: var(--bg); color: var(--text); }
.archive-toolbar-count { margin-left: auto; }
.archive-payment { display: flex; flex-direction: column; gap: 3px; }
.archive-payment small { color: var(--muted); white-space: nowrap; }
@media (max-width: 1000px) {
    .archive-filters { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .archive-filters .search-wrap { grid-column: 1 / -1; }
    .archive-toolbar-count { margin-left: 0; }
}
@media (max-width: 560px) {
    .archive-filters { grid-template-columns: minmax(0, 1fr); gap: 8px; }
    .archive-filters .search-wrap { grid-column: auto; max-width: none; width: 100%; }
    .archive-filter-button, .archive-reset-button { width: 100%; }
    .archive-toolbar-count { justify-self: start; }
}
</style>

<div class="page-header">
    <div><h1>Arsip Peminjaman</h1><p>Riwayat peminjaman yang telah selesai atau ditolak</p></div>
</div>

<form class="toolbar archive-filters" method="GET" action="{{ route('admin.peminjaman.arsip') }}">
    <div class="search-wrap">
        <i class="bi bi-search"></i>
        <input type="search" class="search-input" name="q" value="{{ request('q') }}" placeholder="Cari NIM, nama, instansi, atau lab…" aria-label="Cari arsip peminjaman">
    </div>
    <select class="archive-filter-select" name="status" aria-label="Filter status">
        <option value="">Semua status</option>
        <option value="selesai" @selected(request('status') === 'selesai')>Selesai</option>
        <option value="ditolak" @selected(request('status') === 'ditolak')>Ditolak</option>
    </select>
    <select class="archive-filter-select" name="kategori" aria-label="Filter kategori peminjam">
        <option value="">Semua kategori</option>
        <option value="internal" @selected(request('kategori') === 'internal')>Internal</option>
        <option value="eksternal" @selected(request('kategori') === 'eksternal')>Eksternal</option>
        <option value="eksternal_individu" @selected(request('kategori') === 'eksternal_individu')>Eksternal individu</option>
        <option value="eksternal_instansi" @selected(request('kategori') === 'eksternal_instansi')>Eksternal instansi</option>
    </select>
    <select class="archive-filter-select" name="pembayaran" aria-label="Filter pembayaran">
        <option value="">Semua pembayaran</option>
        <option value="lunas" @selected(request('pembayaran') === 'lunas')>Lunas</option>
        <option value="belum_lunas" @selected(request('pembayaran') === 'belum_lunas')>Belum lunas</option>
        <option value="tidak_berlaku" @selected(request('pembayaran') === 'tidak_berlaku')>Tidak berlaku</option>
    </select>
    <button type="submit" class="archive-filter-button"><i class="bi bi-funnel"></i> Terapkan</button>
    <a href="{{ route('admin.peminjaman.arsip') }}" class="archive-reset-button"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
</form>
<div class="toolbar" style="margin-top:-8px">
    <span class="row-count archive-toolbar-count">{{ $peminjaman->count() }} data</span>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr><th>NIM / Identitas</th><th>Nama</th><th>Jenis</th><th>Lab / Barang</th><th>Tanggal</th><th>Status</th><th>Pembayaran</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $p)
            <tr>
                <td class="mono">{{ $p->nim ?? $p->nim_eksternal ?? '-' }}</td>
                <td style="font-weight:500">{{ $p->mahasiswa->nama ?? $p->nama_peminjam ?? '-' }}</td>
                <td>
                    @if($p->jenis === 'barang')
                        <span class="badge badge-barang">Barang</span>
                    @else
                        <span class="badge badge-lab">Lab</span>
                    @endif
                    @if(($p->kategori_peminjam ?? 'internal') === 'eksternal_individu')
                        <span class="badge badge-default">Eksternal Individu</span>
                    @elseif(($p->kategori_peminjam ?? 'internal') === 'eksternal_instansi')
                        <span class="badge badge-default">Eksternal Instansi</span>
                    @endif
                </td>
                <td>{{ $p->jenis === 'barang' ? $p->nama_barang : $p->nama_lab }}</td>
                <td class="mono">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}@if($p->tanggal_selesai && $p->tanggal_selesai !== $p->tanggal) – {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}@endif</td>
                <td>
                    @php $bc = ['selesai'=>'badge-selesai','ditolak'=>'badge-ditolak'][$p->status] ?? 'badge-default'; @endphp
                    <span class="badge {{ $bc }}">{{ ucfirst($p->status) }}</span>
                </td>
                <td>
                    @if(in_array($p->kategori_peminjam ?? 'internal', ['eksternal_individu', 'eksternal_instansi'], true))
                        <div class="archive-payment">
                            @if($p->status_pembayaran === 'lunas')
                                <span class="badge badge-disetujui">Lunas</span>
                            @else
                                <span class="badge badge-menunggu">Belum lunas</span>
                            @endif
                            <small>Rp {{ number_format((int) ($p->biaya_peminjaman ?? 0), 0, ',', '.') }}</small>
                        </div>
                    @else
                        <span class="badge badge-default">Tidak berlaku</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.peminjaman.show', $p->id_data) }}" class="btn-action btn-view" title="Detail"><i class="bi bi-eye"></i></a>
                </td>
            </tr>
        @empty
            <tr><td colspan="8"><div class="empty-state"><div class="empty-icon"><i class="bi bi-archive"></i></div><p>Arsip tidak ditemukan. Coba ubah kata pencarian atau filter.</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
