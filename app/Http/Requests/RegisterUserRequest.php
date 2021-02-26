<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'role' => 'required|in:1,2,3,4,5',
            'email' => 'required|string|email|unique:users|min:8|max:255',
            'password' => 'required|string|confirmed',
            'grade_id' => 'required_if:role,4|exists:grades,id'
        ];
    }
}
