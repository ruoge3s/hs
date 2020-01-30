<?php
declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

/**
 * 创建权限分组
 * Class PermissionGroupCreateRequest
 * @package App\Request
 */
class PermissionGroupCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required:string',
            'sort' => 'required:numeric'
        ];
    }

}
