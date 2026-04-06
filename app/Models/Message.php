<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'sender_type',
        'message',
        'is_read',
    ];

    public function senderAdmin()
    {
        return $this->belongsTo(Admin::class, 'sender_id');
    }

    public function senderUser()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
