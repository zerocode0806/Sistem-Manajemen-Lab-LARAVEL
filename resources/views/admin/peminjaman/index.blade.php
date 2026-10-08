@extends('admin.layouts.app')
@section('title', 'Permintaan Peminjaman')
@section('content')

<div class="page-header">
    <div>
        <h1>Permintaan Peminjaman</h1>
        <p>Daftar permintaan yang menunggu persetujuan</p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:flex-end">
        <a href="{{ route('admin.peminjaman.externalIndividual.create') }}" class="btn-secondary"><i class="bi bi-person-plus"></i> Eksternal Individu</a>
        <a href="{{ route('admin.peminjaman.externalInstitution.create') }}" class="btn-secondary"><i class="bi bi-building-add"></i> Eksternal Instansi</a>
        <a href="{{ route('admin.peminjaman.create') }}" class="btn-primary"><i class="bi bi-plus"></i> Internal</a>
    </div>
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
                <td class="mono">{{ $p->nim ?? $p->nim_eksternal ?? '-' }}</td>
                <td style="font-weight:500">{{ $p->mahasiswa->nama ?? $p->nama_peminjam ?? '-' }}</td>
                <td>
                    @if($p->jenis === 'barang')
                        <span class="badge badge-barang">Barang</span>
                    @else
                        <span class="badge badge-lab">Lab</span>
                    @endif
                    @if(($p->kategori_peminjam ?? 'internal') === 'eksternal_individu')
                        <span class="badge badge-default">Eksternal Individu</span>
                    @elseif(($p->kategori_peminjam ?? 'internal') === 'eksternal_instansi')
                        <span class="badge badge-default">Eksternal Instansi</span>
                    @endif
                </td>
                <td>{{ $p->jenis === 'barang' ? $p->nama_barang : $p->nama_lab }}@if($p->seluruh_lab)<small style="display:block;color:var(--muted)">Seluruh lab</small>@endif</td>
                <td class="mono">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}@if($p->tanggal_selesai && $p->tanggal_selesai !== $p->tanggal) – {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}@endif</td>
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
