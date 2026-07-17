<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    protected $guarded = [];

    protected $casts = [
        'nominal' => 'integer',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function tagihanSantriDetail()
    {
        return $this->belongsTo(TagihanSantriDetail::class);
    }
}
