<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CMS extends Model
{
    use HasFactory;

    protected $table = 'c_m_s';

    protected $fillable = [
        'page',
        'section',
        'type',
        'title',
        'sub_title',
        'logo',
        'image',
        'gallery',
        'icon',
        'video',
        'duration',
        'description',
        'sub_description',
        'main_text',
        'sub_text',
        'button_text',
        'sub_button_text',
        'link_url',
        'email',
        'phone',
        'status',
        'v1',
        'v2',
        'v3',
    ];

    protected $casts = [
        'gallery' => 'array',
        'v1' => 'array',
        'v2' => 'array',
        'v3' => 'array',

    ];
    // public function products()
    // {
    //     return $this->belongsToMany(Product::class);
    // }
}
