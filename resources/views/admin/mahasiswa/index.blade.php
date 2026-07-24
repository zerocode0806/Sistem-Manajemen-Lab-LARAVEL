@extends('admin.layouts.app')
@section('title', 'Data Mahasiswa')
@section('content')

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
            <tr><th>NIM</th><th>Nama</th><th>No. Telepon</th><th>Alamat</th><th>Aksi</th></tr>
        </thead>
        <tbody id="tableBody">
            @forelse($mahasiswa as $mhs)
            <tr>
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
