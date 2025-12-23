<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CatalogItem;
use App\Models\Intrest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecommenededItemsController extends Controller
{
    public function recommendedList()
    {
        try {
            $user = Auth::user();
            $interestIds = $user->interests()
                ->where('is_active', true)
                ->pluck('interests.id');

            $items = CatalogItem::whereHas('category', function ($q) use ($interestIds) {
                $q->whereIn('interest_id', $interestIds)
                    ->where('status', '1');
            })
                ->where('status', '1')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Recommended items fetched successfully',
                'data'    => $items
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch recommended items',
                'error'   => $th->getMessage()
            ], 500);
        }
    }
}