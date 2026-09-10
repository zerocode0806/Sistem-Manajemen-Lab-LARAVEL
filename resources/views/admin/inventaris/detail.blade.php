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
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.detail-card {
    min-width: 0;
    width: 100%;
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
    padding: 12px 14px;
    vertical-align: middle; 
}

/* ── STYLING MEJA & DRAWER (Diadaptasi dari Inventaris Index) ── */
.inventaris-meja-table-wrap {
    overflow-x: auto;
    border: 1px solid var(--border);
    border-radius: 8px;
    -webkit-overflow-scrolling: touch;
}

.inventaris-meja-table {
    width: 100%;
    min-width: 820px;
}

.inventaris-meja-table th,
.inventaris-meja-table td {
    padding: 12px 14px;
    white-space: nowrap;
    vertical-align: middle;
}

.meja-detail-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    min-height: 32px;
    padding: 0 10px;
    border: 1px solid #bfdbfe;
    border-radius: 7px;
    background: var(--blue-soft);
    color: var(--blue);
    font: 500 12px 'DM Sans', sans-serif;
    cursor: pointer;
    white-space: nowrap;
}

.meja-detail-btn:hover {
    background: #dbeafe;
}

.meja-mobile-list {
    display: none;
}

.meja-mobile-card {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 9px;
    padding: 14px;
}

.meja-mobile-card + .meja-mobile-card {
    margin-top: 10px;
}

.meja-mobile-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding-bottom: 12px;
    margin-bottom: 4px;
    border-bottom: 1px solid var(--border);
}

.meja-mobile-card-title {
    font: 600 14px 'DM Mono', monospace;
}

.meja-mobile-conditions {
    display: grid;
    gap: 0;
}

.meja-mobile-condition {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    min-height: 40px;
    border-bottom: 1px solid var(--border);
    font-size: 13px;
}

.meja-mobile-condition:last-child {
    border-bottom: none;
}

.meja-mobile-condition-label {
    color: var(--muted);
}

.meja-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 8px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}

.meja-status-badge::before {
    content: '';
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: currentColor;
}

.meja-status-badge--normal {
    background: var(--green-soft);
    color: var(--green);
}

.meja-status-badge--warning {
    background: var(--warn-soft);
    color: var(--warn);
}

.meja-status-badge--danger {
    background: var(--red-soft);
    color: var(--red);
}

/* ── DETAIL DRAWER MODAL ── */
.meja-detail-modal {
    position: fixed;
    inset: 0;
    z-index: 1200;
    display: flex;
    justify-content: flex-end;
    visibility: hidden;
    pointer-events: none;
}

.meja-detail-modal.is-open {
    visibility: visible;
    pointer-events: auto;
}

body.meja-modal-open {
    overflow: hidden;
}

.meja-detail-backdrop {
    position: absolute;
    inset: 0;
    border: 0;
    background: rgba(24, 24, 27, .34);
    opacity: 0;
    cursor: pointer;
    transition: opacity .2s ease;
}

.meja-detail-modal.is-open .meja-detail-backdrop {
    opacity: 1;
}

.meja-detail-drawer {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    width: min(460px, 100%);
    height: 100%;
    background: var(--surface);
    border-left: 1px solid var(--border);
    box-shadow: -12px 0 36px rgba(24, 24, 27, .12);
    transform: translateX(100%);
    transition: transform .22s ease;
}

.meja-detail-modal.is-open .meja-detail-drawer {
    transform: translateX(0);
}

.meja-detail-drawer-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    padding: 22px 24px 18px;
    border-bottom: 1px solid var(--border);
}

.meja-detail-eyebrow {
    margin-bottom: 4px;
    color: var(--muted);
    font: 500 10px 'DM Mono', monospace;
    letter-spacing: .08em;
    text-transform: uppercase;
}

.meja-detail-drawer-header h2 {
    font-size: 21px;
    font-weight: 600;
}

.meja-detail-close {
    display: grid;
    place-items: center;
    width: 32px;
    height: 32px;
    flex: 0 0 auto;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: var(--surface);
    color: var(--muted);
    cursor: pointer;
}

