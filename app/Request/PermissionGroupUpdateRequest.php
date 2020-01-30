<?php
declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

/**
 * 更新权限分组
 * Class PermissionGroupCreateRequest
 * @package App\Request
 */
class PermissionGroupUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id'   => 'required|exists:permission_group',
            'name' => 'string',
            'sort' => 'numeric'
        ];
    }

}
