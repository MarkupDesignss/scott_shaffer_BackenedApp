<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListItem extends Model
{
    protected $fillable = [
        'list_id',
        'catalog_item_id',
        'custom_text',
        'position'
    ];

    public function list()
    {
        return $this->belongsTo(ListModel::class);
    }

    public function catalogItem()
    {
        return $this->belongsTo(CatalogItem::class);
    }
}
