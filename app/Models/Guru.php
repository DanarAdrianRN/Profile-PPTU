<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'gurus';

    protected $fillable = [
        'foto',
        'nama_lengkap',
        'kategori',
        'mapel_bidang',
        'pendidikan',
        'status',
        'alamat',
    ];
}
