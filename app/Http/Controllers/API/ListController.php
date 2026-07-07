<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ListModel;
use App\Models\ListMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Services\FirebaseNotificationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;
use App\Models\Notification;
use App\Models\UserDevice;
use Illuminate\Support\Facades\DB;
use App\Models\SubCategory;
use App\Models\CatalogItem;
use App\Models\ListItem;
use App\Models\UserListPosition;
use App\Models\CatalogCategory;

class ListController extends Controller
{
    /* =========================
       Get My Lists (Owner + Group)
    ========================== */ 
    
//     public function index()
// {
//     try {
//         $user = Auth::user();

//         if (!$user) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Unauthorized'
//             ], 401);
//         }

//         $lists = ListModel::where(function ($q) use ($user) {
//                 $q->where('lists.user_id', $user->id) // FIX HERE
//                   ->orWhereHas('members', function ($m) use ($user) {
//                       $m->where('list_members.user_id', $user->id) 
//                         ->where('list_members.status', 'accepted'); 
//                   });
//             })
//             ->leftJoin('user_list_positions as ulp', function ($join) use ($user) {
//                 $join->on('lists.id', '=', 'ulp.list_id')
//                      ->where('ulp.user_id', $user->id);
//             })
//             ->with('items.catalogItem')
//             ->select('lists.*', \DB::raw('COALESCE(ulp.position, lists.id) as final_position'))
//             ->orderBy('final_position')
//             ->get();

//         return response()->json([
//             'success' => true,
//             'data' => $lists
//         ]);

//     } catch (Throwable $e) {
//         return $this->serverError($e);
//     }
// }
    public function index()
    {
        try {
            $user = Auth::user();
    
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }
    
            $lists = ListModel::where(function ($q) use ($user) {
                    $q->where('lists.user_id', $user->id)
                      ->orWhereHas('members', function ($m) use ($user) {
                          $m->where('list_members.user_id', $user->id)
                            ->where('list_members.status', 'accepted');
                      });
                })
                ->leftJoin('user_list_positions as ulp', function ($join) use ($user) {
                    $join->on('lists.id', '=', 'ulp.list_id')
                         ->where('ulp.user_id', $user->id);
                })
                ->with('items.catalogItem')
                ->select(
                    'lists.*',
                    DB::raw('COALESCE(ulp.position, lists.id) as final_position')
                )
                ->orderBy('final_position')
                ->get();
    
            foreach ($lists as $list) {
    
                // If this is a cloned list, show original list items
                if (!empty($list->cloned_from_id)) {
    
                    $originalList = ListModel::with('items.catalogItem')
                        ->find($list->cloned_from_id);
    
                    if ($originalList) {
                        $list->setRelation('items', $originalList->items);
                    }
                }
            }
    
            return response()->json([
                'success' => true,
                'data' => $lists
            ]);
    
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    public function reorderLists(Request $request)
{
    try {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $request->validate([
            'lists' => 'required|array',
            'lists.*.id' => 'required|exists:lists,id',
            'lists.*.position' => 'required|integer|min:1'
        ]);

        foreach ($request->lists as $list) {

            // Check user has access to list (owner or member)
            $isAllowed = ListModel::where('id', $list['id'])
                ->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhereHas('members', function ($q2) use ($user) {
                          $q2->where('user_id', $user->id);
                      });
                })
                ->exists();

            if (!$isAllowed) {
                continue;
            }

            UserListPosition::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'list_id' => $list['id']
                ],
                [
                    'position' => $list['position']
                ]
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'Lists reordered successfully (user-specific)'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
    
   public function search(Request $request)
{
    try {
        $user = Auth::user();
        $query = $re=uest->query('q');

        if (!$query) {
            return response()->json([
                'success' => false,
                'message' => 'Search query is required'
            ], 422);
        }

        $lists = ListModel::where(function ($q) use ($user) {
                // own lists
                $q->where('user_id', $user->id)

                // member lists (accepted)
                ->orWhereHas('members', function ($m) use ($user) {
                    $m->where('user_id', $user->id)
                      ->where('status', 'accepted');
                });
            })
            ->where(function ($q) use ($query) {

                // search by list title
                $q->where('title', 'like', "%{$query}%")

                // search by list items (catalog items)
                ->orWhereHas('items.catalogItem', function ($item) use ($query) {
                    $item->where('name', 'like', "%{$query}%");
                });
            })
            ->with('items.catalogItem')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $lists
        ], 200);

    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'Search failed',
            'error'   => $e->getMessage()
        ], 500);
    }
}

