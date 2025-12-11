<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'age_band',
        'city',
        'dining_budget',
        'has_dogs',
    ];

    /**
     * Relationship: UserProfile belongs to User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}