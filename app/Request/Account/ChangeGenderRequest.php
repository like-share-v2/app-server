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
 * 性别验证器
 *
 *
 * @package App\Request\Account
 */
class ChangeGenderRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'gender' => 'required|in:0,1,2'
        ];
    }
}