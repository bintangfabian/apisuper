<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExam extends FormRequest
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
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:255',
            'started_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
            'started_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i',
            'duration' => 'sometimes|numeric',
            'minimum_score' => 'sometimes|numeric',
        ];
    }
}
