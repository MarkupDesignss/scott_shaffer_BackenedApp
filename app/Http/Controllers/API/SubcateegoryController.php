<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\CatalogItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;


class SubcateegoryController extends Controller
{
    public function subCategories($categoryId)
    {
        try {
            if (!is_numeric($categoryId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid category id',
                    'data'    => []
                ], 422);
            }

            $subCategories = SubCategory::where('category_id', $categoryId)
                ->where('status', 1)
                ->select('id', 'name', 'slug')
                ->orderBy('name')
                ->get();

            if ($subCategories->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No sub-categories found',
                    'data'    => []
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'Sub-categories fetched successfully',
                'data'    => $subCategories
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching sub-categories',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function items($subCategoryId)
{
    if (!is_numeric($subCategoryId)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid sub-category id',
            'data' => []
        ], 422);
    }

    $items = CatalogItem::where('sub_category_id', $subCategoryId)
        ->where('status', 1)
        ->latest()
        ->get();

    if ($items->isEmpty()) {
        return response()->json([
            'success' => true,
            'message' => 'No items found for this sub-category',
            'data' => []
        ]);
    }

    $items->each(function ($item) {
        $item->image_url = $item->image_url
            ? asset('storage/' . $item->image_url)
            : null;
    });

    return response()->json([
        'success' => true,
        'message' => 'Items fetched successfully',
        'data' => $items
    ]);
}

}