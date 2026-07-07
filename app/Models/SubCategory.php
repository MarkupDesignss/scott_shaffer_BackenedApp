<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories';
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(CatalogCategory::class, 'category_id');
    }

    public function items()
    {
        return $this->hasMany(CatalogItem::class, 'sub_category_id');
    }
}
