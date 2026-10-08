@extends('admin.layouts.app')
@section('title', 'Detail Riwayat Inventaris')
@section('content')

@push('styles')
<style>
/* ── DETAIL INVENTARIS ───────────────────────────────────────────── */
.detail-page-header {
    align-items: flex-start;
}

.detail-header-actions {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
}

.detail-stat-grid {
    grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
    margin-bottom: 24px !important;
}

.detail-stat-card {
    min-width: 0;
}

.detail-panels {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 2fr);
    gap: 20px;
    align-items: start;
}

.detail-card {
    min-width: 0;
}

.detail-table-wrap {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 6px;
    border: 1px solid var(--border);
}

.detail-table {
    width: 100%;
    min-width: 300px;
}

.detail-table th,
.detail-table td {
    white-space: nowrap;
}

.meja-history-details-heading {
    margin: 20px 0 10px;
    font-size: 13px;
    color: var(--text);
}

.meja-history-detail-list {
    display: grid;
    gap: 10px;
}

.meja-history-detail-card {
    min-width: 0;
    padding: 13px 14px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: var(--surface-alt, #fafafa);
}

.meja-history-detail-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 10px;
    font-size: 12px;
    font-weight: 700;
}

.meja-history-detail-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1.7fr);
    gap: 10px;
}

.meja-history-detail-field {
    min-width: 0;
}

.meja-history-detail-label {
    display: block;
    margin-bottom: 4px;
    color: var(--muted);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
}

.meja-history-detail-value {
    min-height: 42px;
    padding: 9px 10px;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: var(--surface);
    color: var(--text);
    font-size: 12px;
    line-height: 1.5;
    overflow-wrap: anywhere;
    white-space: pre-wrap;
}

@media (max-width: 900px) {
    .detail-panels {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }
}

