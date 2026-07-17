<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $guarded = [];

    protected $casts = [
        'nilai' => 'integer',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'kuota' => 'integer',
        'terpakai' => 'integer',
        'is_active' => 'boolean',
    ];

    public function gelombangPendaftaran()
    {
        return $this->belongsTo(GelombangPendaftaran::class);
    }

    public function pembayarans()
    {
        return $this->belongsToMany(Pembayaran::class, 'promo_pembayaran')
            ->withTimestamps();
    }

    public function tagihanSantriDetails()
    {
        return $this->hasMany(TagihanSantriDetail::class);
    }
}
