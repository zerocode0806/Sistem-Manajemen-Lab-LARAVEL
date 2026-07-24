@extends('admin.layouts.app')
@section('title', 'Data Admin')
@section('content')

<div class="page-header">
    <div><h1>Data Admin</h1><p>Kelola akun administrator sistem</p></div>
    <a href="{{ route('admin.akun.create') }}" class="btn-primary"><i class="bi bi-plus"></i> Tambah Admin</a>
</div>

<div class="toolbar">
    <div class="search-wrap">
        <i class="bi bi-search"></i>
        <input type="text" class="search-input" id="searchInput" placeholder="Cari nama, email, atau username…">
    </div>
    <span class="row-count" id="rowCount"></span>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr><th>#</th><th>Nama</th><th>Email</th><th>Username</th><th>Aksi</th></tr>
        </thead>
        <tbody id="tableBody">
            @forelse($admins as $admin)
            <tr>
                <td class="mono" style="color:var(--muted)">{{ $admin->id_admin }}</td>
                <td style="font-weight:500">{{ $admin->nama }}</td>
                <td>{{ $admin->email }}</td>
                <td class="mono">{{ $admin->username }}</td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('admin.akun.edit', $admin->id_admin) }}" class="btn-action btn-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                        @if($admin->id_admin !== Auth::guard('admin')->user()->id_admin)
                        <form action="{{ route('admin.akun.destroy', $admin->id_admin) }}" method="POST" style="margin:0">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-action btn-delete" title="Hapus" onclick="return confirm('Hapus admin {{ $admin->nama }}?')"><i class="bi bi-trash"></i></button>
                        </form>
                        @else
                        <span style="font-size:11.5px;color:var(--muted);padding:0 8px">(Akun Anda)</span>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5"><div class="empty-state"><div class="empty-icon"><i class="bi bi-person-badge"></i></div><p>Tidak ada data admin.</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
const rows = Array.from(document.querySelectorAll('#tableBody tr')).filter(r => r.querySelectorAll('td').length > 1);
rows.forEach(r => r.setAttribute('data-search', r.textContent.toLowerCase()));
const rc = document.getElementById('rowCount');
rc.textContent = rows.length + ' admin';
document.getElementById('searchInput').addEventListener('input', function() {
    const q = this.value.toLowerCase(); let v = 0;
    rows.forEach(r => { const s = r.getAttribute('data-search').includes(q); r.style.display = s ? '' : 'none'; if(s) v++; });
    rc.textContent = v + ' admin';
});
</script>
@endpush
@endsection
