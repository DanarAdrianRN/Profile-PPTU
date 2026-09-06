<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToPeriode;

class JadwalPendaftaran extends Model
{
    use BelongsToPeriode;

    protected $fillable = [
        'periode_id',
        'nama_jadwal',
        'tanggal',
        'urutan',
        'is_publish',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'urutan' => 'integer',
        'is_publish' => 'boolean',
    ];

    public function scopePublish($query)
    {
        return $query->where('is_publish', true);
    }
}
