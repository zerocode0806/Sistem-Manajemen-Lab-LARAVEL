<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
@php
    $namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $label = $namaBulan[$periode->bulan - 1] . ' ' . $periode->tahun;
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
.print-btn { margin-bottom: 20px; padding: 8px 18px; background: #1A1A1A; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; }
@media print { .print-btn { display: none; } }
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
@if($periode->riwayatAc->count() > 0)
<table>
    <thead><tr><th>Unit AC</th><th>Kondisi</th></tr></thead>
    <tbody>
        @foreach($periode->riwayatAc as $ac)
        <tr><td>AC #{{ $ac->nomor_ac }}</td><td>{{ ucfirst($ac->kondisi) }}</td></tr>
        @endforeach
    </tbody>
</table>
@else
<p style="color:#888;margin-bottom:16px">Tidak ada data AC.</p>
@endif

<h2>Kondisi Meja & Perangkat</h2>
@if($periode->riwayatMeja->count() > 0)
<table>
    <thead><tr><th>Meja</th><th>CPU</th><th>Keyboard</th><th>Mouse</th><th>Monitor</th><th>Kursi</th></tr></thead>
    <tbody>
        @foreach($periode->riwayatMeja as $m)
        @php $lm = ['normal'=>'Normal','rusak'=>'Rusak','instal_ulang'=>'Instal Ulang','tidak_ada'=>'Tidak Ada']; @endphp
        <tr>
            <td>#{{ $m->nomor_meja }}</td>
            <td>{{ $lm[$m->cpu_kondisi] ?? $m->cpu_kondisi }}</td>
            <td>{{ $lm[$m->keyboard_kondisi] ?? $m->keyboard_kondisi }}</td>
            <td>{{ $lm[$m->mouse_kondisi] ?? $m->mouse_kondisi }}</td>
            <td>{{ $lm[$m->monitor_kondisi] ?? $m->monitor_kondisi }}</td>
            <td>{{ $lm[$m->kursi_kondisi] ?? $m->kursi_kondisi }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p style="color:#888">Tidak ada data meja.</p>
@endif
</body>
</html>
