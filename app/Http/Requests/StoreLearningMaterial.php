<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLearningMaterial extends FormRequest
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
            'title' => 'required|max:255|string',
            'grade_id' => 'required|exists:grades,id',
            'subject_id' => 'required|exists:subjects,id',
            'chapter_id' => 'required|exists:chapters,id',
            'content' => 'required|string',
        ];
    }
}
