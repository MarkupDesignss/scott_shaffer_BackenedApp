<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
        'otp', // optional but recommended
    ];

    /**
     * Attribute casting
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'     => 'datetime',
            'password'              => 'hashed',

            // ✅ BOOLEAN FLAGS
            'is_consent_completed'  => 'boolean',
            'is_interest_completed' => 'boolean',
            'is_profile_completed'  => 'boolean',
            'is_phone_verified'     => 'boolean',
        ];
    }

    /* -------------------------
     | Relationships
     |-------------------------*/

    public function interests()
    {
        return $this->belongsToMany(
            Intrest::class,
            'user_interest',
            'user_id',
            'interest_id'
        )->withTimestamps();
    }

    public function consent()
    {
        return $this->hasOne(UserConsent::class, 'user_id');
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function lists()
    {
        return $this->hasMany(ListModel::class, 'user_id');
    }
}
