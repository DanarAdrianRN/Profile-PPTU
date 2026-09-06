<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToPeriode;

class GelombangPendaftaran extends Model
{
    use BelongsToPeriode;

    protected $fillable = [
        'nama_gelombang',
        'periode_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'urutan',
        'is_publish',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'urutan' => 'integer',
        'is_publish' => 'boolean',
    ];

    protected $appends = [
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | STATUS OTOMATIS
    |--------------------------------------------------------------------------
    */

    public function getStatusAttribute()
    {
        $today = Carbon::today();

        if ($today->lt(Carbon::parse($this->tanggal_mulai))) {
            return 'akan_datang';
        }

        if ($today->gt(Carbon::parse($this->tanggal_selesai))) {
            return 'ditutup';
        }

        return 'aktif';
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPE GELOMBANG AKTIF
    |--------------------------------------------------------------------------
    */

    public function scopeAktif($query)
    {
        return $query
            ->periodeAktif()
            ->where('is_publish', true)
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now());
    }

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function promos()
    {
        return $this->hasMany(Promo::class);
    }

    public function tagihanSantris()
    {
        return $this->hasMany(TagihanSantri::class);
    }
}
