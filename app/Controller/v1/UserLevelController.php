<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Controller\v1;

use App\Controller\AbstractController;
use App\Kernel\Utils\JwtInstance;
use App\Model\UserLevel;
use App\Model\UserMember;
use App\Service\Dao\UserLevelDAO;
use App\Middleware\AuthMiddleware;
use App\Service\Dao\UserMemberDAO;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;

/**
 * 用户等级控制器
 *
 * @Middleware(AuthMiddleware::class)
 * @Controller()
 *
 * @package App\Controller\v1
 */
class UserLevelController extends AbstractController
{
    /**
     * 获取用户等级列表
     *
     * @GetMapping(path="")
     */
    public function getList()
    {
        $user = JwtInstance::instance()->build()->getUser();

        $user_member = [];
        foreach ($this->container->get(UserMemberDAO::class)->getByUserId($user->id) as $member) {
            /** @var UserMember $member */
            $user_member[$member->level] = $member;
        }

        $result = [];
        foreach ($this->container->get(UserLevelDAO::class)->getAllList() as $level) {
            /** @var UserLevel $level */
            if (isset($user_member[$level->level])) {
                if ($user_member[$level->level]->getRaw('effective_time') === -1) {
                    $due_time = 'permanent';
                } else {
                    $due_time = $user_member[$level->level]->effective_time;
                }
            } else {
                $due_time = null;
            }
            $result[] = array_merge($level->toArray(), [
                'is_recommended' => array_key_exists($level->level, $user_member) ? 1 : 0,
                'due_time'       => $due_time
            ]);
        }

        $this->success($result);
    }

    /**
     * 充值页会员列表
     *
     * @GetMapping(path="recharge")
     */
    public function getRechargeLevelList()
    {
        $user_level_list = $this->container->get(UserLevelDAO::class)->getList()->toArray();

        $user = JwtInstance::instance()->build()->getUser();

        $result = array_map(function ($item) use ($user) {
            // 判断用户当前等级
            /* if ($item['level'] <= $user->level && $user->getAttributes()['effective_time'] > time() || $item['type'] === 1) {
                $item['status'] = 0;
            } else {
                $item['status'] = 1;
            } */
            if ($item['type'] === 0) {
                $item['status'] = 1;
            }
            else {
                $item['status'] = 0;
            }
            return $item;
        }, $user_level_list);

        $this->success($result);
    }
}