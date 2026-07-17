<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaleriFoto extends Model
{
    protected $fillable = [

        'galeri_id',
        'gambar',

    ];

    public function galeri()
    {
        return $this->belongsTo(Galeri::class);
    }
}