@forelse($peminjaman as $p)
<tr>
    <td class="mono">{{ $p->nim ?? $p->nim_eksternal ?? '-' }}</td>
    <td>{{ $p->mahasiswa->nama ?? $p->nama_peminjam ?? '-' }}</td>
    <td>
        @if($p->jenis === 'barang')
            <span class="badge badge-barang"><i class="bi bi-box-seam-fill"></i> Barang</span>
        @else
            <span class="badge badge-lab"><i class="bi bi-building"></i> Lab</span>
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
            <a href="{{ route('admin.peminjaman.show', $p->id_data) }}" class="btn-action btn-view"><i class="bi bi-eye"></i></a>
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
<tr>
    <td colspan="7">
        <div class="empty-state">
            <div class="empty-icon"><i class="bi bi-inbox"></i></div>
            <p>Tidak ada permintaan yang menunggu persetujuan.</p>
        </div>
    </td>
</tr>
@endforelse
