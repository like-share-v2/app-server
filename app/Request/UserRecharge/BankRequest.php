<?php

declare(strict_types=1);

namespace App\Request\UserRecharge;

use App\Request\RequestAbstract;

/**
 * 银行卡充值验证
 *
 * @package App\Request\UserRecharge
 */
class BankRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // 'name'       => 'required|max:50',
            // 'bank'       => 'required|max:50',
            // 'bank_name'  => 'required|max:50',
            // 'amount'     => 'required',
            // 'remittance' => 'required',
            // 'country_id' => 'required',
            'voucher'    => 'required'
        ];
    }
}