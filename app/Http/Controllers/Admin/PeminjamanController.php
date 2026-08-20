<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataPinjam;
use App\Models\DataLab;
use App\Models\DataBarang;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    // =========================================================
    // INDEX — hanya menunggu
    // =========================================================
    public function index()
    {
        $peminjaman = DataPinjam::with('mahasiswa')
            ->where('status', 'menunggu')
            ->orderByDesc('id_data')
            ->get();

        return view('admin.peminjaman.index', compact('peminjaman'));
    }

    // =========================================================
    // CREATE
    // =========================================================
    public function create()
    {
        $labs      = DataLab::where('status', 'availabel')->get();
        $barang    = DataBarang::where('stok', '>', 0)->get();
        $mahasiswa = Mahasiswa::orderBy('nama')->get();

        return view('admin.peminjaman.create', compact('labs', 'barang', 'mahasiswa'));
    }

    // =========================================================
    // STORE
    // =========================================================
    public function store(Request $request)
    {
        $tipe = $request->input('tipe_pemohon', 'internal');

        // ── Validasi bercabang ────────────────────────────────
        $rules = [
            'tipe_pemohon' => ['required', 'in:internal,eksternal'],
            'tanggal'      => ['required', 'date'],
            'jam_mulai'    => ['required', 'date_format:H:i'],
            'jam_selesai'  => ['required', 'date_format:H:i', 'after:jam_mulai'],
        ];

        if ($tipe === 'internal') {
            $rules['nim']        = ['required', 'exists:mahasiswa,nim'];
            $rules['jenis']      = ['required', 'in:lab,barang'];
            $rules['nama_lab']   = ['nullable', 'string', 'max:100'];
            $rules['id_barang']  = ['nullable', 'exists:data_barang,id_barang'];
            $rules['nama_barang']= ['nullable', 'string', 'max:100'];
            $rules['jumlah']     = ['nullable', 'integer', 'min:1'];
            $rules['kursi']      = ['nullable', 'integer', 'min:1'];
        } else {
            $rules['nama_instansi']   = ['required', 'string', 'max:255'];
            $rules['pic_instansi']    = ['required', 'string', 'max:255'];
            $rules['kontak_instansi'] = ['required', 'string', 'max:50'];
            $rules['nama_lab']        = ['required', 'string', 'max:100'];
            $rules['tanggal_selesai'] = ['required', 'date', 'after_or_equal:tanggal'];
        }

        $validated = $request->validate($rules);

        // ── Cek konflik kursi (hanya internal + jenis lab) ───
        if (
            $tipe === 'internal' &&
            ($validated['jenis'] ?? '') === 'lab' &&
            !empty($validated['kursi'])
        ) {
            $conflict = DataPinjam::where('nama_lab', $validated['nama_lab'])
                ->where('jenis', 'lab')
                ->where('tanggal', $validated['tanggal'])
                ->whereIn('status', ['menunggu', 'disetujui'])
                ->where('kursi', $validated['kursi'])
                ->where(function ($q) use ($validated) {
                    $q->where('jam_mulai', '<', $validated['jam_selesai'])
                      ->where('jam_selesai', '>', $validated['jam_mulai']);
                })
                ->exists();

            if ($conflict) {
                return back()->withInput()->withErrors([
                    'kursi' => 'Meja ' . $validated['kursi'] . ' sudah dipesan untuk jadwal yang dipilih.',
                ]);
            }
        }

        // ── Hitung biaya eksternal ────────────────────────────
        $durasiHari = null;
        $totalBiaya = null;

        if ($tipe === 'eksternal') {
            $mulai      = Carbon::parse($validated['tanggal']);
            $selesai    = Carbon::parse($validated['tanggal_selesai']);
            $durasiHari = max(1, $mulai->diffInDays($selesai) + 1);
            $totalBiaya = $durasiHari * 75000;
        }

        // ── Susun data yang akan disimpan ─────────────────────
        $data = [
            'tipe_pemohon'     => $tipe,
            'tanggal'          => $validated['tanggal'],
            'jam_mulai'        => $validated['jam_mulai'],
            'jam_selesai'      => $validated['jam_selesai'],
        ];

        if ($tipe === 'internal') {
            $data['nim']         = $validated['nim'];
            $data['jenis']       = $validated['jenis'];
            $data['nama_lab']    = $validated['nama_lab']    ?? null;
            $data['id_barang']   = $validated['id_barang']   ?? null;
            $data['nama_barang'] = $validated['nama_barang'] ?? null;
            $data['jumlah']      = $validated['jumlah']      ?? null;
            $data['kursi']       = $validated['kursi']       ?? null;
            $data['status']      = 'menunggu';
        } else {
            $data['nim']              = null;
            $data['jenis']            = 'lab';
            $data['nama_lab']         = $validated['nama_lab'];
            $data['tanggal_selesai']  = $validated['tanggal_selesai'];
            $data['nama_instansi']    = $validated['nama_instansi'];
            $data['pic_instansi']     = $validated['pic_instansi'];
            $data['kontak_instansi']  = $validated['kontak_instansi'];
            $data['durasi_hari']      = $durasiHari;
            $data['biaya_per_hari']   = 75000;
            $data['total_biaya']      = $totalBiaya;
            $data['status_pembayaran']= 'belum_bayar';
            // Eksternal langsung disetujui karena admin yang input
            $data['status']           = 'disetujui';
        }

        DataPinjam::create($data);

        $successMsg = $tipe === 'eksternal'
            ? 'Peminjaman eksternal berhasil dibuat. Total biaya: Rp ' . number_format($totalBiaya, 0, ',', '.')
            : 'Peminjaman berhasil dibuat.';

        return redirect()->route('admin.peminjaman.index')
                         ->with('success', $successMsg);
    }

    // =========================================================
    // SHOW
    // =========================================================
    public function show(DataPinjam $peminjaman)
    {
        $peminjaman->load('mahasiswa');

        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    // =========================================================
    // APPROVE
    // =========================================================
    public function approve(DataPinjam $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Status tidak valid.');
        }

        if ($peminjaman->jenis === 'barang' && $peminjaman->id_barang) {
            $barang = DataBarang::find($peminjaman->id_barang);
            if ($barang && $barang->stok >= ($peminjaman->jumlah ?? 1)) {
                $barang->decrement('stok', $peminjaman->jumlah ?? 1);
            }
        } elseif ($peminjaman->jenis === 'lab') {
            $lab = DataLab::where('nama_lab', $peminjaman->nama_lab)->first();
            if ($lab && $lab->stok > 0) {
                $lab->decrement('stok');
            }
        }

        $peminjaman->update(['status' => 'disetujui']);

        return redirect()->route('admin.peminjaman.index')
                         ->with('success', 'Peminjaman berhasil disetujui.');
    }

    // =========================================================
    // REJECT
    // =========================================================
    public function reject(DataPinjam $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Status tidak valid.');
        }

        $peminjaman->update(['status' => 'ditolak']);

        return redirect()->route('admin.peminjaman.index')
                         ->with('success', 'Peminjaman berhasil ditolak.');
    }

    // =========================================================
    // CHECKOUT
    // =========================================================
    public function checkout(DataPinjam $peminjaman)
    {
        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Hanya peminjaman yang disetujui yang bisa diselesaikan.');
        }

        if ($peminjaman->jenis === 'barang' && $peminjaman->id_barang) {
            $barang = DataBarang::find($peminjaman->id_barang);
            if ($barang) {
                $barang->increment('stok', $peminjaman->jumlah ?? 1);
            }
        } elseif ($peminjaman->jenis === 'lab') {
            $lab = DataLab::where('nama_lab', $peminjaman->nama_lab)->first();
            if ($lab) {
                $lab->increment('stok');
            }
        }

        $peminjaman->update(['status' => 'selesai']);

        return redirect()->route('admin.peminjaman.riwayat')
                         ->with('success', 'Peminjaman selesai dan stok telah dikembalikan.');
    }

    // =========================================================
    // UPDATE PEMBAYARAN EKSTERNAL
    // =========================================================
    public function updatePembayaran(DataPinjam $peminjaman)
    {
        abort_if($peminjaman->tipe_pemohon !== 'eksternal', 403);

        $peminjaman->update(['status_pembayaran' => 'lunas']);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    // =========================================================
    // RIWAYAT
    // =========================================================
    public function riwayat()
    {
        $peminjaman = DataPinjam::with('mahasiswa')
            ->whereIn('status', ['disetujui'])
            ->orderByDesc('id_data')
            ->get();

        return view('admin.peminjaman.riwayat', compact('peminjaman'));
    }

    // =========================================================
    // ARSIP
    // =========================================================
    public function arsip()
    {
        $peminjaman = DataPinjam::with('mahasiswa')
            ->whereIn('status', ['selesai', 'ditolak'])
            ->orderByDesc('id_data')
            ->get();

        return view('admin.peminjaman.arsip', compact('peminjaman'));
    }

    // =========================================================
    // CHECK SEATS — AJAX
    // =========================================================
    public function checkSeats(Request $request)
    {
        $request->validate([
            'nama_lab'    => 'required|string',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
        ]);

        $lab = DataLab::where('nama_lab', $request->nama_lab)->first();

        if (!$lab) {
            return response()->json(['total_kursi' => 0, 'taken' => []]);
        }

        $taken = DataPinjam::where('nama_lab', $request->nama_lab)
            ->where('jenis', 'lab')
            ->where('tipe_pemohon', 'internal') // eksternal ambil 1 ruangan penuh, tidak pakai kursi
            ->where('tanggal', $request->tanggal)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->whereNotNull('kursi')
            ->where(function ($q) use ($request) {
                $q->where('jam_mulai', '<', $request->jam_selesai)
                  ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->pluck('kursi')
            ->map(fn($v) => (int) $v)
            ->values()
            ->toArray();

        return response()->json([
            'total_kursi' => (int) $lab->jumlah_kursi,
            'taken'       => $taken,
        ]);
    }
}