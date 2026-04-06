<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cause extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'heading',
        'author',
        'author_image',
        'image',
        'target',
        'raised',
        'days_left',
        'is_featured',
        'status',
        'description',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'status'      => 'boolean',
    ];

    // 👇 Auto append URLs
    protected $appends = [
        'image_url',
        'author_image_url',
    ];

    /**
     * 📸 Cause main image URL
     */
    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('uploads/causes/' . $this->image)
            : null;
    }

    /**
     * 👤 Author image URL
     */
    public function getAuthorImageUrlAttribute()
    {
        return $this->author_image
            ? asset('uploads/author_image/' . $this->author_image)
            : null;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
