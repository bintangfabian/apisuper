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
        $attitudeAssessment = $request->validated()['attitude_assessment'];
        foreach ($attitudeAssessment as $key => $value) {
            $value['semester'] = $request->validated()['semester'];
            $value['created_at'] = now();
            $value['updated_at'] = now();
            $attitudeAssessment[$key] = $value;
        }
        try {
            AttitudeAssessment::insert($attitudeAssessment);
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
                return response()->error('Penilaian sikap tidak ditemukan!', StatusCode::UNPROCESSABLE_ENTITY);
            }
            foreach ($value as $keyItem => $item) {
                $tes->fill([
                    $keyItem => $item,
                ]);
            };
            try {
                $tes->update();
            } catch (\Throwable $th) {
                return response()->error('Gagal mengubah Penilaian sikap!');
            }
        }
        return response()->success('Berhasil mengubah Penilaian sikap!');
    }
}
