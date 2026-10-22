@extends('mahasiswa.layouts.app')
@section('title', 'Arsip Peminjaman')
@section('content')

<style>
.student-archive-toolbar { align-items: flex-start; }
.student-archive-filters {
    display: flex;
    align-items: center;
    gap: 6px;
    min-width: 0;
    flex-wrap: wrap;
}
.student-archive-filters .filter-tab {
    padding: 6px 13px;
    border: 1px solid var(--border);
    border-radius: 999px;
    background: var(--surface);
    color: var(--muted);
    font: 500 12.5px 'DM Sans', sans-serif;
    cursor: pointer;
    white-space: nowrap;
    transition: background .15s, color .15s, border-color .15s, box-shadow .15s;
}
.student-archive-filters .filter-tab:hover {
    background: var(--bg);
    color: var(--text);
}
.student-archive-filters .filter-tab.active {
    background: var(--accent);
    border-color: var(--accent);
    color: #fff;
    box-shadow: 0 1px 2px rgba(0, 0, 0, .08);
}
@media (max-width: 760px) {
    .student-archive-toolbar { display: grid; grid-template-columns: minmax(0, 1fr); }
    .student-archive-toolbar .search-wrap { width: 100%; max-width: none; }
    .student-archive-filters {
        width: 100%;
        flex-wrap: nowrap;
        overflow-x: auto;
        padding: 2px 0 6px;
        -webkit-overflow-scrolling: touch;
    }
    .student-archive-filters .filter-tab { flex: 0 0 auto; }
    .student-archive-toolbar .row-count { justify-self: start; }
}
</style>

<div class="page-header">
    <div><h1>Arsip Peminjaman</h1><p>Peminjaman yang telah selesai atau ditolak</p></div>
</div>

<div class="toolbar student-archive-toolbar">
    <div class="search-wrap">
        <i class="bi bi-search"></i>
        <input type="text" class="search-input" id="searchInput" placeholder="Cari nama lab, barang, atau tanggal…">
    </div>
    <div class="student-archive-filters" id="filterTabs" role="group" aria-label="Filter status arsip">
        <button type="button" class="filter-tab active" data-filter="all" aria-pressed="true">Semua</button>
        <button type="button" class="filter-tab" data-filter="selesai" aria-pressed="false">Selesai</button>
        <button type="button" class="filter-tab" data-filter="ditolak" aria-pressed="false">Ditolak</button>
    </div>
    <span class="row-count" id="rowCount"></span>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr><th>Jenis</th><th>Lab / Barang</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody id="tableBody">
            @forelse($peminjaman as $p)
            @php $bc = ['selesai'=>'badge-selesai','ditolak'=>'badge-ditolak'][$p->status] ?? 'badge-default'; @endphp
            <tr data-status="{{ $p->status }}">
                <td>
                    @if($p->jenis === 'barang')
                        <span class="badge badge-barang">Barang</span>
                    @else
                        <span class="badge badge-lab">Lab</span>
                    @endif
                </td>
                <td style="font-weight:500">{{ $p->jenis === 'barang' ? $p->nama_barang : $p->nama_lab }}</td>
                <td class="mono">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}@if($p->tanggal_selesai && $p->tanggal_selesai !== $p->tanggal) – {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}@endif</td>
                <td><span class="badge {{ $bc }}">{{ ucfirst($p->status) }}</span></td>
                <td><a href="{{ route('mahasiswa.peminjaman.show', $p->id_data) }}" class="btn-detail"><i class="bi bi-eye"></i> Detail</a></td>
            </tr>
            @empty
            <tr class="archive-empty-state"><td colspan="5"><div class="empty-state"><div class="empty-icon"><i class="bi bi-archive"></i></div><p>Tidak ada arsip yang sesuai dengan filter.</p></div></td></tr>
            @endforelse
            <tr class="archive-filter-empty" hidden><td colspan="5"><div class="empty-state"><div class="empty-icon"><i class="bi bi-search"></i></div><p>Tidak ada data yang cocok. Coba ubah pencarian atau filter.</p></div></td></tr>
        </tbody>
    </table>
</div>

@push('scripts')
<script>
const rows = Array.from(document.querySelectorAll('#tableBody tr')).filter(r => r.querySelectorAll('td').length > 1);
rows.forEach(r => r.setAttribute('data-search', r.textContent.toLowerCase()));
const rc = document.getElementById('rowCount');
const emptyState = document.querySelector('#tableBody .archive-empty-state');
const filterEmptyState = document.querySelector('#tableBody .archive-filter-empty');
let activeFilter = 'all';

function applyArchiveFilters() {
    const searchTerm = document.getElementById('searchInput').value.trim().toLocaleLowerCase('id');
    let visibleCount = 0;

    rows.forEach(row => {
        const statusMatches = activeFilter === 'all' || row.dataset.status === activeFilter;
        const searchMatches = !searchTerm || row.dataset.search.includes(searchTerm);
        const visible = statusMatches && searchMatches;
        row.hidden = !visible;
        if (visible) visibleCount++;
    });

    rc.textContent = visibleCount + ' data';
    if (emptyState) emptyState.hidden = rows.length > 0;
    if (filterEmptyState) filterEmptyState.hidden = rows.length === 0 || visibleCount > 0;
}

document.querySelectorAll('#filterTabs .filter-tab').forEach(button => {
    button.addEventListener('click', () => {
        activeFilter = button.dataset.filter;
        document.querySelectorAll('#filterTabs .filter-tab').forEach(tab => {
            const selected = tab === button;
            tab.classList.toggle('active', selected);
            tab.setAttribute('aria-pressed', selected ? 'true' : 'false');
        });
        applyArchiveFilters();
    });
});

document.getElementById('searchInput').addEventListener('input', function() {
    applyArchiveFilters();
});
applyArchiveFilters();
</script>
@endpush
@endsection
