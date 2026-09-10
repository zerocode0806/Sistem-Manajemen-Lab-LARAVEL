@extends('admin.layouts.app')
@section('title', 'Inventaris ' . $lab->nama_lab)
@section('content')

@push('styles')
<style>
/* ── INVENTARIS MEJA ─────────────────────────────────────────────── */
.inventaris-page .page-header {
    align-items: flex-start;
}

.inventaris-header-actions {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
}

.inventaris-stat-grid {
    grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
    margin-bottom: 24px !important;
}

.inventaris-meja-table-wrap {
    overflow-x: auto;
    border: 1px solid var(--border);
    border-radius: 8px;
    -webkit-overflow-scrolling: touch;
}

.inventaris-meja-table {
    min-width: 820px;
}

.inventaris-meja-table th,
.inventaris-meja-table td {
    white-space: nowrap;
}

.inventaris-meja-table .cond-select {
    min-width: 104px;
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

.meja-mobile-condition .cond-select {
    width: min(148px, 52%);
    min-width: 0;
    height: 32px;
    padding: 0 8px;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: var(--surface);
    color: var(--text);
    font: 500 12px 'DM Sans', sans-serif;
    cursor: pointer;
    outline: none;
}

.meja-mobile-condition .cond-select:focus {
    border-color: var(--blue);
    box-shadow: 0 0 0 2px rgba(37, 99, 235, .12);
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

/* ── DETAIL DRAWER ──────────────────────────────────────────────── */
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

.meja-detail-field textarea:focus {
    border-color: var(--accent);
}

.meja-detail-field textarea#mejaSpesifikasi {
    min-height: 160px;
    font-family: 'DM Mono', monospace;
    font-size: 12px;
}

.meja-detail-help {
    color: var(--muted);
    font-size: 11.5px;
}

.meja-detail-drawer-footer {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    padding: 16px 24px;
    border-top: 1px solid var(--border);
    background: var(--surface);
}

.meja-detail-save-status {
    display: none;
    align-items: center;
    margin-right: auto;
    color: var(--green);
    font-size: 12px;
}

.meja-detail-save-status.is-visible {
    display: inline-flex;
}

@media (max-width: 900px) {
    .inventaris-stat-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }
}

