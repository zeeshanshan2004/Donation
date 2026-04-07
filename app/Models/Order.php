<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // 👇 optional (agar table name different ho to use karo)
    protected $table = 'orders';

    // 👇 mass assignment fields
    protected $fillable = [
        'user_id',
        'package_id',
        'amount',
        'progress',
        'status',
        'transaction_id',
    ];

    // 👇 optional: default values
    protected $attributes = [
        'progress' => 0,
        'status' => 'active',
    ];

    // 👇 relationships (optional but best practice)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}