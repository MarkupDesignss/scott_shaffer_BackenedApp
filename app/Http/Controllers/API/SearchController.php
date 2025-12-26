<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CatalogCategory;
use App\Models\CatalogItem;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Intrest;
use App\Models\Category;
use App\Models\Item;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|min:2',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $query = $validated['query'];
        $limit = $validated['limit'] ?? 10;

        $results = [];

        /* -------------------------
         | Interests (Food, Travel etc.)
         |--------------------------*/
        $results['interests'] = Intrest::where('is_active', true)
            ->where('name', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->get(['id', 'name', 'icon']);

        /* -------------------------
         | Categories (Pizza, Italian, Cafe etc.)
         |--------------------------*/
        $results['categories'] = CatalogCategory::where('status', true)
            ->where('name', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->get(['id', 'name']);

        /* -------------------------
         | Items (Cheese Pizza, Burger etc.)
         |--------------------------*/
        $results['items'] = CatalogItem::where('status', true)
            ->where('name', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->get(['id', 'name']);


        return response()->json([
            'success' => true,
            'query'   => $query,
            'data'    => $results,
        ], 200);
    }
}
