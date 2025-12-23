<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogCategory extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'color', 'status', 'interest_id'];
    protected $table = 'catalog_categories';

    public function items()
    {
        return $this->hasMany(CatalogItem::class, 'category_id', 'id');
    }

    public function interest()
    {
        return $this->belongsTo(Intrest::class, 'interest_id');
    }
}
