<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedItemBookmark extends Model
{
    protected $table = 'featured_item_bookmarks';
    protected $guarded = [];
    public function featuredListItem()
{
    return $this->belongsTo(FeaturedList::class, 'featured_list_item_id');
}

}