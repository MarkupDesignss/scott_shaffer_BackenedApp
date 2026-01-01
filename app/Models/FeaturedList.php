<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedList extends Model
{
    protected $table = 'featured_lists';
    protected $fillable = [
        'title',
        'image',
        'category_id',
        'list_size',
        'status',
        'display_order',
        'created_by'
    ];

    public function category()
    {
        return $this->belongsTo(CatalogCategory::class, 'category_id');
    }

    public function items()
    {
        return $this->hasMany(FeaturedListItem::class, 'featured_list_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
    public function likes()
    {
        return $this->hasMany(
            FeaturedItemLike::class,
            'featured_list_item_id', // FK column (same table)
            'id'                      // FeaturedList id
        );
    }

    // Bookmarks (list level)
    public function bookmarks()
    {
        return $this->hasMany(
            FeaturedItemBookmark::class,
            'featured_list_item_id',
            'id'
        );
    }

    // Shares (list level)
    public function shares()
    {
        return $this->hasMany(
            FeaturedItemShare::class,
            'featured_list_item_id',
            'id'
        );
    }
}