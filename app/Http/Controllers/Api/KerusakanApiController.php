<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KerusakanApiController extends Controller
{
    /**
     * GET /api/kerusakan
     * Daftar kerusakan selain PC (AC + inventaris tambahan). Hanya baca.
     * Filter opsional: ?jenis=ac|tambahan  dan  ?id_lab=1
     */
    public function index(Request $request)
    {
        $request->validate([
            'jenis'  => 'nullable|in:ac,tambahan',
            'id_lab' => 'nullable|integer',
        ]);

        $jenis = $request->query('jenis');
        $data  = collect();

        // 1) AC per unit: yang kondisinya rusak
        if (! $jenis || $jenis === 'ac') {
            $rows = DB::table('inventaris_ac as a')
                ->leftJoin('data_lab as l', 'l.id_lab', '=', 'a.id_lab')
                ->where('a.kondisi', 'rusak')
                ->when($request->filled('id_lab'), fn ($q) => $q->where('a.id_lab', $request->query('id_lab')))
                ->orderBy('a.id_lab')->orderBy('a.nomor_ac')
                ->get(['a.id_ac', 'a.id_lab', 'a.nomor_ac', 'a.kondisi', 'a.updated_at', 'l.nama_lab', 'l.lokasi']);

            foreach ($rows as $r) {
                $data->push([
                    'jenis'        => 'ac',
                    'id'           => $r->id_ac,
                    'nama'         => 'AC #' . $r->nomor_ac,
                    'kategori'     => 'ac',
                    'id_lab'       => $r->id_lab,
                    'nama_lab'     => $r->nama_lab,
                    'lokasi'       => $r->lokasi,
                    'kondisi'      => $r->kondisi,
                    'jumlah_rusak' => 1,
                    'jumlah_total' => 1,
                    'keterangan'   => null,
                    'updated_at'   => $r->updated_at,
                ]);
            }
        }

        // 2) Inventaris tambahan: kondisi bukan normal ATAU ada jumlah rusak
        if (! $jenis || $jenis === 'tambahan') {
            $rows = DB::table('inventaris_tambahan as t')
                ->leftJoin('data_lab as l', 'l.id_lab', '=', 't.id_lab')
                ->where(function ($q) {
                    $q->where('t.kondisi', '!=', 'normal')
                      ->orWhere('t.jumlah_rusak', '>', 0);
                })
                ->when($request->filled('id_lab'), fn ($q) => $q->where('t.id_lab', $request->query('id_lab')))
                ->orderBy('t.id_lab')->orderBy('t.nama_barang')
                ->get(['t.id_item', 't.id_lab', 't.nama_barang', 't.kategori', 't.jumlah_total', 't.jumlah_rusak',
                       't.kondisi', 't.keterangan', 't.updated_at', 'l.nama_lab', 'l.lokasi']);

            foreach ($rows as $r) {
                $data->push([
                    'jenis'        => 'tambahan',
                    'id'           => $r->id_item,
                    'nama'         => $r->nama_barang,
                    'kategori'     => $r->kategori,
                    'id_lab'       => $r->id_lab,
                    'nama_lab'     => $r->nama_lab,
                    'lokasi'       => $r->lokasi,
                    'kondisi'      => $r->kondisi,
                    'jumlah_rusak' => (int) $r->jumlah_rusak,
                    'jumlah_total' => (int) $r->jumlah_total,
                    'keterangan'   => $r->keterangan,
                    'updated_at'   => $r->updated_at,
                ]);
            }
        }

        return response()->json(['data' => $data->values()->all()]);
    }
}