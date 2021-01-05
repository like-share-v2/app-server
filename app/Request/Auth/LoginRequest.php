<?php

declare(strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link
 */
namespace App\Request\Auth;

use App\Request\RequestAbstract;

/**
 * 登陆验证器
 *
 *
 * @package App\Controller
 */
class LoginRequest extends RequestAbstract
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
            'account' => 'required|alpha_dash|between:5,30',
            'password' => 'required|alpha_dash|between:6,30'
        ];
    }
}
