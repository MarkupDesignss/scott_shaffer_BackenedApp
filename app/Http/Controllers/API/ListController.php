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
use App\Models\UserDevice;

class ListController extends Controller
{
    /* =========================
       Get My Lists (Owner + Group)
    ========================== */
    public function index()
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
                ->with('items.catalogItem')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $lists
            ]);
        } catch (Throwable $e) {
            return $this->serverError($e);
        }
    }


    // public function store(Request $request)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'title'       => 'required|string|max:80',
    //             'category_id' => 'required|exists:catalog_categories,id',
    //             'list_size'   => 'nullable|integer|min:1|max:20',
    //             'is_group'    => 'nullable|boolean',
    //             'user_ids'    => 'nullable|array',
    //             'user_ids.*'  => 'exists:users,id',
    //         ]);

    //         $list = ListModel::create([
    //             'user_id'     => Auth::id(),
    //             'title'       => $validated['title'],
    //             'category_id' => $validated['category_id'],
    //             'list_size'   => $validated['list_size'] ?? null,
    //             'is_group'    => $validated['is_group'] ?? false,
    //         ]);

    //         // Group list → owner as accepted member
    //         if ($list->is_group) {
    //             $list->members()->create([
    //                 'user_id' => Auth::id(),
    //                 'status'  => 'accepted'
    //             ]);

    //             // Invite members if provided
    //             if (!empty($validated['user_ids'])) {
    //                 foreach ($validated['user_ids'] as $userId) {
    //                     ListMember::firstOrCreate(
    //                         [
    //                             'list_id' => $list->id,
    //                             'user_id' => $userId
    //                         ],
    //                         [
    //                             'status' => 'invited'
    //                         ]
    //                     );
    //                 }
    //             }
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'List created successfully',
    //             'data'    => $list->load('members')
    //         ], 201);
    //     } catch (Throwable $e) {
    //         return $this->serverError($e);
    //     }
    // }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title'       => 'required|string|max:80',
                'category_id' => 'required|exists:catalog_categories,id',
                'list_size'   => 'nullable|integer|min:1|max:20',
                'is_group'    => 'nullable|boolean',
                'user_ids'    => 'nullable|array',
                'user_ids.*'  => 'exists:users,id',
            ]);

            $list = ListModel::create([
                'user_id'     => Auth::id(),
                'title'       => $validated['title'],
                'category_id' => $validated['category_id'],
                'list_size'   => $validated['list_size'] ?? null,
                'is_group'    => $validated['is_group'] ?? false,
            ]);

            $firebase = new FirebaseNotificationService();

            // Group list logic
            if ($list->is_group) {
                // Owner
                $list->members()->create([
                    'user_id' => Auth::id(),
                    'status'  => 'accepted'
                ]);

                // Invite members
                if (!empty($validated['user_ids'])) {
                    foreach ($validated['user_ids'] as $userId) {
                        ListMember::firstOrCreate(
                            [
                                'list_id' => $list->id,
                                'user_id' => $userId
                            ],
                            [
                                'status' => 'invited'
                            ]
                        );

                        // 🔔 Firebase Notification
                        $firebase->sendToUser(
                            $userId,
                            'You are invited to a list',
                            Auth::user()->name . ' invited you to join "' . $list->title . '"',
                            [
                                'list_id' => (string) $list->id,
                                'type'    => 'list_invite'
                            ]
                        );
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'List created successfully',
                'data'    => $list->load('members')
            ], 201);
        } catch (\Throwable $e) {
            return $this->serverError($e);
        }
    }

    // public function registerDevice(Request $request)
    // {
    //     $validated = $request->validate([
    //         'user_id'      => 'required|exists:users,id',
    //         'device_token' => 'required|string',
    //         'device_type'  => 'nullable|string',
    //     ]);

    //     UserDevice::updateOrCreate(
    //         ['user_id' => $validated['user_id']],
    //         [
    //             'device_token' => $validated['device_token'],
    //             'device_type'  => $validated['device_type'] ?? 'android',
    //         ]
    //     );

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Device registered successfully'
    //     ]);
    // }


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
    public function update(Request $request, $id)
    {
        try {
            $list = ListModel::findOrFail($id);
            $this->authorizeList($list, true);

            $validated = $request->validate([
                'title' => 'sometimes|string|max:80'
            ]);

            $list->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'List updated',
                'data' => $list
            ]);
        } catch (Throwable $e) {
            return $this->serverError($e);
        }
    }

    /* =========================
       Delete List
    ========================== */
    public function destroy($id)
    {
        try {
            $list = ListModel::findOrFail($id);
            $this->authorizeList($list);

            $list->delete();

            return response()->json([
                'success' => true,
                'message' => 'List deleted'
            ]);
        } catch (Throwable $e) {
            return $this->serverError($e);
        }
    }

    /* =========================
       Invite Uers List
    ========================== */

    public function inviteUserList()
    {
        try {
            $users = User::where('id', '!=', Auth::id())
                ->select('id', 'full_name', 'email')
                ->get();

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
}