@media (max-width: 768px) {
    .detail-page-header {
        gap: 14px;
        margin-bottom: 20px;
    }

    .detail-page-header h1 {
        font-size: 21px;
        line-height: 1.25;
    }

    .detail-page-header p {
        line-height: 1.45;
    }

    .detail-header-actions {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .detail-header-actions .btn-secondary {
        justify-content: center;
        width: 100%;
        min-width: 0;
    }

    .detail-stat-grid {
        gap: 10px !important;
        margin-bottom: 16px !important;
    }

    .detail-stat-card {
        padding: 15px 12px;
    }

    .detail-stat-card .stat-card-label {
        min-height: 28px;
        margin-bottom: 7px;
        font-size: 10px;
        line-height: 1.35;
    }

    .detail-stat-card .stat-card-value {
        font-size: 26px;
    }

    .detail-panels {
        grid-template-columns: minmax(0, 1fr);
        gap: 14px;
    }

    .detail-card {
        padding: 16px 14px;
    }

    .detail-card .card-header {
        margin-bottom: 12px;
        padding-bottom: 12px;
    }

    .detail-card .card-header h2 {
        font-size: 14px;
    }

    .detail-table th,
    .detail-table td {
        padding: 10px 12px;
        font-size: 12px;
    }

    .meja-history-detail-grid {
        grid-template-columns: minmax(0, 1fr);
    }

    .breadcrumb {
        white-space: nowrap;
        overflow-x: auto;
        scrollbar-width: none;
        padding-bottom: 2px;
    }

    .breadcrumb::-webkit-scrollbar {
        display: none;
    }
}

@media (max-width: 480px) {
    .detail-stat-card {
        padding: 14px 10px;
    }

    .detail-stat-card .stat-card-label {
        letter-spacing: 0;
    }

    .detail-stat-card .stat-card-value {
        font-size: 24px;
    }
}
</style>
@endpush

@php
    $namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $label = $namaBulan[$periode->bulan - 1] . ' ' . $periode->tahun;
    $mejaConditionFields = [
        'cpu_kondisi' => 'CPU',
        'keyboard_kondisi' => 'Keyboard',
        'mouse_kondisi' => 'Mouse',
        'monitor_kondisi' => 'Monitor',
        'kursi_kondisi' => 'Kursi',
    ];
@endphp

<div class="breadcrumb">
    <a href="{{ route('admin.lab.index') }}">Laboratorium</a>
    <i class="bi bi-chevron-right"></i>
    <a href="{{ route('admin.inventaris.riwayat', $periode->id_lab) }}">Riwayat Inventaris</a>
    <i class="bi bi-chevron-right"></i><span class="current">{{ $label }}</span>
</div>

<div class="page-header detail-page-header">
    <div>
        <h1>Detail Inventaris – {{ $label }}</h1>
        <p>{{ $periode->lab->nama_lab }} · Dicatat oleh: {{ $periode->dicatat_oleh ?? '-' }}</p>
    </div>
    <div class="detail-header-actions">
        <a href="{{ route('admin.inventaris.exportPeriode', $periode->id_periode) }}" class="btn-secondary"><i class="bi bi-file-earmark-excel"></i> Export Excel</a>
        <a href="{{ route('admin.inventaris.riwayat', $periode->id_lab) }}" class="btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="stat-grid detail-stat-grid">
    <div class="stat-card detail-stat-card"><div class="stat-card-label">JUMLAH KURSI</div><div class="stat-card-value">{{ $periode->jumlah_kursi }}</div></div>
    <div class="stat-card detail-stat-card"><div class="stat-card-label">JUMLAH MEJA</div><div class="stat-card-value">{{ $periode->jumlah_meja }}</div></div>
    <div class="stat-card detail-stat-card"><div class="stat-card-label">JUMLAH AC</div><div class="stat-card-value">{{ $periode->jumlah_ac }}</div></div>
</div>

<div class="detail-panels">
    <div class="card detail-card">
        <div class="card-header"><div class="card-header-icon"><i class="bi bi-wind"></i></div><h2>Kondisi AC</h2></div>
        @if($periode->riwayatAc->count() > 0)
        <div class="detail-table-wrap">
            <table class="detail-table">
                <thead><tr><th>Unit</th><th>Kondisi</th></tr></thead>
                <tbody>
                    @foreach($periode->riwayatAc as $ac)
                    <tr>
                        <td class="mono">AC #{{ $ac->nomor_ac }}</td>
                        <td>
                            @php $c = $ac->kondisi === 'normal' ? 'badge-available' : 'badge-rusak'; @endphp
                            <span class="badge {{ $c }}">{{ ucfirst($ac->kondisi) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state" style="padding:20px"><p>Tidak ada data AC.</p></div>
        @endif
    </div>

    <div class="card detail-card">
        <div class="card-header"><div class="card-header-icon"><i class="bi bi-grid-3x3"></i></div><h2>Kondisi Meja & Perangkat</h2></div>
        @if($periode->riwayatMeja->count() > 0)
        <div class="detail-table-wrap">
            <table class="detail-table">
                <thead><tr><th>Meja</th><th>CPU</th><th>Keyboard</th><th>Mouse</th><th>Monitor</th><th>Kursi</th></tr></thead>
                <tbody>
                    @foreach($periode->riwayatMeja as $m)
                    <tr>
                        <td class="mono" style="font-weight:600">#{{ $m->nomor_meja }}</td>
                        @foreach(array_keys($mejaConditionFields) as $f)
                        <td>
                            @php
                                $v = $m->$f;
                                $badgeC = $v === 'normal' ? 'badge-available' : ($v === 'rusak' ? 'badge-rusak' : 'badge-perbaikan');
                                $label2 = ['normal'=>'Normal','rusak'=>'Rusak','instal_ulang'=>'Instal Ulang','tidak_ada'=>'Tidak Ada'][$v] ?? $v;
                            @endphp
                            <span class="badge {{ $badgeC }}" style="font-size:10.5px">{{ $label2 }}</span>
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <h3 class="meja-history-details-heading">Keterangan & Spesifikasi PC</h3>
        <div class="meja-history-detail-list">
            @foreach($periode->riwayatMeja as $m)
            <article class="meja-history-detail-card">
                <div class="meja-history-detail-card-header">
                    <span>Meja #{{ $m->nomor_meja }}</span>
                    <span class="mono" style="color:var(--muted);font-size:11px">Detail perangkat</span>
                </div>
                <div class="meja-history-detail-grid">
                    <div class="meja-history-detail-field">
                        <span class="meja-history-detail-label">Keterangan</span>
                        <div class="meja-history-detail-value">{{ $m->keterangan ?: 'Tidak ada keterangan.' }}</div>
                    </div>
                    <div class="meja-history-detail-field">
                        <span class="meja-history-detail-label">Spesifikasi PC</span>
                        <div class="meja-history-detail-value">{{ $m->spesifikasi_pc ?: 'Belum ada spesifikasi PC.' }}</div>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        @else
        <div class="empty-state" style="padding:20px"><p>Tidak ada data meja.</p></div>
        @endif
    </div>
</div>
@endsection
