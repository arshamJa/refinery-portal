<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogImage extends Model
{
    use HasFactory;

    protected $fillable=['blog_id','image'];


    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    public function getImageUrlAttribute()
    {
        return url('storage/' . $this->image);
    }
}
