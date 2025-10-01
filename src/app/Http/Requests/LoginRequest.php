<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\Requests\LoginRequest as FormRequest;

class LoginRequest extends FormRequestLoginRequest
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
            'email' => 'required|email', //email → 必須 & メール形式
            'password' => 'required'
            //password → 必須
        ];
    }
}

