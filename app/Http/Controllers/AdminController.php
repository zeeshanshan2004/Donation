<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;

class AdminController extends Controller
{


    public function dashboard()
    {
        return view('admin.dashboard');
    }


    // Admin chat dashboard - show all users
    public function getUsersForChat()
    {
        $users = User::where('role', 'user')->get();
        return view('admin.chats.index', compact('users'));
    }

    // Fetch messages for a specific user
    public function getMessages($userId)
    {
        $messages = Message::where(function ($q) use ($userId) {
            $q->where('sender_id', auth()->id())->where('receiver_id', $userId);
        })
            ->orWhere(function ($q) use ($userId) {
                $q->where('sender_id', $userId)->where('receiver_id', auth()->id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    // Send message to user
    public function sendMessage(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->user_id,
            'sender_type' => 'admin',
            'message' => $request->message,
            'is_read' => 0
        ]);

        return response()->json(['success' => true]);
    }
}
