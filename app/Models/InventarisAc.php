<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarisAc extends Model
{
    use HasFactory;

    protected $table = 'inventaris_ac';
    protected $primaryKey = 'id_ac';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_lab',
        'nomor_ac',
        'kondisi',
    ];

    public function lab()
    {
        return $this->belongsTo(DataLab::class, 'id_lab', 'id_lab');
    }
}
