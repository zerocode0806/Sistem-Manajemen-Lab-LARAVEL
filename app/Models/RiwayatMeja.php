<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatMeja extends Model
{
    use HasFactory;

    protected $table = 'riwayat_meja';
    protected $primaryKey = 'id_riwayat_meja';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_periode',
        'id_lab',
        'nomor_meja',
        'cpu_kondisi',
        'keyboard_kondisi',
        'mouse_kondisi',
        'monitor_kondisi',
        'kursi_kondisi',
    ];

    public function periode()
    {
        return $this->belongsTo(PeriodeInventaris::class, 'id_periode', 'id_periode');
    }
}
