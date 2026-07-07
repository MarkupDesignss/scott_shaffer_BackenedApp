<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CatalogCategory;
use App\Models\CatalogItem;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Intrest;
use App\Models\ListModel;
use App\Models\Category;
use App\Models\FeaturedList;
use App\Models\Item;

class SearchController extends Controller
{
   public function globalSearch(Request $request)
{
    try {
        $validated = $request->validate([
            'query' => 'required|string|min:2',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $query = $validated['query'];
        $limit = $validated['limit'] ?? 10;

        /* -------------------------
         | Interests (with image)
         |--------------------------*/
        $interests = Intrest::where('is_active', true)
            ->where('name', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->get(['id', 'name', 'icon'])
            ->map(function ($interest) {
                return [
                    'id'   => $interest->id,
                    'name' => $interest->name,
                    'icon' => $interest->icon
                        ? asset('storage/' . $interest->icon)
                        : null,
                ];
            });

        /* -------------------------
         | Categories (NO image)
         |--------------------------*/
        $categories = CatalogCategory::where('status', true)
            ->where('name', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->get(['id', 'name']);

        /* -------------------------
         | Items (with image)
         |--------------------------*/
        $items = CatalogItem::where('status', true)
            ->where('name', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->get(['id', 'name', 'image_url'])
            ->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'name'  => $item->name,
                    'image' => $item->image_url
                        ? asset('storage/' . $item->image_url)
                        : null,
                ];
            });
            
            /* -------------------------
            | Featured Lists
            |--------------------------*/
            // $featuredLists = FeaturedList::with('items')->where('status', true)
            //     ->where('title', 'LIKE', "%{$query}%")
            //     ->limit($limit)
            //     ->get(['id', 'title', 'image', 'category_id'])
            //     ->map(function ($list) {
            //         return [
            //             'id'          => $featuredLists->id,
            //             'title'       => $featuredLists->title,
            //             'category_id' => $featuredLists->category_id,
                        
            //         ];
            //     });
            
            /* -------------------------
            | User Lists
            |--------------------------*/
            $lists = ListModel::with('items')->where('status', true)
                ->whereNull('deleted_at')
                ->where('title', 'LIKE', "%{$query}%")
                ->limit($limit)
                ->get([
                    'id',
                    'title',
                    'category_id',
                    'sub_category_id',
                    'list_size',
                    'visibility'
                ]);
                
        return response()->json([
            'success' => true,
            'query'   => $query,
            'data'    => [
                'interests'      => $interests,
                'categories'     => $categories,
                'items'          => $items,
                // 'featured_lists' => $featuredLists,
                'lists'          => $lists,
            ],
        ], 200);

        // return response()->json([
        //     'success' => true,
        //     'query'   => $query,
        //     'data'    => [
        //         'interests'  => $interests,
        //         'categories' => $categories,
        //         'items'      => $items,
        //     ],
        // ], 200);

    } catch (\Throwable $th) {
        return response()->json([
            'success' => false,
            'message' => 'Global search failed',
            'error'   => $th->getMessage(),
        ], 500);
    }
}

}
