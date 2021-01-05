<?php
declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Kernel\WechatFactory;

use GuzzleHttp\HandlerStack;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\CoroutineHandler;
use Hyperf\Guzzle\HandlerStackFactory;
use Overtrue\Socialite\Providers\AbstractProvider;
use Psr\Container\ContainerInterface;
use EasyWeChat\MiniProgram\Application;
use EasyWeChat\Factory;
use GuzzleHttp\Client;
use Psr\SimpleCache\CacheInterface;

/**
 * MiniProgramFactory
 *
 * @property \EasyWeChat\MiniProgram\Auth\AccessToken           $access_token
 * @property \EasyWeChat\MiniProgram\DataCube\Client            $data_cube
 * @property \EasyWeChat\MiniProgram\AppCode\Client             $app_code
 * @property \EasyWeChat\MiniProgram\Auth\Client                $auth
 * @property \EasyWeChat\OfficialAccount\Server\Guard           $server
 * @property \EasyWeChat\MiniProgram\Encryptor                  $encryptor
 * @property \EasyWeChat\MiniProgram\TemplateMessage\Client     $template_message
 * @property \EasyWeChat\OfficialAccount\CustomerService\Client $customer_service
 * @property \EasyWeChat\MiniProgram\Plugin\Client              $plugin
 * @property \EasyWeChat\MiniProgram\Plugin\DevClient           $plugin_dev
 * @property \EasyWeChat\MiniProgram\UniformMessage\Client      $uniform_message
 * @property \EasyWeChat\MiniProgram\ActivityMessage\Client     $activity_message
 * @property \EasyWeChat\MiniProgram\Express\Client             $logistics
 * @property \EasyWeChat\MiniProgram\NearbyPoi\Client           $nearby_poi
 * @property \EasyWeChat\MiniProgram\Soter\Client               $soter
 * @property \EasyWeChat\BasicService\Media\Client              $media
 * @property \EasyWeChat\BasicService\ContentSecurity\Client    $content_security
 * @property \EasyWeChat\MiniProgram\Mall\ForwardsMall          $mall
 * @property \EasyWeChat\MiniProgram\SubscribeMessage\Client    $subscribe_message
 * @property \EasyWeChat\MiniProgram\RealtimeLog\Client         $realtime_log
 * @property \EasyWeChat\MiniProgram\Search\Client              $search
 * @property \EasyWeChat\MiniProgram\Live\Client                $live
 * @package App\Kernel\WechatFactory
 */
class MiniProgramFactory
{
    /**
     * @Inject()
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Application
     */
    private $app;

    /**
     * MiniProgramFactory constructor.
     */
    public function __construct()
    {
        AbstractProvider::setGuzzleOptions([
            'http_errors' => false,
            'handler'     => HandlerStack::create(new CoroutineHandler()),
        ]);
    }

    /**
     * 注册小程序APP
     */
    private function register()
    {
        $app = Factory::miniProgram(config('wechat.mini_program'));
        $config = $app['config']->get('http', []);
        $config['handler'] = $this->container->get(HandlerStackFactory::class)->create();
        $app->rebind('http_client', new Client($config));
        $cache = $this->container->get(CacheInterface::class);
        $app->rebind('cache', $cache);
        $app['guzzle_handler'] = $this->container->get(HandlerStackFactory::class)->create();
        $this->app = $app;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function __get($id)
    {
        $this->register();
        if ($this->app->shouldDelegate($id)) {
            return $this->app->delegateTo($id);
        }
        return $this->app->offsetGet($id);
    }
}