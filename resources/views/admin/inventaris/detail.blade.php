@extends('admin.layouts.app')
@section('title', 'Detail Riwayat Inventaris')
@section('content')

@push('styles')
<style>
.periode-inventaris-page .periode-page-header {
    align-items: flex-start;
}

.periode-header-actions {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
}

.periode-stat-grid {
    grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
    margin-bottom: 24px !important;
}

.periode-ac-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(155px, 1fr));
    gap: 10px;
}

.periode-ac-card {
    min-width: 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding: 13px 14px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: var(--bg);
}

.periode-ac-label {
    font-size: 11.5px;
    font-weight: 600;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .04em;
}

.periode-readonly-note {
    margin: -8px 0 20px;
    color: var(--muted);
    font-size: 12px;
}

.periode-note {
    margin-bottom: 20px;
    padding: 14px 16px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: var(--surface);
}

.periode-note strong {
    display: block;
    margin-bottom: 5px;
    font-size: 12px;
}

.periode-note p {
    color: var(--text);
    font-size: 13px;
    white-space: pre-wrap;
    overflow-wrap: anywhere;
}

.periode-inventaris-page .periode-card {
    margin-bottom: 20px;
}

.periode-meja-table-wrap {
    width: 100%;
    overflow-x: auto;
    border: 1px solid var(--border);
    border-radius: 8px;
    -webkit-overflow-scrolling: touch;
}

.periode-meja-table {
    min-width: 860px;
}

.periode-meja-table th,
.periode-meja-table td {
    white-space: nowrap;
}

.periode-meja-table .periode-detail-column {
    min-width: 150px;
    text-align: center;
}

.periode-meja-mobile-list {
    display: none;
}

.periode-meja-mobile-card {
    padding: 14px;
    border: 1px solid var(--border);
    border-radius: 9px;
    background: var(--bg);
}

.periode-meja-mobile-card + .periode-meja-mobile-card {
    margin-top: 10px;
}

.periode-meja-mobile-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding-bottom: 12px;
    margin-bottom: 4px;
    border-bottom: 1px solid var(--border);
}

.periode-meja-mobile-title {
    font: 600 14px 'DM Mono', monospace;
}

.periode-condition-list {
    display: grid;
}

.periode-condition-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    min-height: 40px;
    border-bottom: 1px solid var(--border);
    font-size: 13px;
}

.periode-condition-row:last-child {
    border-bottom: none;
}

.periode-condition-label {
    color: var(--muted);
}


