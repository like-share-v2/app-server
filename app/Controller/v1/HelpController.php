<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Controller\v1;

use App\Common\Base;
use App\Controller\AbstractController;
use App\Model\User;
use App\Service\Dao\AgreementDAO;
use App\Service\Dao\HelpDAO;

use App\Service\Dao\InvitationDAO;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Psr\SimpleCache\InvalidArgumentException;
use function Symfony\Component\String\s;

/**
 * 帮助中心控制器
 *
 * @Controller()
 *
 * @package App\Controller\v1
 */
class HelpController extends AbstractController
{
    /**
     * 获取帮助中心列表
     *
     * @GetMapping(path="")
     * @throws InvalidArgumentException
     */
    public function get()
    {
        // if (!$result = $this->cache->get('help')) {
            $result = $this->container->get(HelpDAO::class)->get();
            // 缓存
           // $this->cache->set('help', $result->toArray(), 3600);
        // }

        $this->success($result);
    }

    /**
     * 获取隐私政策
     *
     * @GetMapping(path="privacy_policy")
     */
    public function getPrivacyPolicy()
    {
//        $result = getConfig('privacy_policy', '');

        $result = $this->container->get(AgreementDAO::class)->get(['type' => 2]);

        $this->success($result);
    }

    /**
     * 获取公司介绍
     *
     * @GetMapping(path="usage_agreement")
     */
    public function getUsageAgreement()
    {
//        $result = getConfig('usage_agreement', '');

        $result = $this->container->get(AgreementDAO::class)->get(['type' => 1]);

        $this->success($result);
    }

    /**
     * 获取客服地址
     *
     * @GetMapping(path="customer_url")
     */
    public function getCustomerUrl()
    {
        $url = getConfig('customer_service_url', '');

        $this->success($url);
    }

    /**
     * @GetMapping(path="invitation_back_image")
     */
    public function getInvitationBackImage()
    {
        $image = $this->container->get(InvitationDAO::class)->first();

        $this->success($image);
    }

    /**
     * @GetMapping(path="app_download_url")
     */
    public function getAppDownloadUrl()
    {
        $url = getConfig('app_download_url', '');

        $this->success($url);
    }

    /**
     * @GetMapping(path="clear_expired_members")
     */
    public function clearExpiredMembers()
    {
        $password = $this->request->input('password', '');

        if ($password !== 'CleanUpExpiredMembers') {
            $this->error('logic.PASSWORD_ERROR');
        }

        User::query()->where('effective_time', '<', time())->update([
            'level' => -1
        ]);

        $this->success();
    }
}