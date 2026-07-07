<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CatalogCategory;
use App\Models\CatalogItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    // public function categories()
    // {
    //     try {
    //         $categories = CatalogCategory::where('status', '1')
    //             ->select('id', 'name')
    //             ->orderBy('name')
    //             ->get();

    //         if ($categories->isEmpty()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'No categories found.',
    //                 'data' => []
    //             ], 404);
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Categories fetched successfully.',
    //             'data' => $categories
    //         ], 200);
    //     } catch (\Throwable $e) {
    //         Log::error('Catalog Categories API Error', [
    //             'error' => $e->getMessage()
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Something went wrong while fetching categories.'
    //         ], 500);
    //     }
    // }
    
    public function categories()
    {
        try {
            // Get the authenticated user
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated.',
                    'data' => []
                ], 401);
            }
            
            // Get user's interests using DB query
            $userInterests = DB::table('user_interest')
                ->where('user_id', $user->id)
                ->pluck('interest_id')
                ->toArray();
                
            
            if (empty($userInterests)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No interests found for this user.',
                    'data' => []
                ], 404);
            }
            
            // Fetch categories matching user's interests using DB query with join
            $categories = DB::table('catalog_categories')
                ->where('catalog_categories.status', '1')
                ->whereIn('catalog_categories.interest_id', $userInterests)
                ->select(
                    'catalog_categories.id',
                    'catalog_categories.name',
                    'catalog_categories.slug',
                    'catalog_categories.icon',
                    'catalog_categories.color',
                    'catalog_categories.interest_id'
                )
                ->orderBy('catalog_categories.name')
                ->get();
            
            if ($categories->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No categories found matching your interests.',
                    'data' => []
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Categories fetched successfully based on your interests.',
                'data' => $categories
            ], 200);
            
        } catch (\Throwable $e) {
            Log::error('Catalog Categories API Error', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching categories.'
            ], 500);
        }
    }


    public function items(Request $request, $category_id)
    {
        try {
            // Validate query params
            $validator = Validator::make($request->all(), [
                'search' => 'nullable|string|max:100',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters.',
                    'errors'  => $validator->errors()
                ], 422);
            }
    
            // Fetch items
            $items = CatalogItem::where('status', '1')
                ->with('category:id,name')
                ->where('category_id', $category_id)
                ->when($request->filled('search'), function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                })
                ->select('id', 'category_id', 'name', 'description', 'image_url')
                ->get()
                ->map(function ($item) {
                    $item->image_url = $item->image_url
                        ? url('storage/' . $item->image_url)
                        : null;
    
                    return $item;
                });
    
            if ($items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No items found for this category.',
                    'data'    => []
                ], 404);
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Catalog items fetched successfully.',
                'data'    => $items
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