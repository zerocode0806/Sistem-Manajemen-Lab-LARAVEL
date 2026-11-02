@extends('admin.layouts.app')
@section('title', 'Data Mahasiswa')
@section('content')

<style>
    /* Pagination mahasiswa */
.pagination-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 16px 20px;
    border-top: 1px solid var(--border);
    flex-wrap: wrap;
}

.pagination-info {
    font-size: 12.5px;
    color: var(--muted);
}

.pagination-links nav {
    display: flex;
    align-items: center;
}

.pagination-links nav > div:first-child {
    display: none;
}

.pagination-links nav > div:last-child {
    display: flex;
    align-items: center;
    gap: 5px;
}

.pagination-links a,
.pagination-links span[aria-current="page"] span,
.pagination-links span[aria-disabled="true"] span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    height: 34px;
    padding: 0 10px;
    border: 1px solid var(--border);
    border-radius: 7px;
    font-size: 12.5px;
    text-decoration: none;
    color: var(--text);
    background: var(--card);
}

.pagination-links span[aria-current="page"] span {
    background: var(--accent);
    color: white;
    border-color: var(--accent);
}

.pagination-links a:hover {
    background: var(--bg);
}

.pagination-links span[aria-disabled="true"] span {
    opacity: .45;
}

@media (max-width: 600px) {
    .pagination-wrapper {
        flex-direction: column;
        align-items: flex-start;
    }

    .pagination-links {
        width: 100%;
        overflow-x: auto;
    }
}
</style>

<div class="page-header">
    <div><h1>Data Mahasiswa</h1><p>Kelola data mahasiswa yang terdaftar di sistem</p></div>
    <a href="{{ route('admin.mahasiswa.create') }}" class="btn-primary"><i class="bi bi-plus"></i> Tambah Mahasiswa</a>
</div>

<div class="toolbar">
    <div class="search-wrap">
        <i class="bi bi-search"></i>
        <input type="text" class="search-input" id="searchInput" placeholder="Cari nama atau NIM mahasiswa…">
    </div>
    <span class="row-count" id="rowCount"></span>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr><th style="width:65px">No.</th><th>NIM</th><th>Nama</th><th>No. Telepon</th><th>Alamat</th><th>Aksi</th></tr>
        </thead>
        <tbody id="tableBody">
            @forelse($mahasiswa as $index => $mhs)
            <tr>
                <td class="mono" style="color:var(--muted)">
                    {{ $mahasiswa->firstItem() + $index }}
                </td>
                <td class="mono">{{ $mhs->nim }}</td>
                <td style="font-weight:500">{{ $mhs->nama }}</td>
                <td class="mono">{{ $mhs->no_telepon }}</td>
                <td style="color:var(--muted);max-width:200px">{{ $mhs->alamat }}</td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('admin.mahasiswa.show', $mhs->nim) }}" class="btn-action btn-view" title="Detail"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('admin.mahasiswa.edit', $mhs->nim) }}" class="btn-action btn-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.mahasiswa.destroy', $mhs->nim) }}" method="POST" style="margin:0">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-action btn-delete" title="Hapus" onclick="return confirm('Hapus mahasiswa {{ $mhs->nama }}?')"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5"><div class="empty-state"><div class="empty-icon"><i class="bi bi-people"></i></div><p>Belum ada data mahasiswa.</p></div></td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- PAGINATION --}}
    @if($mahasiswa->hasPages())
        <div class="pagination-wrapper">
            <div class="pagination-info">
                Menampilkan
                <strong>{{ $mahasiswa->firstItem() }}–{{ $mahasiswa->lastItem() }}</strong>
                dari
                <strong>{{ $mahasiswa->total() }}</strong> mahasiswa
            </div>

            <div class="pagination-links">
                {{ $mahasiswa->links() }}
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
const rows = Array.from(document.querySelectorAll('#tableBody tr')).filter(r => r.querySelectorAll('td').length > 1);
rows.forEach(r => r.setAttribute('data-search', r.textContent.toLowerCase()));
const rc = document.getElementById('rowCount');
rc.textContent = rows.length + ' mahasiswa';
document.getElementById('searchInput').addEventListener('input', function() {
    const q = this.value.toLowerCase(); let v = 0;
    rows.forEach(r => { const s = r.getAttribute('data-search').includes(q); r.style.display = s ? '' : 'none'; if(s) v++; });
    rc.textContent = v + ' mahasiswa';
});
</script>
@endpush
@endsection
