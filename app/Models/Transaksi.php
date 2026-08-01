<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [

        'response_midtrans' => 'array',

        'tanggal_bayar' => 'datetime',

        'expired_at' => 'datetime',

    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI KE PENDAFTARAN
    |--------------------------------------------------------------------------
    */

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI KE MASTER PEMBAYARAN
    |--------------------------------------------------------------------------
    */

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI KE ADMIN PENCATAT (khusus transaksi manual/tunai)
    |--------------------------------------------------------------------------
    */

    public function dicatatOlehAdmin()
    {
        return $this->belongsTo(Admin::class, 'dicatat_oleh_admin_id');
    }
}