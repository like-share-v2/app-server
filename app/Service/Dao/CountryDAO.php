<?php

declare(strict_types=1);

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\Country;

/**
 * 国家DAO
 *
 * @package App\Service\Dao
 */
class CountryDAO extends Base
{
    /**
     * 获取国家列表
     *
     * @return mixed
     */
    public function get()
    {
        return Country::query()->orderBy('id')->get();
    }

    /**
     * 通过ID获取国家
     *
     * @param int $id
     * @return mixed
     */
    public function firstById(int $id): ?Country
    {
        return Country::query()->where('id', $id)->first();
    }
}