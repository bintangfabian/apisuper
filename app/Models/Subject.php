<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function learningMaterials()
    {
        return $this->hasMany(LearningMaterial::class);
    }
}
