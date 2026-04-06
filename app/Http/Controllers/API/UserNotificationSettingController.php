<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserNotificationSetting;
use Illuminate\Support\Facades\Auth;

class UserNotificationSettingController extends Controller
{
    /**
     * ✅ Get current user notification settings
     */
    public function index()
    {
        $user = Auth::user();

        // firstOrCreate ensures a settings row exists
        $settings = UserNotificationSetting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'email_notifications' => true,
                'push_notifications' => true,
                'special_announcements' => true,
                'blessing_updates' => true,
                'community_announcements' => true,
                'fcm_token' => null
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Settings fetched successfully',
            'data' => $settings
        ]);
    }

    /**
     * ✅ Update user notification settings
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // validation
        $request->validate([
           'email_notifications' => 'nullable|in:true,false,1,0',
            'push_notifications' => 'nullable|in:true,false,1,0',
            'special_announcements' => 'nullable|in:true,false,1,0',
            'blessing_updates' => 'nullable|in:true,false,1,0',
            'community_announcements' => 'nullable|in:true,false,1,0',
            'fcm_token' => 'nullable|string',
        ]);

        $settings = UserNotificationSetting::updateOrCreate(
            ['user_id' => $user->id],
            [
                'email_notifications' => filter_var($request->email_notifications, FILTER_VALIDATE_BOOLEAN),
                'push_notifications' => filter_var($request->push_notifications, FILTER_VALIDATE_BOOLEAN),
                'special_announcements' => filter_var($request->special_announcements, FILTER_VALIDATE_BOOLEAN),
                'blessing_updates' => filter_var($request->blessing_updates, FILTER_VALIDATE_BOOLEAN),
                'community_announcements' => filter_var($request->community_announcements, FILTER_VALIDATE_BOOLEAN),
                'fcm_token' => $request->fcm_token,
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Settings updated successfully',
            'data' => $settings
        ]);
    }
}
