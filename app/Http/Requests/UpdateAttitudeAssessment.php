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
            'student_id' => 'required|exists:students,id',
            'semester' => 'required|string|max:1|exists:attitude_assessment,semester',
            'behavior' => 'required|string|max:255',
            'neatness' => 'required|string|max:255',
            'discipline' => 'required|string|max:255',
            'cooperation' => 'required|string|max:255',
            'creative' => 'required|string|max:255',
            'information' => 'required|string|max:255',
        ];
    }
}
