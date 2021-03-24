<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studentAttendances()
    {
        return $this->hasMany(StudentAttendance::class);
    }

    public function guardianOfStudent()
    {
        return $this->hasOne(GuardianOfStudent::class);
    }
    
    public function attitudeAssessments()
    {
        return $this->hasMany(AttitudeAssessment::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
}
