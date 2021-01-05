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
 * 在线充值验证器
 *
 *
 * @package App\Request\UserRecharge
 */
class OnlineRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'channel' => 'required',
            'level'   => 'required|integer|gt:0'
        ];
    }
}