<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\FaqController;
use App\Http\Controllers\API\CauseController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\TermConditionController;
use App\Http\Controllers\API\UserNotificationSettingController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\Frontend\CauseController as FrontCauseController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\API\DonationController;
use App\Http\Controllers\API\StripeWebhookController;
use App\Http\Controllers\API\KycController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------|
*/

// Example admin save-fcm route (kept as you had it)
Route::post('/save-fcm-token', function (Request $request) {
    $request->validate([
        'token' => 'required|string',
    ]);

    DB::table('users')
        ->where('id', auth()->id())
        ->update(['fcm_token' => $request->token]);

    return response()->json(['success' => true]);
})->middleware('auth:sanctum')->name('api.fcm.save');

// AUTH prefix
Route::prefix('auth')->group(function () {

    // Public
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);

    // Forgot / Reset
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('verify-forgot-otp', [AuthController::class, 'verifyForgotOtp']);

    // Protected (JWT)
    Route::middleware('auth:api')->group(function () {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('logout', [AuthController::class, 'logout']);
        // KYC
        Route::post('kyc/submit-details', [KycController::class, 'submitDetails']);
        Route::post('kyc/confirm-agreement', [KycController::class, 'confirmAgreement']);
        Route::get('kyc/status', [KycController::class, 'status']);
        Route::post('update-profile', [AuthController::class, 'updateProfile']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
        Route::post('save-token', [AuthController::class, 'saveFcmToken']);
        Route::post('confirm-payment', [AuthController::class, 'confirmPayment']);

        // CHAT routes under protected middleware (so client must send Bearer token)
        Route::post('chat/send', [ChatController::class, 'sendMessage']);
        Route::get('chat/messages/{receiver_id}', [ChatController::class, 'getMessages']);
        Route::get('chat/unread-count', [ChatController::class, 'unreadCount']);
        Route::post('save-fcm-token', [ChatController::class, 'saveFcmToken']);

    });

    // If you intentionally want public chat routes (no auth), move above routes outside middleware.
});


Route::middleware('auth:api')->group(function () {
    Route::post('/change-password', [AuthController::class, 'changePassword']);
});

Route::prefix('causes')->name('api.causes.')->group(function () {
    Route::get('/', [CauseController::class, 'index'])->name('index');
    Route::get('/{id}', [CauseController::class, 'show'])->name('show');
});

Route::get('categories', [CategoryController::class, 'index']);
Route::get('terms', [TermConditionController::class, 'index']);
Route::get('faqs', [FaqController::class, 'index']);
Route::post('notification-settings', [UserNotificationSettingController::class, 'update']);

// Stripe Webhook (Public)
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);






Route::middleware('auth:api')->group(function () {
    Route::post('/donations/create-intent', [DonationController::class, 'createIntent']);
    Route::get('/donations', [DonationController::class, 'history']);

// Removed duplicate KYC route
});


Route::middleware('auth:api')->group(function () {
    Route::get('/reports/dashboard', [App\Http\Controllers\API\ReportController::class, 'index']);
});

// ✅ Packages API (Public)
Route::get('/packages', [App\Http\Controllers\Api\PackageController::class, 'index']);
Route::get('/packages/{id}', [App\Http\Controllers\Api\PackageController::class, 'show']);

Route::middleware('auth:api')->group(function () {
    Route::post('/packages/subscribe', [App\Http\Controllers\API\PackageOrderController::class, 'subscribe']);
    Route::get('/packages/my-orders', [App\Http\Controllers\API\PackageOrderController::class, 'myOrders']);
});


Route::get('/Dashboard', [App\Http\Controllers\Api\DashboardController::class, 'show']);
