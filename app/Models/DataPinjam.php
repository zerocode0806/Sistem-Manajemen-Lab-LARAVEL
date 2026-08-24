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

    // app/Models/DataPinjam.php

    protected $fillable = [
        'tipe_pemohon',
        'nim',
        'jenis',
        'tanggal',
        'tanggal_selesai',
        'jam_mulai',
        'jam_selesai',
        'nama_lab',
        'id_barang',
        'nama_barang',
        'jumlah',
        'kursi',
        'status',
        // kolom baru eksternal
        'nama_instansi',
        'pic_instansi',
        'kontak_instansi',
        'durasi_hari',
        'biaya_per_hari',
        'total_biaya',
        'status_pembayaran',
        'alamat_instansi',
        'keperluan',
        'no_surat',
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
