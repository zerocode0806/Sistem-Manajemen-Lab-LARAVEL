<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataLab;
use App\Models\InventarisAc;
use App\Models\InventarisMeja;
use App\Models\InventarisTambahan;
use App\Models\PeriodeInventaris;
use App\Models\RiwayatAc;
use App\Models\RiwayatMeja;
use App\Models\RiwayatTambahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventarisController extends Controller
{
    public function index($id_lab)
    {
        $lab = DataLab::findOrFail($id_lab);
        $acRows = InventarisAc::where('id_lab', $id_lab)->orderBy('nomor_ac')->get();
        $mejaRows = InventarisMeja::where('id_lab', $id_lab)->orderBy('nomor_meja')->get();
        $tambahanRows = InventarisTambahan::where('id_lab', $id_lab)->orderBy('kategori')->orderBy('nama_barang')->get();
        $acNormal = $acRows->where('kondisi', 'normal')->count();
        $acRusak = $acRows->where('kondisi', 'rusak')->count();

        return view('admin.inventaris.index', compact('lab', 'acRows', 'mejaRows', 'tambahanRows', 'acNormal', 'acRusak'));
    }

    /** JSON updates for the inline inventory controls. */
    public function update(Request $request)
    {
        $request->validate([
            'type' => 'required|in:ac,meja,pc_status,tambahan,lab',
            'id' => 'required|integer',
            'field' => 'required|string',
            'value' => 'nullable',
        ]);

        if ($request->type === 'ac') {
            $ac = InventarisAc::findOrFail($request->id);
            abort_unless($request->field === 'kondisi' && in_array($request->value, ['normal', 'rusak'], true), 422, 'Kondisi AC tidak valid.');
            $ac->update(['kondisi' => $request->value]);
        } elseif ($request->type === 'meja') {
            $meja = InventarisMeja::findOrFail($request->id);
            $conditionFields = ['cpu_kondisi' => ['normal', 'rusak', 'instal_ulang'], 'keyboard_kondisi' => ['normal', 'rusak', 'tidak_ada'], 'mouse_kondisi' => ['normal', 'rusak', 'tidak_ada'], 'monitor_kondisi' => ['normal', 'rusak', 'tidak_ada'], 'kursi_kondisi' => ['normal', 'rusak', 'tidak_ada']];
            if (isset($conditionFields[$request->field])) {
                abort_unless(in_array($request->value, $conditionFields[$request->field], true), 422, 'Kondisi tidak valid.');
                $meja->update([$request->field => $request->value]);
            } elseif (in_array($request->field, ['keterangan', 'spesifikasi_pc'], true)) {
                abort_if($request->value !== null && mb_strlen((string) $request->value) > 20000, 422, 'Isi terlalu panjang.');
                $meja->update([$request->field => $request->value]);
            } else {
                abort(422, 'Field inventaris meja tidak valid.');
            }
        } elseif ($request->type === 'pc_status') {
            $pc = InventarisMeja::findOrFail($request->id);
            $fields = ['cpu_status', 'monitor_status', 'keyboard_status', 'mouse_status', 'windows_status'];
            $allowed = ['normal', 'trouble', 'hilang', 'aktivasi_ulang', 'instal_ulang'];
            abort_unless(in_array($request->field, $fields, true) && in_array($request->value, $allowed, true), 422, 'Status inventaris PC tidak valid.');
            $pc->update([$request->field => $request->value]);
        } elseif ($request->type === 'tambahan') {
            $item = InventarisTambahan::findOrFail($request->id);
            if ($request->field === 'jumlah_total' || $request->field === 'jumlah_rusak') {
                abort_unless(is_numeric($request->value) && (int) $request->value >= 0 && (int) $request->value <= 100000, 422, 'Jumlah harus bilangan bulat positif.');
                $newTotal = $request->field === 'jumlah_total' ? (int) $request->value : (int) $item->jumlah_total;
                $newDamaged = $request->field === 'jumlah_rusak' ? (int) $request->value : (int) $item->jumlah_rusak;
                abort_if($newDamaged > $newTotal, 422, 'Jumlah rusak tidak boleh melebihi jumlah total.');
                $item->update([$request->field => (int) $request->value]);
            } elseif ($request->field === 'kondisi') {
                abort_unless(in_array($request->value, ['normal', 'rusak', 'hilang', 'kurang_lengkap'], true), 422, 'Kondisi barang tidak valid.');
                $item->update(['kondisi' => $request->value]);
            } elseif ($request->field === 'keterangan') {
                abort_if($request->value !== null && mb_strlen((string) $request->value) > 2000, 422, 'Keterangan terlalu panjang.');
                $item->update(['keterangan' => $request->value]);
            } else {
                abort(422, 'Field inventaris tambahan tidak valid.');
            }
        } elseif ($request->type === 'lab') {
            $lab = DataLab::findOrFail($request->id);
            abort_unless($request->field === 'jumlah_kursi' && is_numeric($request->value) && (int) $request->value >= 0, 422, 'Jumlah kursi tidak valid.');
            $lab->update(['jumlah_kursi' => (int) $request->value]);
        }

        return response()->json(['success' => true]);
    }

    public function storeTambahan(Request $request, $id_lab)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:120',
            'kategori' => 'required|in:kursi_meja,ac,peralatan,lainnya',
            'jumlah_total' => 'required|integer|min:0|max:100000',
            'jumlah_rusak' => 'required|integer|min:0|lte:jumlah_total',
            'kondisi' => 'required|in:normal,rusak,hilang,kurang_lengkap',
            'keterangan' => 'nullable|string|max:2000',
        ]);
        DataLab::findOrFail($id_lab);
        InventarisTambahan::create(array_merge($validated, ['id_lab' => $id_lab]));
        return redirect()->route('admin.inventaris.index', $id_lab)->with('success', 'Barang inventaris berhasil ditambahkan.');
    }

    public function storePc($id_lab)
    {
        DataLab::findOrFail($id_lab);
        $nextNumber = (int) InventarisMeja::where('id_lab', $id_lab)->max('nomor_meja') + 1;
        InventarisMeja::create([
            'id_lab' => $id_lab,
            'nomor_meja' => $nextNumber,
            'cpu_kondisi' => 'normal',
            'keyboard_kondisi' => 'normal',
            'mouse_kondisi' => 'normal',
            'monitor_kondisi' => 'normal',
            'kursi_kondisi' => 'normal',
            'cpu_status' => 'normal',
            'monitor_status' => 'normal',
            'keyboard_status' => 'normal',
            'mouse_status' => 'normal',
            'windows_status' => 'normal',
        ]);
        return redirect()->route('admin.inventaris.index', $id_lab)->with('success', "PC #{$nextNumber} berhasil ditambahkan.");
    }

    public function hapusPc($id_lab, $id_meja)
    {
        InventarisMeja::where('id_lab', $id_lab)->findOrFail($id_meja)->delete();
        return redirect()->route('admin.inventaris.index', $id_lab)->with('success', 'Unit PC berhasil dihapus.');
    }

    public function hapusTambahan($id_lab, $id_item)
    {
        InventarisTambahan::where('id_lab', $id_lab)->findOrFail($id_item)->delete();
        return redirect()->route('admin.inventaris.index', $id_lab)->with('success', 'Barang inventaris berhasil dihapus.');
    }

    public function tambahAc($id_lab)
    {
        DataLab::findOrFail($id_lab);
        $lastNo = InventarisAc::where('id_lab', $id_lab)->max('nomor_ac') ?? 0;
        InventarisAc::create(['id_lab' => $id_lab, 'nomor_ac' => $lastNo + 1, 'kondisi' => 'normal']);
        return redirect()->route('admin.inventaris.index', $id_lab)->with('success', 'AC berhasil ditambahkan.');
    }

    public function hapusAc($id_lab, $id_ac)
    {
        InventarisAc::where('id_lab', $id_lab)->findOrFail($id_ac)->delete();
        $acs = InventarisAc::where('id_lab', $id_lab)->orderBy('nomor_ac')->get();
        foreach ($acs as $i => $ac) $ac->update(['nomor_ac' => $i + 1]);
        return redirect()->route('admin.inventaris.index', $id_lab)->with('success', 'AC berhasil dihapus.');
    }

    /** Save/replace this lab's monthly snapshot from the main inventory page. */
    public function simpanPeriode(Request $request, $id_lab)
    {
        $data = $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2099',
            'keterangan' => 'nullable|string|max:255',
        ]);
        $lab = DataLab::findOrFail($id_lab);
        DB::transaction(function () use ($data, $id_lab, $lab) {
            $acRows = InventarisAc::where('id_lab', $id_lab)->orderBy('nomor_ac')->get();
            $mejaRows = InventarisMeja::where('id_lab', $id_lab)->orderBy('nomor_meja')->get();
            $extraRows = InventarisTambahan::where('id_lab', $id_lab)->orderBy('id_item')->get();
            $periode = PeriodeInventaris::updateOrCreate(
                ['id_lab' => $id_lab, 'bulan' => $data['bulan'], 'tahun' => $data['tahun']],
                ['jumlah_kursi' => $lab->jumlah_kursi, 'jumlah_meja' => $mejaRows->count(), 'jumlah_ac' => $acRows->count(), 'tanggal_catat' => now(), 'dicatat_oleh' => Auth::guard('admin')->user()->nama ?? 'Admin', 'keterangan' => $data['keterangan'] ?? null]
            );
            $periode->riwayatAc()->delete();
            $periode->riwayatMeja()->delete();
            $periode->riwayatTambahan()->delete();
            foreach ($acRows as $ac) RiwayatAc::create(['id_periode' => $periode->id_periode, 'id_lab' => $id_lab, 'nomor_ac' => $ac->nomor_ac, 'kondisi' => $ac->kondisi]);
            foreach ($mejaRows as $meja) RiwayatMeja::create([
                'id_periode' => $periode->id_periode, 'id_lab' => $id_lab, 'nomor_meja' => $meja->nomor_meja,
                'cpu_kondisi' => $meja->cpu_kondisi, 'keyboard_kondisi' => $meja->keyboard_kondisi, 'mouse_kondisi' => $meja->mouse_kondisi,
                'monitor_kondisi' => $meja->monitor_kondisi, 'kursi_kondisi' => $meja->kursi_kondisi,
                'cpu_status' => $meja->cpu_status, 'monitor_status' => $meja->monitor_status, 'keyboard_status' => $meja->keyboard_status,
                'mouse_status' => $meja->mouse_status, 'windows_status' => $meja->windows_status,
                'keterangan' => $meja->keterangan, 'spesifikasi_pc' => $meja->spesifikasi_pc,
            ]);
            foreach ($extraRows as $item) RiwayatTambahan::create([
                'id_periode' => $periode->id_periode, 'id_lab' => $id_lab, 'nama_barang' => $item->nama_barang,
                'kategori' => $item->kategori, 'jumlah_total' => $item->jumlah_total, 'jumlah_rusak' => $item->jumlah_rusak,
                'kondisi' => $item->kondisi, 'keterangan' => $item->keterangan,
            ]);
        });
        return redirect()->route('admin.inventaris.riwayat', $id_lab)->with('success', 'Snapshot inventaris berhasil disimpan untuk periode yang dipilih.');
    }

    public function riwayat($id_lab)
    {
        $lab = DataLab::findOrFail($id_lab);
        $periode = PeriodeInventaris::where('id_lab', $id_lab)->orderByDesc('tahun')->orderByDesc('bulan')->get();
        return view('admin.inventaris.riwayat', compact('lab', 'periode'));
    }

    public function detailPeriode($id_periode)
    {
        $periode = PeriodeInventaris::with(['lab', 'riwayatAc', 'riwayatMeja', 'riwayatTambahan'])->findOrFail($id_periode);
        return view('admin.inventaris.detail', compact('periode'));
    }

    public function hapusPeriode($id_lab, $id_periode)
    {
        PeriodeInventaris::where('id_lab', $id_lab)->findOrFail($id_periode)->delete();
        return redirect()->route('admin.inventaris.riwayat', $id_lab)->with('success', 'Catatan periode berhasil dihapus.');
    }

    public function export($id_lab)
    {
        $lab = DataLab::findOrFail($id_lab);
        $acRows = InventarisAc::where('id_lab', $id_lab)->orderBy('nomor_ac')->get();
        $mejaRows = InventarisMeja::where('id_lab', $id_lab)->orderBy('nomor_meja')->get();
        $tambahanRows = InventarisTambahan::where('id_lab', $id_lab)->orderBy('kategori')->orderBy('nama_barang')->get();
        return response(view('admin.inventaris.export', compact('lab', 'acRows', 'mejaRows', 'tambahanRows')));
    }

    public function exportPeriode($id_periode)
    {
        $periode = PeriodeInventaris::with(['lab', 'riwayatAc', 'riwayatMeja', 'riwayatTambahan'])->findOrFail($id_periode);
        return response(view('admin.inventaris.export_periode', compact('periode')));
    }
}
