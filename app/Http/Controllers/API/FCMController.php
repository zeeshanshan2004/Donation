<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class FCMController extends Controller
{
    public static function sendNotification($token, $title, $body)
    {
        $serverKey = env('FCM_SERVER_KEY');

        return Http::withToken($serverKey)->post(
            'https://fcm.googleapis.com/fcm/send',
            [
                'to' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body
                ]
            ]
        )->json();
    }
}
