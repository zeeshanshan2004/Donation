<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Google\Client;

// Controllers
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TermConditionController;
use App\Http\Controllers\Admin\CauseController;
use App\Http\Controllers\Frontend\CauseController as FrontCauseController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TestNotificationController;
use App\Http\Controllers\API\FCMController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AdminChatController;
use App\Http\Controllers\Admin\SettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| 🔥 FIREBASE FCM TEST ROUTES
|--------------------------------------------------------------------------
*/
Route::view('/firebase-test', 'firebase-test');
Route::view('/test-fcm', 'test-fcm');

Route::post('/save-fcm-token', function (Request $request) {
    $token = $request->input('token');

    DB::table('fcm_tokens')->updateOrInsert(
        ['token' => $token],
        ['updated_at' => now()]
    );

    return response()->json(['success' => true, 'message' => 'Token saved!']);
})->name('fcm.save');

Route::get('/send-notification', [FCMController::class, 'sendNotification'])->name('fcm.send');
Route::get('/send-browser-notification', function () {
    $token = 'your_device_token_here';
    $credentialsPath = storage_path('app/firebase/donation-app-3ec1f-firebase-adminsdk-fbsvc-139c22eab1.json');

    $client = new Client();
    $client->setAuthConfig($credentialsPath);
    $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    $accessToken = $client->fetchAccessTokenWithAssertion();
    $tokenValue = $accessToken['access_token'];

    $url = 'https://fcm.googleapis.com/v1/projects/donation-app-3ec1f/messages:send';
    $body = [
        'message' => [
            'token' => $token,
            'notification' => [
                'title' => '🔥 Test Push from Laravel',
                'body' => 'Hello Usama! Notification direct browser pe gaya hai 💬',
            ],
            'webpush' => [
                'fcm_options' => ['link' => 'https://google.com']
            ],
        ],
    ];

    $response = Http::withToken($tokenValue)->post($url, $body);

    return [
        'status' => $response->status(),
        'response' => $response->json(),
    ];
})->name('fcm.browser');

Route::get('/send-test-notification', [TestNotificationController::class, 'send'])->name('fcm.test');







Route::get('/user/chat', function () {
    return view('user.chat');
});


/*
|--------------------------------------------------------------------------
| 🧭 ADMIN ROUTES (Auth + Dashboard + Chat)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // 🔐 Login/Logout
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    // 🔒 Protected routes
    Route::middleware(['auth:admin'])->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');


        // FAQs
        Route::resource('faqs', FaqController::class);

        // Categories
        Route::resource('categories', CategoryController::class);

        // Users
        Route::get('users', [UserController::class, 'index'])->name('users.index');

        // Terms & Conditions
        Route::get('terms_conditions/edit', [TermConditionController::class, 'edit'])->name('terms_conditions.edit');
        Route::put('terms_conditions/update', [TermConditionController::class, 'update'])->name('terms_conditions.update');

        // Causes
        Route::resource('causes', CauseController::class);
        Route::post('causes/{cause}/toggle-featured', [CauseController::class, 'toggleFeatured'])->name('causes.toggle-featured');
        Route::post('causes/{cause}/toggle-status', [CauseController::class, 'toggleStatus'])->name('causes.toggle-status');

        // Donations
        Route::get('donations', [\App\Http\Controllers\Admin\DonationController::class, 'index'])->name('donations.index');

        // Packages
        Route::resource('packages', \App\Http\Controllers\Admin\PackageController::class);

        // KYC Management
        Route::get('kyc', [\App\Http\Controllers\Admin\KycController::class, 'index'])->name('kyc.index');
        Route::get('kyc/{id}', [\App\Http\Controllers\Admin\KycController::class, 'show'])->name('kyc.show');
        Route::post('kyc/{id}/approve', [\App\Http\Controllers\Admin\KycController::class, 'approve'])->name('kyc.approve');
        Route::post('kyc/{id}/reject', [\App\Http\Controllers\Admin\KycController::class, 'reject'])->name('kyc.reject');

        // Support Chat
        Route::get('chat', [AdminChatController::class, 'index'])->name('chat.index');
        Route::get('chat/users', [AdminChatController::class, 'users'])->name('chat.users');
        Route::get('chat/messages/{id}', [AdminChatController::class, 'messages'])->name('chat.messages');
        Route::post('chat/send', [AdminChatController::class, 'send'])->name('chat.send');

        // System Settings
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings/update', [SettingController::class, 'update'])->name('settings.update');
    });
});

/*
|--------------------------------------------------------------------------
| 🌐 FRONTEND ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('causes')->name('frontend.causes.')->group(function () {
    Route::get('/', [FrontCauseController::class, 'index'])->name('index');
    Route::get('/{id}', [FrontCauseController::class, 'show'])->name('show');
});
