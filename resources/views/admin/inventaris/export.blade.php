<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Export Inventaris {{ $lab->nama_lab }}</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: Arial, sans-serif; font-size: 12px; padding: 20px; color: #111; }
h1 { font-size: 17px; margin-bottom: 4px; }
h2 { font-size: 14px; margin: 14px 0 6px; color: #333; }
.meta { font-size: 10px; color: #555; margin-bottom: 12px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
th, td { border: 1px solid #bbb; padding: 4px 5px; text-align: left; font-size: 10px; }
th { background: #f0f0f0; font-weight: 600; }
.table-scroll { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
.period-meja-table { table-layout: fixed; width: 100%; }
.period-meja-table th,
.period-meja-table td { vertical-align: top; overflow-wrap: anywhere; line-height: 1.25; }
.period-meja-table th:nth-child(1) { width: 5%; }
.period-meja-table th:nth-child(2) { width: 7%; }
.period-meja-table th:nth-child(3) { width: 8%; }
.period-meja-table th:nth-child(4) { width: 7%; }
.period-meja-table th:nth-child(5) { width: 8%; }
.period-meja-table th:nth-child(6) { width: 7%; }
.period-meja-table th:nth-child(7) { width: 18%; }
.period-meja-table th:nth-child(8) { width: 34%; }
.period-meja-table .condition-cell { text-align: center; }
.period-meja-table .text-cell {
    white-space: normal;
    overflow-wrap: anywhere;
    line-height: 1.25;
}
.print-btn { margin-bottom: 20px; padding: 8px 18px; background: #1A1A1A; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; }
@page { size: landscape; margin: 8mm; }
@media screen and (max-width: 700px) {
    body { padding: 14px; }
    .table-scroll { margin-right: -2px; }
    .period-meja-table { min-width: 980px; }
}
@media print {
    .print-btn { display: none; }
    body { padding: 0; font-size: 11px; }
    .table-scroll { overflow: visible; }
    .period-meja-table { min-width: 0; }
    th, td { padding: 3px 4px; font-size: 10px; }
    h1 { font-size: 15px; }
    h2 { font-size: 12px; margin: 10px 0 5px; }
    .meta { font-size: 9px; margin-bottom: 8px; }
    table { margin-bottom: 7px; }
    .period-meja-table th, .period-meja-table td { line-height: 1.2; }
    .period-meja-table tr { break-inside: avoid; page-break-inside: avoid; }
}
</style>
</head>
<body>
<button class="print-btn" onclick="window.print()">🖨 Cetak / Simpan PDF</button>

<h1>Inventaris Laboratorium – {{ $lab->nama_lab }}</h1>
<div class="meta">
    Lokasi: {{ $lab->lokasi ?? '-' }} &nbsp;|&nbsp;
    Jumlah Kursi: {{ $lab->jumlah_kursi }} &nbsp;|&nbsp;
    Dicetak: {{ now()->format('d-m-Y H:i') }}
</div>

<h2>Inventaris AC</h2>
@if($acRows->count() > 0)
<table>
    <thead><tr><th>Unit AC</th><th>Kondisi</th></tr></thead>
    <tbody>
        @foreach($acRows as $ac)
        <tr>
            <td>AC #{{ $ac->nomor_ac }}</td>
            <td>{{ ucfirst($ac->kondisi) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p style="color:#888;margin-bottom:16px">Tidak ada data AC.</p>
@endif

<h2>Inventaris Meja & Perangkat</h2>
@if($mejaRows->count() > 0)
<table>
    <thead>
        <tr>
            <th>Meja</th><th>CPU</th><th>Keyboard</th><th>Mouse</th><th>Monitor</th><th>Kursi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($mejaRows as $m)
        @php
            $labelMap = ['normal'=>'Normal','rusak'=>'Rusak','instal_ulang'=>'Instal Ulang','tidak_ada'=>'Tidak Ada'];
        @endphp
        <tr>
            <td>#{{ $m->nomor_meja }}</td>
            <td>{{ $labelMap[$m->cpu_kondisi] ?? $m->cpu_kondisi }}</td>
            <td>{{ $labelMap[$m->keyboard_kondisi] ?? $m->keyboard_kondisi }}</td>
            <td>{{ $labelMap[$m->mouse_kondisi] ?? $m->mouse_kondisi }}</td>
            <td>{{ $labelMap[$m->monitor_kondisi] ?? $m->monitor_kondisi }}</td>
            <td>{{ $labelMap[$m->kursi_kondisi] ?? $m->kursi_kondisi }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<h2>Detail Keterangan & Spesifikasi PC</h2>
<table class="detail-info-table">
    <thead><tr><th>Meja</th><th>Keterangan</th><th>Spesifikasi PC</th></tr></thead>
    <tbody>
        @foreach($mejaRows as $m)
        <tr>
            <td>#{{ $m->nomor_meja }}</td>
            <td class="text-cell">{{ $m->keterangan ?: 'Tidak ada keterangan.' }}</td>
            <td class="text-cell">{{ $m->spesifikasi_pc ?: 'Belum ada spesifikasi PC.' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p style="color:#888">Tidak ada data meja.</p>
@endif
</body>
</html>
