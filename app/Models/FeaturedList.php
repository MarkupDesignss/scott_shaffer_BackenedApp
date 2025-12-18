<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedList extends Model
{
    protected $table = 'featured_lists';
    protected $fillable = [
        'title',
        'category_id',
        'list_size',
        'status',
        'display_order',
        'created_by'
    ];

    public function category()
    {
        return $this->belongsTo(CatalogCategory::class);
    }

    public function items()
    {
        return $this->hasMany(FeaturedListItem::class)
            ->orderBy('position');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
