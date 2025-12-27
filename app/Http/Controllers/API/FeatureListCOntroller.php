<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FeaturedList;
use App\Models\FeaturedListItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeatureListController extends Controller
{
public function index(Request $request)
{
    try {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication token missing or invalid',
            ], 401);
        }

        $interestId = $request->query('interest_id');
        $userInterestIds = $user->interests()->pluck('interests.id')->toArray();

        // Determine which interests to fetch
        if ($interestId) {
            if (!in_array($interestId, $userInterestIds)) {
                return response()->json(['success' => true, 'data' => []]);
            }
            $interestIdsToFetch = [$interestId];
        } else {
            $interestIdsToFetch = $userInterestIds;
        }

        // Fetch featured lists matching the interests
        $lists = FeaturedList::where('status', 'live')
            ->whereHas('category', function ($q) use ($interestIdsToFetch) {
                $q->whereIn('interest_id', $interestIdsToFetch);
            })
            ->with('category')
            ->orderBy('display_order')
            ->get()
            ->map(function ($list) {
            return [
                'id'            => $list->id,
                'title'         => $list->title,
                'image'         => $list->image ? url('storage/' . $list->image) : null,
                'category_id'   => $list->category_id,
                'list_size'     => $list->list_size,
                'status'        => $list->status,
                'display_order' => $list->display_order,
                'category'      => $list->category ? [
                    'id'       => $list->category->id,
                    'name'     => $list->category->name,
                    'interest' => $list->category->interest ? [
                        'id'   => $list->category->interest->id,
                        'name' => $list->category->interest->name
                    ] : null
                ] : null
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

public function show($id)
{
    try {
        $list = FeaturedList::with([
            'category.interest',
            'items' => fn ($q) => $q->orderBy('position'),
            'items.catalogItem'
        ])->where('status', 'live')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id'        => $list->id,
                'title'     => $list->title,
                'image'     => $list->image ? url('storage/'.$list->image) : null,
                'list_size' => $list->list_size,
                'status'        => $list->status,
                'display_order' => $list->display_order,
                'category' => [
                    'id'   => $list->category->id,
                    'name' => $list->category->name,
                ],

                'interest' => [
                    'id'   => $list->category->interest->id,
                    'name' => $list->category->interest->name,
                ],

                'items' => $list->items->map(fn ($item) =>
                    $item->catalogItem ? [
                        'id'          => $item->catalogItem->id,
                        'name'        => $item->catalogItem->name,
                        'description' => $item->catalogItem->description,
                        'image'       => $item->catalogItem->image_url
                            ? url('storage/'.$item->catalogItem->image_url)
                            : null,
                        'position'    => $item->position,
                    ] : null
                )->filter()->values()
            ]
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'Featured list not found'
        ], 404);
    }
}

public function items($listId)
{
    try {
        $list = FeaturedList::with(['items.catalogItem'])->findOrFail($listId);
        $items = $list->items->map(function ($item) {
            if (!$item->catalogItem) return null;

            return [
                'id'       => $item->catalogItem->id,
                'name'     => $item->catalogItem->name,
                'description' => $item->catalogItem->description,
                'image'    => $item->image_url ? url('storage/' . $item->image_url) : null,
                'position' => $item->position,
            ];
        })->filter()->values();

        return response()->json([
            'success' => true,
            'data'    => $items
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch featured list items',
            'error'   => $e->getMessage()
        ], 500);
    }
}

}