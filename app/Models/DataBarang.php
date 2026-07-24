<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataBarang extends Model
{
    use HasFactory;

    protected $table = 'data_barang';
    protected $primaryKey = 'id_barang';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_lab',
        'kode_barang',
        'nama_barang',
        'kategori',
        'stok',
        'kondisi',
        'status',
        'keterangan',
    ];

    public function lab()
    {
        return $this->belongsTo(DataLab::class, 'id_lab', 'id_lab');
    }

    public function pinjaman()
    {
        return $this->hasMany(DataPinjam::class, 'id_barang', 'id_barang');
    }
}
