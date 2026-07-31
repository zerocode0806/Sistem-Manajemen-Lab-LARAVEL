@extends('mahasiswa.layouts.app')
@section('title', 'Arsip Peminjaman')
@section('content')

<div class="page-header">
    <div><h1>Arsip Peminjaman</h1><p>Peminjaman yang telah selesai atau ditolak</p></div>
</div>

<div class="toolbar">
    <div class="search-wrap">
        <i class="bi bi-search"></i>
        <input type="text" class="search-input" id="searchInput" placeholder="Cari nama lab, barang, atau tanggal…">
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
            <tr>
                <td>
                    @if($p->jenis === 'barang')
                        <span class="badge badge-barang">Barang</span>
                    @else
                        <span class="badge badge-lab">Lab</span>
                    @endif
                </td>
                <td style="font-weight:500">{{ $p->jenis === 'barang' ? $p->nama_barang : $p->nama_lab }}</td>
                <td class="mono">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                <td><span class="badge {{ $bc }}">{{ ucfirst($p->status) }}</span></td>
                <td><a href="{{ route('mahasiswa.peminjaman.show', $p->id_data) }}" class="btn-detail"><i class="bi bi-eye"></i> Detail</a></td>
            </tr>
            @empty
            <tr><td colspan="5"><div class="empty-state"><div class="empty-icon"><i class="bi bi-archive"></i></div><p>Arsip kosong.</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
const rows = Array.from(document.querySelectorAll('#tableBody tr')).filter(r => r.querySelectorAll('td').length > 1);
rows.forEach(r => r.setAttribute('data-search', r.textContent.toLowerCase()));
const rc = document.getElementById('rowCount');
rc.textContent = rows.length + ' data';
document.getElementById('searchInput').addEventListener('input', function() {
    const q = this.value.toLowerCase(); let v = 0;
    rows.forEach(r => { const s = r.getAttribute('data-search').includes(q); r.style.display = s ? '' : 'none'; if(s) v++; });
    rc.textContent = v + ' data';
});
</script>
@endpush
@endsection