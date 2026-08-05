<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataPinjam;
use App\Models\DataLab;
use App\Models\DataBarang;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = DataPinjam::with('mahasiswa')
                         ->where('status', 'menunggu')
                         ->orderByDesc('id_data')
                         ->get();
        return view('admin.peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $labs      = DataLab::where('status', 'availabel')->get();
        $barang    = DataBarang::where('stok', '>', 0)->get();
        $mahasiswa = Mahasiswa::orderBy('nama')->get();
        return view('admin.peminjaman.create', compact('labs', 'barang', 'mahasiswa'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim'         => ['required', 'exists:mahasiswa,nim'],
            'jenis'       => ['required', 'in:lab,barang'],
            'tanggal'     => ['required', 'date'],
            'jam_mulai'   => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i'],
            'nama_lab'    => ['nullable', 'string', 'max:100'],
            'id_barang'   => ['nullable', 'exists:data_barang,id_barang'],
            'nama_barang' => ['nullable', 'string', 'max:100'],
            'jumlah'      => ['nullable', 'integer', 'min:1'],
            'kursi'       => ['nullable', 'integer', 'min:1'],
        ]);

        // Validate kursi not already taken for lab booking
        if ($validated['jenis'] === 'lab' && !empty($validated['kursi'])) {
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
                return back()->withInput()
                    ->withErrors(['kursi' => 'Meja ' . $validated['kursi'] . ' sudah dipesan untuk jadwal yang dipilih.']);
            }
        }

        $validated['status'] = 'menunggu';
        DataPinjam::create($validated);

        return redirect()->route('admin.peminjaman.index')
                         ->with('success', 'Peminjaman berhasil dibuat.');
    }

    public function show(DataPinjam $peminjaman)
    {
        $peminjaman->load('mahasiswa');
        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    public function approve(DataPinjam $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        // Kurangi stok saat disetujui
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

    public function reject(DataPinjam $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $peminjaman->update(['status' => 'ditolak']);

        return redirect()->route('admin.peminjaman.index')
                         ->with('success', 'Peminjaman berhasil ditolak.');
    }

    public function checkout(DataPinjam $peminjaman)
    {
        if ($peminjaman->status !== 'disetujui') {
            return redirect()->back()->with('error', 'Hanya peminjaman yang disetujui yang bisa diselesaikan.');
        }

        // Kembalikan stok saat selesai
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

    public function riwayat()
    {
        $peminjaman = DataPinjam::with('mahasiswa')
                         ->whereIn('status', ['disetujui', 'selesai'])
                         ->orderByDesc('id_data')
                         ->get();
        return view('admin.peminjaman.riwayat', compact('peminjaman'));
    }

    public function arsip()
    {
        $peminjaman = DataPinjam::with('mahasiswa')
                         ->whereIn('status', ['selesai', 'ditolak'])
                         ->orderByDesc('id_data')
                         ->get();
        return view('admin.peminjaman.arsip', compact('peminjaman'));
    }

    /**
     * AJAX – return taken seats for a given lab / date / time window.
     * Used by the Seat Picker component.
     *
     * GET /admin/peminjaman/check-seats?nama_lab=X&tanggal=Y&jam_mulai=Z&jam_selesai=W
     */
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

        // Find taken (booked) seats: same lab, same date, overlapping time, active status
        $taken = DataPinjam::where('nama_lab', $request->nama_lab)
            ->where('jenis', 'lab')
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
