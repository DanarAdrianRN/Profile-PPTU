<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteVisit extends Model
{
    protected $fillable = [
        'session_id',
        'ip_address',
        'path',
        'user_agent',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'date',
    ];
}
