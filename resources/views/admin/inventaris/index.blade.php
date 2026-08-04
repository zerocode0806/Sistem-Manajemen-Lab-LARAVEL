@extends('admin.layouts.app')
@section('title', 'Inventaris ' . $lab->nama_lab)
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.lab.index') }}">Laboratorium</a>
    <i class="bi bi-chevron-right"></i>
    <span class="current">Inventaris {{ $lab->nama_lab }}</span>
</div>

<div class="page-header">
    <div>
        <h1>Inventaris Lab</h1>
        <p>{{ $lab->nama_lab }} · {{ $lab->lokasi }}</p>
    </div>
    <div style="display:flex;gap:8px">
        <a href="{{ route('admin.inventaris.riwayat', $lab->id_lab) }}" class="btn-secondary"><i class="bi bi-clock-history"></i> Riwayat Bulanan</a>
        <a href="{{ route('admin.inventaris.export', $lab->id_lab) }}" class="btn-secondary"><i class="bi bi-file-earmark-excel"></i> Export</a>
    </div>
</div>

{{-- Summary Cards --}}
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-card-label">JUMLAH KURSI</div>
        <div class="stat-card-value" id="jumlahKursiText">{{ $lab->jumlah_kursi }}</div>
        <div class="stat-card-sub" style="margin-top:6px">
            <button id="editKursiBtn" style="font-size:11.5px;color:var(--blue);background:none;border:none;cursor:pointer;padding:0;font-family:inherit">
                <i class="bi bi-pencil"></i> Edit
            </button>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label">TOTAL MEJA</div>
        <div class="stat-card-value">{{ count($mejaRows) }}</div>
        <div class="stat-card-sub">Unit tersedia</div>
    </div>
    <div class="stat-card green">
        <div class="stat-card-label">AC NORMAL</div>
        <div class="stat-card-value">{{ $acNormal }}</div>
        <div class="stat-card-sub">dari {{ count($acRows) }} unit</div>
    </div>
    <div class="stat-card red">
        <div class="stat-card-label">AC RUSAK</div>
        <div class="stat-card-value">{{ $acRusak }}</div>
        <div class="stat-card-sub">Perlu perhatian</div>
    </div>
</div>

{{-- AC Section --}}
<div class="card" style="margin-bottom:20px">
    <div class="card-header" style="justify-content:space-between">
        <div style="display:flex;align-items:center;gap:10px">
            <div class="card-header-icon"><i class="bi bi-wind"></i></div>
            <h2>Inventaris AC</h2>
        </div>
        <form action="{{ route('admin.inventaris.tambahAc', $lab->id_lab) }}" method="POST" style="margin:0">
            @csrf
            <button type="submit" class="btn-secondary" style="font-size:12.5px"><i class="bi bi-plus"></i> Tambah AC</button>
        </form>
    </div>

    @if(count($acRows) > 0)
    <div style="display:flex;flex-wrap:wrap;gap:10px">
        @foreach($acRows as $ac)
        <div style="background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:14px 16px;min-width:160px">
            <div style="font-size:11.5px;font-weight:600;color:var(--muted);margin-bottom:8px;text-transform:uppercase;letter-spacing:.04em">AC Unit #{{ $ac->nomor_ac }}</div>
            <select class="cond-select" data-type="ac" data-id="{{ $ac->id_ac }}" style="width:100%;height:34px;padding:0 10px;border:1px solid var(--border);border-radius:7px;font-family:DM Sans,sans-serif;font-size:13px;background:var(--surface);cursor:pointer;outline:none">
                <option value="normal" @selected($ac->kondisi === 'normal')>Normal</option>
                <option value="rusak" @selected($ac->kondisi === 'rusak')>Rusak</option>
            </select>
            <div style="margin-top:8px;text-align:right">
                <a href="{{ route('admin.inventaris.hapusAc', [$lab->id_lab, $ac->id_ac]) }}" style="font-size:11.5px;color:var(--red);text-decoration:none" onclick="return confirm('Hapus AC Unit #{{ $ac->nomor_ac }}?')"><i class="bi bi-trash"></i> Hapus</a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state" style="padding:28px"><div class="empty-icon"><i class="bi bi-wind"></i></div><p>Belum ada AC. Klik "Tambah AC" untuk menambahkan.</p></div>
    @endif
</div>

{{-- Meja Section --}}
<div class="card">
    <div class="card-header">
        <div class="card-header-icon"><i class="bi bi-grid-3x3"></i></div>
        <h2>Inventaris Meja & Perangkat</h2>
    </div>
    <div style="overflow:auto;border-radius:8px;border:1px solid var(--border)">
        <table>
            <thead>
                <tr>
                    <th>Meja</th>
                    <th>CPU</th>
                    <th>Keyboard</th>
                    <th>Mouse</th>
                    <th>Monitor</th>
                    <th>Kursi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mejaRows as $meja)
                <tr>
                    <td class="mono" style="font-weight:600">#{{ $meja->nomor_meja }}</td>
                    @foreach(['cpu_kondisi'=>['normal','rusak','instal_ulang'], 'keyboard_kondisi'=>['normal','rusak','tidak_ada'], 'mouse_kondisi'=>['normal','rusak','tidak_ada'], 'monitor_kondisi'=>['normal','rusak','tidak_ada'], 'kursi_kondisi'=>['normal','rusak','tidak_ada']] as $field => $options)
                    <td>
                        <select class="cond-select" data-type="meja" data-id="{{ $meja->id_meja }}" data-field="{{ $field }}" style="height:30px;padding:0 8px;border:1px solid var(--border);border-radius:6px;font-family:DM Sans,sans-serif;font-size:12.5px;background:var(--surface);cursor:pointer;outline:none">
                            @foreach($options as $opt)
                                <option value="{{ $opt }}" @selected($meja->$field === $opt)>{{ ucwords(str_replace('_', ' ', $opt)) }}</option>
                            @endforeach
                        </select>
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
const LAB_ID = '{{ $lab->id_lab }}';

async function saveCondition(select) {
    const type  = select.getAttribute('data-type');
    const id    = select.getAttribute('data-id');
    const field = select.getAttribute('data-field') || 'kondisi';
    const value = select.value;

    try {
        const res = await fetch('{{ route("admin.inventaris.update") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ type, id, field, value })
        });
        const result = await res.json();
        if (!result.success) alert('Gagal menyimpan: ' + (result.message || 'error'));
    } catch (e) {
        alert('Gagal menyimpan perubahan. Periksa koneksi Anda.');
    }
}

document.querySelectorAll('.cond-select').forEach(sel => {
    sel.addEventListener('change', () => saveCondition(sel));
});

// Edit jumlah kursi
document.getElementById('editKursiBtn').addEventListener('click', async () => {
    const current = document.getElementById('jumlahKursiText').textContent.trim();
    const input = prompt('Masukkan jumlah kursi baru:', current);
    if (input === null) return;
    const value = parseInt(input, 10);
    if (isNaN(value) || value < 0) { alert('Masukkan angka yang valid.'); return; }
    try {
        const res = await fetch('{{ route("admin.inventaris.update") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ type: 'lab', id: LAB_ID, field: 'jumlah_kursi', value })
        });
        const result = await res.json();
        if (result.success) {
            document.getElementById('jumlahKursiText').textContent = value;
        } else {
            alert('Gagal menyimpan: ' + (result.message || 'error'));
        }
    } catch (e) { alert('Gagal menyimpan perubahan.'); }
});
</script>
@endpush
@endsection
