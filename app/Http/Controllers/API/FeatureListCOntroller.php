<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FeaturedItemBookmark;
use App\Models\FeaturedItemLike;
use App\Models\FeaturedList;
use App\Models\FeaturedListItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeatureListController extends Controller
{

    // public function index(Request $request)
    // {
    //     try {
    //         $user = Auth::user();

    //         if (!$user) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Authentication token missing or invalid',
    //             ], 401);
    //         }

    //         $interestId = $request->query('interest_id');
    //         $userInterestIds = $user->interests()->pluck('interests.id')->toArray();

    //         // Determine which interests to fetch
    //         if ($interestId) {
    //             if (!in_array($interestId, $userInterestIds)) {
    //                 return response()->json(['success' => true, 'data' => []]);
    //             }
    //             $interestIdsToFetch = [$interestId];
    //         } else {
    //             $interestIdsToFetch = $userInterestIds;
    //         }

    //         // Fetch featured lists matching the interests
    //         $lists = FeaturedList::where('status', 'live')
    //             ->whereHas('category', function ($q) use ($interestIdsToFetch) {
    //                 $q->whereIn('interest_id', $interestIdsToFetch);
    //             })
    //             ->with('category')
    //             ->orderBy('display_order')
    //             ->get()
    //             ->map(function ($list) {
    //             return [
    //                 'id'            => $list->id,
    //                 'title'         => $list->title,
    //                 'image'         => $list->image ? url('storage/' . $list->image) : null,
    //                 'category_id'   => $list->category_id,
    //                 'list_size'     => $list->list_size,
    //                 'status'        => $list->status,
    //                 'display_order' => $list->display_order,
    //                 'category'      => $list->category ? [
    //                     'id'       => $list->category->id,
    //                     'name'     => $list->category->name,
    //                     'interest' => $list->category->interest ? [
    //                         'id'   => $list->category->interest->id,
    //                         'name' => $list->category->interest->name
    //                     ] : null
    //                 ] : null
    //             ];
    //         });

    //         return response()->json([
    //             'success' => true,
    //             'data'    => $lists
    //         ]);

    //     } catch (\Throwable $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to fetch featured lists',
    //             'error'   => $e->getMessage()
    //         ], 500);
    //     }
    // }

    // public function show($id)
    // {
    //     try {
    //         $list = FeaturedList::with([
    //             'category.interest',
    //             'items' => fn ($q) => $q->orderBy('position'),
    //             'items.catalogItem'
    //         ])->where('status', 'live')->findOrFail($id);

    //         return response()->json([
    //             'success' => true,
    //             'data' => [
    //                 'id'        => $list->id,
    //                 'title'     => $list->title,
    //                 'image'     => $list->image ? url('storage/'.$list->image) : null,
    //                 'list_size' => $list->list_size,
    //                 'status'        => $list->status,
    //                 'display_order' => $list->display_order,
    //                 'category' => [
    //                     'id'   => $list->category->id,
    //                     'name' => $list->category->name,
    //                 ],

    //                 'interest' => [
    //                     'id'   => $list->category->interest->id,
    //                     'name' => $list->category->interest->name,
    //                 ],

    //                 'items' => $list->items->map(fn ($item) =>
    //                     $item->catalogItem ? [
    //                         'id'          => $item->catalogItem->id,
    //                         'name'        => $item->catalogItem->name,
    //                         'description' => $item->catalogItem->description,
    //                         'image'       => $item->catalogItem->image_url
    //                             ? url('storage/'.$item->catalogItem->image_url)
    //                             : null,
    //                         'position'    => $item->position,
    //                     ] : null
    //                 )->filter()->values()
    //             ]
    //         ]);
    //     } catch (\Throwable $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Featured list not found'
    //         ], 404);
    //     }
    // }

    // public function items($listId)
    // {
    //     try {
    //         $list = FeaturedList::with(['items.catalogItem'])->findOrFail($listId);
    //         $items = $list->items->map(function ($item) {
    //             if (!$item->catalogItem) return null;

    //             return [
    //                 'id'       => $item->catalogItem->id,
    //                 'name'     => $item->catalogItem->name,
    //                 'description' => $item->catalogItem->description,
    //                 'image'    => $item->image_url ? url('storage/' . $item->image_url) : null,
    //                 'position' => $item->position,
    //             ];
    //         })->filter()->values();

    //         return response()->json([
    //             'success' => true,
    //             'data'    => $items
    //         ]);

    //     } catch (\Throwable $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to fetch featured list items',
    //             'error'   => $e->getMessage()
    //         ], 500);
    //     }
    // }

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
                ->withCount([
                    'likes as likes_count',
                    'bookmarks as saves_count',
                    'shares as shares_count',
                ])
                ->withExists([
                    'likes as is_liked' => function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    },
                    'bookmarks as is_saved' => function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    },
                ])
                ->orderBy('display_order')
                ->get()
                ->map(function ($list) {
                    return [
                        'id'            => $list->id,
                        'title'         => $list->title,

                        // FIXED IMAGE URL
                        'image' => $list->image
                            ? url('storage/featured_lists/' . basename($list->image))
                            : null,

                        'list_size'     => $list->list_size,
                        'status'        => $list->status,
                        'display_order' => $list->display_order,

                        'likes_count'  => (int) $list->likes_count,
                        'saves_count'  => (int) $list->saves_count,
                        'shares_count' => (int) $list->shares_count,

                        'is_liked' => (bool) $list->is_liked,
                        'is_saved' => (bool) $list->is_saved,

                        'category' => [
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
                'data'    => $lists,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch featured lists',
                'error'   => $e->getMessage(),
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
                        ->with('catalogItem');
                }
            ])
                ->withCount([
                    'likes as likes_count',
                    'bookmarks as saves_count',
                    'shares as shares_count',
                ])
                ->withExists([
                    'likes as is_liked' => function ($q) use ($userId) {
                        $q->where('user_id', $userId);
                    },
                    'bookmarks as is_saved' => function ($q) use ($userId) {
                        $q->where('user_id', $userId);
                    },
                ])
                ->where('status', 'live')
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id'            => $list->id,
                    'title'         => $list->title,

                    // IXED FEATURED LIST IMAGE
                    'image' => $list->image
                        ? url('storage/featured_lists/' . basename($list->image))
                        : null,

                    'list_size'     => $list->list_size,
                    'status'        => $list->status,
                    'display_order' => $list->display_order,

                    'likes_count'  => (int) $list->likes_count,
                    'saves_count'  => (int) $list->saves_count,
                    'shares_count' => (int) $list->shares_count,

                    'is_liked' => (bool) $list->is_liked,
                    'is_saved' => (bool) $list->is_saved,

                    'category' => [
                        'id'   => $list->category->id,
                        'name' => $list->category->name,
                    ],
                    'interest' => [
                        'id'   => $list->category->interest->id,
                        'name' => $list->category->interest->name,
                    ],

                    'items' => $list->items->map(function ($item) {
                        return [
                            'id'          => $item->id,
                            'name'        => $item->catalogItem->name,
                            'description' => $item->catalogItem->description,

                            // IXED CATALOG ITEM IMAGE
                            'image' => $item->catalogItem->image_url
                                ? url('storage/category-items/' . basename($item->catalogItem->image_url))
                                : null,

                            'position'    => $item->position,
                        ];
                    }),
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Featured list not found',
            ], 404);
        }
    }



    public function searchFeatureList(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication token missing or invalid',
                ], 401);
            }

            $search = $request->query('q');

            if (!$search) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                ]);
            }

            $userInterestIds = $user->interests()
                ->pluck('interests.id')
                ->toArray();

            $lists = FeaturedList::where('status', 'live')
                ->whereHas('category', function ($q) use ($userInterestIds) {
                    $q->whereIn('interest_id', $userInterestIds);
                })
                ->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                        ->orWhereHas('category', function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%{$search}%");
                        })
                        ->orWhereHas('category.interest', function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%{$search}%");
                        });
                })
                ->with('category.interest')
                ->withCount([
                    'likes as likes_count',
                    'bookmarks as saves_count',
                    'shares as shares_count',
                ])
                ->withExists([
                    'likes as is_liked' => function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    },
                    'bookmarks as is_saved' => function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    },
                ])
                ->orderBy('display_order')
                ->get()
                ->map(function ($list) {
                    return [
                        'id'            => $list->id,
                        'title'         => $list->title,

                        // FIXED IMAGE URL
                        'image' => $list->image
                            ? url('storage/featured_lists/' . basename($list->image))
                            : null,

                        'list_size'     => $list->list_size,
                        'status'        => $list->status,
                        'display_order' => $list->display_order,

                        'likes_count'  => (int) $list->likes_count,
                        'saves_count'  => (int) $list->saves_count,
                        'shares_count' => (int) $list->shares_count,

                        'is_liked' => (bool) $list->is_liked,
                        'is_saved' => (bool) $list->is_saved,

                        'category' => [
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
                'data'    => $lists,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to search featured lists',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function myLikedFeaturedLists()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated'
                ], 401);
            }

            $likes = FeaturedItemLike::with('featuredListItem')
                ->where('user_id', $user->id)
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'count'   => $likes->count(),
                'data'    => $likes,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch liked featured lists',
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function myBookmarkedFeaturedLists()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated'
                ], 401);
            }

            $bookmarks = FeaturedItemBookmark::with('featuredListItem')
                ->where('user_id', $user->id)
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'count'   => $bookmarks->count(),
                'data'    => $bookmarks,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch bookmarked featured lists',
                'error'   => $th->getMessage(),
            ], 500);
        }
    }
    
    public function items($id)
    {
        try {
            $user = Auth::user();
    
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication token missing or invalid',
                ], 401);
            }
    
            $list = FeaturedList::where('status', 'live')
                ->with([
                    'items' => function ($q) {
                        $q->orderBy('position')
                          ->with('catalogItem');
                    }
                ])
                ->findOrFail($id);
    
            $items = $list->items->map(function ($item) {
                return [
                    'id'          => $item->id,
                    'catalog_item_id' => $item->catalog_item_id,
                    'name'        => $item->catalogItem->name,
                    'description' => $item->catalogItem->description,
                    'image' => $item->catalogItem->image_url
                                ? url($item->catalogItem->image_url)
                                : null,
                    'position'    => $item->position,
                ];
            });
    
            return response()->json([
                'success' => true,
                'featured_list' => [
                    'id'    => $list->id,
                    'title' => $list->title,
                ],
                'items' => $items,
            ]);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Featured list not found',
            ], 404);
    
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch featured list items',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

}
