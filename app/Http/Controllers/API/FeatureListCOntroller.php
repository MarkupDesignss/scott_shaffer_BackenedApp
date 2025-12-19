<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FeaturedList;
use App\Models\FeaturedListItem;
use Illuminate\Http\Request;

class FeatureListController extends Controller
{
    public function index()
    {
        try {
            $lists = FeaturedList::where('status', 'live')
                ->with(['items.catalogItem'])
                ->orderBy('display_order')
                ->get()
                ->map(function ($list) {
                    return [
                        'featured_list_id' => $list->id,
                        'title' => $list->title,
                        'description' => $list->description,
                        'items' => $list->items->map(function ($item) {
                            return [
                                'id' => $item->catalogItem->id,
                                'name' => $item->catalogItem->name,
                                'description' => $item->catalogItem->description,
                                'image_url' => $item->catalogItem->image_url,
                                'position' => $item->position,
                            ];
                        })->values(),
                    ];
                });

            return response()->json($lists);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Failed to fetch live featured lists',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
