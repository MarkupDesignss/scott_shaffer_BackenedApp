<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FeaturedItemBookmark;
use App\Models\FeaturedItemLike;
use App\Models\FeaturedItemShare;
use App\Models\FeaturedList;
use App\Models\ListModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ActionController extends Controller
{
    /**
     * Toggle Like on Featured List
     */
    public function toggleLike($featuredListId)
    {
        $user = Auth::user();

        $like = FeaturedItemLike::where([
            'user_id' => $user->id,
            'featured_list_item_id' => $featuredListId, // 🔁 storing featured_list_id
        ])->first();

        if ($like) {
            $like->delete();

            return response()->json([
                'success' => true,
                'liked'   => false,
                'data'    => null,
            ]);
        }

        $newLike = FeaturedItemLike::create([
            'user_id' => $user->id,
            'featured_list_item_id' => $featuredListId,
        ]);

        return response()->json([
            'success' => true,
            'liked'   => true,
            'data'    => $newLike,
        ]);
    }
    
        public function removeLike($featuredListId)
    {
        $user = Auth::user();

        // Find the like for this user and featured list
        $like = FeaturedItemLike::where([
            'user_id' => $user->id,
            'featured_list_item_id' => $featuredListId,
        ])->first();

        if (!$like) {
            return response()->json([
                'success' => false,
                'message' => 'Like not found',
            ], 404);
        }

        try {
            $like->delete();

            return response()->json([
                'success' => true,
                'message' => 'Like removed successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove like',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle Bookmark on Featured List
     */
    public function toggleBookmark($featuredListId)
    {
        $user = Auth::user();

        $bookmark = FeaturedItemBookmark::where([
            'user_id' => $user->id,
            'featured_list_item_id' => $featuredListId,
        ])->first();

        if ($bookmark) {
            $bookmark->delete();

            return response()->json([
                'success' => true,
                'saved'   => false,
                'data'    => null,
            ]);
        }

        $newBookmark = FeaturedItemBookmark::create([
            'user_id' => $user->id,
            'featured_list_item_id' => $featuredListId,
        ]);

        return response()->json([
            'success' => true,
            'saved'   => true,
            'data'    => $newBookmark,
        ]);
    }
    
    public function removeBookmark($featuredListId)
{
    $user = Auth::user();

    // Find the bookmark for this user and featured list
    $bookmark = FeaturedItemBookmark::where([
        'user_id' => $user->id,
        'featured_list_item_id' => $featuredListId,
    ])->first();

    if (!$bookmark) {
        return response()->json([
            'success' => false,
            'message' => 'Bookmark not found',
        ], 404);
    }

    // dd($bookmark);
    try {
        $bookmark->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bookmark removed successfully',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to remove bookmark',
            'error' => $e->getMessage(),
        ], 500);
    }
}


    /**
     * Generate Share Link for Featured List
     */
    public function generateShareLink($featuredListId)
    {
        try {
            $list = FeaturedList::findOrFail($featuredListId);

            $shareUrl = url("/featured-lists/{$list->id}");

            return response()->json([
                'success'   => true,
                'message'   => 'Link generated',
                'share_url' => $shareUrl,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to generate link',
                'data'    => $th->getMessage(),
            ]);
        }
    }

    /**
     * Store Share Record for Featured List
     */
    public function share(Request $request, $featuredListId)
    {
        $request->validate([
            'platform' => 'required|string|max:50',
        ]);

        FeaturedItemShare::create([
            'user_id' => Auth::id(),
            'featured_list_item_id' => $featuredListId,
            'platform' => $request->platform,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Share recorded successfully',
        ]);
    }
    
        public function toggleListLike($listId)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required.',
                ], 401);
            }

            $like = FeaturedItemLike::where([
                'user_id' => $user->id,
                'list_id' => $listId,
            ])->first();

            if ($like) {
                $like->delete();

                return response()->json([
                    'success' => true,
                    'liked'   => false,
                    'message' => 'List unliked successfully.',
                ]);
            }

            FeaturedItemLike::create([
                'user_id' => $user->id,
                'list_id' => $listId,
            ]);

            return response()->json([
                'success' => true,
                'liked'   => true,
                'message' => 'List liked successfully.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Toggle list like failed', [
                'list_id' => $listId,
                'error'   => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to process like request at this time.',
            ], 500);
        }
    }
    
        public function removeListLike($listId)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required.',
                ], 401);
            }

            $like = FeaturedItemLike::where([
                'user_id' => $user->id,
                'list_id' => $listId,
            ])->first();

            if (!$like) {
                return response()->json([
                    'success' => false,
                    'message' => 'Like not found.',
                ], 404);
            }

            $like->delete();

            return response()->json([
                'success' => true,
                'message' => 'List unliked successfully.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Remove list like failed', [
                'list_id' => $listId,
                'error'   => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to remove like at this time.',
            ], 500);
        }
    }


    public function generateListShareLink($listId)
{
    try {
        $list = ListModel::find($listId);

        if (!$list) {
            return response()->json([
                'success' => false,
                'message' => 'List not found.',
            ], 404);
        }

        // ✅ Always correct base URL
        $baseUrl = rtrim(url('/'), '/');

        return response()->json([
            'success'   => true,
            'message'   => 'Link generated',
            'share_url' => $baseUrl . '/recommended-lists/' . $list->id,
        ]);

    } catch (\Throwable $e) {
        Log::error('Generate list share link failed', [
            'list_id' => $listId,
            'error'   => $e->getMessage(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Unable to generate share link.',
        ], 500);
    }
}



    public function shareList(Request $request, $listId)
    {
        try {
            $userId = Auth::id();

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required.',
                ], 401);
            }

            $request->validate([
                'platform' => 'required|string|max:50',
            ]);

            $list = ListModel::find($listId);

            if (!$list) {
                return response()->json([
                    'success' => false,
                    'message' => 'List not found.',
                ], 404);
            }

            FeaturedItemShare::create([
                'user_id'  => $userId,
                'list_id'  => $listId,
                'platform' => $request->platform,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Share recorded successfully.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('List share failed', [
                'list_id' => $listId,
                'error'   => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to record share at this time.',
            ], 500);
        }
    }
    
     public function myLikedFeaturedLists()
    {
        $user = Auth::user();

        $lists = FeaturedList::whereHas('likes', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->withCount([
                'likes as likes_count',
                'bookmarks as saves_count',
                'shares as shares_count',
            ])
            ->withExists([
                'likes as is_liked' => fn($q) => $q->where('user_id', $user->id),
                'bookmarks as is_saved' => fn($q) => $q->where('user_id', $user->id),
            ])
            ->where('status', 'live')
            ->get()
            ->map(function ($list) {

                return [
                    'id'            => $list->id,
                    'title'         => $list->title,
                    'list_size'     => $list->list_size,
                    'status'        => $list->status,

                    // ✅ FULL IMAGE URL
                    'image' => $list->image
                        ? url('storage/' . ltrim($list->image, '/'))
                        : null,

                    'likes_count'   => (int) $list->likes_count,
                    'saves_count'   => (int) $list->saves_count,
                    'shares_count'  => (int) $list->shares_count,

                    'is_liked'      => (bool) $list->is_liked,
                    'is_saved'      => (bool) $list->is_saved,
                ];
            });

        return response()->json([
            'success' => true,
            'data'    => $lists,
        ]);
    }


    // public function myBookmarkedFeaturedLists()
    // {
    //     $user = Auth::user();

    //     $lists = FeaturedList::whereHas('bookmarks', function ($q) use ($user) {
    //         $q->where('user_id', $user->id);
    //     })
    //         ->withCount([
    //             'likes as likes_count',
    //             'bookmarks as saves_count',
    //             'shares as shares_count',
    //         ])
    //         ->withExists([
    //             'likes as is_liked' => fn($q) => $q->where('user_id', $user->id),
    //             'bookmarks as is_saved' => fn($q) => $q->where('user_id', $user->id),
    //         ])
    //         ->where('status', 'live')
    //         ->get()
    //         ->map(function ($list) {

    //             return [
    //                 'id'            => $list->id,
    //                 'title'         => $list->title,
    //                 'list_size'     => $list->list_size,
    //                 'status'        => $list->status,

    //                 // ✅ FULL IMAGE URL
    //                 'image' => $list->image
    //                     ? url('storage/' . ltrim($list->image, '/'))
    //                     : null,

    //                 'likes_count'   => (int) $list->likes_count,
    //                 'saves_count'   => (int) $list->saves_count,
    //                 'shares_count'  => (int) $list->shares_count,

    //                 'is_liked'      => (bool) $list->is_liked,
    //                 'is_saved'      => (bool) $list->is_saved,
    //             ];
    //         });

    //     return response()->json([
    //         'success' => true,
    //         'data'    => $lists,
    //     ]);
    // }
    
    public function myBookmarkedFeaturedLists()
{
    try {
        // 🔐 Auth check
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
                'data'    => [],
            ], 401);
        }

        $lists = FeaturedList::whereHas('bookmarks', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status', 'live')
            ->with([
                'category:id,name,interest_id',
                'category.interest:id,name',
            ])
            ->withCount([
                'likes as likes_count',
                'bookmarks as saves_count',
                'shares as shares_count',
            ])
            ->withExists([
                'likes as is_liked' => fn ($q) => $q->where('user_id', $user->id),
                'bookmarks as is_saved' => fn ($q) => $q->where('user_id', $user->id),
            ])
            ->get();

        // 🧾 Empty case
        if ($lists->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No bookmarked featured lists found',
                'data'    => [],
            ], 200);
        }

        $data = $lists->map(function ($list) {

            $category = $list->category;
            $interest = $category?->interest;

            return [
                'id'        => (int) $list->id,
                'title'     => (string) $list->title,
                'list_size' => (int) $list->list_size,
                'status'    => (string) $list->status,

                // 📂 Category (direct)
                'category' => $category ? [
                    'id'   => (int) $category->id,
                    'name' => (string) $category->name,
                ] : null,

                // 🎯 Interest (via category)
                'interest' => $interest ? [
                    'id'   => (int) $interest->id,
                    'name' => (string) $interest->name,
                ] : null,

                // 🖼 Image
                'image' => $list->image
                    ? url('storage/' . ltrim($list->image, '/'))
                    : null,

                // 📊 Counters
                'likes_count'  => (int) $list->likes_count,
                'saves_count'  => (int) $list->saves_count,
                'shares_count' => (int) $list->shares_count,

                // ❤️ Flags
                'is_liked' => (bool) $list->is_liked,
                'is_saved' => (bool) $list->is_saved,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Bookmarked featured lists fetched successfully',
            'data'    => $data,
        ], 200);

    } catch (\Illuminate\Database\QueryException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Database error occurred',
            'error'   => config('app.debug') ? $e->getMessage() : null,
            'data'    => [],
        ], 500);

    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong',
            'error'   => config('app.debug') ? $e->getMessage() : null,
            'data'    => [],
        ], 500);
    }
}


    public function myLikedLists()
    {
        $user = Auth::user();

        $lists = ListModel::whereHas('likes', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->withCount([
                'likes as likes_count',
                'shares as shares_count',
            ])
            ->withExists([
                'likes as is_liked' => fn($q) => $q->where('user_id', $user->id),
            ])
            ->with('items.catalogItem')
            ->get()
            ->map(function ($list) {

                return [
                    'id'           => $list->id,
                    'title'        => $list->title,
                    'likes_count'  => (int) $list->likes_count,
                    'shares_count' => (int) $list->shares_count,
                    'is_liked'     => (bool) $list->is_liked,

                    'items' => $list->items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'catalog_item' => $item->catalogItem ? [
                                'id'          => $item->catalogItem->id,
                                'name'        => $item->catalogItem->name,

                                // ✅ FULL IMAGE URL
                                'image_url' => $item->catalogItem->image_url
                                    ? url('storage/' . ltrim($item->catalogItem->image_url, '/'))
                                    : null,
                            ] : null,
                        ];
                    }),
                ];
            });

        return response()->json([
            'success' => true,
            'data'    => $lists,
        ]);
    }
}