.meja-detail-close:hover {
    background: var(--bg);
    color: var(--text);
}

.meja-detail-drawer-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px 24px 24px;
}

.meja-detail-section + .meja-detail-section {
    margin-top: 22px;
}

.meja-detail-section-title {
    margin-bottom: 10px;
    color: var(--muted);
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .06em;
    text-transform: uppercase;
}

.meja-detail-condition-list {
    display: grid;
    gap: 7px;
}

.meja-detail-condition-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 10px 12px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: var(--bg);
    font-size: 13px;
}

.meja-detail-field {
    display: flex;
    flex-direction: column;
    gap: 7px;
}

.meja-detail-field + .meja-detail-field {
    margin-top: 14px;
}

.meja-detail-field label {
    color: var(--text);
    font-size: 12.5px;
    font-weight: 600;
}

.meja-detail-field textarea {
    width: 100%;
    min-height: 84px;
    padding: 10px 11px;
    border: 1px solid var(--border);
    border-radius: 8px;
    outline: none;
    resize: vertical;
    background: var(--surface);
    color: var(--text);
    font: 13px/1.55 'DM Sans', sans-serif;
}

.meja-detail-field textarea[readonly] {
    background: var(--bg);
    cursor: default;
}

.meja-detail-field textarea[readonly]:focus {
    border-color: var(--border);
}

.meja-detail-field textarea#mejaSpesifikasi {
    min-height: 160px;
    font-family: 'DM Mono', monospace;
    font-size: 12px;
}

