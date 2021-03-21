<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
    ];

    public function image()
    {
        return $this->morphOne('App\Models\Image', 'imageable');
    }


    public function user()
    {
        $this->belongsTo(User::class);
    }
}
