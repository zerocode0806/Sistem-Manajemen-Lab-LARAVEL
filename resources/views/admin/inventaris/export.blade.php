<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Export Inventaris {{ $lab->nama_lab }}</title>
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
@else
<p style="color:#888">Tidak ada data meja.</p>
@endif
</body>
</html>
