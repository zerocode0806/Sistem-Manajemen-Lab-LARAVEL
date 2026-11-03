@extends('admin.layouts.app')
@section('title', 'Riwayat Inventaris')
@section('content')
@push('styles')<style>
    .history-actions {
        display: flex;
        gap: 7px;
        align-items: center;
        flex-wrap: wrap
    }

    .history-table-wrap {
        overflow-x: auto;
        border: 1px solid var(--border);
        border-radius: 9px;
        -webkit-overflow-scrolling: touch
    }

    .history-table {
        width: 100%;
        min-width: 760px;
        border-collapse: collapse
    }

    .history-table th {
        background: #e7fbfb
    }

    .history-table th,
    .history-table td {
        padding: 11px 12px;
        border-bottom: 1px solid var(--border);
        text-align: left;
        white-space: nowrap
    }

    .history-table tr:last-child td {
        border-bottom: 0
    }

    .history-mobile {
        display: none
    }

    .history-card {
        padding: 14px;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--surface)
    }

    .history-card+.history-card {
        margin-top: 10px
    }

    .history-card h3 {
        font-size: 15px;
        margin: 0 0 9px
    }

    .history-meta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 9px;
        color: var(--muted);
        font-size: 12px
    }

    .history-card-actions {
        display: flex;
        gap: 8px;
        margin-top: 13px
    }

    .history-card-actions .btn-secondary {
        flex: 1;
        justify-content: center
    }

    @media(max-width:650px) {
        .history-table-wrap {
            display: none
        }

        .history-mobile {
            display: block
        }

        .history-actions {
            display: grid;
            grid-template-columns: 1fr 1fr
        }

        .page-header {
            align-items: flex-start !important
        }
    }
</style>@endpush
<div class="breadcrumb"><a href="{{ route('admin.lab.index') }}">Laboratorium</a><i class="bi bi-chevron-right"></i><a href="{{ route('admin.inventaris.index', $lab->id_lab) }}">Inventaris {{ $lab->nama_lab }}</a><i class="bi bi-chevron-right"></i><span class="current">Riwayat Periode</span></div>
<div class="page-header">
    <div>
        <h1>Riwayat Inventaris</h1>
        <p>{{ $lab->nama_lab }} · Snapshot inventaris yang tersimpan</p>
    </div>
    <div class="history-actions"><a href="{{ route('admin.inventaris.index', $lab->id_lab) }}" class="btn-primary"><i class="bi bi-plus-lg"></i> Isi / Simpan Inventaris</a></div>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="inv-callout" style="margin-bottom:16px;padding:12px 14px;border-radius:8px;background:var(--blue-soft);font-size:12px"><i class="bi bi-info-circle"></i> Pengisian dan penyimpanan periode dilakukan dari halaman inventaris lab. Periode bulan/tahun yang sama akan diperbarui jika disimpan ulang.</div>
<div class="history-table-wrap">
    <table class="history-table">
        <thead>
            <tr>
                <th>Periode</th>
                <th>Kursi</th>
                <th>PC / Meja</th>
                <th>AC</th>
                <th>Dicatat Oleh</th>
                <th>Waktu Catat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($periode as $p)@php($namaBulan=['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'])@php($label=($namaBulan[$p->bulan-1] ?? 'Periode').' '.$p->tahun)
            <tr>
                <td><strong>{{ $label }}</strong></td>
                <td>{{ $p->jumlah_kursi }}</td>
                <td>{{ $p->jumlah_meja }}</td>
                <td>{{ $p->jumlah_ac }}</td>
                <td>{{ $p->dicatat_oleh ?? '—' }}</td>
                <td>{{ $p->tanggal_catat ? \Carbon\Carbon::parse($p->tanggal_catat)->format('d-m-Y H:i') : '—' }}</td>
                <td>
                    <div style="display:flex;gap:7px"><a class="btn-secondary" href="{{ route('admin.inventaris.detailPeriode', $p->id_periode) }}" title="Lihat"><i class="bi bi-eye"></i></a><a class="btn-secondary" href="{{ route('admin.inventaris.exportPeriode', $p->id_periode) }}" title="Cetak"><i class="bi bi-printer"></i></a>
                        <form action="{{ route('admin.inventaris.hapusPeriode', [$lab->id_lab, $p->id_periode]) }}" method="POST" onsubmit="return confirm('Hapus snapshot {{ $label }}?')">@csrf @method('DELETE')<button class="btn-secondary" type="submit" title="Hapus"><i class="bi bi-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty<tr>
                <td colspan="7">
                    <div class="empty-state" style="padding:24px">
                        <p>Belum ada snapshot inventaris. Isi inventaris lalu simpan periode dari halaman inventaris lab.</p><a href="{{ route('admin.inventaris.index', $lab->id_lab) }}" class="btn-primary"><i class="bi bi-plus-lg"></i> Mulai isi inventaris</a>
                    </div>
                </td>
            </tr>@endforelse
        </tbody>
    </table>
</div>
<div class="history-mobile">@forelse($periode as $p)@php($namaBulan=['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'])@php($label=($namaBulan[$p->bulan-1] ?? 'Periode').' '.$p->tahun)
    <article class="history-card">
        <h3>{{ $label }}</h3>
        <div class="history-meta"><span>Kursi: <strong>{{ $p->jumlah_kursi }}</strong></span><span>PC/Meja: <strong>{{ $p->jumlah_meja }}</strong></span><span>AC: <strong>{{ $p->jumlah_ac }}</strong></span><span>Dicatat: {{ $p->dicatat_oleh ?? '—' }}</span></div>
        <div class="history-card-actions"><a class="btn-secondary" href="{{ route('admin.inventaris.detailPeriode', $p->id_periode) }}"><i class="bi bi-eye"></i> Detail</a><a class="btn-secondary" href="{{ route('admin.inventaris.exportPeriode', $p->id_periode) }}"><i class="bi bi-printer"></i> Cetak</a></div>
        <form action="{{ route('admin.inventaris.hapusPeriode', [$lab->id_lab, $p->id_periode]) }}" method="POST" style="margin-top:8px" onsubmit="return confirm('Hapus snapshot {{ $label }}?')">@csrf @method('DELETE')<button class="btn-secondary" type="submit" style="width:100%;justify-content:center"><i class="bi bi-trash"></i> Hapus snapshot</button></form>
    </article>
    @empty<div class="empty-state">
        <p>Belum ada snapshot inventaris.</p><a href="{{ route('admin.inventaris.index', $lab->id_lab) }}" class="btn-primary">Isi inventaris</a>
    </div>@endforelse
</div>
@endsection