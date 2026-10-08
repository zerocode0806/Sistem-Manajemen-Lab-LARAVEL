<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPinjam extends Model
{
    use HasFactory;

    protected $table = 'data_pinjam';
    protected $primaryKey = 'id_data';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'nim',
        'jenis',
        'kategori_peminjam',
        'nama_peminjam',
        'nim_eksternal',
        'prodi_peminjam',
        'kontak_peminjam',
        'nama_instansi',
        'tujuan_peminjaman',
        'tanggal',
        'tanggal_selesai',
        'tanggal_dikembalikan',
        'jam_mulai',
        'jam_selesai',
        'jam_dikembalikan',
        'nama_lab',
        'id_barang',
        'nama_barang',
        'jumlah',
        'kursi',
        'seluruh_lab',
        'status',
        'biaya_peminjaman',
        'status_pembayaran',
        'pembayaran_dikonfirmasi_pada',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function barang()
    {
        return $this->belongsTo(DataBarang::class, 'id_barang', 'id_barang');
    }
}
