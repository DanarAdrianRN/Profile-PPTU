<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VirtualTourHotspot extends Model
{
    protected $guarded = [];

    protected $casts = [
        'yaw' => 'decimal:4',
        'pitch' => 'decimal:4',
        'target_yaw' => 'decimal:4',
        'target_pitch' => 'decimal:4',
        'target_fov' => 'decimal:4',
        'is_active' => 'boolean',
    ];

    public function scene()
    {
        return $this->belongsTo(VirtualTourScene::class, 'virtual_tour_scene_id');
    }

    public function targetScene()
    {
        return $this->belongsTo(VirtualTourScene::class, 'target_scene_id');
    }

    public function getYawDegreeAttribute(): float
    {
        return round(rad2deg((float) ($this->yaw ?? 0)), 4);
    }

    public function getPitchDegreeAttribute(): float
    {
        return round(rad2deg((float) ($this->pitch ?? 0)), 4);
    }

    public function getTargetYawDegreeAttribute(): ?float
    {
        return $this->target_yaw === null ? null : round(rad2deg((float) $this->target_yaw), 4);
    }

    public function getTargetPitchDegreeAttribute(): ?float
    {
        return $this->target_pitch === null ? null : round(rad2deg((float) $this->target_pitch), 4);
    }

    public function getTargetFovDegreeAttribute(): ?float
    {
        return $this->target_fov === null ? null : round(rad2deg((float) $this->target_fov), 4);
    }
}
