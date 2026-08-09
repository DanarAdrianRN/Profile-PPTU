<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminActivityLog extends Model
{
    protected $guarded = [];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function subjek()
    {
        return $this->morphTo(__FUNCTION__, 'subjek_type', 'subjek_id');
    }
}