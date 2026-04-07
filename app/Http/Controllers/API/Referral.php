<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    // Table name agar 'referrals' se mukhtalif hai to yahan likhein
    protected $table = 'referrals';

    // Kaunse columns fillable hain
    protected $fillable = [
        'referrer_id',      // Jisne invite kiya (Jo banda login hai)
        'referred_user_id', // Jisne join kiya (Naya banda)
        'commission_earned' // Maan lo $3 jo aapne kaha tha
    ];

    /**
     * Relationship: Wo Banda jisne Refer kiya (The Inviter)
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * Relationship: Wo Banda jo naya join hua (The New Member)
     */
    public function referredUser()
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }
}