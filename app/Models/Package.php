<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

protected $fillable = ['name', 'amount', 'referral_required', 'tax_percentage', 'community_share', 'status', 'icon'];

    protected $appends = ['total_collected', 'tax', 'payout'];

    // Total Collected = Amount * Referrals
    public function getTotalCollectedAttribute()
    {
        return round($this->amount * $this->referral_required, 2);
    }

    // Tax = Total Collected * (Tax % / 100)
    public function getTaxAttribute()
    {
        return round($this->total_collected * ($this->tax_percentage / 100), 2);
    }

    // Payout = Total - Tax - Community
    public function getPayoutAttribute()
    {
        return round($this->total_collected - $this->tax - $this->community_share, 2);
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\PackageOrder::class, 'package_id');
    }
}