@media (max-width: 768px) {
    .inventaris-header-actions {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .inventaris-header-actions .btn-secondary {
        justify-content: center;
        min-width: 0;
    }

    .inventaris-stat-grid {
        gap: 10px !important;
        margin-bottom: 16px !important;
    }

    .inventaris-page .card {
        padding: 16px 14px;
    }

    .inventaris-page .card-header {
        gap: 10px;
    }

    .inventaris-page .card-header h2 {
        font-size: 14px;
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
}

@media (max-width: 480px) {
    .inventaris-stat-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }
}
</style>
@endpush

<div class="inventaris-page">
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
    <div class="inventaris-header-actions">
        <a href="{{ route('admin.inventaris.riwayat', $lab->id_lab) }}" class="btn-secondary"><i class="bi bi-clock-history"></i> Riwayat Bulanan</a>
        <a href="{{ route('admin.inventaris.export', $lab->id_lab) }}" class="btn-secondary"><i class="bi bi-file-earmark-excel"></i> Export</a>
    </div>
</div>

{{-- Summary Cards --}}
<div class="stat-grid inventaris-stat-grid">
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

    @php
        $mejaConditionFields = [
            'cpu_kondisi'      => ['label' => 'CPU',      'options' => ['normal', 'rusak', 'instal_ulang']],
            'keyboard_kondisi' => ['label' => 'Keyboard', 'options' => ['normal', 'rusak', 'tidak_ada']],
            'mouse_kondisi'    => ['label' => 'Mouse',    'options' => ['normal', 'rusak', 'tidak_ada']],
            'monitor_kondisi'  => ['label' => 'Monitor',  'options' => ['normal', 'rusak', 'tidak_ada']],
            'kursi_kondisi'    => ['label' => 'Kursi',    'options' => ['normal', 'rusak', 'tidak_ada']],
        ];
        $conditionLabels = [
            'normal' => 'Normal',
            'rusak' => 'Rusak',
            'instal_ulang' => 'Instal Ulang',
            'tidak_ada' => 'Tidak Ada',
        ];
    @endphp

    {{-- Desktop: concise table --}}
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
                @foreach($mejaRows as $meja)
                <tr>
                    <td class="mono" style="font-weight:600">#{{ $meja->nomor_meja }}</td>
                    @foreach($mejaConditionFields as $field => $config)
                    <td>
                        <select class="cond-select" data-type="meja" data-id="{{ $meja->id_meja }}" data-field="{{ $field }}" style="height:30px;padding:0 8px;border:1px solid var(--border);border-radius:6px;font-family:DM Sans,sans-serif;font-size:12.5px;background:var(--surface);cursor:pointer;outline:none">
                            @foreach($config['options'] as $opt)
                                <option value="{{ $opt }}" @selected($meja->$field === $opt)>{{ ucwords(str_replace('_', ' ', $opt)) }}</option>
                            @endforeach
                        </select>
                    </td>
                    @endforeach
                    <td>
                        <button type="button" class="meja-detail-btn meja-detail-trigger" data-meja-id="{{ $meja->id_meja }}">
                            <i class="bi bi-eye"></i> Detail
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile: one readable card per desk --}}
    <div class="meja-mobile-list">
        @forelse($mejaRows as $meja)
        <article class="meja-mobile-card">
            <div class="meja-mobile-card-header">
                <div class="meja-mobile-card-title">Meja #{{ $meja->nomor_meja }}</div>
                <button type="button" class="meja-detail-btn meja-detail-trigger" data-meja-id="{{ $meja->id_meja }}">
                    <i class="bi bi-eye"></i> Detail
                </button>
            </div>
            <div class="meja-mobile-conditions">
                @foreach($mejaConditionFields as $field => $config)
                    <div class="meja-mobile-condition">
                        <span class="meja-mobile-condition-label">{{ $config['label'] }}</span>
                        <select
                            class="cond-select"
                            data-type="meja"
                            data-id="{{ $meja->id_meja }}"
                            data-field="{{ $field }}"
                            aria-label="Kondisi {{ $config['label'] }} meja {{ $meja->nomor_meja }}"
                        >
                            @foreach($config['options'] as $opt)
                                <option value="{{ $opt }}" @selected($meja->$field === $opt)>{{ $conditionLabels[$opt] ?? ucwords(str_replace('_', ' ', $opt)) }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
            </div>
        </article>
        @empty
        <div class="empty-state" style="padding:28px">
            <div class="empty-icon"><i class="bi bi-grid-3x3"></i></div>
            <p>Belum ada data meja untuk laboratorium ini.</p>
        </div>
        @endforelse
    </div>
</div>

@php
    $mejaDetailData = $mejaRows->mapWithKeys(function ($meja) {
        return [(string) $meja->id_meja => [
            'nomor' => $meja->nomor_meja,
            'cpu_kondisi' => $meja->cpu_kondisi,
            'keyboard_kondisi' => $meja->keyboard_kondisi,
            'mouse_kondisi' => $meja->mouse_kondisi,
            'monitor_kondisi' => $meja->monitor_kondisi,
            'kursi_kondisi' => $meja->kursi_kondisi,
            'keterangan' => $meja->keterangan,
            'spesifikasi_pc' => $meja->spesifikasi_pc,
        ]];
    })->all();
@endphp

<script type="application/json" id="mejaDetailData">{!! json_encode($mejaDetailData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>

{{-- Detail/edit drawer --}}
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
                    <textarea id="mejaKeterangan" maxlength="20000" placeholder="Contoh: Mouse rusak karena tidak dapat menyala."></textarea>
                </div>
            </section>

            <section class="meja-detail-section">
                <h3 class="meja-detail-section-title">Informasi Komputer</h3>
                <div class="meja-detail-field">
                    <label for="mejaSpesifikasi">Spesifikasi PC</label>
                    <textarea id="mejaSpesifikasi" maxlength="20000" placeholder="Paste spesifikasi PC dari Windows di sini..."></textarea>
                    <div class="meja-detail-help">Bisa langsung copy-paste dari Windows Settings &gt; System &gt; About.</div>
                </div>
            </section>
        </div>

        <div class="meja-detail-drawer-footer">
            <span class="meja-detail-save-status" id="mejaDetailSaveStatus">
                <i class="bi bi-check2"></i>&nbsp; Tersimpan
            </span>
            <button type="button" class="btn-secondary" data-meja-close>Batal</button>
            <button type="button" class="btn-primary" id="saveMejaDetailBtn">
                <i class="bi bi-check2"></i> Simpan
            </button>
        </div>
    </aside>
</div>
</div>

@push('scripts')
<script>
const LAB_ID = '{{ $lab->id_lab }}';
const INVENTORY_UPDATE_URL = new URL(
    @json(parse_url(route('admin.inventaris.update'), PHP_URL_PATH)),
    window.location.origin
).toString();
const CSRF_TOKEN = '{{ csrf_token() }}';
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

async function sendInventoryUpdate(payload) {
    const response = await fetch(INVENTORY_UPDATE_URL, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
        },
        body: JSON.stringify(payload),
    });

    const result = await response.json().catch(() => ({}));
    if (!response.ok || !result.success) {
        throw new Error(result.message || 'Gagal menyimpan perubahan.');
    }

    return result;
}

async function saveCondition(select) {
    const type = select.getAttribute('data-type');
    const id = select.getAttribute('data-id');
    const field = select.getAttribute('data-field') || 'kondisi';
    const value = select.value;
    const previousValue = select.getAttribute('data-previous-value') || value;

    select.disabled = true;

    try {
        await sendInventoryUpdate({ type, id, field, value });
        select.setAttribute('data-previous-value', value);

        if (type === 'meja' && MEJA_DETAIL_DATA[id]) {
            MEJA_DETAIL_DATA[id][field] = value;
        }
    } catch (error) {
        select.value = previousValue;
        alert(error.message || 'Gagal menyimpan perubahan.');
    } finally {
        select.disabled = false;
    }
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
    document.getElementById('mejaDetailSaveStatus').classList.remove('is-visible');

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

document.querySelectorAll('.cond-select').forEach(select => {
    select.setAttribute('data-previous-value', select.value);
    select.addEventListener('change', () => saveCondition(select));
});

document.getElementById('saveMejaDetailBtn').addEventListener('click', async () => {
    if (!activeMejaId || !MEJA_DETAIL_DATA[activeMejaId]) return;

    const button = document.getElementById('saveMejaDetailBtn');
    const status = document.getElementById('mejaDetailSaveStatus');
    const keterangan = document.getElementById('mejaKeterangan').value;
    const spesifikasiPc = document.getElementById('mejaSpesifikasi').value;

    button.disabled = true;
    status.classList.remove('is-visible');

    try {
        await Promise.all([
            sendInventoryUpdate({
                type: 'meja',
                id: activeMejaId,
                field: 'keterangan',
                value: keterangan,
            }),
            sendInventoryUpdate({
                type: 'meja',
                id: activeMejaId,
                field: 'spesifikasi_pc',
                value: spesifikasiPc,
            }),
        ]);

        MEJA_DETAIL_DATA[activeMejaId].keterangan = keterangan;
        MEJA_DETAIL_DATA[activeMejaId].spesifikasi_pc = spesifikasiPc;
        status.classList.add('is-visible');
    } catch (error) {
        alert(error.message || 'Gagal menyimpan detail meja.');
    } finally {
        button.disabled = false;
    }
});

// Edit jumlah kursi
document.getElementById('editKursiBtn').addEventListener('click', async () => {
    const current = document.getElementById('jumlahKursiText').textContent.trim();
    const input = prompt('Masukkan jumlah kursi baru:', current);
    if (input === null) return;
    const value = parseInt(input, 10);
    if (isNaN(value) || value < 0) {
        alert('Masukkan angka yang valid.');
        return;
    }

    try {
        await sendInventoryUpdate({
            type: 'lab',
            id: LAB_ID,
            field: 'jumlah_kursi',
            value,
        });
        document.getElementById('jumlahKursiText').textContent = value;
    } catch (error) {
        alert(error.message || 'Gagal menyimpan perubahan.');
    }
});
</script>
@endpush
@endsection
