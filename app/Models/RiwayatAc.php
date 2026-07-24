<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatAc extends Model
{
    use HasFactory;

    protected $table = 'riwayat_ac';
    protected $primaryKey = 'id_riwayat_ac';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_periode',
        'id_lab',
        'nomor_ac',
        'kondisi',
    ];

    public function periode()
    {
        return $this->belongsTo(PeriodeInventaris::class, 'id_periode', 'id_periode');
    }
}
