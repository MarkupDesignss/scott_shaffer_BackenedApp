<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Campaign extends Model
{
    use SoftDeletes;


    protected $fillable = [
        'name',
        'title',
        'subtitle',
        'image_url',
        'cta_text',
        'cta_url',
        'status',
        'requires_consent',
        'starts_at',
        'ends_at'
    ];


    protected $casts = [
        'requires_consent' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];
}
