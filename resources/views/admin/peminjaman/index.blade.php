@extends('admin.layouts.app')
@section('title', 'Permintaan Peminjaman')
@section('content')

<div class="page-header">
    <div>
        <h1>Permintaan Peminjaman</h1>
        <p>Daftar permintaan yang menunggu persetujuan</p>
    </div>
    <a href="{{ route('admin.peminjaman.create') }}" class="btn-primary"><i class="bi bi-plus"></i> Buat Peminjaman</a>
</div>

<div class="toolbar">
    <div class="search-wrap">
        <i class="bi bi-search"></i>
        <input type="text" class="search-input" id="searchInput" placeholder="Cari NIM, nama, atau lab…">
    </div>
    <span class="row-count" id="rowCount"></span>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr><th>NIM</th><th>Nama</th><th>Jenis</th><th>Lab / Barang</th><th>Tanggal</th><th>Jam</th><th>Aksi</th></tr>
        </thead>
        <tbody id="tableBody">
            @forelse($peminjaman as $p)
            <tr>
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
                <td><span class="time-range">{{ substr($p->jam_mulai,0,5) }} – {{ substr($p->jam_selesai,0,5) }}</span></td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('admin.peminjaman.show', $p->id_data) }}" class="btn-action btn-view" title="Detail"><i class="bi bi-eye"></i></a>
                        <form action="{{ route('admin.peminjaman.approve', $p->id_data) }}" method="POST" style="margin:0">
                            @csrf
                            <button type="submit" class="btn-action btn-approve"><i class="bi bi-check-lg"></i> Setujui</button>
                        </form>
                        <form action="{{ route('admin.peminjaman.reject', $p->id_data) }}" method="POST" style="margin:0">
                            @csrf
                            <button type="submit" class="btn-action btn-reject"><i class="bi bi-x-lg"></i> Tolak</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7"><div class="empty-state"><div class="empty-icon"><i class="bi bi-inbox"></i></div><p>Tidak ada permintaan yang menunggu persetujuan.</p></div></td></tr>
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
