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
 * 修改身份证验证器
 *
 *
 * @package App\Request\Account
 */
class ChangeIdCardRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id_card' => 'required|size:18'
        ];
    }
}