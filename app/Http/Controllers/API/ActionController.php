<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FeaturedItemBookmark;
use App\Models\FeaturedItemLike;
use App\Models\FeaturedItemShare;
use App\Models\FeaturedList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
