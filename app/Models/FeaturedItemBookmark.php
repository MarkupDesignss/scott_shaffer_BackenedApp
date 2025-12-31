<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedItemBookmark extends Model
{
    protected $table = 'featured_item_bookmarks';
    protected $guarded = [];

    public function item()
    {
        return $this->belongsTo(FeaturedListItem::class, 'featured_list_item_id');
    }
}
