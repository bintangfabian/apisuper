<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttitude extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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
            'semester' => 'required|char',
            'behavior' => 'required|string|max:255',
            'neatness' => 'required|string|max:255',
            'discipline' => 'required|string|max:255',
            'cooperation' => 'required|string|max:255',
            'information' => 'required|string|max:255',
        ];
    }
}
