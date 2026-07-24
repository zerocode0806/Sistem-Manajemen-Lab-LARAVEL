<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarisMeja extends Model
{
    use HasFactory;

    protected $table = 'inventaris_meja';
    protected $primaryKey = 'id_meja';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_lab',
        'nomor_meja',
        'cpu_kondisi',
        'keyboard_kondisi',
        'mouse_kondisi',
        'monitor_kondisi',
        'kursi_kondisi',
    ];

    public function lab()
    {
        return $this->belongsTo(DataLab::class, 'id_lab', 'id_lab');
    }
}
