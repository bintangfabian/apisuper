<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function learningMaterials()
    {
        return $this->hasMany(LearningMaterial::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
