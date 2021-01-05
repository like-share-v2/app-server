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
 * 修改银行卡验证器
 *
 *
 * @package App\Request\Account
 */
class ChangeBankRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'       => 'required',
            'account'    => 'required',
            'trade_pass' => 'required',
            // 'phone'      => 'required',
            // 'ifsc'       => 'required',
            'bank_code'  => 'required'
        ];
    }
}