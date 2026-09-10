<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataLab;
use App\Models\InventarisAc;
use App\Models\InventarisMeja;
use App\Models\PeriodeInventaris;
use App\Models\RiwayatAc;
use App\Models\RiwayatMeja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventarisController extends Controller
{
    /** Index – inventaris per lab */
    public function index($id_lab)
    {
        $lab     = DataLab::findOrFail($id_lab);
        $acRows  = InventarisAc::where('id_lab', $id_lab)->orderBy('nomor_ac')->get();
        $mejaRows = InventarisMeja::where('id_lab', $id_lab)->orderBy('nomor_meja')->get();

        $acNormal = $acRows->where('kondisi', 'normal')->count();
        $acRusak  = $acRows->where('kondisi', 'rusak')->count();

        return view('admin.inventaris.index', compact('lab', 'acRows', 'mejaRows', 'acNormal', 'acRusak'));
    }

    /** AJAX – update kondisi AC, meja, atau jumlah_kursi lab */
    public function update(Request $request)
    {
        $request->validate([
            'type'  => 'required|in:ac,meja,lab',
            'id'    => 'required|integer',
            'field' => 'required|string',
            'value' => 'nullable',
        ]);

        try {
            if ($request->type === 'ac') {
                $ac = InventarisAc::findOrFail($request->id);
                if ($request->field !== 'kondisi' || !in_array($request->value, ['normal', 'rusak'], true)) {
                    abort(422, 'Kondisi AC tidak valid.');
                }
                $ac->update(['kondisi' => $request->value]);
            } elseif ($request->type === 'meja') {
                $meja = InventarisMeja::findOrFail($request->id);
                $conditionFields = [
                    'cpu_kondisi'      => ['normal', 'rusak', 'instal_ulang'],
                    'keyboard_kondisi' => ['normal', 'rusak', 'tidak_ada'],
                    'mouse_kondisi'    => ['normal', 'rusak', 'tidak_ada'],
                    'monitor_kondisi'  => ['normal', 'rusak', 'tidak_ada'],
                    'kursi_kondisi'    => ['normal', 'rusak', 'tidak_ada'],
                ];
                $textFields = ['keterangan', 'spesifikasi_pc'];

                if (array_key_exists($request->field, $conditionFields)) {
                    if (!in_array($request->value, $conditionFields[$request->field], true)) {
                        abort(422, 'Kondisi perangkat tidak valid.');
                    }
                    $meja->update([$request->field => $request->value]);
                } elseif (in_array($request->field, $textFields, true)) {
                    $value = $request->input('value');
                    if ($value !== null && mb_strlen((string) $value) > 20000) {
                        abort(422, 'Isi terlalu panjang.');
                    }
                    $meja->update([$request->field => $value]);
                } else {
                    abort(422, 'Field inventaris meja tidak valid.');
                }
            } elseif ($request->type === 'lab') {
                $lab = DataLab::findOrFail($request->id);
                if ($request->field === 'jumlah_kursi' && is_numeric($request->value) && (int) $request->value >= 0) {
                    $lab->update(['jumlah_kursi' => (int) $request->value]);
                } else {
                    abort(422, 'Data jumlah kursi tidak valid.');
                }
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                throw $e;
            }
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /** Tambah satu unit AC ke lab */
    public function tambahAc($id_lab)
    {
        $lab = DataLab::findOrFail($id_lab);
        $lastNo = InventarisAc::where('id_lab', $id_lab)->max('nomor_ac') ?? 0;

        InventarisAc::create([
            'id_lab'   => $id_lab,
            'nomor_ac' => $lastNo + 1,
            'kondisi'  => 'normal',
        ]);

        // Update jumlah_meja is not needed; AC count derived from rows.
        return redirect()->route('admin.inventaris.index', $id_lab)
                         ->with('success', 'AC berhasil ditambahkan.');
    }

    /** Hapus satu unit AC */
    public function hapusAc($id_lab, $id_ac)
    {
        InventarisAc::where('id_lab', $id_lab)->findOrFail($id_ac)->delete();

        // Re-number AC units
        $acs = InventarisAc::where('id_lab', $id_lab)->orderBy('nomor_ac')->get();
        foreach ($acs as $i => $ac) {
            $ac->update(['nomor_ac' => $i + 1]);
        }

        return redirect()->route('admin.inventaris.index', $id_lab)
                         ->with('success', 'AC berhasil dihapus.');
    }

    /** Simpan snapshot (periode bulanan) */
    public function simpanPeriode(Request $request, $id_lab)
    {
        $request->validate([
            'bulan'  => 'required|integer|min:1|max:12',
            'tahun'  => 'required|integer|min:2020|max:2099',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $lab      = DataLab::findOrFail($id_lab);
        $acRows   = InventarisAc::where('id_lab', $id_lab)->get();
        $mejaRows = InventarisMeja::where('id_lab', $id_lab)->get();

        $periode = PeriodeInventaris::create([
            'id_lab'       => $id_lab,
            'bulan'        => $request->bulan,
            'tahun'        => $request->tahun,
            'jumlah_kursi' => $lab->jumlah_kursi,
            'jumlah_meja'  => $mejaRows->count(),
            'jumlah_ac'    => $acRows->count(),
            'tanggal_catat' => now(),
            'dicatat_oleh' => Auth::guard('admin')->user()->nama ?? 'Admin',
            'keterangan'   => $request->keterangan,
        ]);

        // Snapshot AC
        foreach ($acRows as $ac) {
            RiwayatAc::create([
                'id_periode' => $periode->id_periode,
                'id_lab'     => $id_lab,
                'nomor_ac'   => $ac->nomor_ac,
                'kondisi'    => $ac->kondisi,
            ]);
        }

        // Snapshot Meja
        foreach ($mejaRows as $meja) {
            RiwayatMeja::create([
                'id_periode'       => $periode->id_periode,
                'id_lab'           => $id_lab,
                'nomor_meja'       => $meja->nomor_meja,
                'cpu_kondisi'      => $meja->cpu_kondisi,
                'keyboard_kondisi' => $meja->keyboard_kondisi,
                'mouse_kondisi'    => $meja->mouse_kondisi,
                'monitor_kondisi'  => $meja->monitor_kondisi,
                'kursi_kondisi'    => $meja->kursi_kondisi,
                'keterangan'       => $meja->keterangan,
                'spesifikasi_pc'   => $meja->spesifikasi_pc,
            ]);
        }

        return redirect()->route('admin.inventaris.riwayat', $id_lab)
                         ->with('success', 'Catatan inventaris berhasil disimpan.');
    }

    /** Riwayat bulanan per lab */
    public function riwayat($id_lab)
    {
        $lab     = DataLab::findOrFail($id_lab);
        $periode = PeriodeInventaris::where('id_lab', $id_lab)
                       ->orderByDesc('tahun')->orderByDesc('bulan')
                       ->get();

        return view('admin.inventaris.riwayat', compact('lab', 'periode'));
    }

    /** Detail satu periode */
    public function detailPeriode($id_periode)
    {
        $periode = PeriodeInventaris::with(['lab', 'riwayatAc', 'riwayatMeja'])
                       ->findOrFail($id_periode);

        return view('admin.inventaris.detail', compact('periode'));
    }

    /** Hapus satu periode */
    public function hapusPeriode($id_lab, $id_periode)
    {
        PeriodeInventaris::where('id_lab', $id_lab)->findOrFail($id_periode)->delete();

        return redirect()->route('admin.inventaris.riwayat', $id_lab)
                         ->with('success', 'Catatan periode berhasil dihapus.');
    }

    /** Export inventaris saat ini sebagai HTML (printable) */
    public function export($id_lab)
    {
        $lab      = DataLab::findOrFail($id_lab);
        $acRows   = InventarisAc::where('id_lab', $id_lab)->orderBy('nomor_ac')->get();
        $mejaRows = InventarisMeja::where('id_lab', $id_lab)->orderBy('nomor_meja')->get();

        return response(view('admin.inventaris.export', compact('lab', 'acRows', 'mejaRows')));
    }

    /** Export riwayat satu periode sebagai HTML (printable) */
    public function exportPeriode($id_periode)
    {
        $periode = PeriodeInventaris::with(['lab', 'riwayatAc', 'riwayatMeja'])
                       ->findOrFail($id_periode);

        return response(view('admin.inventaris.export_periode', compact('periode')));
    }
}
