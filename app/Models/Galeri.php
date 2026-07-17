<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    protected $fillable = [
        'judul',
        'thumbnail',
        'tanggal_kegiatan',
        'status',
        'deskripsi',
        'created_by_admin_id',
        'updated_by_admin_id',
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
    ];

    public function fotos()
    {
        return $this->hasMany(GaleriFoto::class, 'galeri_id');
    }
}