<?php

declare(strict_types=1);

namespace App\Request\UserRecharge;

use App\Request\RequestAbstract;

/**
 * 充值会员验证器
 *
 * @package App\Request\UserRecharge
 */
class LevelRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'level' => 'required'
        ];
    }
}