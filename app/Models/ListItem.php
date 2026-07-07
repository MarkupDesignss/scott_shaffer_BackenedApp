<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListItem extends Model
{
    protected $fillable = [
        'list_id',
        'catalog_item_id',
        'custom_item_name',
        'custom_text',
        'position',
        'user_positions',
        'position_updated_count',
    ];
    
    protected $casts = [
        'user_positions' => 'array',
    ];

    public function list()
    {
        return $this->belongsTo(ListModel::class);
    }

    public function catalogItem()
    {
        return $this->belongsTo(CatalogItem::class, 'catalog_item_id');
    }
}
