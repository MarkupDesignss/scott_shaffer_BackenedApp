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
}