<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
@php
    $namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $label = $namaBulan[$periode->bulan - 1] . ' ' . $periode->tahun;
    $acRows = $periode->riwayatAc->sortBy('nomor_ac');
    $mejaRows = $periode->riwayatMeja->sortBy('nomor_meja');
    $conditionLabels = [
        'normal' => 'Normal',
        'rusak' => 'Rusak',
        'instal_ulang' => 'Instal Ulang',
        'tidak_ada' => 'Tidak Ada',
    ];
@endphp
<title>Export Inventaris {{ $label }} – {{ $periode->lab->nama_lab }}</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: Arial, sans-serif; font-size: 13px; padding: 30px; color: #111; }
h1 { font-size: 18px; margin-bottom: 4px; }
h2 { font-size: 15px; margin: 20px 0 8px; color: #333; }
.meta { font-size: 12px; color: #555; margin-bottom: 20px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
th, td { border: 1px solid #ccc; padding: 6px 10px; text-align: left; font-size: 12px; }
th { background: #f0f0f0; font-weight: 600; }
.table-scroll { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
.period-meja-table { table-layout: fixed; }
.period-meja-table th,
.period-meja-table td { vertical-align: top; overflow-wrap: anywhere; line-height: 1.4; }
.period-meja-table th:nth-child(1) { width: 6%; }
.period-meja-table th:nth-child(2) { width: 8%; }
.period-meja-table th:nth-child(3) { width: 10%; }
.period-meja-table th:nth-child(4) { width: 8%; }
.period-meja-table th:nth-child(5) { width: 9%; }
.period-meja-table th:nth-child(6) { width: 8%; }
.period-meja-table th:nth-child(7) { width: 25%; }
.period-meja-table th:nth-child(8) { width: 26%; }
.period-meja-table .condition-cell { text-align: center; }
.period-meja-table .text-cell {
    white-space: pre-wrap;
    overflow-wrap: anywhere;
    line-height: 1.45;
}
.print-btn { margin-bottom: 20px; padding: 8px 18px; background: #1A1A1A; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; }
@page { size: landscape; margin: 10mm; }
@media screen and (max-width: 700px) {
    body { padding: 14px; }
    .table-scroll { margin-right: -2px; }
    .period-meja-table { min-width: 980px; }
}
@media print {
    .print-btn { display: none; }
    body { padding: 0; }
    .table-scroll { overflow: visible; }
    .period-meja-table { min-width: 0; }
    .period-meja-table tr { break-inside: avoid; page-break-inside: avoid; }
}
</style>
</head>
<body>
<button class="print-btn" onclick="window.print()">🖨 Cetak / Simpan PDF</button>

<h1>Riwayat Inventaris – {{ $label }}</h1>
<div class="meta">
    Lab: {{ $periode->lab->nama_lab }} &nbsp;|&nbsp;
    Dicatat oleh: {{ $periode->dicatat_oleh ?? '-' }} &nbsp;|&nbsp;
    Tanggal catat: {{ \Carbon\Carbon::parse($periode->tanggal_catat)->format('d-m-Y H:i') }}
</div>

<table style="width:auto;margin-bottom:24px">
    <tr><th>Jumlah Kursi</th><td>{{ $periode->jumlah_kursi }}</td></tr>
    <tr><th>Jumlah Meja</th><td>{{ $periode->jumlah_meja }}</td></tr>
    <tr><th>Jumlah AC</th><td>{{ $periode->jumlah_ac }}</td></tr>
    @if($periode->keterangan)<tr><th>Keterangan</th><td>{{ $periode->keterangan }}</td></tr>@endif
</table>

<h2>Kondisi AC</h2>
@if($acRows->count() > 0)
<table>
    <thead><tr><th>Unit AC</th><th>Kondisi</th></tr></thead>
    <tbody>
        @foreach($acRows as $ac)
        <tr><td>AC #{{ $ac->nomor_ac }}</td><td>{{ ucfirst($ac->kondisi) }}</td></tr>
        @endforeach
    </tbody>
</table>
@else
<p style="color:#888;margin-bottom:16px">Tidak ada data AC.</p>
@endif

<h2>Kondisi Meja, Perangkat & Detail PC</h2>
@if($mejaRows->count() > 0)
<div class="table-scroll">
    <table class="period-meja-table">
        <thead>
            <tr>
                <th>Meja</th>
                <th>CPU</th>
                <th>Keyboard</th>
                <th>Mouse</th>
                <th>Monitor</th>
                <th>Kursi</th>
                <th>Keterangan</th>
                <th>Spesifikasi PC</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mejaRows as $m)
            <tr>
                <td>#{{ $m->nomor_meja }}</td>
                <td class="condition-cell">{{ $conditionLabels[$m->cpu_kondisi] ?? ucfirst(str_replace('_', ' ', $m->cpu_kondisi ?? '-')) }}</td>
                <td class="condition-cell">{{ $conditionLabels[$m->keyboard_kondisi] ?? ucfirst(str_replace('_', ' ', $m->keyboard_kondisi ?? '-')) }}</td>
                <td class="condition-cell">{{ $conditionLabels[$m->mouse_kondisi] ?? ucfirst(str_replace('_', ' ', $m->mouse_kondisi ?? '-')) }}</td>
                <td class="condition-cell">{{ $conditionLabels[$m->monitor_kondisi] ?? ucfirst(str_replace('_', ' ', $m->monitor_kondisi ?? '-')) }}</td>
                <td class="condition-cell">{{ $conditionLabels[$m->kursi_kondisi] ?? ucfirst(str_replace('_', ' ', $m->kursi_kondisi ?? '-')) }}</td>
                <td class="text-cell">{{ $m->keterangan ?: 'Tidak ada keterangan.' }}</td>
                <td class="text-cell">{{ $m->spesifikasi_pc ?: 'Belum ada spesifikasi PC.' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<p style="color:#888">Tidak ada data meja.</p>
@endif
</body>
</html>
