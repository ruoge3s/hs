<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class PermissionUpdateRequest extends FormRequest
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
            'id'        => 'exists:permissions',
            'name'      => 'string|max:255',
            'describe'  => 'string|max:255',
            'gid'       => 'exists:permission_group,id'
        ];
    }
}
