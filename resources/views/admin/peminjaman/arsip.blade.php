@extends('admin.layouts.app')
@section('title', 'Arsip Peminjaman')
@section('content')

<div class="page-header">
    <div><h1>Arsip Peminjaman</h1><p>Riwayat peminjaman yang telah selesai atau ditolak</p></div>
</div>

<div class="toolbar">
    <div class="search-wrap">
        <i class="bi bi-search"></i>
        <input type="text" class="search-input" id="searchInput" placeholder="Cari NIM, nama, atau lab…">
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
            <tr><th>NIM</th><th>Nama</th><th>Jenis</th><th>Lab / Barang</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody id="tableBody">
            @forelse($peminjaman as $p)
            <tr data-status="{{ $p->status }}">
                <td class="mono">{{ $p->nim }}</td>
                <td style="font-weight:500">{{ $p->mahasiswa->nama ?? '-' }}</td>
                <td>
                    @if($p->jenis === 'barang')
                        <span class="badge badge-barang">Barang</span>
                    @else
                        <span class="badge badge-lab">Lab</span>
                    @endif
                </td>
                <td>{{ $p->jenis === 'barang' ? $p->nama_barang : $p->nama_lab }}</td>
                <td class="mono">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                <td>
                    @php $bc = ['selesai'=>'badge-selesai','ditolak'=>'badge-ditolak'][$p->status] ?? 'badge-default'; @endphp
                    <span class="badge {{ $bc }}">{{ ucfirst($p->status) }}</span>
                </td>
                <td>
                    <a href="{{ route('admin.peminjaman.show', $p->id_data) }}" class="btn-action btn-view" title="Detail"><i class="bi bi-eye"></i></a>
                </td>
            </tr>
            @empty
            <tr><td colspan="7"><div class="empty-state"><div class="empty-icon"><i class="bi bi-archive"></i></div><p>Arsip kosong.</p></div></td></tr>
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

function applyFilters(q = '') {
    let v = 0;
    rows.forEach(r => {
        const statusOk = activeFilter === 'all' || r.getAttribute('data-status') === activeFilter;
        const searchOk = !q || r.getAttribute('data-search').includes(q);
        const show = statusOk && searchOk;
        r.style.display = show ? '' : 'none';
        if (show) v++;
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

document.getElementById('searchInput').addEventListener('input', function() {
    applyFilters(this.value.toLowerCase());
});
</script>
@endpush
@endsection
