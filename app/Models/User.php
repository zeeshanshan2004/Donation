<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Str; // File ke top par ye lazmi check karein



class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;



    protected $fillable = [
        'name',
        'email',
        'password',
        'otp',          // ✅ added
        'is_verified',  // ✅ added
        'nickname',
        'country',
        'gender',
        'kyc_status',   // ✅ added for KYC
        'referred_by',
        'referral_code',
        'is_paid',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp', // ✅ hide OTP from API response
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_verified' => 'boolean', // ✅ cast to boolean
        'kyc_status' => 'boolean',  // ✅ cast to boolean
        'is_paid' => 'boolean',
    ];

    // ✅ JWT Required Methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }


    public function notificationSetting()
    {
        return $this->hasOne(UserNotificationSetting::class);
    }

    /**
     * Get the KYC submission for this user
     */
    public function kycSubmission()
    {
        return $this->hasOne(KycSubmission::class);
    }

    /**
     * Check if user's KYC is approved
     */
    public function isKycApproved()
    {
        return $this->kyc_status === true || $this->kyc_status === 1;
    }

    /**
     * Get the user who referred this user
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    /**
     * Get the users referred by this user
     */
    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }






protected static function boot()
{
    parent::boot();

    // Jab bhi naya user Register hoga, ye code chalega
    static::creating(function ($user) {
        if (empty($user->referral_code)) {
            $user->referral_code = self::generateUniqueReferralCode();
        }
    });
}

public static function generateUniqueReferralCode()
{
    do {
        // Random code: REF- aur 6 characters (e.g., REF-A1B2C3)
        $code = 'REF-' . strtoupper(Str::random(6));
    } while (self::where('referral_code', $code)->exists()); // Duplicate check

    return $code;
}
    
}
