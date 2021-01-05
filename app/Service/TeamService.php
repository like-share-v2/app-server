<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Service;

use App\Common\Base;
use App\Kernel\Utils\JwtInstance;
use App\Service\Dao\UserLevelDAO;
use App\Service\Dao\UserRelationDAO;

/**
 * 团队服务
 *
 *
 * @package App\Service
 */
class TeamService extends Base
{
    /**
     * 我的团队
     *
     * @return mixed
     */
    public function getTeamList(array $params)
    {
        $user_id = JwtInstance::instance()->build()->getId();

        // 获取用户下级
        return $this->container->get(UserRelationDAO::class)->getLowers($params, $user_id)->toArray();
    }
}