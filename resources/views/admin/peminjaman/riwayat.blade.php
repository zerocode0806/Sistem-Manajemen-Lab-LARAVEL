@extends('admin.layouts.app')
@section('title', 'Ongoing Peminjaman')
@section('content')

<div class="page-header">
    <div><h1>Ongoing Peminjaman</h1><p>Peminjaman yang telah disetujui dan sedang berlangsung</p></div>
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
            <tr><th>NIM</th><th>Nama</th><th>Jenis</th><th>Lab / Barang</th><th>Tanggal</th><th>Jam</th><th>Status</th><th>Aksi</th></tr>
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
                    @php $bc = ['disetujui'=>'badge-disetujui','selesai'=>'badge-selesai'][$p->status] ?? 'badge-default'; @endphp
                    <span class="badge {{ $bc }}">{{ ucfirst($p->status) }}</span>
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('admin.peminjaman.show', $p->id_data) }}" class="btn-action btn-view" title="Detail"><i class="bi bi-eye"></i></a>
                        @if($p->status === 'disetujui')
                        <form action="{{ route('admin.peminjaman.checkout', $p->id_data) }}" method="POST" style="margin:0">
                            @csrf
                            <button type="submit" class="btn-action btn-checkout" onclick="return confirm('Tandai selesai?')"><i class="bi bi-check2-circle"></i> Selesai</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8"><div class="empty-state"><div class="empty-icon"><i class="bi bi-check-circle"></i></div><p>Tidak ada peminjaman yang sedang berlangsung.</p></div></td></tr>
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
