<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

/**
 * Class MenuCreateRequest
 * 菜单创建请求表单
 * @package App\Request
 */
class MenuCreateRequest extends FormRequest
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
            'pid'       => 'exists:menus,id',
            'name'      => 'required|string|unique:menus,id',
            'describe'  => 'required|string'
        ];
    }
}
