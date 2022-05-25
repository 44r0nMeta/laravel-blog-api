<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;


class UpdateUserRequest extends FormRequest
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
    public function rules(Request $request)
    {
        //return dd($request);
        return [
            "name" => ['min:4', 'max:60', 'required'],
            "email" => ['email','required','unique:users,email,'.$request->user->id],
            "old_password" => ['required_with:password'],
            "password" => ['min:8','required_with:old_password']
        ];
    }
}
