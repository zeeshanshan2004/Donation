<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermCondition extends Model
{
    use HasFactory;

    protected $table = 'terms_conditions'; // ye table ka exact name hai
    protected $fillable = ['title', 'content', 'status'];
}
