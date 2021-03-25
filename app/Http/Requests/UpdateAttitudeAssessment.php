<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttitudeAssessment extends FormRequest
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
            'attitude_assessment' => 'required|array',
            'semester' => 'required|string|max:1|exists:attitude_assessments,semester',
            'attitude_assessment.*.student_id' => 'exists:students,id|required',
            'attitude_assessment.*.behavior' => 'required|string|max:255|in:sangat baik,baik,cukup,kurang',
            'attitude_assessment.*.neatness' => 'required|string|max:255|in:sangat baik,baik,cukup,kurang',
            'attitude_assessment.*.discipline' => 'required|string|max:255|in:sangat baik,baik,cukup,kurang',
            'attitude_assessment.*.cooperation' => 'required|string|max:255|in:sangat baik,baik,cukup,kurang',
            'attitude_assessment.*.creative' => 'required|string|max:255|in:sangat baik,baik,cukup,kurang',
            'attitude_assessment.*.information' => 'required|string|max:255',
        ];
    }
}
