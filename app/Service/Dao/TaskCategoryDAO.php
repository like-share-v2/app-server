<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\TaskCategory;

/**
 * 任务分类DAO
 *
 *
 * @package App\Service\Dao
 */
class TaskCategoryDAO extends Base
{
    /**
     * 获取任务分类列表
     *
     * @return mixed
     */
    public function get()
    {
        return TaskCategory::query()->where('status', 1)->select(['id', 'name', 'icon'])->orderByDesc('sort')->orderByDesc('id')->get();
    }

    /**
     * 通过ID获取任务分类
     *
     * @param int $id
     * @return mixed
     */
    public function firstById(int $id): ?TaskCategory
    {
        return TaskCategory::query()->where('id', $id)->first();
    }
}