<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class AdminUserRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'username'              => 'required|between:5,16|unique:users|alpha_dash',
            'nickname'              => 'required|string',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|confirmed|between:5,16' ,
            'password_confirmation' => 'required|same:password',
        ];
    }
}
