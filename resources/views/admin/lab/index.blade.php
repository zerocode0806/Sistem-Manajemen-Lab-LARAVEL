@extends('admin.layouts.app')
@section('title', 'Laboratorium')
@section('content')

<div class="page-header">
    <div>
        <h1>Data Laboratorium</h1>
        <p>Kelola data lab yang tersedia di sistem</p>
    </div>
    <a href="{{ route('admin.lab.create') }}" class="btn-primary"><i class="bi bi-plus"></i> Tambah Lab</a>
</div>

<div class="toolbar">
    <div class="search-wrap">
        <i class="bi bi-search"></i>
        <input type="text" class="search-input" id="searchInput" placeholder="Cari nama atau lokasi lab…">
    </div>
    <span class="row-count" id="rowCount"></span>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Lab</th>
                <th>Lokasi</th>
                <th>Stok</th>
                <th>Kursi</th>
                <th>Meja</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            @forelse($labs as $lab)
            <tr>
                <td class="mono" style="color:var(--muted)">{{ $lab->id_lab }}</td>
                <td style="font-weight:500">{{ $lab->nama_lab }}</td>
                <td style="color:var(--muted)">{{ $lab->lokasi ?? '-' }}</td>
                <td class="mono">{{ $lab->stok }}</td>
                <td class="mono">{{ $lab->jumlah_kursi }}</td>
                <td class="mono">{{ $lab->jumlah_meja }}</td>
                <td>
                    @if($lab->status === 'availabel')
                        <span class="badge badge-available">Tersedia</span>
                    @else
                        <span class="badge badge-unavailable">Tidak Tersedia</span>
                    @endif
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('admin.inventaris.index', $lab->id_lab) }}" class="btn-action btn-view" title="Inventaris Lab"><i class="bi bi-clipboard-data"></i></a>
                        <a href="{{ route('admin.lab.edit', $lab->id_lab) }}" class="btn-action btn-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.lab.destroy', $lab->id_lab) }}" method="POST" style="margin:0">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-action btn-delete" title="Hapus" onclick="return confirm('Hapus lab {{ $lab->nama_lab }}?')"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-building"></i></div>
                        <p>Belum ada data lab. <a href="{{ route('admin.lab.create') }}" style="color:var(--blue)">Tambah lab pertama</a></p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
const tableBody = document.getElementById('tableBody');
const rowCount  = document.getElementById('rowCount');
const rows = Array.from(tableBody.querySelectorAll('tr')).filter(r => r.querySelectorAll('td').length > 1);
rows.forEach(r => r.setAttribute('data-search', r.textContent.toLowerCase()));
rowCount.textContent = rows.length + ' lab';
document.getElementById('searchInput').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    let v = 0;
    rows.forEach(r => { const show = r.getAttribute('data-search').includes(q); r.style.display = show ? '' : 'none'; if(show) v++; });
    rowCount.textContent = v + ' lab';
});
</script>
@endpush
@endsection
