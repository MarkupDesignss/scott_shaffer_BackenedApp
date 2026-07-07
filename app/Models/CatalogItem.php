<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
class CatalogItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'sub_category_id',
        'name',
        'description',
        'image_url',
        'status'
    ];
    protected $table = 'catalog_items';
    
     public function getImageUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }

        return URL::to('/') . '/storage/' . ltrim($value, '/');
    }

    public function category()
    {
        return $this->belongsTo(CatalogCategory::class,'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(CatalogTag::class);
    }
        public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }
}