public function store(Request $request)
{
    DB::beginTransaction();

    try {
        $validated = $request->validate([
            'title'           => 'required|string|max:80',
            'category_id'     => 'required|exists:catalog_categories,id',
            'sub_category_id' => 'nullable',
            'list_size'       => 'nullable|integer|min:1',
            'is_group'        => 'nullable|boolean',
            'user_ids'        => 'nullable|array',
            'user_ids.*'      => 'exists:users,id',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Group logic
        |--------------------------------------------------------------------------
        */
        $inviteUserIds = collect($validated['user_ids'] ?? [])
            ->unique()
            ->reject(fn ($id) => $id == Auth::id())
            ->values();

        $isGroup = $inviteUserIds->isNotEmpty()
            ? true
            : ($validated['is_group'] ?? false);

        /*
        |--------------------------------------------------------------------------
        | Prepare sub_category value
        |--------------------------------------------------------------------------
        */
        $subCategoryValue = null;

        if (isset($validated['sub_category_id'])) {
            $subCategoryValue = is_array($validated['sub_category_id'])
                ? json_encode($validated['sub_category_id'])
                : (string) $validated['sub_category_id'];
        }

        /*
        |--------------------------------------------------------------------------
        | Create List
        |--------------------------------------------------------------------------
        */
        $list = ListModel::create([
            'user_id'         => Auth::id(),
            'title'           => $validated['title'],
            'category_id'     => $validated['category_id'],
            'sub_category_id' => $subCategoryValue,
            'list_size'       => $validated['list_size'] ?? null,
            'is_group'        => $isGroup,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Group creator + invited users
        |--------------------------------------------------------------------------
        */
        if ($isGroup) {

            // Creator
            ListMember::firstOrCreate(
                ['list_id' => $list->id, 'user_id' => Auth::id()],
                ['status' => 'accepted']
            );

            // Invited users + notification
            foreach ($inviteUserIds as $userId) {

                ListMember::firstOrCreate(
                    [
                        'list_id' => $list->id,
                        'user_id' => $userId
                    ],
                    [
                        'status' => 'invited'
                    ]
                );

                // Insert notification
                DB::table('notifications')->insert([
                    'sender_id'   => Auth::id(),
                    'receiver_id' => $userId,
                    'type'        => 'list_invite',
                    'title'       => 'List Invitation',
                    'body'        => 'You have been invited to join a list: ' . $list->title,
                    'data'        => json_encode([
                        'list_id' => $list->id
                    ]),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'List created successfully',
            'data' => [
                'id'             => $list->id,
                'title'          => $list->title,
                'category_id'    => $list->category_id,
                'sub_category'   => $list->sub_category_id,
                'is_group'       => (bool) $list->is_group,
                'list_size'      => $list->list_size,
            ]
        ], 201);

    } catch (\Throwable $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Failed to create list',
            'error'   => config('app.debug') ? $e->getMessage() : null,
        ], 500);
    }
}
    /* =========================
       Show List
    ========================== */
    public function show($id)
    {
        try {
            $list = ListModel::with('items.catalogItem')->findOrFail($id);
            $this->authorizeList($list);

            return response()->json([
                'success' => true,
                'data' => $list
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'List not found'], 404);
        } catch (Throwable $e) {
            return $this->serverError($e);
        }
    }

    /* =========================
       Update List
    ========================== */
    // public function update(Request $request, $id)
    // {
    //     try {
    //         $list = ListModel::findOrFail($id);
    //         // $this->authorizeList($list, true);

    //         $validated = $request->validate([
    //             'title' => 'sometimes|string|max:80'
    //         ]);
                
    //         $list->update($validated);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'List updated',
    //             'data' => $list
    //         ]);
    //     } catch (Throwable $e) {
    //         return $this->serverError($e);
    //     }
    // }
    
    public function update(Request $request, $id)
    {
        try {
    
            $list = ListModel::findOrFail($id);
    
            $validated = $request->validate([
                'title'           => 'sometimes|string|max:80',
                'category_id'     => 'sometimes|exists:catalog_categories,id',
                'sub_category_id' => 'nullable',
                'list_size'       => 'nullable|integer|min:1',
            ]);
    
            /*
            |--------------------------------------------------------------------------
            | Prepare sub_category value
            |--------------------------------------------------------------------------
            */
            if (array_key_exists('sub_category_id', $validated)) {
    
                $validated['sub_category_id'] = is_array($validated['sub_category_id'])
                    ? json_encode($validated['sub_category_id'])
                    : $validated['sub_category_id'];
            }
    
            $list->update($validated);
    
            return response()->json([
                'success' => true,
                'message' => 'List updated successfully',
                'data'    => $list->fresh()
            ]);
    
        } catch (\Throwable $e) {
    
            return response()->json([
                'success' => false,
                'message' => 'Failed to update list',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /* =========================
       Delete List
    ========================== */
    public function destroy($id)
{
    try {
        $user = Auth::user();

        $list = ListModel::where('id', $id)
            // ->where('user_id', $user->id)
            ->first();
            // dd($list);           
        if (!$list) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized or list not found'
            ], 403);
        }

        $list->delete();

        return response()->json([
            'success' => true,
            'message' => 'List deleted successfully'
        ], 200);

    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'List not deleted',
            'error'   => $e->getMessage()
        ], 500);
    }
}
    
    public function inviteUserList(Request $request) // Request injection for category_id
    {
        try {
            $categoryId = $request->input('category_id');

            // Base query: active users except current user
            $query = User::where('id', '!=', Auth::id())
                        ->where('status', true);

            // If category_id is provided, filter users by that category's interest
            if ($categoryId) {
                // Fetch the interest_id from catalog_categories for given category_id
                $category = CatalogCategory::find($categoryId);
                
                if ($category && $category->interest_id) {
                    $interestId = $category->interest_id;
                    
                    // Join user_interest to get users having that interest_id
                    $query->whereHas('interests', function ($q) use ($interestId) {
                        $q->where('interest_id', $interestId);
                    });
                } else {
                    // If category not found or has no interest_id, return empty list or handle as needed
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid category or no interest linked.'
                    ]);
                }
            }

            $users = $query->select('id', 'full_name', 'email')->get();

            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        } catch (Throwable $e) {
            return $this->serverError($e);
        }
    }

    /* =========================
       Invite Members
    ========================== */
    public function inviteMembers(Request $request, $listId)
    {
        try {
            $list = ListModel::findOrFail($listId);
            $this->authorizeList($list);

            $request->validate([
                'user_ids' => 'required|array',
                'user_ids.*' => 'exists:users,id'
            ]);

            foreach ($request->user_ids as $userId) {
                ListMember::firstOrCreate(
                    [
                        'list_id' => $listId,
                        'user_id' => $userId
                    ],
                    [
                        'status' => 'invited'
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Members invited'
            ]);
        } catch (Throwable $e) {
            return $this->serverError($e);
        }
    }

    /* =========================
       My Invitations
    ========================== */
    public function myInvitations()
    {
        try {
            $invites = ListMember::with('list')
                ->where('user_id', Auth::id())
                ->where('status', 'invited')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $invites
            ]);
        } catch (Throwable $e) {
            return $this->serverError($e);
        }
    }

    /* =========================
       Accept Invite
    ========================== */
    public function acceptInvite($listId)
    {
        try {
            ListMember::where('list_id', $listId)
                ->where('user_id', Auth::id())
                ->where('status', 'invited')
                ->update(['status' => 'accepted']);

            return response()->json([
                'success' => true,
                'message' => 'Invitation accepted'
            ]);
        } catch (Throwable $e) {
            return $this->serverError($e);
        }
    }

    /* =========================
       Reject Invite
    ========================== */
    public function rejectInvite($listId)
    {
        try {
            ListMember::where('list_id', $listId)
                ->where('user_id', Auth::id())
                ->where('status', 'invited')
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Invitation rejected'
            ]);
        } catch (Throwable $e) {
            return $this->serverError($e);
        }
    }

    /* =========================
       Remove Member (Owner)
    ========================== */
    public function removeMember($listId, $userId)
    {
        try {
            $list = ListModel::findOrFail($listId);
            $this->authorizeList($list);

            ListMember::where('list_id', $listId)
                ->where('user_id', $userId)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Member removed'
            ]);
        } catch (Throwable $e) {
            return $this->serverError($e);
        }
    }

    /* =========================
       Leave Group
    ========================== */
    public function leaveGroup($listId)
    {
        try {
            ListMember::where('list_id', $listId)
                ->where('user_id', Auth::id())
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'You left the group'
            ]);
        } catch (Throwable $e) {
            return $this->serverError($e);
        }
    }

    /* =========================
       Authorization Helper
    ========================== */
    private function authorizeList(ListModel $list, bool $allowEditors = false)
    {
        if ($list->user_id === Auth::id()) {
            return true;
        }

        if ($list->is_group) {
            $isMember = $list->members()
                ->where('user_id', Auth::id())
                ->where('status', 'accepted')
                ->exists();

            if ($isMember) {
                return true;
            }
        }

        abort(403, 'Unauthorized');
    }

    private function serverError(Throwable $e)
    {
        logger()->error($e);

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong',
            'error' => $e->getMessage()
        ], 500);
    }


    /* =========================
   Publish Multiple Lists
========================= */
    public function publishLists(Request $request)
    {
        try {
            $request->validate([
                'list_ids' => 'required|array',
                'list_ids.*' => 'integer',
            ]);

            $userId = Auth::id();
            $requestedIds = $request->list_ids;

            // Owner lists only
            $lists = ListModel::whereIn('id', $requestedIds)
                ->where('user_id', $userId)
                ->get();

            $updatedIds = $lists->pluck('id')->toArray();
            $invalidIds = array_diff($requestedIds, $updatedIds);

            // Update status & visibility
            $lists->each(function ($list) {
                $list->update([
                    'status' => 'published',
                    'visibility' => 'public'
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Lists processed',
                'published_lists' => $lists,
            ]);
        } catch (Throwable $e) {
            return $this->serverError($e);
        }
    }

public function allPublishedList()
{
    try {
        $user = Auth::user();
        $baseUrl = 'https://www.markupdesigns.net/scott-shafer/storage/';

        $lists = ListModel::where('status', 'published')
            ->where(function ($q) use ($user) {
                $q->where('visibility', 'public')
                  ->orWhere('user_id', $user->id);
            })
            ->with([
                'items.catalogItem',
                'likes',
                'shares',
            ])
            ->withCount([
                'likes',
                'shares',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $lists->each(function ($list) use ($user, $baseUrl) {

            //  is_liked flag
            $list->is_liked = $user
                ? $list->likes->contains('user_id', $user->id)
                : false;

            //  share url
            $list->share_url = url('/published-lists/' . $list->id);

            //  image url fix
            $list->items->each(function ($item) use ($baseUrl) {
                if (
                    $item->catalogItem &&
                    $item->catalogItem->image_url &&
                    !str_starts_with($item->catalogItem->image_url, 'http')
                ) {
                    $item->catalogItem->image_url =
                        $baseUrl . $item->catalogItem->image_url;
                }
            });

            /**
             *  SHOW sub_category_id DIRECTLY FROM LISTS TABLE
             * (string / longtext – jo bhi save hai)
             */
            $list->sub_category_id = $list->sub_category_id;

            unset($list->likes, $list->shares);
        });

        return response()->json([
            'success' => true,
            'data'    => $lists
        ], 200);

    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch published lists',
            'error'   => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}



public function singlePublishedList(Request $request)
{
    try {
        $user = Auth::user();
        $baseUrl = 'https://www.markupdesigns.net/scott-shafer/storage/';
        $listIds = $request->input('list_ids'); // array
        

        if (!is_array($listIds) || empty($listIds)) {
            return response()->json([
                'success' => false,
                'message' => 'list_ids array is required'
            ], 422);
        }

        $lists = ListModel::where('status', 'published')
            ->whereIn('id', $listIds)
            ->where(function ($q) use ($user) {
                $q->where('visibility', 'public')
                  ->orWhere('user_id', $user->id);
            })
            ->with([
                'items.catalogItem',
                'likes',
                'shares',
            ])
            ->withCount([
                'likes',
                'shares',
            ])
            ->get();

        $lists->each(function ($list) use ($user, $baseUrl) {

            $list->is_liked = $user
                ? $list->likes->contains('user_id', $user->id)
                : false;

            $list->share_url = url('/published-lists/' . $list->id);

            $list->items->each(function ($item) use ($baseUrl) {
                if (
                    $item->catalogItem &&
                    $item->catalogItem->image_url &&
                    !str_starts_with($item->catalogItem->image_url, 'http')
                ) {
                    $item->catalogItem->image_url =
                        $baseUrl . $item->catalogItem->image_url;
                }
            });

            unset($list->likes, $list->shares);
        });
// dd($lists);
        return response()->json([
            'success' => true,
            'message' =>'List published successfully',
            'data' => $lists
        ], 200);

    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch published lists',
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Clone a published/featured list for the authenticated user.
 *
 * @param int $originalListId
 * @return \Illuminate\Http\JsonResponse
 */
    public function cloneList($originalListId)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // Find the original list (must be published or featured)
            $originalList = ListModel::where('id', $originalListId)
                ->where('status', 'published')   // Only published lists can be cloned
                ->first();

            if (!$originalList) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only published list can be cloned',
                ], 404);
            }

            DB::beginTransaction();

            // Create cloned list (user becomes owner)
               $clonedList = ListModel::create([
                'user_id'         => $user->id,
                'title'           => $originalList->title . ' (Copy)',
                'category_id'     => $originalList->category_id,
                'sub_category_id' => $originalList->sub_category_id,
                'list_size'       => $originalList->list_size,
                'is_group'        => false,
                'status'          => 'draft',
                'visibility'      => 'private',
                'cloned_from_id'  => $originalList->id, // Original list ID
            ]);

            // Clone list items (pivot table)
            $originalItems = ListItem::where('list_id', $originalList->id)->get();
            foreach ($originalItems as $item) {
                ListItem::create([
                    'list_id'         => $clonedList->id,
                    'catalog_item_id' => $item->catalog_item_id,
                    'quantity'        => $item->quantity,
                    'order'           => $item->order,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'List cloned successfully',
                'data'    => $clonedList->load('items.catalogItem')
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to clone list',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }




    public function accept(Request $request)
    {
        $user   = Auth::user();
        
        $listId = $request->list_id;
    
        DB::beginTransaction();
    
        try {
            $member = ListMember::where([
                'list_id' => $listId,
                'user_id' => $user->id,
                'status'  => 'invited',
            ])->first();
    
            if (!$member) {
                DB::rollBack();
                return response()->json([
                    'success' => true,
                    'message' => 'Invitation not found or already handled',
                ]);
            }
    
            $member->update(['status' => 'accepted']);
    
            //  Delete invite notification
            Notification::where('receiver_id', $user->id)
                ->where('type', 'list_invite')
                ->whereJsonContains('data->list_id', $listId)
                ->update(['read_at' => now()]);
                //->delete();
    
            $list = ListModel::with('user')->findOrFail($listId);
    
            Notification::create([
                'sender_id'   => $user->id,
                'receiver_id' => $list->user_id,
                'type'        => 'list_invite_accepted',
                'title'       => 'Invitation Accepted',
                'body'        => $user->full_name . ' accepted your list invitation',
                'data'        => ['list_id' => $listId],
                'read_at'     => now(),
            ]);
    
            DB::commit();
    
            (new FirebaseNotificationService())->sendToUser(
                $list->user_id,
                'Invitation Accepted',
                $user->full_name . ' accepted your list invitation',
                [
                    'type'    => 'list_invite_accepted',
                    'list_id' => (string) $listId,
                ]
            );
    
            return response()->json([
                'success' => true,
                'message' => 'Invitation accepted successfully',
                'user'    => $user,
            ]);
    
        } catch (\Throwable $e) {
            DB::rollBack();
    
            return response()->json([
                'success' => false,
                'message' => 'Failed to accept invitation',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function reject(Request $request)
    {
        $user   = Auth::user();
        $listId = $request->list_id;
    
        DB::beginTransaction();
    
        try {
            $member = ListMember::where([
                'list_id' => $listId,
                'user_id' => $user->id,
                'status'  => 'invited',
            ])->first();
    
            if (!$member) {
                DB::rollBack();
                return response()->json([
                    'success' => true,
                    'message' => 'Invitation not found or already handled',
                ]);
            }
    
            // Reject invite
            $member->update(['status' => 'rejected']);
    
            // Delete invite notification (same as accept)
            Notification::where('receiver_id', $user->id)
                ->where('type', 'list_invite')
                ->whereJsonContains('data->list_id', $listId)
                ->update(['read_at' => now()]);
                //->delete();
    
            // Fetch list owner
            $list = ListModel::with('user')->findOrFail($listId);
    
            // Notify owner
            Notification::create([
                'sender_id'   => $user->id,
                'receiver_id' => $list->user_id,
                'type'        => 'list_invite_rejected',
                'title'       => 'Invitation Rejected',
                'body'        => $user->full_name . ' rejected your list invitation',
                'data'        => ['list_id' => $listId],
                'read_at'     => now(),
            ]);
    
            DB::commit();
    
            // Firebase push
            (new FirebaseNotificationService())->sendToUser(
                $list->user_id,
                'Invitation Rejected',
                $user->full_name . ' rejected your list invitation',
                [
                    'type'    => 'list_invite_rejected',
                    'list_id' => (string) $listId,
                ]
            );
    
            return response()->json([
                'success' => true,
                'message' => 'Invitation rejected successfully',
                'user'    => $user,
            ]);
    
        } catch (\Throwable $e) {
            DB::rollBack();
    
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject invitation',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


}
