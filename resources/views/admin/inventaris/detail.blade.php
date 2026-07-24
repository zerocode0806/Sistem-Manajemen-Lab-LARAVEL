@extends('admin.layouts.app')
@section('title', 'Detail Riwayat Inventaris')
@section('content')

@php
    $namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $label = $namaBulan[$periode->bulan - 1] . ' ' . $periode->tahun;
@endphp

<div class="breadcrumb">
    <a href="{{ route('admin.lab.index') }}">Laboratorium</a>
    <i class="bi bi-chevron-right"></i>
    <a href="{{ route('admin.inventaris.riwayat', $periode->id_lab) }}">Riwayat Inventaris</a>
    <i class="bi bi-chevron-right"></i><span class="current">{{ $label }}</span>
</div>

<div class="page-header">
    <div>
        <h1>Detail Inventaris – {{ $label }}</h1>
        <p>{{ $periode->lab->nama_lab }} · Dicatat oleh: {{ $periode->dicatat_oleh ?? '-' }}</p>
    </div>
    <div style="display:flex;gap:8px">
        <a href="{{ route('admin.inventaris.exportPeriode', $periode->id_periode) }}" class="btn-secondary"><i class="bi bi-file-earmark-excel"></i> Export Excel</a>
        <a href="{{ route('admin.inventaris.riwayat', $periode->id_lab) }}" class="btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="stat-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:24px">
    <div class="stat-card"><div class="stat-card-label">JUMLAH KURSI</div><div class="stat-card-value">{{ $periode->jumlah_kursi }}</div></div>
    <div class="stat-card"><div class="stat-card-label">JUMLAH MEJA</div><div class="stat-card-value">{{ $periode->jumlah_meja }}</div></div>
    <div class="stat-card"><div class="stat-card-label">JUMLAH AC</div><div class="stat-card-value">{{ $periode->jumlah_ac }}</div></div>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:20px;align-items:start">
    <div class="card">
        <div class="card-header"><div class="card-header-icon"><i class="bi bi-wind"></i></div><h2>Kondisi AC</h2></div>
        @if($periode->riwayatAc->count() > 0)
        <div style="overflow:hidden;border-radius:6px;border:1px solid var(--border)">
            <table>
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

    <div class="card">
        <div class="card-header"><div class="card-header-icon"><i class="bi bi-grid-3x3"></i></div><h2>Kondisi Meja & Perangkat</h2></div>
        @if($periode->riwayatMeja->count() > 0)
        <div style="overflow:hidden;border-radius:6px;border:1px solid var(--border)">
            <table>
                <thead><tr><th>Meja</th><th>CPU</th><th>Keyboard</th><th>Mouse</th><th>Monitor</th><th>Kursi</th></tr></thead>
                <tbody>
                    @foreach($periode->riwayatMeja as $m)
                    <tr>
                        <td class="mono" style="font-weight:600">#{{ $m->nomor_meja }}</td>
                        @foreach(['cpu_kondisi','keyboard_kondisi','mouse_kondisi','monitor_kondisi','kursi_kondisi'] as $f)
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
        @else
        <div class="empty-state" style="padding:20px"><p>Tidak ada data meja.</p></div>
        @endif
    </div>
</div>
@endsection
