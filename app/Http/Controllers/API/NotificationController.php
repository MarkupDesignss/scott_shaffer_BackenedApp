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

                // Logic for invitations
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
            ->with([
                'sender:id,full_name',
                'sender.profile:user_id,profile_image',
                'receiver:id,full_name',
                'receiver.profile:user_id,profile_image'
            ])
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $notifications->through(function ($n) use ($user) {

                $listId       = $n->data['list_id'] ?? null;
                $actionable   = false;
                $actionStatus = null;
                $canRespond   = false;

                //  List invitation logic (ONLY receiver can act)
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
            
                'sender' => [
                    'id' => $n->sender?->id,
                    'full_name' => $n->sender?->full_name,
                    'profile_image' => $n->sender?->profile?->profile_image
                        ? ( $n->sender->profile->profile_image)
                        : null,
                ],
            
                'receiver' => [
                    'id' => $n->receiver?->id,
                    'full_name' => $n->receiver?->full_name,
                    'profile_image' => $n->receiver?->profile?->profile_image
                        ? ($n->receiver->profile->profile_image)
                        : null,
                ],
            ];
            }),
        ]);
    }
    
    
    public function markAsRead($id)
    {
        try {
    
            $notification = Notification::where('receiver_id', auth()->id())
                ->findOrFail($id);
                
                if(!$notification){
                    return response()->json([
                            'success' => true,
                            'message' => 'No notification found.',
                        ],422);
                }
    
            $notification->update([
                'read_at' => now()
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read successfully.',
                'data'    => $notification
            ]);
    
        } catch (\Exception $e) {
    
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function markAllAsRead()
    {
        try {
    
            Notification::where('receiver_id', auth()->id())
                ->whereNull('read_at')
                ->update([
                    'read_at' => now()
                ]);
    
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read successfully.'
            ]);
    
        } catch (\Exception $e) {
    
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function deleteNotification($id)
    {
        try {
    
            $notification = Notification::where('receiver_id', auth()->id())
                ->findOrFail($id);
                
                 if(!$notification){
                    return response()->json([
                            'success' => true,
                            'message' => 'No notification found.',
                        ],422);
                }
    
            $notification->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully.'
            ]);
    
        } catch (\Exception $e) {
    
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function deleteAllNotifications()
    {
        try {
    
            Notification::where('receiver_id', auth()->id())
                ->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'All notifications deleted successfully.'
            ]);
    
        } catch (\Exception $e) {
    
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