.meja-detail-drawer-footer {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    padding: 16px 24px;
    border-top: 1px solid var(--border);
    background: var(--surface);
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

    .detail-header-actions {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .detail-stat-grid {
        gap: 10px !important;
        margin-bottom: 16px !important;
    }

    .detail-card {
        padding: 16px 14px;
    }

    .inventaris-meja-table-wrap {
        display: none;
    }

    .meja-mobile-list {
        display: block;
    }

    .meja-detail-drawer {
        width: 100%;
        height: min(92vh, 760px);
        align-self: flex-end;
        border-top: 1px solid var(--border);
        border-left: 0;
        border-radius: 14px 14px 0 0;
        transform: translateY(100%);
    }

    .meja-detail-modal.is-open .meja-detail-drawer {
        transform: translateY(0);
    }

    .meja-detail-drawer-header {
        padding: 18px 18px 15px;
    }

    .meja-detail-drawer-body {
        padding: 18px;
    }

    .meja-detail-drawer-footer {
        padding: 14px 18px;
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
</style>
@endpush

@php
    $namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $label = $namaBulan[$periode->bulan - 1] . ' ' . $periode->tahun;
    
    // Konfigurasi Field Meja
    $mejaConditionFields = [
        'cpu_kondisi' => 'CPU',
        'keyboard_kondisi' => 'Keyboard',
        'mouse_kondisi' => 'Mouse',
        'monitor_kondisi' => 'Monitor',
        'kursi_kondisi' => 'Kursi',
    ];
    
    // Mapping Label Status
    $conditionLabels = [
        'normal' => 'Normal',
        'rusak' => 'Rusak',
        'instal_ulang' => 'Instal Ulang',
        'tidak_ada' => 'Tidak Ada',
    ];
    
    // Siapkan Data Meja untuk Drawer (JSON)
    $mejaDetailData = [];
    foreach($periode->riwayatMeja as $index => $meja) {
        $mejaDetailData[(string) $index] = [
            'nomor' => $meja->nomor_meja,
            'cpu_kondisi' => $meja->cpu_kondisi,
            'keyboard_kondisi' => $meja->keyboard_kondisi,
            'mouse_kondisi' => $meja->mouse_kondisi,
            'monitor_kondisi' => $meja->monitor_kondisi,
            'kursi_kondisi' => $meja->kursi_kondisi,
            'keterangan' => $meja->keterangan,
            'spesifikasi_pc' => $meja->spesifikasi_pc,
        ];
    }
@endphp

{{-- Inject JSON Data --}}
<script type="application/json" id="mejaDetailData">{!! json_encode($mejaDetailData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>

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
    
    <!-- PANEL AC (Tetap Sama) -->
    <div class="card detail-card">
        <div class="card-header"><div class="card-header-icon"><i class="bi bi-wind"></i></div><h2>Kondisi AC</h2></div>
        @if($periode->riwayatAc->count() > 0)
        <div class="detail-table-wrap">
            <table class="detail-table">
                <thead><tr><th>Unit</th><th>Kondisi</th></tr></thead>
                <tbody>
                    @foreach($periode->riwayatAc as $ac)
                    <tr>
                        <td class="mono" style="width: 20%;">AC #{{ $ac->nomor_ac }}</td>
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

    <!-- PANEL MEJA (Menggunakan gaya Inventaris Index dengan Drawer) -->
    <div class="card detail-card">
        <div class="card-header"><div class="card-header-icon"><i class="bi bi-grid-3x3"></i></div><h2>Kondisi Meja & Detail Perangkat</h2></div>
        
        @if($periode->riwayatMeja->count() > 0)
        
        {{-- Desktop: Tabel Rapi --}}
        <div class="inventaris-meja-table-wrap">
            <table class="inventaris-meja-table">
                <thead>
                    <tr>
                        <th>Meja</th>
                        <th>CPU</th>
                        <th>Keyboard</th>
                        <th>Mouse</th>
                        <th>Monitor</th>
                        <th>Kursi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($periode->riwayatMeja as $index => $m)
                    <tr>
                        <td class="mono" style="font-weight:600">#{{ $m->nomor_meja }}</td>
                        
                        @foreach(array_keys($mejaConditionFields) as $f)
                        <td>
                            @php
                                $v = $m->$f;
                                $badgeC = $v === 'normal' ? 'meja-status-badge--normal' : ($v === 'rusak' ? 'meja-status-badge--danger' : 'meja-status-badge--warning');
                                $labelVal = $conditionLabels[$v] ?? $v;
                            @endphp
                            <span class="meja-status-badge {{ $badgeC }}">{{ $labelVal }}</span>
                        </td>
                        @endforeach
                        
                        <td>
                            <button type="button" class="meja-detail-btn meja-detail-trigger" data-meja-id="{{ $index }}">
                                <i class="bi bi-eye"></i> Detail
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile: Layout Card --}}
        <div class="meja-mobile-list">
            @foreach($periode->riwayatMeja as $index => $m)
            <article class="meja-mobile-card">
                <div class="meja-mobile-card-header">
                    <div class="meja-mobile-card-title">Meja #{{ $m->nomor_meja }}</div>
                    <button type="button" class="meja-detail-btn meja-detail-trigger" data-meja-id="{{ $index }}">
                        <i class="bi bi-eye"></i> Detail
                    </button>
                </div>
                <div class="meja-mobile-conditions">
                    @foreach($mejaConditionFields as $field => $label)
                        <div class="meja-mobile-condition">
                            <span class="meja-mobile-condition-label">{{ $label }}</span>
                            @php
                                $v = $m->$field;
                                $badgeC = $v === 'normal' ? 'meja-status-badge--normal' : ($v === 'rusak' ? 'meja-status-badge--danger' : 'meja-status-badge--warning');
                                $labelVal = $conditionLabels[$v] ?? $v;
                            @endphp
                            <span class="meja-status-badge {{ $badgeC }}">{{ $labelVal }}</span>
                        </div>
                    @endforeach
                </div>
            </article>
            @endforeach
        </div>

        @else
        <div class="empty-state" style="padding:20px"><p>Tidak ada data meja.</p></div>
        @endif
    </div>

</div>

{{-- Detail Drawer (Read-Only) --}}
<div class="meja-detail-modal" id="mejaDetailModal" aria-hidden="true">
    <button type="button" class="meja-detail-backdrop" data-meja-close aria-label="Tutup detail meja"></button>
    <aside class="meja-detail-drawer" role="dialog" aria-modal="true" aria-labelledby="mejaDetailTitle">
        <div class="meja-detail-drawer-header">
            <div>
                <div class="meja-detail-eyebrow">Informasi inventaris</div>
                <h2 id="mejaDetailTitle">Meja —</h2>
            </div>
            <button type="button" class="meja-detail-close" id="mejaDetailCloseButton" data-meja-close aria-label="Tutup">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="meja-detail-drawer-body">
            <section class="meja-detail-section">
                <h3 class="meja-detail-section-title">Kondisi Perangkat</h3>
                <div class="meja-detail-condition-list" id="mejaDetailConditions"></div>
            </section>

            <section class="meja-detail-section">
                <h3 class="meja-detail-section-title">Catatan Meja</h3>
                <div class="meja-detail-field">
                    <label for="mejaKeterangan">Keterangan</label>
                    <textarea id="mejaKeterangan" readonly placeholder="Tidak ada keterangan."></textarea>
                </div>
            </section>

            <section class="meja-detail-section">
                <h3 class="meja-detail-section-title">Informasi Komputer</h3>
                <div class="meja-detail-field">
                    <label for="mejaSpesifikasi">Spesifikasi PC</label>
                    <textarea id="mejaSpesifikasi" readonly placeholder="Tidak ada spesifikasi."></textarea>
                </div>
            </section>
        </div>

        <div class="meja-detail-drawer-footer">
            <button type="button" class="btn-secondary" data-meja-close>Tutup</button>
        </div>
    </aside>
</div>

@endsection

@push('scripts')
<script>
// Data detail dari backend
const MEJA_DETAIL_DATA = JSON.parse(
    document.getElementById('mejaDetailData')?.textContent || '{}'
);

const conditionMeta = [
    ['cpu_kondisi', 'CPU'],
    ['keyboard_kondisi', 'Keyboard'],
    ['mouse_kondisi', 'Mouse'],
    ['monitor_kondisi', 'Monitor'],
    ['kursi_kondisi', 'Kursi'],
];

const conditionLabels = {
    normal: 'Normal',
    rusak: 'Rusak',
    instal_ulang: 'Instal Ulang',
    tidak_ada: 'Tidak Ada',
};

let activeMejaId = null;

function conditionClass(value) {
    if (value === 'normal') return 'meja-status-badge--normal';
    if (value === 'rusak') return 'meja-status-badge--danger';
    return 'meja-status-badge--warning';
}

function conditionBadge(value) {
    const label = conditionLabels[value] || value || 'Belum diisi';
    return `<span class="meja-status-badge ${conditionClass(value)}">${label}</span>`;
}

function renderMejaConditions(data) {
    const container = document.getElementById('mejaDetailConditions');
    container.innerHTML = conditionMeta.map(([field, label]) => `
        <div class="meja-detail-condition-row">
            <span>${label}</span>
            ${conditionBadge(data[field])}
        </div>
    `).join('');
}

function openMejaDetail(id) {
    const data = MEJA_DETAIL_DATA[String(id)];
    if (!data) return;

    activeMejaId = String(id);
    document.getElementById('mejaDetailTitle').textContent = `Meja #${data.nomor}`;
    renderMejaConditions(data);
    document.getElementById('mejaKeterangan').value = data.keterangan || '';
    document.getElementById('mejaSpesifikasi').value = data.spesifikasi_pc || '';

    const modal = document.getElementById('mejaDetailModal');
    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('meja-modal-open');
    document.getElementById('mejaDetailCloseButton')?.focus();
}

function closeMejaDetail() {
    const modal = document.getElementById('mejaDetailModal');
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('meja-modal-open');
    activeMejaId = null;
}

// Event Listeners
document.querySelectorAll('.meja-detail-trigger').forEach(button => {
    button.addEventListener('click', () => {
        openMejaDetail(button.getAttribute('data-meja-id'));
    });
});

document.querySelectorAll('[data-meja-close]').forEach(button => {
    button.addEventListener('click', closeMejaDetail);
});

document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && activeMejaId !== null) {
        closeMejaDetail();
    }
});
</script>
@endpush