<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedItemShare extends Model
{
    protected $table = 'featured_item_shares';
    protected $guarded = [];
        public function list()
    {
        return $this->belongsTo(ListModel::class, 'list_id');
    }
}
