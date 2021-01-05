<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\Help;

/**
 * 帮助中心DAO
 *
 *
 * @package App\Service\Dao
 */
class HelpDAO extends Base
{
    /**
     * 获取帮助中心列表
     *
     * @return mixed
     */
    public function get()
    {
        return Help::query()->where('status', 1)->orderByDesc('sort')->orderByDesc('id')->get();
    }
}