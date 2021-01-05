<?php

declare(strict_types=1);

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\IpBlackList;

/**
 * @package App\Service\Dao
 */
class IpBlackListDAO extends Base
{
    public function checkIpExist(string $ip)
    {
        return IpBlackList::query()->where('ip', $ip)->exists();
    }
}