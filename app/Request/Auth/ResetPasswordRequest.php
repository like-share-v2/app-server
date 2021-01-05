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
 *
 * @package App\Request\Auth
 */
class ResetPasswordRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'country_code' => 'required',
            'password'     => 'required|alpha_dash|between:6,30|confirmed',
            'phone'        => 'required',
            'code'         => 'required|digits:6'
        ];
    }
}