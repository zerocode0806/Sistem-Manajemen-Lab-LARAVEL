<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarisTambahan extends Model
{
    protected $table = 'inventaris_tambahan';
    protected $primaryKey = 'id_item';
    protected $fillable = ['id_lab', 'nama_barang', 'kategori', 'jumlah_total', 'jumlah_rusak', 'kondisi', 'keterangan'];

    public function lab()
    {
        return $this->belongsTo(DataLab::class, 'id_lab', 'id_lab');
    }
}
