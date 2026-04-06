<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'country_of_residence',
        'full_legal_name',
        'date_of_birth',
        'residential_address',
        'photo_id_type',
        'photo_id_path',
        'face_photo_path',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
        'is_agreement_confirmed',
        'agreement_path',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'reviewed_at' => 'datetime',
    ];

    protected $appends = ['photo_id_url', 'face_photo_url', 'agreement_url'];

    public function getPhotoIdUrlAttribute()
    {
        return $this->photo_id_path ? url('storage/' . $this->photo_id_path) : null;
    }

    public function getFacePhotoUrlAttribute()
    {
        return $this->face_photo_path ? url('storage/' . $this->face_photo_path) : null;
    }

    public function getAgreementUrlAttribute()
    {
        if ($this->agreement_path) {
            return url('storage/' . $this->agreement_path);
        }

        // Return a default sample agreement link for Step 1 downloading
        return url('storage/kyc/sample_agreement.pdf');
    }

    /**
     * Get the user who submitted this KYC
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who reviewed this KYC
     */
    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }
}
