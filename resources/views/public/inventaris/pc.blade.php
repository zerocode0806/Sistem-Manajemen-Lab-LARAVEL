<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow,noarchive">
    <title>PC #{{ $pc->nomor_meja }} — {{ $pc->lab?->nama_lab ?? 'Inventaris Lab' }}</title>
    <style>
        *{box-sizing:border-box}
        body{margin:0;background:#f4f7fb;color:#172033;font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif;line-height:1.55}
        .shell{width:min(680px,100%);margin:0 auto;padding:28px 16px 44px}
        .brand{display:flex;align-items:center;gap:10px;margin-bottom:20px;color:#27364c;font-size:12px;font-weight:700;letter-spacing:.06em}
        .brand-mark{width:34px;height:34px;display:grid;place-items:center;border-radius:10px;background:#e0f2fe;color:#0369a1;font-size:14px}
        .card{background:#fff;border:1px solid #e3eaf2;border-radius:16px;box-shadow:0 8px 28px rgba(26,47,77,.055);padding:22px 24px;margin-bottom:14px}
        .eyebrow{margin:0 0 4px;color:#64748b;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}
        h1{margin:0;font-size:clamp(24px,5vw,30px);letter-spacing:-.03em;line-height:1.2}
        .host{margin:6px 0 0;color:#475569;font-size:15px;overflow-wrap:anywhere}
        .status{display:inline-flex;align-items:center;gap:8px;margin-top:16px;padding:7px 13px;border-radius:999px;font-size:13px;font-weight:700}
        .status:before{width:8px;height:8px;border-radius:50%;background:currentColor;content:""}
        .status.ok{background:#ecfdf5;color:#047857}
        .status.warn{background:#fff7ed;color:#c2410c}
        .issues{margin:10px 0 0;color:#9a3412;font-size:13px}
        .info{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px 20px;margin-top:20px;padding-top:18px;border-top:1px solid #edf1f5}
        h2{margin:0 0 6px;font-size:13px;color:#64748b;font-weight:700;letter-spacing:.06em;text-transform:uppercase}
        .row{display:grid;grid-template-columns:130px minmax(0,1fr);gap:12px;padding:11px 0;border-bottom:1px solid #edf1f5}
        .row:last-child{border-bottom:0;padding-bottom:0}
        .label{color:#64748b;font-size:13px;font-weight:600}
        .value{font-size:14px;overflow-wrap:anywhere}
        .info .label{display:block;margin-bottom:2px}
        .info .value{font-size:15px;font-weight:600}
        .muted{color:#94a3b8;font-weight:400}
        .foot{padding:6px 4px;color:#94a3b8;font-size:12px;text-align:center}
        @media(max-width:520px){
            .card{padding:19px 17px}
            .info{grid-template-columns:1fr}
            .row{grid-template-columns:105px minmax(0,1fr)}
        }
    </style>
</head>
<body>
@php
    // Parse spesifikasi_pc (format "Label<TAB>Nilai" atau "Label  Nilai")
    $specItems = collect(preg_split('/\r\n|\r|\n/', trim((string) $pc->spesifikasi_pc)))
        ->filter(fn ($line) => trim($line) !== '')
        ->map(function ($line) {
            $line = trim($line);
            if (str_contains($line, "\t")) {
                [$label, $value] = array_pad(explode("\t", $line, 2), 2, '');
                return ['label' => trim($label), 'value' => trim($value)];
            }
            if (preg_match('/^(.+?)\s{2,}(.+)$/u', $line, $m)) {
                return ['label' => trim($m[1]), 'value' => trim($m[2])];
            }
            return ['label' => null, 'value' => $line];
        });

    // Cari nilai spesifikasi berdasarkan kemungkinan nama label
    $find = function (array $keys, array $exact = []) use ($specItems) {
        foreach ($specItems as $item) {
            if ($item['label'] === null || $item['value'] === '') continue;
            $l = mb_strtolower($item['label']);
            if (in_array($l, $exact, true)) return $item['value'];
            foreach ($keys as $k) {
                if (str_contains($l, $k)) return $item['value'];
            }
        }
        return null;
    };

    $hostname = $pc->hostname ?? $find(['device name', 'nama perangkat', 'hostname', 'nama komputer']);
    $cpu      = $find(['processor', 'prosesor', 'cpu']);
    $ram      = $find(['installed ram', 'ram', 'memori', 'memory']);
    $gpu      = $find(['graphics', 'gpu', 'vga', 'kartu grafis']);
    $storage  = $find(['storage', 'penyimpanan', 'ssd', 'hdd', 'disk']);
    $osName   = $find(['sistem operasi', 'operating system', 'edition', 'edisi'], ['os']);
    $osVer    = $find(['version', 'versi']);

    // Komponen yang bermasalah (untuk catatan singkat)
    $problemComponents = collect($statuses ?? [])->filter(fn ($s) => $s !== 'normal')->keys();

    $dash = '<span class="muted">Belum diisi</span>';
@endphp

<main class="shell">
    <div class="brand">
        <span class="brand-mark" aria-hidden="true">PC</span>
        <span>INVENTARIS LABORATORIUM</span>
    </div>

    {{-- Identitas & Status --}}
    <section class="card">
        <p class="eyebrow">{{ $pc->lab?->nama_lab ?? 'Laboratorium' }}</p>
        <h1>PC #{{ $pc->nomor_meja }}</h1>
        <p class="host">{{ $hostname ?: 'Hostname belum diisi' }}</p>

        <span class="status {{ $needsAttention ? 'warn' : 'ok' }}">
            {{ $needsAttention ? 'Perlu pemeriksaan' : 'Kondisi normal' }}
        </span>
        @if($needsAttention && $problemComponents->isNotEmpty())
            <p class="issues">Perlu dicek: {{ $problemComponents->implode(', ') }}</p>
        @endif

        <div class="info">
            <div>
                <span class="label">Kode Aset / Nomor Meja</span>
                <span class="value">{{ $pc->nomor_meja }}</span>
            </div>
            <div>
                <span class="label">Lokasi</span>
                <span class="value">{{ $pc->lab?->nama_lab ?? '—' }}@if($pc->lab?->lokasi) <span class="muted">· {{ $pc->lab->lokasi }}</span>@endif</span>
            </div>
        </div>
    </section>

    {{-- Spesifikasi Hardware Utama --}}
    <section class="card" aria-labelledby="hw-title">
        <h2 id="hw-title">Spesifikasi Hardware</h2>
        <div class="row"><div class="label">Processor (CPU)</div><div class="value">{!! $cpu ? e($cpu) : $dash !!}</div></div>
        <div class="row"><div class="label">Kapasitas RAM</div><div class="value">{!! $ram ? e($ram) : $dash !!}</div></div>
        <div class="row"><div class="label">Kartu Grafis (GPU)</div><div class="value">{!! $gpu ? e($gpu) : $dash !!}</div></div>
        <div class="row"><div class="label">Storage</div><div class="value">{!! $storage ? e($storage) : $dash !!}</div></div>
    </section>

    {{-- Sistem & Pendukung Praktikum --}}
    <section class="card" aria-labelledby="sys-title">
        <h2 id="sys-title">Sistem &amp; Pendukung Praktikum</h2>
        <div class="row"><div class="label">Sistem Operasi</div><div class="value">{!! $osName ? e($osName) : $dash !!}</div></div>
        <div class="row"><div class="label">Versi</div><div class="value">{!! $osVer ? e($osVer) : $dash !!}</div></div>
    </section>

    <footer class="foot">Halaman publik hanya menampilkan informasi inventaris PC ini.</footer>
</main>
</body>
</html>