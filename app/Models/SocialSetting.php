<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialSetting extends Model
{
    protected $table = 'social_settings';
    protected $fillable = [
        'title',
        'image',
        'link',
        'status',
    ];
}
