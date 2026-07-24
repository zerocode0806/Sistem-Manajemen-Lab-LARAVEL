<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataPinjam;
use App\Models\DataLab;

class DashboardController extends Controller
{
    public function index()
    {
        $total_lab       = DataLab::count();
        $total_menunggu  = DataPinjam::where('status', 'menunggu')->count();
        $total_disetujui = DataPinjam::where('status', 'disetujui')->count();
        $total_ditolak   = DataPinjam::where('status', 'ditolak')->count();
        $peminjaman      = DataPinjam::with('mahasiswa')
                              ->where('status', 'menunggu')
                              ->orderByDesc('id_data')
                              ->get();

        return view('admin.dashboard', compact(
            'total_lab', 'total_menunggu', 'total_disetujui', 'total_ditolak', 'peminjaman'
        ));
    }

    /** AJAX – returns only the tbody rows */
    public function refresh()
    {
        $peminjaman = DataPinjam::with('mahasiswa')
                         ->where('status', 'menunggu')
                         ->orderByDesc('id_data')
                         ->get();

        return view('admin.dashboard_refresh', compact('peminjaman'));
    }
}
