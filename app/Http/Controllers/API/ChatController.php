<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Send message (User side)
    public function sendMessage(Request $request)
    {
        // Try to get authenticated user via API guard
        $user = Auth::guard('api')->user();

        // If not API user, maybe it's session-based admin testing?
        if (!$user && auth('admin')->check()) {
            $user = auth('admin')->user();
            $senderType = 'admin';
        } else {
            $senderType = 'user';
        }

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'receiver_id' => 'required',
            'message' => 'required'
        ]);

        // Create message
        $msg = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $request->receiver_id,
            'sender_type' => $senderType,
            'message' => $request->message,
        ]);

        return response()->json(['message' => 'Message sent', 'data' => $msg]);
    }

    // Get chat (User side perspective) - Full history with specific ID
    public function getMessages($receiver_id)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $myId = $user->id;
        $targetId = $receiver_id;

        // Fetch ALL messages between this user and the specific target (Admin)
        $messages = Message::where(function ($q) use ($myId, $targetId) {
            $q->where('sender_id', $myId)->where('sender_type', 'user')->where('receiver_id', $targetId);
        })
            ->orWhere(function ($q) use ($myId, $targetId) {
                $q->where('sender_id', $targetId)->where('sender_type', 'admin')->where('receiver_id', $myId);
            })
            ->orderBy('id', 'ASC')
            ->get();

        // Mark incoming messages from this specific target as read
        Message::where('sender_id', $targetId)
            ->where('sender_type', 'admin')
            ->where('receiver_id', $myId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'status' => true,
            'messages' => $messages
        ]);
    }

    // Get total unread count for the authenticated user
    public function unreadCount()
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $count = Message::where('receiver_id', $user->id)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->count();

        return response()->json([
            'status' => true,
            'unread_count' => $count
        ]);
    }
}
