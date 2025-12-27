<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ListModel;
use App\Models\ListItem;
use Illuminate\Support\Facades\Auth;
use Throwable;

class ListItemController extends Controller
{
    public function store(Request $request, $id)
    {
        try {
            $list = ListModel::find($id);
            // dd($list);
            $this->authorizeList($list);

            $validated = $request->validate([
                'catalog_item_id' => 'nullable|exists:catalog_items,id',
                'custom_text'     => 'nullable|string|max:120',
                'position'        => 'required|integer|min:1',
            ]);

            if (empty($validated['catalog_item_id']) && empty($validated['custom_text'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Either catalog_item_id or custom_text is required'
                ], 422);
            }

            // if (!empty($validated['catalog_item_id']) && !empty($validated['custom_text'])) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Only one of catalog_item_id or custom_text is allowed'
            //     ], 422);
            // }

            $item = ListItem::create([
                'list_id'         => $list->id,
                'catalog_item_id' => $validated['catalog_item_id'] ?? null,
                'custom_text'     => $validated['custom_text'] ?? null,
                'position'        => $validated['position'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item added successfully',
                'data'    => $item
            ], 201);
        } catch (Throwable $e) {
            return $this->serverError($e);
        }
    }

    public function destroy($listId, $itemId)
    {
        try {
            $list = ListModel::find($listId);
            $item = ListItem::find($itemId);
            $this->authorizeList($list);

            if ($item->list_id !== $list->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item does not belong to this list'
                ], 404);
            }

            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item removed successfully'
            ]);
        } catch (Throwable $e) {
            return $this->serverError($e);
        }
    }

    /* =========================
       Helper Methods
    ========================== */

    private function authorizeList(ListModel $list)
    {
        if ($list->user_id !== Auth::id()) {
            abort(403, 'Unauthorized list access');
        }
    }

    private function serverError(Throwable $e)
    {
        logger()->error($e);

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong. Please try again later.',
            'reason'  => $e->getMessage()
        ], 500);
    }
}