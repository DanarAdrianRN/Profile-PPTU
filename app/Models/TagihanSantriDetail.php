<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagihanSantriDetail extends Model
{
    protected $guarded = [];

    protected $casts = [
        'nominal_awal' => 'integer',
        'potongan_promo' => 'integer',
        'nominal_akhir' => 'integer',
        'tanggal_bayar' => 'datetime',
    ];

    public function tagihanSantri()
    {
        return $this->belongsTo(TagihanSantri::class);
    }

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    public function transaksiDetails()
    {
        return $this->hasMany(TransaksiDetail::class);
    }
}
