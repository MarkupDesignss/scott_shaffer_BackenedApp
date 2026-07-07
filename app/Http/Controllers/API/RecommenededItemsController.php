<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CatalogItem;
use App\Models\Intrest;
use App\Models\ListModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecommenededItemsController extends Controller
{
    public function recommendedList()
    {
        try {
            $user = Auth::user();

            $lists = ListModel::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhereHas('members', function ($m) use ($user) {
                        $m->where('user_id', $user->id)
                            ->where('status', 'accepted');
                    });
            })
                ->with([
                    'items.catalogItem',
                    'user:id,full_name'
                ])
                ->withCount([
                    // 🔹 reuse featured tables using list_id
                    'likes as likes_count' => function ($q) {
                        $q->whereNotNull('list_id');
                    },
                    'shares as shares_count' => function ($q) {
                        $q->whereNotNull('list_id');
                    },
                ])
                ->withExists([
                    'likes as is_liked' => function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    },
                ])
                ->latest()
                ->get()
                ->map(function ($list) {

                    return [
                        'id'           => $list->id,
                        'user_id'      => $list->user_id,
                        'title'        => $list->title,
                        'category_id'  => $list->category_id,
                        'list_size'    => $list->list_size,
                        'is_group'     => $list->is_group,
                        'status'       => $list->status,
                        'visibility'   => $list->visibility,
                        'created_at'   => $list->created_at,

                        // ✅ LIKE / SHARE DATA
                        'likes_count'  => (int) $list->likes_count,
                        'shares_count' => (int) $list->shares_count,
                        'is_liked'     => (bool) $list->is_liked,

                        // ✅ ITEMS WITH FULL IMAGE URL
                        'items' => $list->items->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'catalog_item' => $item->catalogItem ? [
                                    'id'          => $item->catalogItem->id,
                                    'name'        => $item->catalogItem->name,
                                    'description' => $item->catalogItem->description,
                                    'image_url'   => $item->catalogItem->image_url
                                        ? asset('storage/' . $item->catalogItem->image_url)
                                        : null,
                                ] : null,
                            ];
                        }),

                        'user' => $list->user,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Recommended lists fetched successfully',
                'data'    => $lists,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch recommended lists',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
