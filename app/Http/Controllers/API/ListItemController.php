<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ListItem;
use App\Models\ListModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ListItemController extends Controller
{
    /* =========================
       GET: List Items
    ========================== */
    public function index($listId)
    {
        try {
            $list = ListModel::findOrFail($listId);
            //$this->authorizeList($list);

            $items = ListItem::with('catalogItem.category')
                ->where('list_id', $listId)
                ->orderBy('position')
                ->get()
                ->map(function ($item) {
                    $catalog = $item->catalogItem; // check relation
                    return [
                        'id' => $item->id,
                        'type' => $item->catalog_item_id ? 'catalog' : 'custom',
                        'position' => $item->position,
                        'catalog_item' => $catalog ? [
                            'id' => $catalog->id,
                            'name' => $catalog->name,
                            'category' => $catalog->category->name ?? null,
                        ] : null,
                        'custom_item_name' => $item->custom_item_name,
                        'custom_text' => $item->custom_text,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $items
            ]);
        } catch (Throwable $e) {
            return $this->serverError($e);
        }
    }


    /* =========================
       POST: Add Item to List
    ========================== */
    public function store(Request $request, $listId)
    {
        try {
            $list = ListModel::findOrFail($listId);
           // $this->authorizeList($list);

            // Manual validator
            $validator = Validator::make($request->all(), [
                'catalog_item_id'  => 'nullable|exists:catalog_items,id',
                'custom_item_name' => 'nullable|string|max:120',
                'custom_text'      => 'nullable|string',
                'position'         => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            // Either catalog OR custom (not both, not none)
            if (
                (empty($validated['catalog_item_id']) && empty($validated['custom_item_name'])) ||
                (!empty($validated['catalog_item_id']) && !empty($validated['custom_item_name']))
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Provide either catalog_item_id OR custom_item_name'
                ], 422);
            }

            $item = ListItem::create([
                'list_id' => $listId,
                'catalog_item_id' => $validated['catalog_item_id'] ?? null,
                'custom_item_name' => $validated['custom_item_name'] ?? null,
                'custom_text' => $validated['custom_text'] ?? null,
                'position' => $validated['position'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item added successfully',
                'data' => $item
            ], 201);

        } catch (Throwable $e) {
            return $this->serverError($e);
        }
    }

    /* =========================
       PUT: Update Custom Item
    ========================== */
    public function update(Request $request, $listId, $itemId)
    {
        try {
            $list = ListModel::findOrFail($listId);
            $this->authorizeList($list);

            $item = ListItem::where('list_id', $listId)->findOrFail($itemId);

            // Catalog item editable nahi hoga
            if ($item->catalog_item_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Catalog items cannot be edited'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'custom_item_name' => 'required|string|max:120',
                'custom_text' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $item->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Item updated successfully',
                'data' => $item
            ]);
        } catch (Throwable $e) {
            return $this->serverError($e);
        }
    }

    /* =========================
       PUT: Reorder Items
    ========================== */
    public function reorder(Request $request, $listId)
    {
        try {
            $list = ListModel::findOrFail($listId);
            //$this->authorizeList($list);

            $validator = Validator::make($request->all(), [
                'items' => 'required|array',
                'items.*.id' => 'required|exists:list_items,id',
                'items.*.position' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::transaction(function () use ($validator, $listId) {
                foreach ($validator->validated()['items'] as $item) {
                    ListItem::where('id', $item['id'])
                        ->where('list_id', $listId)
                        ->update(['position' => $item['position']]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'List reordered successfully'
            ]);
        } catch (Throwable $e) {
            return $this->serverError($e);
        }
    }

    /* =========================
       DELETE: Remove Item
    ========================== */
    public function destroy($listId, $itemId)
    {
        try {
            $list = ListModel::findOrFail($listId);
            $this->authorizeList($list);

            $item = ListItem::where('list_id', $listId)->findOrFail($itemId);
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
            'message' => 'Something went wrong',
            'reason' => $e->getMessage()
        ], 500);
    }
}