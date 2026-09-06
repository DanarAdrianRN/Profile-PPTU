<?php

namespace App\Models\Concerns;

use App\Models\Periode;

trait BelongsToPeriode
{
    public function scopeUntukPeriode($query, ?int $periodeId)
    {
        return $periodeId === null
            ? $query->whereRaw('1 = 0')
            : $query->where($this->qualifyColumn('periode_id'), $periodeId);
    }

    public function scopePeriodeAktif($query)
    {
        return $query->whereHas('periode', fn ($periode) => $periode->where('is_active', true));
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
}