/* Detail meja: gunakan drawer yang konsisten dengan halaman inventaris utama */
.periode-detail-btn {
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
.periode-detail-btn:hover { background: #dbeafe; }

.periode-detail-modal {
    position: fixed;
    inset: 0;
    z-index: 1200;
    display: flex;
    justify-content: flex-end;
    visibility: hidden;
    pointer-events: none;
}
.periode-detail-modal.is-open {
    visibility: visible;
    pointer-events: auto;
}
body.periode-modal-open { overflow: hidden; }

.periode-detail-backdrop {
    position: absolute;
    inset: 0;
    border: 0;
    background: rgba(24, 24, 27, .34);
    opacity: 0;
    cursor: pointer;
    transition: opacity .2s ease;
}
.periode-detail-modal.is-open .periode-detail-backdrop { opacity: 1; }

.periode-detail-drawer {
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
.periode-detail-modal.is-open .periode-detail-drawer { transform: translateX(0); }

.periode-detail-drawer-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    padding: 22px 24px 18px;
    border-bottom: 1px solid var(--border);
}
.periode-detail-eyebrow {
    margin-bottom: 4px;
    color: var(--muted);
    font: 500 10px 'DM Mono', monospace;
    letter-spacing: .08em;
    text-transform: uppercase;
}
.periode-detail-drawer-header h2 {
    margin: 0;
    font-size: 21px;
    font-weight: 600;
}
.periode-detail-close {
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
.periode-detail-close:hover { background: var(--bg); color: var(--text); }

.periode-detail-drawer-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px 24px 24px;
}
.periode-detail-section + .periode-detail-section { margin-top: 22px; }
.periode-detail-section-title {
    margin: 0 0 10px;
    color: var(--muted);
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .06em;
    text-transform: uppercase;
}
.periode-detail-readonly-field {
    display: flex;
    flex-direction: column;
    gap: 7px;
}
.periode-detail-readonly-field + .periode-detail-readonly-field { margin-top: 14px; }
.periode-detail-readonly-field label {
    color: var(--text);
    font-size: 12.5px;
    font-weight: 600;
}
.periode-detail-readonly-value {
    min-height: 44px;
    padding: 11px 12px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: var(--bg);
    color: var(--text);
    font-size: 13px;
    line-height: 1.55;
    overflow-wrap: anywhere;
    white-space: pre-wrap;
}
.periode-detail-readonly-value.is-spec {
    min-height: 120px;
    font-family: 'DM Mono', monospace;
    font-size: 12px;
}
.periode-detail-empty { color: var(--muted); font-style: italic; }

.periode-detail-drawer-footer {
    display: flex;
    justify-content: flex-end;
    padding: 14px 24px;
    border-top: 1px solid var(--border);
    background: var(--surface);
}

@media (max-width: 900px) {
    .periode-stat-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }
}

@media (max-width: 768px) {
    .periode-page-header {
        gap: 14px;
        margin-bottom: 20px;
    }

    .periode-page-header h1 {
        font-size: 21px;
        line-height: 1.25;
    }

    .periode-header-actions {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .periode-header-actions .btn-secondary {
        justify-content: center;
        min-width: 0;
    }

    .periode-stat-grid {
        gap: 10px !important;
        margin-bottom: 16px !important;
    }

    .periode-inventaris-page .periode-card {
        padding: 16px 14px;
    }

    .periode-inventaris-page .card-header {
        gap: 10px;
    }

    .periode-inventaris-page .card-header h2 {
        font-size: 14px;
    }

    .periode-meja-table-wrap {
        display: none;
    }

    .periode-meja-mobile-list {
        display: block;
    }

    .periode-detail-drawer {
        width: 100%;
        height: min(92vh, 760px);
        align-self: flex-end;
        border-top: 1px solid var(--border);
        border-left: 0;
        border-radius: 14px 14px 0 0;
        transform: translateY(100%);
    }

    .periode-detail-modal.is-open .periode-detail-drawer {
        transform: translateY(0);
    }

    .periode-detail-drawer-header {
        padding: 18px 18px 15px;
    }

    .periode-detail-drawer-body {
        padding: 18px;
    }

    .periode-detail-drawer-footer {
        padding: 14px 18px;
    }
}

@media (max-width: 480px) {
    .periode-stat-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }

    .periode-stat-card {
        padding: 14px 10px;
    }

    .periode-stat-card .stat-card-label {
        min-height: 28px;
        font-size: 10px;
        line-height: 1.35;
    }

    .periode-stat-card .stat-card-value {
        font-size: 24px;
    }
}
</style>
@endpush

@php
    $namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $label = $namaBulan[$periode->bulan - 1] . ' ' . $periode->tahun;
    $acRows = $periode->riwayatAc->sortBy('nomor_ac');
    $mejaRows = $periode->riwayatMeja->sortBy('nomor_meja');
    $acNormal = $acRows->where('kondisi', 'normal')->count();
    $acRusak = $acRows->where('kondisi', 'rusak')->count();
    $mejaConditionFields = [
        'cpu_kondisi' => 'CPU',
        'keyboard_kondisi' => 'Keyboard',
        'mouse_kondisi' => 'Mouse',
        'monitor_kondisi' => 'Monitor',
        'kursi_kondisi' => 'Kursi',
    ];
    $conditionLabels = [
        'normal' => 'Normal',
        'rusak' => 'Rusak',
        'instal_ulang' => 'Instal Ulang',
        'tidak_ada' => 'Tidak Ada',
    ];
    $conditionClasses = [
        'normal' => 'badge-available',
        'rusak' => 'badge-rusak',
        'instal_ulang' => 'badge-perbaikan',
        'tidak_ada' => 'badge-default',
    ];
@endphp

<div class="periode-inventaris-page">
    <div class="breadcrumb">
        <a href="{{ route('admin.lab.index') }}">Laboratorium</a>
        <i class="bi bi-chevron-right"></i>
        <a href="{{ route('admin.inventaris.index', $periode->id_lab) }}">Inventaris {{ $periode->lab->nama_lab }}</a>
        <i class="bi bi-chevron-right"></i>
        <a href="{{ route('admin.inventaris.riwayat', $periode->id_lab) }}">Riwayat Bulanan</a>
        <i class="bi bi-chevron-right"></i>
        <span class="current">{{ $label }}</span>
    </div>

    <div class="page-header periode-page-header">
        <div>
            <h1>Inventaris Lab</h1>
            <p>{{ $periode->lab->nama_lab }} · Snapshot {{ $label }}</p>
            <p style="margin-top:4px">
                Dicatat oleh: {{ $periode->dicatat_oleh ?? '-' }}
                @if($periode->tanggal_catat)
                    · {{ \Carbon\Carbon::parse($periode->tanggal_catat)->format('d M Y, H:i') }}
                @endif
            </p>
        </div>
        <div class="periode-header-actions">
            <a href="{{ route('admin.inventaris.exportPeriode', $periode->id_periode) }}" class="btn-secondary">
                <i class="bi bi-file-earmark-excel"></i> Export Periode
            </a>
            <a href="{{ route('admin.inventaris.riwayat', $periode->id_lab) }}" class="btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <p class="periode-readonly-note"><i class="bi bi-info-circle"></i> Ini adalah snapshot inventaris periode dan hanya dapat dilihat.</p>

    <div class="stat-grid periode-stat-grid">
        <div class="stat-card periode-stat-card">
            <div class="stat-card-label">JUMLAH KURSI</div>
            <div class="stat-card-value">{{ $periode->jumlah_kursi }}</div>
        </div>
        <div class="stat-card periode-stat-card">
            <div class="stat-card-label">TOTAL MEJA</div>
            <div class="stat-card-value">{{ $periode->jumlah_meja ?? $mejaRows->count() }}</div>
            <div class="stat-card-sub">Unit tercatat</div>
        </div>
        <div class="stat-card green periode-stat-card">
            <div class="stat-card-label">AC NORMAL</div>
            <div class="stat-card-value">{{ $acNormal }}</div>
            <div class="stat-card-sub">dari {{ $acRows->count() }} unit</div>
        </div>
        <div class="stat-card red periode-stat-card">
            <div class="stat-card-label">AC RUSAK</div>
            <div class="stat-card-value">{{ $acRusak }}</div>
            <div class="stat-card-sub">Perlu perhatian</div>
        </div>
    </div>

    @if($periode->keterangan)
    <section class="periode-note">
        <strong><i class="bi bi-card-text"></i> Keterangan Periode</strong>
        <p>{{ $periode->keterangan }}</p>
    </section>
    @endif

    <section class="card periode-card">
        <div class="card-header">
            <div class="card-header-icon"><i class="bi bi-wind"></i></div>
            <h2>Inventaris AC</h2>
        </div>

        @if($acRows->isNotEmpty())
        <div class="periode-ac-grid">
            @foreach($acRows as $ac)
            <article class="periode-ac-card">
                <span class="periode-ac-label">AC Unit #{{ $ac->nomor_ac }}</span>
                <span class="badge {{ $ac->kondisi === 'normal' ? 'badge-available' : 'badge-rusak' }}">
                    {{ ucfirst($ac->kondisi) }}
                </span>
            </article>
            @endforeach
        </div>
        @else
        <div class="empty-state" style="padding:28px">
            <div class="empty-icon"><i class="bi bi-wind"></i></div>
            <p>Tidak ada data AC pada periode ini.</p>
        </div>
        @endif
    </section>

    <section class="card periode-card">
        <div class="card-header">
            <div class="card-header-icon"><i class="bi bi-grid-3x3"></i></div>
            <h2>Inventaris Meja & Perangkat</h2>
        </div>

        @if($mejaRows->isNotEmpty())
        <div class="periode-meja-table-wrap">
            <table class="periode-meja-table">
                <thead>
                    <tr>
                        <th>Meja</th>
                        @foreach($mejaConditionFields as $fieldLabel)
                            <th>{{ $fieldLabel }}</th>
                        @endforeach
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mejaRows as $meja)
                    <tr>
                        <td class="mono" style="font-weight:600">#{{ $meja->nomor_meja }}</td>
                        @foreach($mejaConditionFields as $field => $fieldLabel)
                            @php $value = $meja->{$field}; @endphp
                            <td>
                                <span class="badge {{ $conditionClasses[$value] ?? 'badge-default' }}">
                                    {{ $conditionLabels[$value] ?? ucfirst(str_replace('_', ' ', $value ?? '-')) }}
                                </span>
                            </td>
                        @endforeach
                        <td class="periode-detail-column">
                            <button
                                type="button"
                                class="periode-detail-btn periode-detail-trigger"
                                data-meja-nomor="{{ $meja->nomor_meja }}"
                                data-keterangan="{{ $meja->keterangan ?? '' }}"
                                data-spesifikasi="{{ $meja->spesifikasi_pc ?? '' }}"
                                data-cpu="{{ $meja->cpu_kondisi ?? '' }}"
                                data-keyboard="{{ $meja->keyboard_kondisi ?? '' }}"
                                data-mouse="{{ $meja->mouse_kondisi ?? '' }}"
                                data-monitor="{{ $meja->monitor_kondisi ?? '' }}"
                                data-kursi="{{ $meja->kursi_kondisi ?? '' }}"
                            >
                                <i class="bi bi-eye"></i> Detail
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="periode-meja-mobile-list">
            @foreach($mejaRows as $meja)
            <article class="periode-meja-mobile-card">
                <div class="periode-meja-mobile-header">
                    <span class="periode-meja-mobile-title">Meja #{{ $meja->nomor_meja }}</span>
                    <button
                        type="button"
                        class="periode-detail-btn periode-detail-trigger"
                        data-meja-nomor="{{ $meja->nomor_meja }}"
                        data-keterangan="{{ $meja->keterangan ?? '' }}"
                        data-spesifikasi="{{ $meja->spesifikasi_pc ?? '' }}"
                                data-cpu="{{ $meja->cpu_kondisi ?? '' }}"
                                data-keyboard="{{ $meja->keyboard_kondisi ?? '' }}"
                                data-mouse="{{ $meja->mouse_kondisi ?? '' }}"
                                data-monitor="{{ $meja->monitor_kondisi ?? '' }}"
                                data-kursi="{{ $meja->kursi_kondisi ?? '' }}"
                    >
                        <i class="bi bi-eye"></i> Detail
                    </button>
                </div>
                <div class="periode-condition-list">
                    @foreach($mejaConditionFields as $field => $fieldLabel)
                        @php $value = $meja->{$field}; @endphp
                        <div class="periode-condition-row">
                            <span class="periode-condition-label">{{ $fieldLabel }}</span>
                            <span class="badge {{ $conditionClasses[$value] ?? 'badge-default' }}">
                                {{ $conditionLabels[$value] ?? ucfirst(str_replace('_', ' ', $value ?? '-')) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </article>
            @endforeach
        </div>
        @else
        <div class="empty-state" style="padding:28px">
            <div class="empty-icon"><i class="bi bi-grid-3x3"></i></div>
            <p>Tidak ada data meja pada periode ini.</p>
        </div>
        @endif
    </section>

    {{-- Drawer detail baca-saja, mengikuti pola detail pada halaman inventaris utama --}}
    <div class="periode-detail-modal" id="periodeDetailModal" aria-hidden="true">
        <button type="button" class="periode-detail-backdrop" data-periode-detail-close aria-label="Tutup detail meja"></button>
        <aside class="periode-detail-drawer" role="dialog" aria-modal="true" aria-labelledby="periodeDetailTitle">
            <div class="periode-detail-drawer-header">
                <div>
                    <div class="periode-detail-eyebrow">Informasi inventaris · Snapshot {{ $label }}</div>
                    <h2 id="periodeDetailTitle">Meja —</h2>
                </div>
                <button type="button" class="periode-detail-close" id="periodeDetailCloseButton" data-periode-detail-close aria-label="Tutup">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="periode-detail-drawer-body">
                <section class="periode-detail-section">
                    <h3 class="periode-detail-section-title">Kondisi Perangkat</h3>
                    <div class="periode-condition-list" id="periodeDetailConditions"></div>
                </section>

                <section class="periode-detail-section">
                    <h3 class="periode-detail-section-title">Catatan Meja</h3>
                    <div class="periode-detail-readonly-field">
                        <label>Keterangan</label>
                        <div class="periode-detail-readonly-value" id="periodeDetailKeterangan">Tidak ada keterangan.</div>
                    </div>
                </section>

                <section class="periode-detail-section">
                    <h3 class="periode-detail-section-title">Informasi Komputer</h3>
                    <div class="periode-detail-readonly-field">
                        <label>Spesifikasi PC</label>
                        <div class="periode-detail-readonly-value is-spec" id="periodeDetailSpesifikasi">Belum ada spesifikasi PC.</div>
                    </div>
                </section>
            </div>

            <div class="periode-detail-drawer-footer">
                <button type="button" class="btn-secondary" data-periode-detail-close>Tutup</button>
            </div>
        </aside>
    </div>
</div>

@push('scripts')
<script>
(() => {
    const modal = document.getElementById('periodeDetailModal');
    if (!modal) return;

    const title = document.getElementById('periodeDetailTitle');
    const keterangan = document.getElementById('periodeDetailKeterangan');
    const spesifikasi = document.getElementById('periodeDetailSpesifikasi');
    const closeButton = document.getElementById('periodeDetailCloseButton');
    const conditions = document.getElementById('periodeDetailConditions');
    const conditionMeta = [
        ['cpu', 'CPU'],
        ['keyboard', 'Keyboard'],
        ['mouse', 'Mouse'],
        ['monitor', 'Monitor'],
        ['kursi', 'Kursi']
    ];
    const conditionLabels = {
        normal: 'Normal',
        rusak: 'Rusak',
        instal_ulang: 'Instal Ulang',
        tidak_ada: 'Tidak Ada'
    };
    let lastTrigger = null;

    function renderConditions(button) {
        conditions.replaceChildren();
        conditionMeta.forEach(([key, label]) => {
            const value = button.dataset[key] || '';
            const row = document.createElement('div');
            row.className = 'periode-condition-row';

            const name = document.createElement('span');
            name.textContent = label;
            name.className = 'periode-condition-label';

            const badge = document.createElement('span');
            badge.className = 'badge ' + ({
                normal: 'badge-available',
                rusak: 'badge-rusak',
                instal_ulang: 'badge-perbaikan',
                tidak_ada: 'badge-default'
            }[value] || 'badge-default');
            badge.textContent = conditionLabels[value] || value || 'Belum diisi';

            row.append(name, badge);
            conditions.appendChild(row);
        });
    }

    function setDetailValue(element, value, fallback) {
        const content = (value || '').trim();
        element.textContent = content || fallback;
        element.classList.toggle('periode-detail-empty', !content);
    }

    function openDetail(button) {
        lastTrigger = button;
        title.textContent = `Meja #${button.dataset.mejaNomor || '—'}`;
        renderConditions(button);
        setDetailValue(keterangan, button.dataset.keterangan, 'Tidak ada keterangan.');
        setDetailValue(spesifikasi, button.dataset.spesifikasi, 'Belum ada spesifikasi PC.');
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('periode-modal-open');
        closeButton.focus();
    }

    function closeDetail() {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('periode-modal-open');
        if (lastTrigger) lastTrigger.focus();
    }

    document.querySelectorAll('.periode-detail-trigger').forEach(button => {
        button.addEventListener('click', () => openDetail(button));
    });

    document.querySelectorAll('[data-periode-detail-close]').forEach(button => {
        button.addEventListener('click', closeDetail);
    });

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && modal.classList.contains('is-open')) {
            closeDetail();
        }
    });
})();
</script>
@endpush
@endsection
