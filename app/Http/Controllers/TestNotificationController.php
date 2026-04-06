<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class TestNotificationController extends Controller
{
    public function send()
    {
        try {
            // Step 1: Firebase service account load
            $factory = (new Factory)->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));
            $messaging = $factory->createMessaging();

            // Step 2: Topic choose karo (sab subscribers is topic pe receive karenge)
            $topic = 'test-topic';

            // Step 3: Notification banate hain
            $notification = Notification::create(
                '🔥 Laravel Firebase Working!',
                'This is a test notification from your Laravel backend (topic-based).'
            );

            // Step 4: Message banate hain
            $message = CloudMessage::withTarget('topic', $topic)
                ->withNotification($notification)
                ->withData([
                    'screen' => 'cause_detail',
                    'extra' => 'topic_message'
                ]);

            // Step 5: Send
            $messaging->send($message);

            return response()->json(['success' => true, 'message' => 'Notification sent to topic!']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
