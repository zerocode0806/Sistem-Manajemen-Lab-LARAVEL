@extends('admin.layouts.app')
@section('title', 'Arsip Peminjaman')
@section('content')

<div class="page-header">
    <div><h1>Arsip Peminjaman</h1><p>Riwayat peminjaman yang telah selesai atau ditolak</p></div>
</div>

<div class="toolbar">
    <div class="search-wrap">
        <i class="bi bi-search"></i>
        <input type="text" class="search-input" id="searchInput" placeholder="Cari NIM, nama instansi, atau lab…">
    </div>

    {{-- Filter Status --}}
    <div class="filter-tabs" id="filterTabs">
        <button class="filter-tab active" data-status="all">Semua</button>
        <button class="filter-tab" data-status="selesai">Selesai</button>
        <button class="filter-tab" data-status="ditolak">Ditolak</button>
    </div>

    {{-- Filter Tipe --}}
    <div class="filter-tabs" id="filterTipe">
        <button class="filter-tab active" data-tipe="all">Semua Tipe</button>
        <button class="filter-tab" data-tipe="internal">
            <i class="bi bi-person-fill"></i> Internal
        </button>
        <button class="filter-tab" data-tipe="eksternal">
            <i class="bi bi-building-fill"></i> Eksternal
        </button>
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
                <th>Biaya</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            @forelse($peminjaman as $p)
            <tr data-status="{{ $p->status }}" data-tipe="{{ $p->tipe_pemohon }}">

                {{-- Pemohon --}}
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

                {{-- Tanggal --}}
                <td class="mono" style="font-size:12.5px">
                    {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}
                    @if($p->tipe_pemohon === 'eksternal' && $p->tanggal_selesai)
                        <br><span style="color:var(--muted)">
                            s/d {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}
                        </span>
                        <br><span style="color:var(--muted)">{{ $p->durasi_hari }} hari</span>
                    @endif
                </td>

                {{-- Biaya: hanya eksternal --}}
                <td>
                    @if($p->tipe_pemohon === 'eksternal')
                        <span style="font-weight:500;font-size:13px">
                            Rp {{ number_format($p->total_biaya, 0, ',', '.') }}
                        </span>
                        <br>
                        @if($p->status_pembayaran === 'lunas')
                            <span style="font-size:11px;color:#065f46;
                                         display:inline-flex;align-items:center;gap:3px">
                                <i class="bi bi-check-circle-fill"></i> Lunas
                            </span>
                        @else
                            <span style="font-size:11px;color:#92400e;
                                         display:inline-flex;align-items:center;gap:3px">
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
                            'selesai' => 'badge-selesai',
                            'ditolak' => 'badge-ditolak',
                        ][$p->status] ?? 'badge-default';
                    @endphp
                    <span class="badge {{ $bc }}">{{ ucfirst($p->status) }}</span>
                </td>

                {{-- Aksi --}}
                <td>
                    <a href="{{ route('admin.peminjaman.show', $p->id_data) }}"
                       class="btn-action btn-view" title="Detail">
                        <i class="bi bi-eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-archive"></i></div>
                        <p>Arsip kosong.</p>
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

const rc          = document.getElementById('rowCount');
let activeStatus  = 'all';
let activeTipe    = 'all';

function applyFilters() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    let v = 0;

    rows.forEach(r => {
        const statusOk = activeStatus === 'all' || r.getAttribute('data-status') === activeStatus;
        const tipeOk   = activeTipe   === 'all' || r.getAttribute('data-tipe')   === activeTipe;
        const searchOk = !q || r.getAttribute('data-search').includes(q);
        const show     = statusOk && tipeOk && searchOk;

        r.style.display = show ? '' : 'none';
        if (show) v++;
    });

    rc.textContent = v + ' data';
}

// Init count
rc.textContent = rows.length + ' data';

// Filter status
document.querySelectorAll('#filterTabs .filter-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('#filterTabs .filter-tab')
                .forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        activeStatus = btn.getAttribute('data-status');
        applyFilters();
    });
});

// Filter tipe
document.querySelectorAll('#filterTipe .filter-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('#filterTipe .filter-tab')
                .forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        activeTipe = btn.getAttribute('data-tipe');
        applyFilters();
    });
});

// Search
document.getElementById('searchInput').addEventListener('input', applyFilters);
</script>
@endpush
@endsection