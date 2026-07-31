@extends('mahasiswa.layouts.app')
@section('title', 'Riwayat Peminjaman')
@section('content')

<div class="page-header">
    <div><h1>Riwayat Peminjaman</h1><p>Daftar semua peminjaman yang pernah Anda ajukan</p></div>
    <a href="{{ route('mahasiswa.peminjaman.create') }}" class="btn-primary"><i class="bi bi-plus"></i> Ajukan Baru</a>
</div>

<div class="toolbar">
    <div class="search-wrap">
        <i class="bi bi-search"></i>
        <input type="text" class="search-input" id="searchInput" placeholder="Cari nama lab, barang, atau tanggal…">
    </div>
    <div class="filter-tabs" id="filterTabs">
        <button class="filter-tab active" data-filter="all">Semua</button>
        <button class="filter-tab" data-filter="selesai">Selesai</button>
        <button class="filter-tab" data-filter="ditolak">Ditolak</button>
    </div>
    <span class="row-count" id="rowCount"></span>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr><th>Jenis</th><th>Lab / Barang</th><th>Tanggal</th><th>Jam</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody id="tableBody">
            @forelse($peminjaman as $p)
            @php $bc = ['menunggu'=>'badge-menunggu','disetujui'=>'badge-disetujui','ditolak'=>'badge-ditolak','selesai'=>'badge-selesai'][$p->status] ?? 'badge-default'; @endphp
            <tr data-status="{{ $p->status }}">
                <td>
                    @if($p->jenis === 'barang')
                        <span class="badge badge-barang">Barang</span>
                    @else
                        <span class="badge badge-lab">Lab</span>
                    @endif
                </td>
                <td style="font-weight:500">{{ $p->jenis === 'barang' ? $p->nama_barang : $p->nama_lab }}</td>
                <td class="mono">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                <td><span class="time-range">{{ substr($p->jam_mulai,0,5) }} – {{ substr($p->jam_selesai,0,5) }}</span></td>
                <td><span class="badge {{ $bc }}">{{ ucfirst($p->status) }}</span></td>
                <td><a href="{{ route('mahasiswa.peminjaman.show', $p->id_data) }}" class="btn-detail"><i class="bi bi-eye"></i> Detail</a></td>
            </tr>
            @empty
            <tr><td colspan="6"><div class="empty-state"><div class="empty-icon"><i class="bi bi-inbox"></i></div><p>Belum ada riwayat peminjaman.</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
const rows = Array.from(document.querySelectorAll('#tableBody tr')).filter(r => r.querySelectorAll('td').length > 1);
rows.forEach(r => r.setAttribute('data-search', r.textContent.toLowerCase()));
const rc = document.getElementById('rowCount');
let activeFilter = 'all';
function applyFilters(q) {
    let v = 0;
    rows.forEach(r => {
        const ok = (activeFilter === 'all' || r.getAttribute('data-status') === activeFilter) && (!q || r.getAttribute('data-search').includes(q));
        r.style.display = ok ? '' : 'none'; if(ok) v++;
    });
    rc.textContent = v + ' data';
}
rc.textContent = rows.length + ' data';
document.querySelectorAll('.filter-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.filter-tab').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        activeFilter = btn.getAttribute('data-filter');
        applyFilters(document.getElementById('searchInput').value.toLowerCase());
    });
});
document.getElementById('searchInput').addEventListener('input', function() { applyFilters(this.value.toLowerCase()); });
</script>
@endpush
@endsection
