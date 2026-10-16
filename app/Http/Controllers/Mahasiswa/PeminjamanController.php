<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\DataPinjam;
use App\Models\DataLab;
use App\Models\DataBarang;
use App\Services\LabReservationAvailability;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function create()
    {
        $labs   = DataLab::where('status', 'availabel')->get();
        $barang = DataBarang::where('stok', '>', 0)->get();
        return view('mahasiswa.peminjaman.create', compact('labs', 'barang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis'       => ['required', 'in:lab,barang'],
            'tanggal'     => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal'],
            'jam_mulai'   => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i'],
            'nama_lab'    => ['nullable', 'required_if:jenis,lab', 'string', 'max:100', 'exists:data_lab,nama_lab'],
            'id_barang'   => ['nullable', 'required_if:jenis,barang', 'exists:data_barang,id_barang'],
            'nama_barang' => ['nullable', 'string', 'max:100'],
            'jumlah'      => ['nullable', 'required_if:jenis,barang', 'integer', 'min:1'],
            'kursi'       => ['nullable', 'required_if:jenis,lab', 'integer', 'min:1'],
        ]);

        if (!$this->isEndAfterStart($validated)) {
            return back()->withInput()->withErrors([
                'jam_selesai' => 'Waktu akhir harus setelah waktu mulai.',
            ]);
        }

        if ($validated['jenis'] === 'lab') {
            if (empty($validated['kursi'])) {
                return back()->withInput()->withErrors([
                    'kursi' => 'Pilih satu kursi yang tersedia.',
                ]);
            }

            if (!LabReservationAvailability::isAvailable(
                $validated['nama_lab'],
                $validated['tanggal'],
                $validated['jam_mulai'],
                $validated['tanggal_selesai'],
                $validated['jam_selesai'],
                (int) $validated['kursi'],
                false,
            )) {
                return back()->withInput()->withErrors([
                    'kursi' => 'Kursi tersebut sudah dipesan atau seluruh lab sedang digunakan pada jadwal yang dipilih.',
                ]);
            }
        }

        $validated['nim']    = Auth::guard('mahasiswa')->user()->nim;
        $validated['kategori_peminjam'] = 'internal';
        $validated['seluruh_lab'] = false;
        $validated['status'] = 'menunggu';

        DataPinjam::create($validated);

        return redirect()->route('mahasiswa.peminjaman.riwayat')
                         ->with('success', 'Peminjaman berhasil diajukan, silahkan pergi ke ICT center untuk menyerahkan kartu identitas.');
    }

    public function riwayat()
    {
        $nim        = Auth::guard('mahasiswa')->user()->nim;
        $peminjaman = DataPinjam::where('nim', $nim)->orderByDesc('id_data')->get();
        return view('mahasiswa.peminjaman.riwayat', compact('peminjaman'));
    }

    public function show(DataPinjam $peminjaman)
    {
        $nim = Auth::guard('mahasiswa')->user()->nim;
        if ($peminjaman->nim !== $nim) {
            abort(403);
        }
        return view('mahasiswa.peminjaman.show', compact('peminjaman'));
    }

    public function arsip()
    {
        $nim        = Auth::guard('mahasiswa')->user()->nim;
        $peminjaman = DataPinjam::where('nim', $nim)
                         ->whereIn('status', ['selesai', 'ditolak'])
                         ->orderByDesc('id_data')
                         ->get();
        return view('mahasiswa.peminjaman.arsip', compact('peminjaman'));
    }

    /**
     * AJAX – return taken seats for a given lab / date / time window.
     * Used by the Seat Picker component.
     *
     * GET /mahasiswa/peminjaman/check-seats?nama_lab=X&tanggal=Y&tanggal_selesai=Y2&jam_mulai=Z&jam_selesai=W
     */
    public function checkSeats(Request $request)
    {
        $request->validate([
            'nama_lab'    => 'required|string',
            'tanggal'     => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
        ]);

        $lab = DataLab::where('nama_lab', $request->nama_lab)->first();

        if (!$lab) {
            return response()->json(['total_kursi' => 0, 'taken' => []]);
        }

        $endDate = $request->input('tanggal_selesai') ?: $request->tanggal;
        if (!$this->isEndAfterStart([
            'tanggal' => $request->tanggal,
            'tanggal_selesai' => $endDate,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ])) {
            return response()->json(['message' => 'Waktu akhir harus setelah waktu mulai.'], 422);
        }

        $taken = LabReservationAvailability::takenSeats(
            $request->nama_lab,
            $request->tanggal,
            $request->jam_mulai,
            $endDate,
            $request->jam_selesai,
            (int) $lab->jumlah_kursi,
        );

        return response()->json([
            'total_kursi' => (int) $lab->jumlah_kursi,
            'taken'       => $taken,
        ]);
    }

    private function isEndAfterStart(array $data): bool
    {
        return Carbon::parse($data['tanggal_selesai'] . ' ' . $data['jam_selesai'])
            ->gt(Carbon::parse($data['tanggal'] . ' ' . $data['jam_mulai']));
    }
}
