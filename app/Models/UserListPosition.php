<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserListPosition extends Model
{
    use HasFactory;

    protected $table = 'user_list_positions';

    protected $fillable = [
        'user_id',
        'list_id',
        'position',
    ];

    /**
     * List relation
     */
    public function list()
    {
        return $this->belongsTo(ListModel::class, 'list_id');
    }

    /**
     * User relation
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}