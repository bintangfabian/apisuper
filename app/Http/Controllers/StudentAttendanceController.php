<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentAttendance;
use App\Http\Requests\UpdateStudentAttendance;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\StatusCode;
use Illuminate\Http\Request;

class StudentAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Recap Student Attendance
     *
     * @return \Illuminate\Http\Response
     */
    public function recap($gradeId, $month, $year)
    {
        $studentAttendances = StudentAttendance::whereMonth('date', $month)->whereYear('date', $year)->whereHas('student', function ($q) use ($gradeId) {
            $q->where('grade_id', $gradeId);
        })->with('student.user')->get();
        $uniqueStudentId = collect($studentAttendances)->unique('student_id');
        $newArray = [];
        foreach ($uniqueStudentId as $key => $value) {
            $attendances =  ($this->getAttendances($value['student_id'], collect($studentAttendances)))->toArray();
            array_push($newArray, [
                'id' => $value['student']['user']['id'],
                'name' => $value['student']['user']['name'],
                'attendances' => $attendances,
            ]);
        }
        return $newArray;
    }

    /**
     * Recap Student Attendance recapByMonthAndYear
     *
     * @return \Illuminate\Http\Response
     */
    public function recapByMonthAndYear($month, $year, Request $request)
    {
        $user = $request->user();
        // return $user->guardianOfStudent->student_id;
        if ($user->hasRole('Siswa')) {
            try {
                $student = Student::where('user_id', $user->id)->firstOrFail();
            } catch (\Throwable $th) {
                return response()->error('Siswa tidak ditemukan!');
            }
            $studentAttendances = StudentAttendance::whereMonth('date', $month)->where('student_id', $student->id)->whereYear('date', $year)->with('student.user')->get();
            $uniqueStudentId = collect($studentAttendances)->unique('student_id');
            $newArray = [];
            foreach ($uniqueStudentId as $key => $value) {
                $attendances =  ($this->getAttendances($value['student_id'], collect($studentAttendances)))->toArray();
                array_push($newArray, [
                    'id' => $value['student']['user']['id'],
                    'name' => $value['student']['user']['name'],
                    'attendances' => $attendances,
                ]);
            }
            return $newArray;
        } else if ($user->hasRole('Wali Siswa')) {
            $guardianOfStudentId = $user->id;
            try {
                $student = Student::where('id', $user->guardianOfStudent->student_id)->firstOrFail();
            } catch (\Throwable $th) {
                return response()->error('Siswa tidak ditemukan!');
            }
            $studentAttendances = StudentAttendance::whereMonth('date', $month)->where('student_id', $user->guardianOfStudent->student_id)->whereYear('date', $year)->with('student.user')->get();
            $uniqueStudentId = collect($studentAttendances)->unique('student_id');
            $newArray = [];
            foreach ($uniqueStudentId as $key => $value) {
                $attendances =  ($this->getAttendances($value['student_id'], collect($studentAttendances)))->toArray();
                array_push($newArray, [
                    'id' => $value['student']['user']['id'],
                    'name' => $value['student']['user']['name'],
                    'attendances' => $attendances,
                ]);
            }
            return $newArray;
        }
    }

    private function getAttendances($studentId, $studentAttendances)
    {
        $attendances = collect([]);
        $filtered = $studentAttendances->filter(function ($value) use ($studentId) {
            return $value['student_id'] === $studentId;
        });

        collect($filtered)->each(function ($item, $key) use ($attendances) {
            $attendances->push(collect($item)->only(['status', 'date']));
        });
        return $attendances;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreStudentAttendance  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentAttendance $request)
    {
        $studentAttendance = $request->validated()['student_attendance'];
        foreach ($studentAttendance as $key => $value) {
            $value['date'] = $request->validated()['date'];
            $value['created_at'] = now();
            $value['updated_at'] = now();
            $studentAttendance[$key] = $value;
        }
        try {
            StudentAttendance::insert($studentAttendance);
        } catch (\Throwable $th) {
            return response()->error('Gagal menambahkan kehadiran siswa!');
        }
        return response()->success('Berhasil menambahkan kehadiran siswa!');
    }

    /**
     * Display the specified resource.
     *
     * @param  date  $date
     * @return \Illuminate\Http\Response
     */
    public function showByGradeAndDate($date, $gradeId)
    {
        $studentAttendance = StudentAttendance::where('date', $date)->whereHas('student', function ($q) use ($gradeId) {
            $q->where('grade_id', $gradeId);
        })->with('student.user')->get();
        // dd(count($studentAttendance));
        if (count($studentAttendance) === 0) {
            return response()->successWithKey(Student::where('grade_id', $gradeId)->with('user')->get(), 'students');
        }
        return response()->successWithKey($studentAttendance, 'student_attendance');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentAttendance $request, $date)
    {
        $studentAttendance = $request->validated()['student_attendance'];
        foreach ($studentAttendance as $key => $value) {
            try {
                $tes =  StudentAttendance::where('date', $date)->where('student_id', $value['student_id'])->first();
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  date  $date
     * @param  int  $gradeId
     * @return \Illuminate\Http\Response
     */
    public function destroyByGradeAndDate($date, $gradeId)
    {
        try {
            StudentAttendance::where('date', $date)->whereHas('student', function ($q) use ($gradeId) {
                $q->where('grade_id', $gradeId);
            })->firstOrFail();
        } catch (\Throwable $th) {
            return response()->error('Kehadiran siswa tidak ditemukan!', StatusCode::UNPROCESSABLE_ENTITY);
        }

        try {
            StudentAttendance::where('date', $date)->whereHas('student', function ($q) use ($gradeId) {
                $q->where('grade_id', $gradeId);
            })->delete();
        } catch (\Throwable $th) {
            return response()->error('Gagal menghapus kehadiran siswa!', StatusCode::INTERNAL_SERVER_ERROR);
        }
        return response()->successWithMessage('Berhasil menghapus kehadiran siswa!', StatusCode::OK);
    }
}
