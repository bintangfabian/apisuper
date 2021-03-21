<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestion extends FormRequest
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
            'content' => 'string|required',
            'type' => 'string|required|in:Multiple Choice,Essay',
            'subject_id' => 'string|required|exists:subjects,id',
            'grade_id' => 'string|required|exists:grades,id',
            'essay_answer' => 'string|required_if:type,Essay',
            'options' => 'array|required_if:type,Multiple Choice|min:4',
            'options.*.name' => 'string|required|in:a,b,c,d',
            'options.*.content' => 'string|required',
            'options.*.is_true' => 'boolean|required',
        ];
    }
}
