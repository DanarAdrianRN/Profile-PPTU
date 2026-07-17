<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VirtualTourHotspot extends Model
{
    protected $guarded = [];

    protected $casts = [
        'yaw' => 'decimal:4',
        'pitch' => 'decimal:4',
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
}
