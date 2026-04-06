<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminChatController extends Controller
{
    // Admin Chat Dashboard
    public function index()
    {
        return view('admin.support.chat');
    }

    // List all users with unread message counts, sorted by latest message
    public function users()
    {
        $adminId = Auth::guard('admin')->id();

        // Get users and join with the latest message timestamp
        $users = User::select('users.id', 'users.name')
            ->selectSub(function ($query) use ($adminId) {
                $query->from('messages')
                    ->select('created_at')
                    ->where(function ($q) use ($adminId) {
                        $q->whereColumn('sender_id', 'users.id')
                            ->where('sender_type', 'user')
                            ->where('receiver_id', $adminId);
                    })
                    ->orWhere(function ($q) use ($adminId) {
                        $q->whereColumn('receiver_id', 'users.id')
                            ->where('sender_id', $adminId)
                            ->where('sender_type', 'admin');
                    })
                    ->orderBy('created_at', 'DESC')
                    ->limit(1);
            }, 'latest_message_at')
            ->orderBy('latest_message_at', 'DESC')
            ->get();

        $users->map(function ($user) use ($adminId) {
            $user->unread_count = Message::where('sender_id', $user->id)
                ->where('sender_type', 'user')
                ->where('receiver_id', $adminId)
                ->where('is_read', false)
                ->count();
            return $user;
        });

        return $users;
    }

    // Fetch chat messages between admin and a specific user
    public function messages($userId)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return response()->json(['error' => 'Admin not logged in'], 401);
        }

        // Mark incoming messages as read
        Message::where('sender_id', $userId)
            ->where('sender_type', 'user')
            ->where('receiver_id', $admin->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return Message::where(function ($q) use ($userId, $admin) {
            $q->where('sender_id', $admin->id)
                ->where('sender_type', 'admin')
                ->where('receiver_id', $userId);
        })->orWhere(function ($q) use ($userId, $admin) {
            $q->where('sender_id', $userId)
                ->where('sender_type', 'user')
                ->where('receiver_id', $admin->id);
        })->orderBy('id', 'ASC')->get();
    }

    // Send message from admin to user
    public function send(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return response()->json(['error' => 'Admin not logged in'], 401);
        }

        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);

        // Save message in database
        $msg = Message::create([
            'sender_id' => $admin->id,
            'receiver_id' => $request->receiver_id,
            'sender_type' => 'admin',
            'message' => $request->message,
        ]);

        return response()->json(['status' => 'sent', 'data' => $msg]);
    }
}
