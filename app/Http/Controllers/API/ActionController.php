<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FeaturedItemBookmark;
use App\Models\FeaturedItemLike;
use App\Models\FeaturedItemShare;
use App\Models\FeaturedListItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActionController extends Controller
{
    public function toggleLike($id)
    {
        $user = Auth::user();

        $like = FeaturedItemLike::where([
            'user_id' => $user->id,
            'featured_list_item_id' => $id
        ])->first();

        if ($like) {
            $like->delete();

            return response()->json([
                'success' => true,
                'liked'   => false,
                'data'    => null
            ]);
        }

        $newLike = FeaturedItemLike::create([
            'user_id' => $user->id,
            'featured_list_item_id' => $id
        ]);

        return response()->json([
            'success' => true,
            'liked'   => true,
            'data'    => $newLike
        ]);
    }

    public function toggleBookmark($id)
    {
        $user = Auth::user();

        $bookmark = FeaturedItemBookmark::where([
            'user_id' => $user->id,
            'featured_list_item_id' => $id
        ])->first();

        if ($bookmark) {
            $bookmark->delete();

            return response()->json([
                'success' => true,
                'saved'   => false,
                'data'    => null
            ]);
        }

        $newBookmark = FeaturedItemBookmark::create([
            'user_id' => $user->id,
            'featured_list_item_id' => $id
        ]);

        return response()->json([
            'success' => true,
            'saved'   => true,
            'data'    => $newBookmark
        ]);
    }

    public function generateShareLink($id)
    {
        $item = FeaturedListItem::with('catalogItem')->findOrFail($id);

        $shareUrl = url("/shared/featured-item/{$item->id}");

        return response()->json([
            'success'   => true,
            'share_url' => $shareUrl
        ]);
    }

    public function share(Request $request, $id)
    {
        $request->validate([
            'platform' => 'required|string|max:50'
        ]);

        FeaturedItemShare::create([
            'user_id'               => Auth::id(),
            'featured_list_item_id' => $id,
            'platform'              => $request->platform
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Share recorded successfully'
        ]);
    }
}
