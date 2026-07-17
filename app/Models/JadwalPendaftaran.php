<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPendaftaran extends Model
{
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

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function scopePublish($query)
    {
        return $query->where('is_publish', true);
    }
}
