<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotificationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_notifications',
        'push_notifications',
        'special_announcements',
        'blessing_updates',
        'community_announcements',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
