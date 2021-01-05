<?php

declare(strict_types=1);

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\Banner;

/**
 * 轮播DAO
 *
 * @package App\Service\Dao
 */
class BannerDAO extends Base
{
    public function get()
    {
        return Banner::query()->orderByDesc('sort')->orderByDesc('id')->get();
    }
}