<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\DataPinjam;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $nim             = Auth::guard('mahasiswa')->user()->nim;
        $total_pinjam    = DataPinjam::where('nim', $nim)->count();
        $total_menunggu  = DataPinjam::where('nim', $nim)->where('status', 'menunggu')->count();
        $total_disetujui = DataPinjam::where('nim', $nim)->where('status', 'disetujui')->count();
        $total_ditolak   = DataPinjam::where('nim', $nim)->where('status', 'ditolak')->count();
        $peminjaman      = DataPinjam::where('nim', $nim)
                              ->orderByDesc('id_data')
                              ->limit(5)
                              ->get();

        return view('mahasiswa.dashboard', compact(
            'total_pinjam', 'total_menunggu', 'total_disetujui', 'total_ditolak', 'peminjaman'
        ));
    }
}
