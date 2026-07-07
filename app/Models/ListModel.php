<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListModel extends Model
{

    protected $table = 'lists';

    protected $guarded = [];
    // protected $fillable = [
    //     'user_id',
    //     'title',
    //     'category_id',
    //     'sub_category_id',
    //     'list_size',
    //     'is_group',
    //     'status',
    //     'visibility',
    //     'cloned_from_id'
    // ];

    protected $casts = [
    'is_group' => 'boolean',
    'sub_category_id' => 'array',
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
        public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
        public function likes()
    {
        return $this->hasMany(FeaturedItemLike::class, 'list_id');
    }

    public function shares()
    {
        return $this->hasMany(FeaturedItemShare::class, 'list_id');
    }
        public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }
}