<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLoginToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'token_hash',
        'expires_at',
        'used_at',
        'requested_by_admin_id',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
