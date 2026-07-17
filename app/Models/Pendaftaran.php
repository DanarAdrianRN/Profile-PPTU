<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $guarded = [];

    protected $casts = [

        'ekstrakurikuler' => 'array',
        'sumber_info' => 'array',
        'tanggal_lahir' => 'date',
        'baca_pegon' => 'boolean',
        'tulis_pegon' => 'boolean',

    ];

    

    /*
    |--------------------------------------------------------------------------
    | RELASI PENDIDIKAN
    |--------------------------------------------------------------------------
    */

    public function pendidikan()
    {
        return $this->hasOne(PendaftaranPendidikan::class);
    }

    public function gelombangPendaftaran()
    {
        return $this->belongsTo(GelombangPendaftaran::class);
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI ORANG TUA
    |--------------------------------------------------------------------------
    */

    public function orangTuas()
    {
        return $this->hasMany(PendaftaranOrangTua::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI DOKUMEN
    |--------------------------------------------------------------------------
    */

    public function dokumens()
    {
        return $this->hasMany(PendaftaranDokumen::class);
    }

    public function hasilTes()
    {
        return $this->hasOne(PendaftaranHasilTes::class);
    }

    public function tagihanSantri()
    {
        return $this->hasOne(TagihanSantri::class);
    }

        /*
    |--------------------------------------------------------------------------
    | RELASI TRANSAKSI
    |--------------------------------------------------------------------------
    */

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
