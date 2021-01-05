<?php

declare(strict_types=1);

namespace App\Controller\v1;

use App\Constants\Constants;
use App\Controller\AbstractController;
use App\Kernel\Utils\JwtInstance;
use App\Service\Dao\AgreementDAO;
use App\Service\Dao\TaskCategoryDAO;
use App\Service\Dao\UserBillDAO;
use App\Service\Dao\UserLevelDAO;
use App\Middleware\AuthMiddleware;

use App\Service\Dao\UserRechargeDAO;
use App\Service\IndexNotifyService;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;

/**
 * 首页控制器
 *
 * @Controller()
 * @package App\Controller\v1
 */
class IndexController extends AbstractController
{
    /**
     * @GetMapping(path="")
     */
    public function index()
    {
        try {
            $token        = $this->request->getHeaderLine(Constants::AUTHORIZATION);
            $user_id      = JwtInstance::instance()->decode($token)->getId();
            $today_profit = $this->container->get(UserBillDAO::class)->getTodayProfitByUserId($user_id);
        }
        catch (\Throwable $e) {
            $user_id      = 0;
            $today_profit = 0;
        }
        // 会员等级列表
        $level = $this->container->get(UserLevelDAO::class)->getAllList();
        // 今日收益
        // $today_profit = $this->container->get(UserBillDAO::class)->getTodayProfitByUserId($user_id);
        // 任务分类
        $task_category  = $this->container->get(TaskCategoryDAO::class)->get()->toArray();
        $toolbar_images = getConfig('toolbar_images', []);
        $toolbar_list   = [];
        $i              = 0;
        foreach (getConfig('toolbar_urls', []) as $title => $url) {
            if (!isset($toolbar_images[$i])) {
                continue;
            }
            $toolbar_list[] = [
                'title' => $title,
                'url'   => $url,
                'icon'  => config('static_url') . $toolbar_images[$i]
            ];
            $i++;
        }
        $this->success([
            'level'                => $level,
            'today_profit'         => $today_profit,
            'user_level_notify'    => $this->container->get(IndexNotifyService::class)->getNewUserRecharge(),
            'task_category'        => $task_category,
            'complete_member_data' => $this->container->get(IndexNotifyService::class)->getTodayTaskCompleteRank(),
            'publish_member_data'  => $this->container->get(IndexNotifyService::class)->getTodayPublishTaskRank(),
            'id'                   => $user_id,
            'toolbar'              => [
                'isShow' => getConfig('toolbar_switch', false),
                'list'   => $toolbar_list
            ]
        ]);
    }

    /**
     * 获取首页弹窗内容
     *
     * @GetMapping(path="homePopUp")
     */
    public function homePopUp()
    {
        $result = $this->container->get(AgreementDAO::class)->get(['type' => 4]);

        $this->success($result);
    }
}