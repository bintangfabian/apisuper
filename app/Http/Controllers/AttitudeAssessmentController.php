<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreAttitude;
use App\Models\AttitudeAssessment;
use App\Models\Student;
use App\StatusCode;

class AttitudeAssessmentController extends Controller
{
    public function index()
    {
        $attitude = AttitudeAssessment::get();
        return $attitude;
    }

    public function showByGradeAndDate($semester, $gradeId)
    {
        $attitude = AttitudeAssessment::where('semester', $semester)->whereHas('student', function ($q) use ($gradeId) {
            $q->where('grade_id', $gradeId);
        })->with('student.user')->get();
        // dd(count($studentAttendance));
        if (count($attitude) === 0) {
            return response()->successWithKey(Student::where('grade_id', $gradeId)->with('user')->get(), 'students');
        }
        return response()->successWithKey($attitude, 'attitude');
    }

    public function store(StoreAttitude $request)
    {
        $attitude = new AttitudeAssessment($request->validated());
        try {
            $attitude->save();
        } catch (\Throwable $th) {
            return $th;
            return response()->error('Gagal menambahkan data attitude!', StatusCode::INTERNAL_SERVER_ERROR);
        }

        return response()->successWithMessage('Berhasil menambahkan data attitude!');
    }
}
