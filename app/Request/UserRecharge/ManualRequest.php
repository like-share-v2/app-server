<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link
 */

namespace App\Request\UserRecharge;

use App\Request\RequestAbstract;

/**
 * 手动充值验证器
 *
 *
 * @package App\Request\UserRecharge
 */
class ManualRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'level'    => 'required|gt:0',
            'trade_no' => 'required',
            'image'    => 'required|max:255'
        ];
    }
}