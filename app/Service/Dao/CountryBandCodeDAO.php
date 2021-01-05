<?php

declare(strict_types=1);

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\CountryBankCode;

/**
 * @package App\Service\Dao
 */
class CountryBandCodeDAO extends Base
{
    /**
     * 通过国家ID获取银行代码列表
     *
     * @param int $country_id
     * @return mixed
     */
    public function getListByCountryId(int $country_id)
    {
        return CountryBankCode::query()->where('country_id', $country_id)->get();
    }
}