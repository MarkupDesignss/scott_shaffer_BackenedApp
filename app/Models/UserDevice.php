<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    protected $table = 'user_devices';

    protected $fillable = [
        'user_id',
        'device_token',
        'device_type',
    ];
}
