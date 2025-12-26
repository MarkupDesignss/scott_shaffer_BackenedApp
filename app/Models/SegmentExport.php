<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SegmentExport extends Model
{
    protected $fillable = [
        'segment_id',
        'admin_id',
        'file_path',
        'filters_snapshot',
    ];
    protected $table = 'segment_exports';
    protected $casts = [
        'filters_snapshot' => 'array',
    ];

    public function segment()
    {
        return $this->belongsTo(Segment::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
