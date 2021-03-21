<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamQuestion extends FormRequest
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
            'exam_question_list' => 'array|required',
            'exam_question_list.*.question_id' => 'required|exists:questions,id',
            'exam_question_list.*.exam_id' => 'required|exists:exams,id',
        ];
    }
}
