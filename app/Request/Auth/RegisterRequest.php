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
 * 注册验证器
 *
 *
 * @package App\Request\Auth
 */
class RegisterRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'country_id'      => 'required',
            'country_code'    => 'required',
            // 'account'         => 'required|alpha_dash|between:5,30',
            'password'        => 'required|alpha_dash|between:6,30|confirmed',
            'phone'           => 'required',
//            'code'            => 'required|digits:6',
            'invitation_code' => 'required'
        ];
    }
}