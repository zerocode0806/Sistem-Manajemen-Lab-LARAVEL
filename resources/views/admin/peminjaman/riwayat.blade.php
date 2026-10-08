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
            <tr><th>NIM</th><th>Nama</th><th>Jenis</th><th>Lab / Barang</th><th>Tanggal</th><th>Jam</th><th>Status</th><th>Pembayaran</th><th>Aksi</th></tr>
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
                    @php $bc = ['disetujui'=>'badge-disetujui','selesai'=>'badge-selesai'][$p->status] ?? 'badge-default'; @endphp
                    <span class="badge {{ $bc }}">{{ ucfirst($p->status) }}</span>
                </td>
                <td>
                    @if(in_array($p->kategori_peminjam ?? 'internal', ['eksternal_individu', 'eksternal_instansi'], true))
                        @php
                            $hariTagihan = max(1, (int) \Carbon\Carbon::parse($p->tanggal)->diffInDays(\Carbon\Carbon::parse($p->tanggal_selesai ?: $p->tanggal)) + 1);
                            $jumlahTagihan = (int) ($p->biaya_peminjaman ?? 0);
                            if ($jumlahTagihan <= 0) $jumlahTagihan = $hariTagihan * 75000;
                            $sudahLunas = $p->status_pembayaran === 'lunas';
                            $statusBayar = $sudahLunas
                                ? 'Lunas'
                                : ($p->status_pembayaran === 'belum_lunas' ? 'Belum lunas' : 'Belum diverifikasi');
                            $badgeBayar = $sudahLunas
                                ? 'badge-disetujui'
                                : ($p->status_pembayaran === 'belum_lunas' ? 'badge-menunggu' : 'badge-default');
                        @endphp
                        <span class="mono">Rp{{ number_format($jumlahTagihan, 0, ',', '.') }}</span>
                        <span class="badge {{ $badgeBayar }}" style="display:block;width:max-content;margin-top:4px">
                            {{ $statusBayar }}
                        </span>
                    @else
                        <span style="color:var(--muted)">—</span>
                    @endif
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('admin.peminjaman.show', $p->id_data) }}" class="btn-action btn-view" title="Detail"><i class="bi bi-eye"></i></a>
                        @if($p->status === 'disetujui')
                            @if(in_array($p->kategori_peminjam ?? 'internal', ['eksternal_individu', 'eksternal_instansi'], true) && $p->status_pembayaran !== 'lunas')
                                <a href="{{ route('admin.peminjaman.show', $p->id_data) }}" class="btn-action btn-checkout" title="Konfirmasi pembayaran sebelum checkout"><i class="bi bi-cash-coin"></i> Konfirmasi</a>
                            @else
                                <a href="{{ route('admin.peminjaman.show', $p->id_data) }}" class="btn-action btn-checkout" title="Isi pengembalian aktual"><i class="bi bi-check2-circle"></i> Selesai</a>
                            @endif
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9"><div class="empty-state"><div class="empty-icon"><i class="bi bi-check-circle"></i></div><p>Tidak ada peminjaman yang sedang berlangsung.</p></div></td></tr>
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
