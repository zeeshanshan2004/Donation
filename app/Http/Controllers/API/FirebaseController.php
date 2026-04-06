<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class FirebaseController extends Controller
{
    /**
     * Send standard FCM notification
     *
     * @param string $token
     * @param string $title
     * @param string $body
     * @return mixed|null
     */
    public static function sendNotification(string $token, string $title, string $body)
    {
        /** @var Messaging $messaging */
        $messaging = App::make(Messaging::class);

        $message = CloudMessage::withTarget('token', $token)
            ->withNotification([
                'title' => $title,
                'body'  => $body,
            ]);

        try {
            return $messaging->send($message);
        } catch (\Exception $e) {
            Log::error('FCM sendNotification error: '.$e->getMessage());
            return null;
        }
    }

    /**
     * Send data-only FCM message for real-time chat
     *
     * @param string $token
     * @param array $data
     * @return mixed|null
     */
    public static function sendDataMessage(string $token, array $data)
    {
        /** @var Messaging $messaging */
        $messaging = App::make(Messaging::class);

        $payload = array_merge([
            'type' => 'chat',
            'timestamp' => now()->toDateTimeString(),
        ], $data);

        $message = CloudMessage::withTarget('token', $token)
            ->withData($payload);

        try {
            return $messaging->send($message);
        } catch (\Exception $e) {
            Log::error('FCM sendDataMessage error: '.$e->getMessage());
            return null;
        }
    }
}
