<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentAttendance extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => 'date|required',
            'student_attendance' => 'required|array',
            'student_attendance.*.student_id' => 'exists:students,id|required',
            'student_attendance.*.status' => 'in:Hadir,Izin,Absen,Sakit,Telat|required',
        ];
    }
}
