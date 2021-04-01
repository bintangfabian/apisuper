<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function essayAnswer()
    {
        return $this->hasOne(EssayAnswer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
}
