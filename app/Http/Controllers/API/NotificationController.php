<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ListMember;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Fetch notifications where user is receiver or sender
        $notifications = Notification::where('receiver_id', $user->id)
            ->orWhere('sender_id', $user->id)
            ->with('sender:id,full_name')
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $notifications->through(function ($n) use ($user) {

                $listId       = $n->data['list_id'] ?? null;
                $actionable   = false;
                $actionStatus = null;
                $canRespond   = false;

                // 🎯 Logic for invitations
                if ($n->type === 'list_invite' && $listId) {

                    if ($n->receiver_id === $user->id) {
                        // Auth user is the invitee → can accept/reject
                        $member = \App\Models\ListMember::where([
                            'list_id' => $listId,
                            'user_id' => $user->id,
                        ])->first();

                        if ($member) {
                            $actionable   = true;
                            $actionStatus = $member->status;
                            $canRespond   = $member->status === 'invited';
                        }
                    } elseif ($n->sender_id === $user->id) {
                        // Auth user is the sender → show they sent this notification
                        $actionable   = false;
                        $actionStatus = 'sent';
                        $canRespond   = false;
                    }
                }

                return [
                    'id'            => $n->id,
                    'type'          => $n->type,
                    'title'         => $n->title,
                    'body'          => $n->body,
                    'list_id'       => $listId,
                    'receiver_id'   => $n->receiver_id, // ← added receiver_id
                    'actionable'    => $actionable,
                    'action_status' => $actionStatus,
                    'can_respond'   => $canRespond,
                    'is_read'       => !is_null($n->read_at),
                    'created_at'    => $n->created_at->diffForHumans(),
                    'sender'        => $n->sender,
                ];
            }),
        ]);
    }
    
    
        public function myNotifications(Request $request)
    {
        $user = Auth::user();

        $notifications = Notification::where('receiver_id', $user->id)
            ->with('sender:id,full_name')
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $notifications->through(function ($n) use ($user) {

                $listId       = $n->data['list_id'] ?? null;
                $actionable   = false;
                $actionStatus = null;
                $canRespond   = false;

                // 🎯 List invitation logic (ONLY receiver can act)
                if ($n->type === 'list_invite' && $listId) {

                    $member = \App\Models\ListMember::where([
                        'list_id' => $listId,
                        'user_id' => $user->id,
                    ])->first();

                    if ($member) {
                        $actionable   = true;
                        $actionStatus = $member->status;
                        $canRespond   = $member->status === 'invited';
                    }
                }

                return [
                    'id'            => $n->id,
                    'type'          => $n->type,
                    'title'         => $n->title,
                    'body'          => $n->body,
                    'list_id'       => $listId,
                    'sender_id'     => $n->sender_id,
                    'receiver_id'   => $n->receiver_id,
                    'actionable'    => $actionable,
                    'action_status' => $actionStatus,
                    'can_respond'   => $canRespond,
                    'is_read'       => !is_null($n->read_at),
                    'created_at'    => $n->created_at->diffForHumans(),
                    'sender'        => $n->sender,
                ];
            }),
        ]);
    }
}
