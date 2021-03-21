<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function learningMaterials()
    {
        return $this->hasMany(LearningMaterial::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
