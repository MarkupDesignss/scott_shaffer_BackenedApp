<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedListItem extends Model
{
    protected $table = 'featured_list_items';
    protected $fillable = [
        'featured_list_id',
        'catalog_item_id',
        'position',
        'status'
    ];

    public function list()
    {
        return $this->belongsTo(FeaturedList::class, 'featured_list_id');
    }

   public function catalogItem()
    {
        return $this->belongsTo(CatalogItem::class, 'catalog_item_id');
    }
    
    public function featuredList()
    {
        return $this->belongsTo(FeaturedList::class, 'featured_list_id');
    }
    
    public function likes()
{
    return $this->hasMany(FeaturedItemLike::class);
}

public function bookmarks()
{
    return $this->hasMany(FeaturedItemBookmark::class);
}

public function shares()
{
    return $this->hasMany(FeaturedItemShare::class);
}

}