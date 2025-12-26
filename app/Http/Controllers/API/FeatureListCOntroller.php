<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FeaturedList;
use App\Models\FeaturedListItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeatureListController extends Controller
{
    public function index($interestId)
    {
    try {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication token missing or invalid',
            ], 401);
        }

        $userInterestIds = $user->interests()->pluck('interests.id');

        if (!$userInterestIds->contains($interestId)) {
            return response()->json([
                'success' => true,
                'data'    => []
            ]);
        }

        $lists = FeaturedList::where('status', 'live')
            ->whereHas('category', function ($q) use ($interestId) {
                $q->where('interest_id', $interestId);
            })
            ->with([
                'category',
                'items' => function ($q) {
                    $q->orderBy('position');
                },
                'items.catalogItem'
            ])
            ->get()
            ->map(function ($list) {
                return [
                    'featured_list_id' => $list->id,
                    'title'            => $list->title,

                    'image' => $list->image
                        ? url('storage/' . $list->image)
                        : null,

                    'list_size' => $list->list_size,
                    'status'    => $list->status,

                    'category' => [
                        'id'   => $list->category->id,
                        'name' => $list->category->name,
                    ],

                    'items' => $list->items->map(function ($item) {
                        if (!$item->catalogItem) return null;

                        return [
                            'id'       => $item->catalogItem->id,
                            'name'     => $item->catalogItem->name,
                            'icon'     => $item->catalogItem->icon,
                            'position' => $item->position,
                        ];
                    })->filter()->values()
                ];
            });

        return response()->json([
            'success' => true,
            'data'    => $lists
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch featured lists',
            'error'   => $e->getMessage()
        ], 500);
    }
}
}