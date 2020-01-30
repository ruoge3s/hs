<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class AdminUserUpdateRequest extends FormRequest
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
            'id'                    => 'required|numeric',
            'username'              => [
                'between:5,16',
                'alpha_dash',
                Rule::unique('users')->whereNot('id', $this->input('id'))

            ],
            'nickname'              => 'string',
            'email'                 => [
                'email',
                Rule::unique('users')->whereNot('id', $this->input('id'))
            ],
            'password'              => 'confirmed|between:5,16' ,
            'password_confirmation' => 'string',
        ];
    }
}
