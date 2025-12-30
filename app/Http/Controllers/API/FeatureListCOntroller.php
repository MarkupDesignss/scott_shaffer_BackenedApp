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

        if ($interestId) {
            if (!in_array($interestId, $userInterestIds)) {
                return response()->json(['success' => true, 'data' => []]);
            }
            $interestIdsToFetch = [$interestId];
        } else {
            $interestIdsToFetch = $userInterestIds;
        }

        $lists = FeaturedList::where('status', 'live')
            ->whereHas('category', function ($q) use ($interestIdsToFetch) {
                $q->whereIn('interest_id', $interestIdsToFetch);
            })
            ->with('category.interest')
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
                    'category'      => [
                        'id'   => $list->category->id,
                        'name' => $list->category->name,
                    ],
                    'interest' => [
                        'id'   => $list->category->interest->id,
                        'name' => $list->category->interest->name,
                    ],
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
        $userId = Auth::id();

        $list = FeaturedList::with([
            'category.interest',
            'items' => function ($q) {
                $q->where('status', 'active')
                  ->orderBy('position')
                  ->with('catalogItem')
                  ->withCount([
                      'likes as likes_count',
                      'bookmarks as saves_count',
                      'shares as shares_count'
                  ]);
            }
        ])
        ->where('status', 'live')
        ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id'            => $list->id,
                'title'         => $list->title,
                'image'         => $list->image ? url('storage/'.$list->image) : null,
                'list_size'     => $list->list_size,
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

                'items' => $list->items->map(function ($item) use ($userId) {
                    return [
                        'id'          => $item->id,
                        'name'        => $item->catalogItem->name,
                        'description' => $item->catalogItem->description,
                        'image'       => $item->catalogItem->image_url
                            ? url('storage/'.$item->catalogItem->image_url)
                            : null,

                        'position' => $item->position,

                        'likes_count'  => (int) $item->likes_count,
                        'saves_count'  => (int) $item->saves_count,
                        'shares_count' => (int) $item->shares_count,

                        'is_liked' => $userId
                            ? $item->likes()->where('user_id', $userId)->exists()
                            : false,

                        'is_saved' => $userId
                            ? $item->bookmarks()->where('user_id', $userId)->exists()
                            : false,
                    ];
                })
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
        $userId = Auth::id();

        $list = FeaturedList::findOrFail($listId);

        $items = $list->items()
            ->where('status', 'active')
            ->orderBy('position')
            ->with('catalogItem')
            ->withCount([
                'likes as likes_count',
                'bookmarks as saves_count',
                'shares as shares_count'
            ])
            ->get()
            ->map(function ($item) use ($userId) {
                return [
                    'id'          => $item->id,
                    'name'        => $item->catalogItem->name,
                    'description' => $item->catalogItem->description,
                    'image'       => $item->catalogItem->image_url
                        ? url('storage/'.$item->catalogItem->image_url)
                        : null,

                    'position' => $item->position,

                    'likes_count'  => (int) $item->likes_count,
                    'saves_count'  => (int) $item->saves_count,
                    'shares_count' => (int) $item->shares_count,

                    'is_liked' => $userId
                        ? $item->likes()->where('user_id', $userId)->exists()
                        : false,

                    'is_saved' => $userId
                        ? $item->bookmarks()->where('user_id', $userId)->exists()
                        : false,
                ];
            });

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