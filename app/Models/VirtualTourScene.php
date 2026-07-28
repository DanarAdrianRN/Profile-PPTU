<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class VirtualTourScene extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_start_scene' => 'boolean',
        'show_on_landing' => 'boolean',
        'urutan' => 'integer',
        'initial_yaw' => 'decimal:4',
        'initial_pitch' => 'decimal:4',
        'initial_fov' => 'decimal:4',
    ];

    public const THUMBNAIL_ICONS = [
        'building' => 'fa-building',
        'mosque' => 'fa-mosque',
        'road' => 'fa-road',
        'field' => 'fa-futbol',
        'home' => 'fa-house',
    ];

    public function hotspots()
    {
        return $this->hasMany(VirtualTourHotspot::class);
    }

    public function activeHotspots()
    {
        return $this->hasMany(VirtualTourHotspot::class)
            ->where('is_active', true);
    }

    public function targetHotspots()
    {
        return $this->hasMany(VirtualTourHotspot::class, 'target_scene_id');
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail && ! $this->thumbnail_icon) {
            return asset('storage/' . ltrim($this->thumbnail, '/'));
        }

        return asset('assets/galeri1.jpg');
    }

    public function getThumbnailIconAttribute(): ?string
    {
        return self::THUMBNAIL_ICONS[$this->thumbnail] ?? null;
    }

    public function getPanoramaUrlAttribute(): ?string
    {
        if (! $this->panorama) {
            return null;
        }

        return asset('storage/' . ltrim($this->panorama, '/'));
    }

    public function getInitialYawDegreeAttribute(): float
    {
        return round(rad2deg((float) ($this->initial_yaw ?? 0)), 4);
    }

    public function getInitialPitchDegreeAttribute(): float
    {
        return round(rad2deg((float) ($this->initial_pitch ?? 0)), 4);
    }

    public function getInitialFovDegreeAttribute(): float
    {
        return round(rad2deg((float) ($this->initial_fov ?? (pi() / 2))), 4);
    }
}
