<?php

declare(strict_types=1);

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\TaskAudit;

/**
 * 任务审核DAO
 *
 * @package App\Service\Dao
 */
class TaskAuditDAO extends Base
{
    public function create(array $data)
    {
        return TaskAudit::query()->create($data);
    }
}