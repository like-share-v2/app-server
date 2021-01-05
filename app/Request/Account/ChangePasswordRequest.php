<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link
 */

namespace App\Request\Account;

use App\Request\RequestAbstract;

/**
 * 修改密码验证器
 *
 *
 * @package App\Request\Account
 */
class ChangePasswordRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'old_password' => 'between:6,30',
            'password' => 'alpha_dash|between:6,30|confirmed',
            'trade_pass' => 'alpha_dash|between:6,30'
        ];
    }
}