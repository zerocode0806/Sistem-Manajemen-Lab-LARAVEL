<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatTambahan extends Model
{
    protected $table = 'riwayat_tambahan';
    protected $primaryKey = 'id_riwayat_tambahan';
    protected $fillable = ['id_periode', 'id_lab', 'nama_barang', 'kategori', 'jumlah_total', 'jumlah_rusak', 'kondisi', 'keterangan'];

    public function periode()
    {
        return $this->belongsTo(PeriodeInventaris::class, 'id_periode', 'id_periode');
    }
}
