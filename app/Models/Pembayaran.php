<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = [

        'jenjang',
        'kategori',
        'nama_pembayaran',
        'nominal',
        'is_active',
    ];

    protected $casts = [
        'nominal' => 'integer',
        'is_active' => 'boolean',
    ];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function promos()
    {
        return $this->belongsToMany(Promo::class, 'promo_pembayaran')
            ->withTimestamps();
    }

    public function tagihanSantriDetails()
    {
        return $this->hasMany(TagihanSantriDetail::class);
    }
}
