<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
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
            'name' => 'string|max:255|sometimes|min:3',
            'email' =>  'sometimes|max:255|string|email|unique:users,email,' . $this->user()->id,
            'password' => 'confirmed|string|max:255|sometimes|min:8',
            'image' => 'image|sometimes|file',
        ];
    }
}
