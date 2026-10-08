<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataPinjam;
use App\Models\DataLab;
use App\Models\DataBarang;
use App\Models\Mahasiswa;
use App\Services\LabReservationAvailability;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    private const EXTERNAL_LAB_DAILY_RATE = 75000;

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

    public function createExternalIndividual()
    {
        $labs = DataLab::where('status', 'availabel')->orderBy('nama_lab')->get();

        return view('admin.peminjaman.external-create', [
            'labs' => $labs,
            'externalType' => 'individual',
        ]);
    }

    public function createExternalInstitution()
    {
        $labs = DataLab::where('status', 'availabel')->orderBy('nama_lab')->get();

        return view('admin.peminjaman.external-create', [
            'labs' => $labs,
            'externalType' => 'institution',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim'         => ['required', 'exists:mahasiswa,nim'],
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

            $lab = DataLab::where('nama_lab', $validated['nama_lab'])->first();
            if (!$lab || (int) $validated['kursi'] > (int) $lab->jumlah_kursi) {
                return back()->withInput()->withErrors([
                    'kursi' => 'Nomor kursi tidak sesuai dengan kapasitas lab.',
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

        $validated['status'] = 'menunggu';
        $validated['kategori_peminjam'] = 'internal';
        $validated['seluruh_lab'] = false;
        DataPinjam::create($validated);

        return redirect()->route('admin.peminjaman.index')
                         ->with('success', 'Peminjaman berhasil dibuat.');
    }

    public function storeExternalIndividual(Request $request)
    {
        $validated = $request->validate([
            'nama_peminjam' => ['required', 'string', 'max:150'],
            'nim_eksternal' => ['required', 'string', 'max:32'],
            'prodi_peminjam' => ['required', 'string', 'max:150'],
            'kontak_peminjam' => ['required', 'string', 'max:50'],
            'tujuan_peminjaman' => ['nullable', 'string', 'max:5000'],
            'nama_lab' => ['required', 'string', 'max:100', 'exists:data_lab,nama_lab'],
            'tanggal' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i'],
            'kursi' => ['required', 'integer', 'min:1'],
        ]);

        if (!$this->isEndAfterStart($validated)) {
            return back()->withInput()->withErrors([
                'jam_selesai' => 'Waktu akhir harus setelah waktu mulai.',
            ]);
        }

        $lab = DataLab::where('nama_lab', $validated['nama_lab'])->first();
        if (!$lab || (int) $validated['kursi'] > (int) $lab->jumlah_kursi) {
            return back()->withInput()->withErrors([
                'kursi' => 'Nomor kursi tidak sesuai dengan kapasitas lab.',
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

        $peminjaman = DataPinjam::create(array_merge($validated, [
            'nim' => null,
            'jenis' => 'lab',
            'kategori_peminjam' => 'eksternal_individu',
            'seluruh_lab' => false,
            'status' => 'disetujui',
            'biaya_peminjaman' => $this->externalBookingFee($validated['tanggal'], $validated['tanggal_selesai']),
            'status_pembayaran' => 'belum_lunas',
            'pembayaran_dikonfirmasi_pada' => null,
        ]));

        return redirect()->route('admin.peminjaman.show', $peminjaman->id_data)
            ->with('success', 'Peminjaman eksternal individu berhasil diajukan.');
    }

    public function storeExternalInstitution(Request $request)
    {
        $validated = $request->validate([
            'nama_peminjam' => ['required', 'string', 'max:150'],
            'nama_instansi' => ['required', 'string', 'max:150'],
            'kontak_peminjam' => ['required', 'string', 'max:50'],
            'tujuan_peminjaman' => ['nullable', 'string', 'max:5000'],
            'nama_lab' => ['required', 'string', 'max:100', 'exists:data_lab,nama_lab'],
            'tanggal' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i'],
        ]);

        if (!$this->isEndAfterStart($validated)) {
            return back()->withInput()->withErrors([
                'jam_selesai' => 'Waktu akhir harus setelah waktu mulai.',
            ]);
        }

        if (!LabReservationAvailability::isAvailable(
            $validated['nama_lab'],
            $validated['tanggal'],
            $validated['jam_mulai'],
            $validated['tanggal_selesai'],
            $validated['jam_selesai'],
            null,
            true,
        )) {
            return back()->withInput()->withErrors([
                'nama_lab' => 'Lab sudah memiliki peminjaman lain pada jadwal tersebut.',
            ]);
        }

        $peminjaman = DataPinjam::create(array_merge($validated, [
            'nim' => null,
            'jenis' => 'lab',
            'kategori_peminjam' => 'eksternal_instansi',
            'kursi' => null,
            'seluruh_lab' => true,
            'status' => 'disetujui',
            'biaya_peminjaman' => $this->externalBookingFee($validated['tanggal'], $validated['tanggal_selesai']),
            'status_pembayaran' => 'belum_lunas',
            'pembayaran_dikonfirmasi_pada' => null,
        ]));

        return redirect()->route('admin.peminjaman.show', $peminjaman->id_data)
            ->with('success', 'Peminjaman eksternal instansi berhasil diajukan.');
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

        if ($peminjaman->jenis === 'lab' && !LabReservationAvailability::isAvailable(
            $peminjaman->nama_lab,
            $peminjaman->tanggal,
            $peminjaman->jam_mulai,
            $peminjaman->tanggal_selesai ?: $peminjaman->tanggal,
            $peminjaman->jam_selesai,
            $peminjaman->kursi ? (int) $peminjaman->kursi : null,
            (bool) $peminjaman->seluruh_lab,
            (int) $peminjaman->id_data,
        )) {
            return redirect()->back()->with('error', 'Jadwal tidak dapat disetujui karena sudah berbenturan dengan peminjaman lain.');
        }

        // Physical item stock is separate from time-based lab availability.
        if ($peminjaman->jenis === 'barang' && $peminjaman->id_barang) {
            $barang = DataBarang::find($peminjaman->id_barang);
            if ($barang && $barang->stok >= ($peminjaman->jumlah ?? 1)) {
                $barang->decrement('stok', $peminjaman->jumlah ?? 1);
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

    public function confirmPayment(DataPinjam $peminjaman)
    {
        if (!$this->isExternalBooking($peminjaman)) {
            return redirect()->route('admin.peminjaman.show', $peminjaman->id_data)
                ->with('error', 'Konfirmasi pembayaran hanya berlaku untuk peminjaman eksternal.');
        }

        if ($peminjaman->status !== 'disetujui') {
            return redirect()->route('admin.peminjaman.show', $peminjaman->id_data)
                ->with('error', 'Pembayaran hanya dapat dikonfirmasi untuk peminjaman yang disetujui.');
        }

        if ($peminjaman->status_pembayaran === 'lunas') {
            return redirect()->route('admin.peminjaman.show', $peminjaman->id_data)
                ->with('error', 'Pembayaran peminjaman ini sudah dikonfirmasi lunas.');
        }

        $fee = (int) $peminjaman->biaya_peminjaman;
        if ($fee <= 0) {
            $fee = $this->externalBookingFee(
                $peminjaman->tanggal,
                $peminjaman->tanggal_selesai ?: $peminjaman->tanggal,
            );
        }

        $peminjaman->update([
            'biaya_peminjaman' => $fee,
            'status_pembayaran' => 'lunas',
            'pembayaran_dikonfirmasi_pada' => now(),
        ]);

        return redirect()->route('admin.peminjaman.show', $peminjaman->id_data)
            ->with('success', 'Pelunasan pembayaran berhasil dikonfirmasi.');
    }

    public function checkout(Request $request, DataPinjam $peminjaman)
    {
        if ($peminjaman->status !== 'disetujui') {
            return redirect()->back()->with('error', 'Hanya peminjaman yang disetujui yang bisa diselesaikan.');
        }

        if ($this->isExternalBooking($peminjaman) && $peminjaman->status_pembayaran !== 'lunas') {
            return redirect()->route('admin.peminjaman.show', $peminjaman->id_data)
                ->with('error', 'Checkout dikunci sampai admin mengonfirmasi pelunasan pembayaran.');
        }

        $validated = $request->validate([
            'tanggal_dikembalikan' => ['required', 'date'],
            'jam_dikembalikan' => ['required', 'date_format:H:i'],
        ]);

        $actualReturn = Carbon::parse($validated['tanggal_dikembalikan'] . ' ' . $validated['jam_dikembalikan']);
        $bookingStart = Carbon::parse($peminjaman->tanggal . ' ' . $peminjaman->jam_mulai);
        if ($actualReturn->lt($bookingStart)) {
            return redirect()->back()->withInput()->withErrors([
                'tanggal_dikembalikan' => 'Waktu pengembalian tidak boleh sebelum waktu mulai peminjaman.',
            ]);
        }

        // Kembalikan stok barang saat selesai. Jadwal lab dikelola lewat rentang waktu.
        if ($peminjaman->jenis === 'barang' && $peminjaman->id_barang) {
            $barang = DataBarang::find($peminjaman->id_barang);
            if ($barang) {
                $barang->increment('stok', $peminjaman->jumlah ?? 1);
            }
        }

        $peminjaman->update([
            'status' => 'selesai',
            'tanggal_dikembalikan' => $validated['tanggal_dikembalikan'],
            'jam_dikembalikan' => $validated['jam_dikembalikan'],
        ]);

        return redirect()->route('admin.peminjaman.riwayat')
                         ->with('success', 'Waktu pengembalian tersimpan dan peminjaman ditandai selesai.');
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
     * GET /admin/peminjaman/check-seats?nama_lab=X&tanggal=Y&tanggal_selesai=Y2&jam_mulai=Z&jam_selesai=W
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

    private function isExternalBooking(DataPinjam $peminjaman): bool
    {
        return in_array($peminjaman->kategori_peminjam, ['eksternal_individu', 'eksternal_instansi'], true);
    }

    private function externalBookingFee(string $startDate, string $endDate): int
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->startOfDay();
        $days = max(1, (int) $start->diffInDays($end) + 1);

        return $days * self::EXTERNAL_LAB_DAILY_RATE;
    }
}
