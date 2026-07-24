@extends('admin.layouts.app')
@section('title', 'Data Barang')
@section('content')

<div class="page-header">
    <div>
        <h1>Data Barang</h1>
        <p>Kelola dan catat seluruh inventaris barang milik laboratorium</p>
    </div>
    <a href="{{ route('admin.barang.create') }}" class="btn-primary"><i class="bi bi-plus"></i> Tambah Barang</a>
</div>

<div class="toolbar">
    <div class="search-wrap">
        <i class="bi bi-search"></i>
        <input type="text" class="search-input" id="searchInput" placeholder="Cari nama, kode, atau kategori barang…">
    </div>
    <span class="row-count" id="rowCount"></span>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Lab</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Kondisi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            @forelse($barang as $item)
            <tr>
                <td class="mono" style="color:var(--muted)">{{ $item->kode_barang }}</td>
                <td style="font-weight:500">{{ $item->nama_barang }}</td>
                <td>{{ $item->lab->nama_lab ?? '-' }}</td>
                <td style="color:var(--muted)">{{ $item->kategori ?? '-' }}</td>
                <td class="mono">{{ $item->stok }}</td>
                <td>
                    @php
                        $kClass = ['baik'=>'badge-baik','rusak'=>'badge-rusak','perbaikan'=>'badge-perbaikan'][$item->kondisi] ?? 'badge-default';
                    @endphp
                    <span class="badge {{ $kClass }}">{{ ucfirst($item->kondisi) }}</span>
                </td>
                <td>
                    @if($item->status === 'availabel')
                        <span class="badge badge-available">Tersedia</span>
                    @else
                        <span class="badge badge-unavailable">Tidak Tersedia</span>
                    @endif
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('admin.barang.edit', $item->id_barang) }}" class="btn-action btn-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.barang.destroy', $item->id_barang) }}" method="POST" style="margin:0">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-action btn-delete" title="Hapus" onclick="return confirm('Hapus barang {{ $item->nama_barang }}?')"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-box-seam"></i></div>
                        <p>Belum ada data barang. <a href="{{ route('admin.barang.create') }}" style="color:var(--blue)">Tambah sekarang</a></p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
const rows = Array.from(document.querySelectorAll('#tableBody tr')).filter(r => r.querySelectorAll('td').length > 1);
rows.forEach(r => r.setAttribute('data-search', r.textContent.toLowerCase()));
const rc = document.getElementById('rowCount');
rc.textContent = rows.length + ' barang';
document.getElementById('searchInput').addEventListener('input', function() {
    const q = this.value.toLowerCase(); let v = 0;
    rows.forEach(r => { const s = r.getAttribute('data-search').includes(q); r.style.display = s ? '' : 'none'; if(s) v++; });
    rc.textContent = v + ' barang';
});
</script>
@endpush
@endsection
