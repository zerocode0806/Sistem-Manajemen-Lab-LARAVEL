<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Mahasiswa extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nama',
        'nim',
        'no_telepon',
        'alamat',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function pinjaman()
    {
        return $this->hasMany(DataPinjam::class, 'nim', 'nim');
    }
}
