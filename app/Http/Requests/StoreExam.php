<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExam extends FormRequest
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
            'grade_id' => 'required|exists:grades,id',
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'started_date' => 'required|date',
            'end_date' => 'required|date',
            'started_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'duration' => 'required|numeric',
            'minimum_score' => 'required|numeric',
        ];
    }
}
