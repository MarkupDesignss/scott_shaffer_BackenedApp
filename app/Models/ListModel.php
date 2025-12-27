<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListModel extends Model
{
    use SoftDeletes;

    protected $table = 'lists';

    protected $fillable = [
        'user_id',
        'title',
        'category_id',
        'list_size',
        'is_group',
        'status',
        'visibility',
        'cloned_from_id'
    ];

    protected $casts = [
    'is_group' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(ListItem::class, 'list_id')
            ->orderBy('position');
    }

    public function category()
    {
        return $this->belongsTo(CatalogCategory::class, 'category_id');
    }

    public function members() 
    {
       return $this->hasMany(ListMember::class, 'list_id');
    }
    
    public function clonedFrom()
    {
        return $this->belongsTo(ListModel::class, 'cloned_from_id');
    }
}