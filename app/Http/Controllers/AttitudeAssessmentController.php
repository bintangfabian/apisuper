<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreAttitude;
use App\Http\Requests\UpdateAttitudeAssessment;
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

    public function showByGradeAndSemester($semester, $gradeId)
    {
        $attitude = AttitudeAssessment::where('semester', $semester)->whereHas('student', function ($q) use ($gradeId) {
            $q->where('grade_id', $gradeId);
        })->with('student.user')->paginate();
        if (count($attitude) === 0) {
            return Student::where('grade_id', $gradeId)->with('user')->paginate(15);
        }
        return $attitude;
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

    public function update(UpdateAttitudeAssessment $request)
    {
        $attitudeAssessment = $request->validated()['attitude_assessment'];
        foreach ($attitudeAssessment as $key => $value) {
            try {
                $tes =  AttitudeAssessment::where('semester', $request->semester)->where('student_id', $value['student_id'])->first();
            } catch (\Throwable $th) {
                return response()->error('Kehadiran siswa tidak ditemukan!', StatusCode::UNPROCESSABLE_ENTITY);
            }
            $tes->fill(['status' => $value['status']]);
            try {
                $tes->update();
            } catch (\Throwable $th) {
                return response()->error('Gagal menambahkan kehadiran siswa!');
            }
        }
        return response()->success('Berhasil menambahkan kehadiran siswa!');
    }
}
