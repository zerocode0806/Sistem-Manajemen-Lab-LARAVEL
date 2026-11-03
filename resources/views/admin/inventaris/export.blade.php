<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Inventaris {{ $lab->nama_lab }}</title>
    <style>
        * {
            box-sizing: border-box
        }

        body {
            font: 12px Arial, sans-serif;
            color: #17202a;
            padding: 20px
        }

        h1 {
            font-size: 18px;
            margin: 0 0 5px
        }

        h2 {
            font-size: 14px;
            margin: 18px 0 7px
        }

        .meta {
            color: #555;
            margin-bottom: 12px
        }

        .scroll {
            width: 100%;
            overflow: auto
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 10px
        }

        th,
        td {
            border: 1px solid #9aa4aa;
            padding: 6px;
            text-align: left;
            vertical-align: top
        }

        th {
            background: #d9f6f6
        }

        .print {
            margin-bottom: 15px;
            padding: 8px 13px;
            background: #163b50;
            color: #fff;
            border: 0;
            border-radius: 5px
        }

        @media(max-width:650px) {
            body {
                padding: 12px
            }

            .scroll table {
                min-width: 720px
            }
        }

        @media print {
            .print {
                display: none
            }

            body {
                padding: 0
            }

            .scroll {
                overflow: visible
            }

            .scroll table {
                min-width: 0
            }

            h2 {
                break-after: avoid
            }

            tr {
                break-inside: avoid
            }
        }
    </style>
</head>

<body><button class="print" onclick="window.print()">Cetak / Simpan PDF</button>
    <h1>Inventaris Laboratorium</h1>
    <div class="meta">{{ $lab->nama_lab }} · {{ $lab->lokasi ?: 'Lokasi tidak dicatat' }} · Kursi: {{ $lab->jumlah_kursi }}</div>
    <h2>Inventaris PC</h2>
    <div class="scroll">
        <table>
            <thead>
                <tr>
                    <th>PC</th>
                    <th>CPU</th>
                    <th>Monitor</th>
                    <th>Keyboard</th>
                    <th>Mouse</th>
                    <th>Windows</th>
                    <th>Keterangan</th>
                    <th>Spesifikasi</th>
                </tr>
            </thead>
            <tbody>@forelse($mejaRows as $pc)<tr>
                    <td>#{{ $pc->nomor_meja }}</td>@foreach(['cpu_status','monitor_status','keyboard_status','mouse_status','windows_status'] as $field)<td>{{ strtoupper(str_replace('_',' ',$pc->$field ?? 'normal')) }}</td>@endforeach<td>{{ $pc->keterangan ?: '—' }}</td>
                    <td>{{ $pc->spesifikasi_pc ?: '—' }}</td>
                </tr>@empty<tr>
                    <td colspan="8">Belum ada data PC.</td>
                </tr>@endforelse</tbody>
        </table>
    </div>
    <h2>Inventaris AC Unit</h2>
    <table>
        <thead>
            <tr>
                <th>Unit</th>
                <th>Kondisi</th>
            </tr>
        </thead>
        <tbody>@forelse($acRows as $ac)<tr>
                <td>AC #{{ $ac->nomor_ac }}</td>
                <td>{{ strtoupper($ac->kondisi) }}</td>
            </tr>@empty<tr>
                <td colspan="2">Belum ada AC.</td>
            </tr>@endforelse</tbody>
    </table>
    <h2>Inventaris Tambahan</h2>
    <div class="scroll">
        <table>
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Barang</th>
                    <th>Total</th>
                    <th>Rusak</th>
                    <th>Baik</th>
                    <th>Kondisi</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>@forelse($tambahanRows as $item)<tr>
                    <td>{{ $item->kategori }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->jumlah_total }}</td>
                    <td>{{ $item->jumlah_rusak }}</td>
                    <td>{{ max(0,$item->jumlah_total-$item->jumlah_rusak) }}</td>
                    <td>{{ strtoupper(str_replace('_',' ',$item->kondisi)) }}</td>
                    <td>{{ $item->keterangan ?: '—' }}</td>
                </tr>@empty<tr>
                    <td colspan="7">Belum ada barang inventaris tambahan.</td>
                </tr>@endforelse</tbody>
        </table>
    </div>
</body>

</html>