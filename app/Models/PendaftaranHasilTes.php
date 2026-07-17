<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranHasilTes extends Model
{
    protected $table = 'pendaftaran_hasil_tes';

    protected $guarded = [];

    protected $casts = [
        'baca_tulis_pegon' => 'integer',
        'doa_harian' => 'integer',
        'ubudiyyah' => 'integer',
        'membaca_al_quran' => 'integer',
        'hafalan_surat_pendek' => 'integer',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function getRataRataAttribute(): ?float
    {
        $nilai = collect([
            $this->baca_tulis_pegon,
            $this->doa_harian,
            $this->ubudiyyah,
            $this->membaca_al_quran,
            $this->hafalan_surat_pendek,
        ])->filter(fn ($item) => is_numeric($item));

        if ($nilai->isEmpty()) {
            return null;
        }

        return round($nilai->avg(), 1);
    }

    public function getPredikatAttribute(): string
    {
        if ($this->rata_rata === null) {
            return '-';
        }

        return match (true) {
            $this->rata_rata >= 85 => 'Sangat Baik',
            $this->rata_rata >= 75 => 'Baik',
            $this->rata_rata >= 65 => 'Cukup',
            default => 'Perlu Bimbingan',
        };
    }

    public function nilaiItems(): array
    {
        return [
            'Baca & Tulis Pegon' => $this->baca_tulis_pegon,
            "Do'a Harian" => $this->doa_harian,
            'Ubudiyyah' => $this->ubudiyyah,
            'Membaca Al-Quran' => $this->membaca_al_quran,
            'Hafalan Surat-surat Pendek' => $this->hafalan_surat_pendek,
            'Wawancara' => $this->wawancara,
        ];
    }
}
