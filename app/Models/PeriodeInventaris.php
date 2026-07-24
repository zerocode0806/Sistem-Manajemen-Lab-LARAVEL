<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeInventaris extends Model
{
    use HasFactory;

    protected $table = 'periode_inventaris';
    protected $primaryKey = 'id_periode';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_lab',
        'bulan',
        'tahun',
        'jumlah_kursi',
        'jumlah_meja',
        'jumlah_ac',
        'tanggal_catat',
        'dicatat_oleh',
        'keterangan',
    ];

    public function lab()
    {
        return $this->belongsTo(DataLab::class, 'id_lab', 'id_lab');
    }

    public function riwayatAc()
    {
        return $this->hasMany(RiwayatAc::class, 'id_periode', 'id_periode');
    }

    public function riwayatMeja()
    {
        return $this->hasMany(RiwayatMeja::class, 'id_periode', 'id_periode');
    }
}
