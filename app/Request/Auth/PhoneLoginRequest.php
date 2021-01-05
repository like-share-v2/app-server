<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Request\Auth;

use App\Request\RequestAbstract;

/**
 * 手机登陆验证器
 *
 *
 * @package App\Request\Auth
 */
class PhoneLoginRequest extends RequestAbstract
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
            'phone' => 'required|regex:/^1[3456789]{1}\d{9}$/',
            'code' => 'required|size:6'
        ];
    }
}