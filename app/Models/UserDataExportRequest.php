<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDataExportRequest extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'file_path',
        'requested_at',
        'completed_at',
        'expires_at',
        'error',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'completed_at' => 'datetime',
        'expires_at'   => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
