<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * ðŸ”¹ Register new user with OTP
     */
    public function register(RegisterRequest $request)
    {
        try {
            $otp = rand(1000, 9999); // ✅ 4 digit OTP

            // Check for referral code
            $referredBy = null;
            if ($request->filled('referral_code')) {
                $referrer = User::where('referral_code', $request->referral_code)->first();
                if ($referrer) {
                    $referredBy = $referrer->id;
                }
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'otp' => $otp,
                'is_verified' => 0, // default not verified
                'kyc_status' => false, // default not approved
                'referred_by' => $referredBy,
            ]);

            // ✅ Automatically create KYC submission entry
            \App\Models\KycSubmission::create([
                'user_id' => $user->id,
                'country_of_residence' => '',
                'full_legal_name' => $request->name,
                'date_of_birth' => now()->subYears(18), // Default placeholder
                'residential_address' => '',
                'photo_id_type' => 'passport',
                'photo_id_path' => '',
                'face_photo_path' => '',
                'status' => 'pending',
            ]);

            // 🔹 Send OTP email
            // Mail::to($user->email)->send(new OtpMail($user->name, $otp));

            return response()->json([
                'status' => true,
                'message' => 'User registered successfully! OTP has been sent to your email.',
                'data' => $user,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Registration failed!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ðŸ”¹ Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:4',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->otp == $request->otp) {
            $user->is_verified = 1;
            $user->otp = null; // ✅ OTP clear after success
            $user->save();

            // ✅ Calculate Payable Fee
            $baseFee = \App\Models\Setting::where('key', 'signup_fee')->value('value') ?? 50;
            $payableFee = $baseFee;

            if ($user->referred_by) {
                $discountPercent = \App\Models\Setting::where('key', 'referral_discount_percentage')->value('value') ?? 10;
                $discountAmount = ($baseFee * $discountPercent) / 100;
                $payableFee = max(0, $baseFee - $discountAmount);
            }

            // 🔹 Generate JWT token for verified user
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'status' => true,
                'message' => 'OTP verified successfully. You are now logged in.',
                'token' => $token,
                'type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'user' => $user,
                'payable_fee' => (float) $payableFee,
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid OTP',
        ], 400);
    }

    /**
     * ðŸ”¹ Login user & return JWT token
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            // $user = User::where('email', $credentials['email'])->first();
            $user = User::with('notificationSetting')
                ->where('email', $credentials['email'])
                ->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found',
                ], 404);
            }

            if (!$user->is_verified) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please verify your OTP before login.',
                ], 403);
            }

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid email or password',
                ], 401);
            }

            // ✅ Check KYC status
            $kycRequired = !$user->isKycApproved();

            return response()->json([
                'status' => true,
                'token' => $token,
                'type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'data' => $user,
                'kyc_required' => $kycRequired,
                'kyc_status' => $user->kyc_status,
            ]);

        } catch (JWTException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Could not create token',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ðŸ”¹ Get user profile
     */
    public function profile()
    {
        try {
            return response()->json([
                'status' => true,
                'user' => auth('api')->user(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Could not fetch user profile',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * ðŸ”¹ Logout user
     */
    public function logout()
    {
        try {
            auth('api')->logout();

            return response()->json([
                'status' => true,
                'message' => 'Successfully logged out',
            ]);

        } catch (JWTException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Logout failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * ðŸ”¹ Forgot Password - generate OTP
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // ðŸ”¹ Generate 4-digit OTP
        $otp = rand(1000, 9999);
        $user->otp = $otp;
        $user->save();

        // ðŸ”¹ Send OTP via email
        Mail::to($user->email)->send(new OtpMail($user->name, $otp));

        return response()->json([
            'status' => true,
            'message' => 'OTP sent to your email for password reset.',
        ]);
    }

    /**
     * ðŸ”¹ Verify Forgot Password OTP (returns JWT token)
     */
    public function verifyForgotOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:4',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], 404);
        }

        if ($user->otp == $request->otp) {
            $user->otp = null; // âœ… Clear OTP after success
            $user->save();

            // ðŸ”¹ Generate JWT token
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'status' => true,
                'message' => 'OTP verified successfully. Use this token to reset your password.',
                'token' => $token,
                'type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'user' => $user,
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid OTP.',
        ], 400);
    }

    /**
     * ðŸ”¹ Reset Password (token required, no email)
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = auth('api')->user(); // âœ… Get user from token

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized or invalid token.',
            ], 401);
        }

        // ðŸ”¹ Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Password has been reset successfully. You can now login.',
        ]);
    }

    /**
     * ðŸ”¹ Update Profile
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = auth('api')->user(); // âœ… current logged-in user

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized or invalid token.',
                ], 401);
            }

            // ðŸ”¹ Validation
            $request->validate([
                'name' => 'sometimes|string|max:100',
                // 'email'     => 'sometimes|email|unique:users,email,' . $user->id,
                // 'password'  => 'sometimes|string|min:6|confirmed',
                'nickname' => 'sometimes|string|max:100',
                'country' => 'sometimes|string|max:100',
                'gender' => 'sometimes|in:male,female,other',
                'image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // ðŸ”¹ Update fields (only if provided)
            if ($request->has('name')) {
                $user->name = $request->name;
            }

            if ($request->has('email')) {
                $user->email = $request->email;
            }

            if ($request->has('phone')) {
                $user->phone = $request->phone;
            }

            // if ($request->has('password')) {
            //     $user->password = Hash::make($request->password);
            // }

            if ($request->has('nickname')) {
                $user->nickname = $request->nickname;
            }

            if ($request->has('country')) {
                $user->country = $request->country;
            }

            if ($request->has('gender')) {
                $user->gender = $request->gender;
            }

            // ðŸ”¹ Handle image upload (optional)
            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/profile'), $imageName);
                $user->image = 'uploads/profile/' . $imageName;
            }

            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully!',
                'user' => $user,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update profile.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function saveFcmToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = Auth::user();
        $user->fcm_token = $request->token;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'FCM token saved.'
        ]);
    }


    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = auth('api')->user(); // JWT token se user

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }

        // ✅ Current password check
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Current password is incorrect.',
            ], 400);
        }

        // ✅ Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Password changed successfully.',
        ]);
    }

    public function confirmPayment(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|string',
        ]);

        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }

        if ($user->is_paid) {
            return response()->json([
                'status' => true,
                'message' => 'User is already marked as paid.',
                'referral_code' => $user->referral_code,
            ]);
        }

        // ✅ Generate Unique Referral Code
        $referralCode = null;
        $isUnique = false;
        while (!$isUnique) {
            $referralCode = 'REF-' . strtoupper(bin2hex(random_bytes(3))); // e.g. REF-A1B2C3
            if (User::where('referral_code', $referralCode)->exists()) {
                continue;
            }
            $isUnique = true;
        }

        // ✅ Update User Status
        $user->is_paid = true;
        $user->referral_code = $referralCode;
        // In a real scenario, we'd save transaction_id too, but sticking to user's "dummy" requirement
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Payment confirmed successfully. Your referral code is now active.',
            'referral_code' => $referralCode,
            'transaction_id' => $request->transaction_id,
        ]);
    }

}
