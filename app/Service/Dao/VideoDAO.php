<?php

declare(strict_types=1);

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\Video;

/**
 * @package App\Service\Dao
 */
class VideoDAO extends Base
{
    /**
     * 获取视频列表
     *
     * @return mixed
     */
    public function get()
    {
        return Video::query()->orderByDesc('sort')->orderByDesc('id')->paginate(10);
    }
}