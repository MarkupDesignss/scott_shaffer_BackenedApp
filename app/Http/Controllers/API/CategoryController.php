<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CatalogCategory;
use App\Models\CatalogItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function categories()
    {
        try {
            $categories = CatalogCategory::where('status', '1')
                ->select('id', 'name', 'slug', 'icon', 'color')
                ->orderBy('name')
                ->get();

            if ($categories->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No categories found.',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Categories fetched successfully.',
                'data' => $categories
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Catalog Categories API Error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching categories.'
            ], 500);
        }
    }


    public function items(Request $request)
    {
        try {
            // ✅ Validate query params
            $validator = Validator::make($request->all(), [
                'category_id' => 'nullable|exists:catalog_categories,id',
                'search'      => 'nullable|string|max:100',
                'page'        => 'nullable|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters.',
                    'errors'  => $validator->errors()
                ], 422);
            }

            $items = CatalogItem::where('status', '1')
                ->with('category:id,name')
                ->when($request->category_id, function ($q) use ($request) {
                    $q->where('category_id', $request->category_id);
                })
                ->when($request->search, function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                })
                ->select('id', 'category_id', 'name', 'description', 'image_url')
                ->paginate(20);

            if ($items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No items found.',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Catalog items fetched successfully.',
                'data' => $items
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Catalog Items API Error', [
                'request' => $request->all(),
                'error'   => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching catalog items.'
            ], 500);
        }
    }

    // public function itemsByCategory($categoryId)
    // {
    //     try {
    //         $category = CatalogCategory::where('id', $categoryId)
    //             ->where('status', '1')
    //             ->first();

    //         if (!$category) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Category not found.'
    //             ], 404);
    //         }

    //         $items = CatalogItem::where('status', '1')
    //             ->where('category_id', $categoryId)
    //             ->select('id', 'name', 'description', 'image_url')
    //             ->orderBy('name')
    //             ->get();

    //         if ($items->isEmpty()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'No items found for this category.',
    //                 'data' => []
    //             ], 404);
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Category items fetched successfully.',
    //             'data' => $items
    //         ], 200);
    //     } catch (\Throwable $e) {
    //         Log::error('Category Items API Error', [
    //             'category_id' => $categoryId,
    //             'error' => $e->getMessage()
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Unable to fetch items.',
    //             'reason'    => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function categoriesByInterest($interestId)
    {
        try {
            if (!is_numeric($interestId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid interest id.'
                ], 422);
            }

            $categories = CatalogCategory::where('status', '1')
                ->whereHas('interest', function ($q) use ($interestId) {
                    $q->where('interest_id', $interestId);
                })
                ->select('id', 'name', 'slug', 'icon', 'color')
                ->orderBy('name')
                ->get();

            if ($categories->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No categories found for this interest.',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Categories fetched successfully.',
                'data' => $categories
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Categories By Interest API Error', [
                'interest_id' => $interestId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'reason'    => $e->getMessage()
            ], 500);
        }
    }
}
