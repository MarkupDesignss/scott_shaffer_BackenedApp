<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatalogItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'image_url',
        'status'
    ];
    protected $table = 'catalog_items';

    public function category()
    {
        return $this->belongsTo(CatalogCategory::class, 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(CatalogTag::class);
    }
}
