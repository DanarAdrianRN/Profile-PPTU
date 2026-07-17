<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranOrangTua extends Model
{
    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | RELASI KE PENDAFTARAN
    |--------------------------------------------------------------------------
    */

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }
}