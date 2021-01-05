<?php

declare(strict_types=1);

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\CountryCode;

/**
 * 国家区号DAO
 *
 * @package App\Service\Dao
 */
class CountryCodeDAO extends Base
{
    /**
     * 获取国家区号列表
     *
     * @return mixed
     */
    public function get()
    {
        return CountryCode::query()->get();
    }
}