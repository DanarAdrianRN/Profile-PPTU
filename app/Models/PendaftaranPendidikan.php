<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranPendidikan extends Model
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