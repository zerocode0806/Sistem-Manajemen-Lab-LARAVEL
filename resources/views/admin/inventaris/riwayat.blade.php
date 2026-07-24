@extends('admin.layouts.app')
@section('title', 'Riwayat Inventaris')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.lab.index') }}">Laboratorium</a>
    <i class="bi bi-chevron-right"></i>
    <a href="{{ route('admin.inventaris.index', $lab->id_lab) }}">Inventaris {{ $lab->nama_lab }}</a>
    <i class="bi bi-chevron-right"></i><span class="current">Riwayat Bulanan</span>
</div>

<div class="page-header">
    <div>
        <h1>Riwayat Inventaris</h1>
        <p>{{ $lab->nama_lab }} – Catatan inventaris per periode bulan</p>
    </div>
</div>

{{-- Simpan Periode Form --}}
<div class="card" style="margin-bottom:24px">
    <div class="card-header">
        <div class="card-header-icon"><i class="bi bi-calendar-plus"></i></div>
        <h2>Simpan Catatan Inventaris Bulan Ini</h2>
    </div>
    <form action="{{ route('admin.inventaris.simpanPeriode', $lab->id_lab) }}" method="POST">
        @csrf
        <div class="field-row" style="margin-bottom:14px">
            <div class="field-group" style="margin-bottom:0">
                <label for="bulan">Bulan</label>
                <select id="bulan" name="bulan">
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $b)
                        <option value="{{ $i+1 }}" @selected(($i+1) == date('n'))>{{ $b }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field-group" style="margin-bottom:0">
                <label for="tahun">Tahun</label>
                <input type="number" id="tahun" name="tahun" value="{{ date('Y') }}" min="2020" max="2099">
            </div>
        </div>
        <div class="field-group">
            <label for="keterangan">Keterangan (opsional)</label>
            <input type="text" id="keterangan" name="keterangan" placeholder="Catatan tambahan untuk periode ini…">
        </div>
        <button type="submit" class="btn-primary"><i class="bi bi-save"></i> Simpan Snapshot Inventaris</button>
    </form>
</div>

{{-- Table --}}
<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Periode</th>
                <th>Kursi</th>
                <th>Meja</th>
                <th>AC</th>
                <th>Dicatat Oleh</th>
                <th>Tanggal Catat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($periode as $p)
            @php
                $namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                $label = $namaBulan[$p->bulan - 1] . ' ' . $p->tahun;
            @endphp
            <tr>
                <td style="font-weight:600">{{ $label }}</td>
                <td class="mono">{{ $p->jumlah_kursi }}</td>
                <td class="mono">{{ $p->jumlah_meja }}</td>
                <td class="mono">{{ $p->jumlah_ac }}</td>
                <td>{{ $p->dicatat_oleh ?? '-' }}</td>
                <td class="mono">{{ \Carbon\Carbon::parse($p->tanggal_catat)->format('d-m-Y H:i') }}</td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('admin.inventaris.detailPeriode', $p->id_periode) }}" class="btn-action btn-view" title="Lihat Detail"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('admin.inventaris.exportPeriode', $p->id_periode) }}" class="btn-action btn-export" title="Export Excel"><i class="bi bi-file-earmark-excel"></i></a>
                        <form action="{{ route('admin.inventaris.hapusPeriode', [$lab->id_lab, $p->id_periode]) }}" method="POST" style="margin:0">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-action btn-delete" title="Hapus" onclick="return confirm('Hapus catatan {{ $label }}?')"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7"><div class="empty-state"><div class="empty-icon"><i class="bi bi-calendar-x"></i></div><p>Belum ada catatan inventaris bulanan untuk lab ini.</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
