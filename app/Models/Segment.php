<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Segment extends Model
{
    use SoftDeletes;
    protected $table = 'segments';
    protected $fillable = [
        'name',
        'filters',
        'estimated_users',
    ];

    protected $casts = [
        'filters' => 'array',
    ];

    public function exports()
    {
        return $this->hasMany(SegmentExport::class);
    }

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaign_segment');
    }
}
