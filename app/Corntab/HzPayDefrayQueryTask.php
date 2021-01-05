<?php
declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Corntab;

use App\Exception\LogicException;
use App\Kernel\Payment\HZPay;
use App\Model\Defray;
use App\Service\Dao\DefrayDAO;
use App\Service\NotifyService;

use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Logger\LoggerFactory;
use Psr\Container\ContainerInterface;

/**
 * HzPayDefrayQueryTask
 *
 * @Crontab(name="HzPayDefrayQuery", rule="*\/1 * * * *", callback="execute", memo="同步惠众支付代付订单")
 * @author
 * @package App\Corntab
 */
class HzPayDefrayQueryTask
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    private $container;

    public function execute()
    {
        foreach ($this->container->get(DefrayDAO::class)->getWaitingNotifyOrderByChannel('hzPay') as $defray) {
            /** @var Defray $defray */
            go(function () use ($defray) {
                try {
                    $result = $this->container->get(HZPay::class)->defrayQuery($defray->order_no);
                    switch ($result['status']) {
                        case 'SUCCESS';
                            $status = 2;
                            break;

                        case 'FAIL':
                            $status = 1;
                            break;

                        default:
                            throw new LogicException('未知状态');
                    }
                    $this->container->get(NotifyService::class)->handlePayout($defray->order_no, $status, $result['order_amount'], $result['order_no']);
                }
                catch (\Throwable $e) {
                    $this->container->get(LoggerFactory::class)->get('log', 'payment')->error($e->getMessage(), [
                        'order_no' => $defray->order_no
                    ]);
                }
            });
        }
    }
}