<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagihanSantri extends Model
{
    protected $guarded = [];

    protected $casts = [
        'nominal_awal' => 'integer',
        'potongan_promo' => 'integer',
        'nominal_akhir' => 'integer',
        'total_dibayar' => 'integer',
        'sisa_tagihan' => 'integer',
        'boleh_dicicil' => 'boolean',
        'jumlah_cicilan' => 'integer',
        'cicilan_terbayar' => 'integer',
        'nominal_per_cicilan' => 'integer',
        'jatuh_tempo' => 'date',
        'promo_snapshot' => 'array',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function gelombangPendaftaran()
    {
        return $this->belongsTo(GelombangPendaftaran::class);
    }

    public function details()
    {
        return $this->hasMany(TagihanSantriDetail::class);
    }
}
