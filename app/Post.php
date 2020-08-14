<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title', 'slug', 'short_text', 'long_text', 'image_url'
    ];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
