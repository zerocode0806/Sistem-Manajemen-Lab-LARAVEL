@extends('admin.layouts.app')
@section('title', 'Data Mahasiswa')
@section('content')

<div class="page-header">
    <div><h1>Data Mahasiswa</h1><p>Kelola data mahasiswa yang terdaftar di sistem</p></div>
    <a href="{{ route('admin.mahasiswa.create') }}" class="btn-primary"><i class="bi bi-plus"></i> Tambah Mahasiswa</a>
</div>

<div class="toolbar">
    <form method="GET" action="{{ route('admin.mahasiswa.index') }}" class="search-wrap" id="searchForm">
        <i class="bi bi-search"></i>
        <input type="text" name="q" id="searchInput" class="search-input"
            value="{{ $q }}" placeholder="Cari nama, NIM, telepon…" autocomplete="off">
    </form>
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

    
</div>

<div class="pagination-wrapper">
    <div class="pagination-info"> ... </div>
    {{ $mahasiswa->links('vendor.pagination.lab') }}
</div>

@push('scripts')
<script>
// Search otomatis 400ms setelah berhenti mengetik (Enter juga tetap bisa)
const form  = document.getElementById('searchForm');
const input = document.getElementById('searchInput');
let timer;

input.addEventListener('input', () => {
    clearTimeout(timer);
    timer = setTimeout(() => {
        sessionStorage.setItem('refocusSearch', '1');
        form.submit();
    }, 400);
});

// Kembalikan fokus ke kolom search setelah halaman reload karena mengetik
if (sessionStorage.getItem('refocusSearch')) {
    sessionStorage.removeItem('refocusSearch');
    input.focus();
    input.setSelectionRange(input.value.length, input.value.length);
}
</script>
@endpush
@endsection
