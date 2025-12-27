<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListMember extends Model
{
    use HasFactory;

    protected $table = 'list_members';

    protected $fillable = [
        'list_id',
        'user_id',
        'status',   // invited / accepted
    ];

    /**
     * The list that this membership belongs to
     */
    public function list()
    {
        return $this->belongsTo(ListModel::class, 'list_id');
    }

    /**
     * The user who is a member of the list
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}