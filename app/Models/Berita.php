<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $fillable = [

        'judul',
        'slug',
        'thumbnail',
        'gambar_detail_1',
        'gambar_detail_2',
        'isi_berita',
        'blockquote',
        'penulis',
        'kategori',
        'status',
        'tanggal_publish',
        'created_by_admin_id',
        'updated_by_admin_id',

    ];

    protected $casts = [

        'tanggal_publish' => 'date',

    ];
}