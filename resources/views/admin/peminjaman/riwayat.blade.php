@extends('admin.layouts.app')
@section('title', 'Ongoing Peminjaman')
@section('content')

<div class="page-header">
    <div><h1>Ongoing Peminjaman</h1><p>Peminjaman yang telah disetujui dan sedang berlangsung</p></div>
</div>

<div class="toolbar">
    <div class="search-wrap">
        <i class="bi bi-search"></i>
        <input type="text" class="search-input" id="searchInput" placeholder="Cari NIM, nama instansi, atau lab…">
    </div>
    <span class="row-count" id="rowCount"></span>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Pemohon</th>
                <th>Tipe</th>
                <th>Jenis</th>
                <th>Lab / Barang</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Biaya</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            @forelse($peminjaman as $p)
            <tr>
                {{-- Pemohon: NIM+nama untuk internal, instansi+PIC untuk eksternal --}}
                <td>
                    @if($p->tipe_pemohon === 'eksternal')
                        <span style="font-weight:500">{{ $p->nama_instansi }}</span>
                        <br><span style="font-size:12px;color:var(--muted)">PIC: {{ $p->pic_instansi }}</span>
                    @else
                        <span style="font-weight:500">{{ $p->mahasiswa->nama ?? '-' }}</span>
                        <br><span class="mono" style="font-size:12px;color:var(--muted)">{{ $p->nim }}</span>
                    @endif
                </td>

                {{-- Tipe --}}
                <td>
                    @if($p->tipe_pemohon === 'eksternal')
                        <span class="badge" style="background:#ede9fe;color:#5b21b6">
                            <i class="bi bi-building-fill"></i> Eksternal
                        </span>
                    @else
                        <span class="badge" style="background:var(--surface-2);color:var(--muted)">
                            <i class="bi bi-person-fill"></i> Internal
                        </span>
                    @endif
                </td>

                {{-- Jenis --}}
                <td>
                    @if($p->jenis === 'barang')
                        <span class="badge badge-barang">Barang</span>
                    @else
                        <span class="badge badge-lab">Lab</span>
                    @endif
                </td>

                {{-- Lab / Barang --}}
                <td>{{ $p->jenis === 'barang' ? $p->nama_barang : $p->nama_lab }}</td>

                {{-- Tanggal: range untuk eksternal, single untuk internal --}}
                <td class="mono" style="font-size:12.5px">
                    {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}
                    @if($p->tipe_pemohon === 'eksternal' && $p->tanggal_selesai)
                        <br><span style="color:var(--muted)">s/d {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}</span>
                        <br><span style="color:var(--muted)">{{ $p->durasi_hari }} hari</span>
                    @endif
                </td>

                {{-- Jam --}}
                <td>
                    <span class="time-range">
                        {{ substr($p->jam_mulai, 0, 5) }} – {{ substr($p->jam_selesai, 0, 5) }}
                    </span>
                </td>

                {{-- Biaya: hanya eksternal --}}
                <td>
                    @if($p->tipe_pemohon === 'eksternal')
                        <span style="font-weight:500;font-size:13px">
                            Rp {{ number_format($p->total_biaya, 0, ',', '.') }}
                        </span>
                        <br>
                        @if($p->status_pembayaran === 'lunas')
                            <span style="font-size:11px;color:#065f46;display:inline-flex;align-items:center;gap:3px">
                                <i class="bi bi-check-circle-fill"></i> Lunas
                            </span>
                        @else
                            <span style="font-size:11px;color:#92400e;display:inline-flex;align-items:center;gap:3px">
                                <i class="bi bi-clock-fill"></i> Belum Bayar
                            </span>
                        @endif
                    @else
                        <span style="color:var(--muted);font-size:12px">—</span>
                    @endif
                </td>

                {{-- Status --}}
                <td>
                    @php
                        $bc = [
                            'disetujui' => 'badge-disetujui',
                            'selesai'   => 'badge-selesai',
                        ][$p->status] ?? 'badge-default';
                    @endphp
                    <span class="badge {{ $bc }}">{{ ucfirst($p->status) }}</span>
                </td>

                {{-- Aksi --}}
                <td>
                    <div class="action-buttons">

                        {{-- Tombol detail --}}
                        <a href="{{ route('admin.peminjaman.show', $p->id_data) }}"
                           class="btn-action btn-view" title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>

                        @if($p->status === 'disetujui')

                            @if($p->tipe_pemohon === 'eksternal')

                                {{-- Konfirmasi lunas dulu jika belum bayar --}}
                                @if($p->status_pembayaran === 'belum_bayar')
                                    <form action="{{ route('admin.peminjaman.updatePembayaran', $p->id_data) }}"
                                          method="POST" style="margin:0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="btn-action btn-checkout"
                                                title="Konfirmasi Pembayaran"
                                                onclick="return confirm('Konfirmasi pembayaran sudah lunas?')"
                                                style="display:inline-flex;align-items:center;gap:4px;font-size:12px;padding:4px 10px">
                                            <i class="bi bi-cash-coin"></i> Lunas
                                        </button>
                                    </form>

                                    {{-- Checkout dikunci --}}
                                    <button class="btn-action btn-checkout"
                                            disabled
                                            title="Selesaikan pembayaran terlebih dahulu"
                                            style="opacity:0.4;cursor:not-allowed">
                                        <i class="bi bi-lock"></i>
                                    </button>

                                @else
                                    {{-- Sudah lunas: bisa checkout --}}
                                    <form action="{{ route('admin.peminjaman.checkout', $p->id_data) }}"
                                          method="POST" style="margin:0">
                                        @csrf
                                        <button type="submit"
                                                class="btn-action btn-checkout"
                                                title="Tandai Selesai"
                                                onclick="return confirm('Tandai selesai & kembalikan stok?')"
                                                style="display:inline-flex;align-items:center;gap:4px;font-size:12px;padding:4px 10px">
                                            <i class="bi bi-check2-circle"></i> Selesai
                                        </button>
                                    </form>
                                @endif

                            @else
                                {{-- Internal: langsung bisa checkout --}}
                                <form action="{{ route('admin.peminjaman.checkout', $p->id_data) }}"
                                      method="POST" style="margin:0">
                                    @csrf
                                    <button type="submit"
                                            class="btn-action btn-checkout"
                                            title="Tandai Selesai"
                                            onclick="return confirm('Tandai selesai?')"
                                            style="display:inline-flex;align-items:center;gap:4px;font-size:12px;padding:4px 10px">
                                        <i class="bi bi-check2-circle"></i> Selesai
                                    </button>
                                </form>
                            @endif

                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-check-circle"></i></div>
                        <p>Tidak ada peminjaman yang sedang berlangsung.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
const rows = Array.from(document.querySelectorAll('#tableBody tr'))
    .filter(r => r.querySelectorAll('td').length > 1);

rows.forEach(r => r.setAttribute('data-search', r.textContent.toLowerCase()));

const rc = document.getElementById('rowCount');
rc.textContent = rows.length + ' data';

document.getElementById('searchInput').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    let v = 0;
    rows.forEach(r => {
        const show = r.getAttribute('data-search').includes(q);
        r.style.display = show ? '' : 'none';
        if (show) v++;
    });
    rc.textContent = v + ' data';
});
</script>
@endpush
@endsection