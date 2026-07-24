<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataLab extends Model
{
    use HasFactory;

    protected $table = 'data_lab';
    protected $primaryKey = 'id_lab';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'nama_lab',
        'lokasi',
        'stok',
        'jumlah_kursi',
        'jumlah_meja',
        'status',
    ];

    public function barang()
    {
        return $this->hasMany(DataBarang::class, 'id_lab', 'id_lab');
    }

    public function inventarisAc()
    {
        return $this->hasMany(InventarisAc::class, 'id_lab', 'id_lab');
    }

    public function inventarisMeja()
    {
        return $this->hasMany(InventarisMeja::class, 'id_lab', 'id_lab');
    }

    public function periodeInventaris()
    {
        return $this->hasMany(PeriodeInventaris::class, 'id_lab', 'id_lab');
    }
}
