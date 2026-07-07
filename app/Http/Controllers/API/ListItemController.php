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
            ListModel::findOrFail($listId);

            $items = ListItem::with('catalogItem.category')
                ->where('status', 'active')
                ->where('list_id', $listId)
                ->orderBy('id', 'asc')
                ->get()
                ->map(function ($item) {

                    $catalog = $item->catalogItem;

                    return [
                        'id'   => $item->id,
                        'type' => $catalog ? 'catalog' : 'custom',

                        // âœ… SAME FIELDS FOR BOTH
                        'item_id'   => $catalog?->id,
                        'name'      => $catalog?->name ?? $item->custom_item_name,
                        'image_url' => $catalog?->image_url
                            ? asset('storage/' . $catalog->image_url)
                            : null,
                        'category'  => $catalog?->category?->name,
                        'description' => $catalog?->description ?? $item->custom_text,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $items   // âœ… catalog + custom together
            ]);
        } catch (Throwable $e) {
            return $this->serverError($e);
        }
    }

    public function updateStatus(Request $request, $itemId)
    {
        try {
            $request->validate([
                'status' => 'required|in:active,inactive',
            ]);

            $item = ListItem::findOrFail($itemId);

            $item->status = $request->status;
            $item->save();

            return response()->json([
                'success' => true,
                'message' => 'Item status updated successfully',
                'data' => [
                    'id'     => $item->id,
                    'status' => $item->status,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return $this->serverError($e);
        }
    }




    /* =========================
       POST: Add Item to List
    ========================== */
    // public function store(Request $request)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'catalog_item_ids'  => 'nullable|array',
    //             'catalog_item_ids.*' => 'exists:catalog_items,id',
    //             'custom_item_name'  => 'nullable|string|max:120',
    //             'custom_text'       => 'nullable|string',
    //             //'position'          => 'required|integer|min:1',
    //         ]);
    //         $listId = $request->listId;
    //         $list = ListModel::findOrFail($listId);
    //         // $this->authorizeList($list);

    //         // Validator

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'errors' => $validator->errors()
    //             ], 422);
    //         }

    //         $validated = $validator->validated();

    //         $items = [];

    //         // Multiple catalog items
    //         if (!empty($validated['catalog_item_ids'])) {
    //             foreach ($validated['catalog_item_ids'] as $catalogId) {
    //                 $items[] = ListItem::create([
    //                     'list_id' => $listId,
    //                     'catalog_item_id' => $catalogId,
    //                     'custom_item_name' => null,
    //                     'custom_text' =>  null,
    //                     //'position' => $validated['position'],
    //                 ]);
    //             }
    //         } elseif (!empty($validated['custom_item_name'])) {
    //             // Single custom item
    //             $items[] = ListItem::create([
    //                 'list_id' => $listId,
    //                 'catalog_item_id' => null,
    //                 'custom_item_name' => $validated['custom_item_name'],
    //                 'custom_text' => $validated['custom_text'] ?? null,
    //                 //'position' => $validated['position'],
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Provide either catalog_item_ids or custom_item_name'
    //             ], 422);
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Item(s) added successfully',
    //             'data' => $items
    //         ], 201);
    //     } catch (Throwable $e) {
    //         return $this->serverError($e);
    //     }
    // }
    
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'catalog_item_ids'  => 'nullable|array',
                'catalog_item_ids.*' => 'exists:catalog_items,id',
                'custom_item_name'  => 'nullable|string|max:120',
                'custom_text'       => 'nullable|string',
            ]);
            
            $listId = $request->listId;
            $list = ListModel::findOrFail($listId);
            
            // Current items count
            $currentItemCount = ListItem::where('list_id', $listId)->count();
            
            // Calculate new items to add
            $newItemsCount = 0;
            if (!empty($request->catalog_item_ids)) {
                $newItemsCount = count($request->catalog_item_ids);
            } elseif (!empty($request->custom_item_name)) {
                $newItemsCount = 1;
            }
            
            // Check list size limit
            if ($currentItemCount + $newItemsCount > $list->list_size) {
                return response()->json([
                    'success' => false,
                    'message' => 'List size limit is ' . $list->list_size . ' items'
                ], 422);
            }
            
            // $this->authorizeList($list);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
    
            $validated = $validator->validated();
            $items = [];
    
            // Multiple catalog items
            if (!empty($validated['catalog_item_ids'])) {
                foreach ($validated['catalog_item_ids'] as $catalogId) {
                    $items[] = ListItem::create([
                        'list_id' => $listId,
                        'catalog_item_id' => $catalogId,
                        'custom_item_name' => null,
                        'custom_text' =>  null,
                    ]);
                }
            } elseif (!empty($validated['custom_item_name'])) {
                // Single custom item
                $items[] = ListItem::create([
                    'list_id' => $listId,
                    'catalog_item_id' => null,
                    'custom_item_name' => $validated['custom_item_name'],
                    'custom_text' => $validated['custom_text'] ?? null,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Provide either catalog_item_ids or custom_item_name'
                ], 422);
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Item(s) added successfully',
                'data' => $items
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
               
            ],500);
        }
    }


    /* =========================
       PUT: Update Custom Item
    ========================== */
    // public function update(Request $request, $listId, $itemId)
    // {
    //     try {
    //         $list = ListModel::findOrFail($listId);
    //         // $this->authorizeList($list);
    //         // dd($list);

    //         $item = ListItem::where('list_id', $listId)->findOrFail($itemId);

    //         // Catalog item editable nahi hoga
    //         if ($item->catalog_item_id) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Catalog items cannot be edited'
    //             ], 403);
    //         }

    //         $validator = Validator::make($request->all(), [
    //             'custom_item_name' => 'required|string|max:120',
    //             'custom_text' => 'nullable|string',
    //         ]);
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'errors' => $validator->errors()
    //             ], 422);
    //         }

    //         $item->update($validator->validated());

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Item updated successfully',
    //             'data' => $item
    //         ]);
    //     } catch (Throwable $e) {
    //         return $this->serverError($e);
    //     }
    // }
    
    // public function update(Request $request, $listId, $itemId)
    // {
    //     try {
    //         $list = ListModel::findOrFail($listId);
    
    //         $item = ListItem::where('list_id', $listId)
    //             ->findOrFail($itemId);
    
    //         // Catalog item editable nahi hoga
    //         if ($item->catalog_item_id) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Catalog items cannot be edited'
    //             ], 403);
    //         }
    
    //         $validator = Validator::make($request->all(), [
    //             'custom_item_name' => 'nullable|string|max:120',
    //             'custom_text'      => 'nullable|string',
    
    //             'new_items' => 'nullable|array',
    //             'new_items.*.custom_item_name' => 'required|string|max:120',
    //             'new_items.*.custom_text' => 'nullable|string',
    //         ]);
    
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'errors' => $validator->errors()
    //             ], 422);
    //         }
    
    //         $validated = $validator->validated();
    
    //         /*
    //         |--------------------------------------------------------------------------
    //         | CHECK LIST SIZE LIMIT
    //         |--------------------------------------------------------------------------
    //         */
    //         $currentItemCount = ListItem::where('list_id', $listId)->count();
    
    //         $newItemsCount = isset($validated['new_items'])
    //             ? count($validated['new_items'])
    //             : 0;
    
    //         if (($currentItemCount + $newItemsCount) > $list->list_size) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'List size limit is ' . $list->list_size . ' items'
    //             ], 422);
    //         }
    
    //         /*
    //         |--------------------------------------------------------------------------
    //         | UPDATE EXISTING ITEM
    //         |--------------------------------------------------------------------------
    //         */
    //         $item->update([
    //             'custom_item_name' => $validated['custom_item_name'],
    //             'custom_text'      => $validated['custom_text'] ?? null,
    //         ]);
    
    //         /*
    //         |--------------------------------------------------------------------------
    //         | CREATE NEW ITEMS
    //         |--------------------------------------------------------------------------
    //         */
    //         $createdItems = [];
    
    //         if (!empty($validated['new_items'])) {
    
    //             foreach ($validated['new_items'] as $newItem) {
    
    //                 $createdItems[] = ListItem::create([
    //                     'list_id'          => $listId,
    //                     'catalog_item_id'  => null,
    //                     'custom_item_name' => $newItem['custom_item_name'],
    //                     'custom_text'      => $newItem['custom_text'] ?? null,
    //                 ]);
    //             }
    //         }
    
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Item updated successfully',
    //             'data' => [
    //                 'updated_item' => $item->fresh(),
    //                 'new_items'    => $createdItems,
    //                 'total_items'  => ListItem::where('list_id', $listId)->count(),
    //                 'list_size'    => $list->list_size,
    //             ]
    //         ]);
    //     } catch (Throwable $e) {
    //         return $this->serverError($e);
    //     }
    // }
    
    public function update(Request $request, $listId, $itemId)
    {
        try {
            $list = ListModel::findOrFail($listId);
    
            $item = ListItem::where('list_id', $listId)
                ->findOrFail($itemId);
    
            // Catalog items cannot be edited
            if ($item->catalog_item_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Catalog items cannot be edited'
                ], 422);
            }
    
            $validator = Validator::make($request->all(), [
    
                // Existing item update (optional)
                'custom_item_name' => 'nullable|string|max:120',
                'custom_text'      => 'nullable|string',
    
                // New items (optional)
                'new_items' => 'nullable|array',
                'new_items.*.custom_item_name' => 'required|string|max:120',
                'new_items.*.custom_text'      => 'nullable|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
    
            $validated = $validator->validated();
    
            /*
            |--------------------------------------------------------------------------
            | AT LEAST ONE ACTION REQUIRED
            |--------------------------------------------------------------------------
            */
            $hasUpdateData = array_key_exists('custom_item_name', $validated)
                || array_key_exists('custom_text', $validated);
    
            $hasNewItems = !empty($validated['new_items']);
    
            if (!$hasUpdateData && !$hasNewItems) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please provide item data to update or new items to add.'
                ], 422);
            }
    
            /*
            |--------------------------------------------------------------------------
            | CHECK LIST SIZE LIMIT
            |--------------------------------------------------------------------------
            */
            $currentItemCount = ListItem::where('list_id', $listId)->count();
    
            $newItemsCount = $hasNewItems
                ? count($validated['new_items'])
                : 0;
    
            if (($currentItemCount + $newItemsCount) > $list->list_size) {
                return response()->json([
                    'success' => false,
                    'message' => 'List size limit is ' . $list->list_size . ' items'
                ], 422);
            }
    
            /*
            |--------------------------------------------------------------------------
            | UPDATE EXISTING ITEM (OPTIONAL)
            |--------------------------------------------------------------------------
            */
            if ($hasUpdateData) {
    
                $item->update([
                    'custom_item_name' => $validated['custom_item_name'] ?? $item->custom_item_name,
                    'custom_text'      => array_key_exists('custom_text', $validated)
                        ? $validated['custom_text']
                        : $item->custom_text,
                ]);
            }
    
            /*
            |--------------------------------------------------------------------------
            | CREATE NEW ITEMS (OPTIONAL)
            |--------------------------------------------------------------------------
            */
            $createdItems = [];
    
            if ($hasNewItems) {
    
                foreach ($validated['new_items'] as $newItem) {
    
                    $createdItems[] = ListItem::create([
                        'list_id'          => $listId,
                        'catalog_item_id'  => null,
                        'custom_item_name' => $newItem['custom_item_name'],
                        'custom_text'      => $newItem['custom_text'] ?? null,
                    ]);
                }
            }
    
            return response()->json([
                'success' => true,
                'message' => 'List updated successfully',
                'data' => [
                    'updated_item' => $item->fresh(),
                    'new_items'    => $createdItems,
                    'total_items'  => ListItem::where('list_id', $listId)->count(),
                    'list_size'    => $list->list_size,
                ]
            ]);
    
        } catch (Throwable $e) {
             return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
               
            ],500);
        }
    }

    /* =========================
       PUT: Reorder Items
    // ========================== */
    public function reorder(Request $request, $listId)
    {
        try {
            $list = ListModel::findOrFail($listId);
            $userId = Auth::id();
            $user   = Auth::user();

            // OPTIONAL (recommended)
            // $this->authorizeList($list);

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

            DB::transaction(function () use ($validator, $listId, $userId) {

                foreach ($validator->validated()['items'] as $item) {

                    $listItem = ListItem::where('id', $item['id'])
                        ->where('list_id', $listId)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $positions = $listItem->user_positions ?? [];

                    if (!is_array($positions)) {
                        $positions = [];
                    }

                    // Update current user's position
                    $positions[$userId] = $item['position'];

                    $listItem->user_positions = $positions;
                    $listItem->position_updated_count = $listItem->position_updated_count + 1;
                    $listItem->save();
                }
            });

            /*
        |--------------------------------------------------------------------------
        | Send Notification to List Owner
        |--------------------------------------------------------------------------
        */
            $ownerId = $list->user_id;

            // Avoid self notification
            if ($ownerId != $userId) {

                // DB notification
                DB::table('notifications')->insert([
                    'sender_id'   => $userId,
                    'receiver_id' => $ownerId,
                    'type'        => 'list_reordered',
                    'title'       => 'List Updated',
                    'body'        => $user->full_name . ' reordered items in your list',
                    'data'        => json_encode([
                        'list_id' => $listId
                    ]),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);

                // Push notification (Firebase)
                // try {
                //     (new FirebaseNotificationService())->sendToUser(
                //         $ownerId,
                //         'List Updated',
                //         $user->full_name . ' reordered items in your list',
                //         [
                //             'type'    => 'list_reordered',
                //             'list_id' => (string) $listId,
                //         ]
                //     );
                // } catch (\Throwable $e) {
                //     // fail silently (important for production)
                //     \Log::error('Firebase notification failed: ' . $e->getMessage());
                // }
            }

            return response()->json([
                'success' => true,
                'message' => 'Reordered Successfully'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    //   public function reorder(Request $request, $listId)
    // {
    //     try {
    //         $list = ListModel::findOrFail($listId);

    //         $validator = Validator::make($request->all(), [
    //             'items' => 'required|array',
    //             'items.*.id' => 'required|exists:list_items,id',
    //             'items.*.position' => 'required|integer|min:1',
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'errors' => $validator->errors()
    //             ], 422);
    //         }

    //         $userId = Auth::id();

    //         DB::transaction(function () use ($validator, $listId, $userId) {

    //             foreach ($validator->validated()['items'] as $item) {

    //                 $listItem = ListItem::where('id', $item['id'])
    //                     ->where('list_id', $listId)
    //                     ->lockForUpdate()
    //                     ->firstOrFail();

    //                 // cast already applied → no json_encode needed
    //                 $positions = $listItem->user_positions ?? [];

    //                 // ensure array
    //                 if (!is_array($positions)) {
    //                     $positions = [];
    //                 }

    //                 // set current user's position
    //                 $positions[$userId] = $item['position'];

    //                 // INAL FIX (manual increment)
    //                 $listItem->user_positions = $positions;
    //                 $listItem->position_updated_count = $listItem->position_updated_count + 1;
    //                 $listItem->save();
    //             }
    //         });

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Reordered Successfully'
    //         ]);

    //     } catch (\Throwable $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Something went wrong',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    /* =========================
       DELETE: Remove Item
    ========================== */
    public function destroy($listId, $itemId)
    {
        try {
            $list = ListModel::findOrFail($listId);
            // $this->authorizeList($list);

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