<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserConsent extends Model
{
    protected $table = 'user_consents';

    protected $guarded = [];

    protected $casts = [
        'accepted_terms_privacy' => 'boolean',
        'campaign_marketing'     => 'boolean',
        'accepted_at'            => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
