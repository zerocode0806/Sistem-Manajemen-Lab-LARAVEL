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
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'nama_lab',
        'id_barang',
        'nama_barang',
        'jumlah',
        'kursi',
        'status',
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
