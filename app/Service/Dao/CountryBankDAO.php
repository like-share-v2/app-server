<?php

declare(strict_types=1);

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\CountryBank;
use App\Model\CountryBankCode;

/**
 * 国家银行卡DAO
 *
 * @package App\Service\Dao
 */
class CountryBankDAO extends Base
{
    /**
     * 通过国家ID获取国家银行卡
     *
     * @param int $country_id
     * @return mixed
     */
    public function firstByCountryId(int $country_id): ?CountryBank
    {
        return CountryBank::query()->where('country_id', $country_id)->first();
    }

    /**
     * @param string $bank_code
     *
     * @return mixed
     */
    public function getBankByBankCode(string $bank_code): ?CountryBankCode
    {
        return CountryBankCode::where('code', $bank_code)->first();
    }
}