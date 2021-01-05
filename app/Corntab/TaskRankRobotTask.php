<?php
declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Corntab;

use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Swoole\Timer;

/**
 * TaskRankRobotTask
 *
 * @Crontab(name="TaskRankRobot", rule="0 9-18\/1 * * *", callback="execute", memo="任务随机机器人")
 * @author
 * @package App\Corntab
 */
class TaskRankRobotTask
{
    /**
     * @Inject
     * @var Redis
     */
    private $redis;

    public function execute()
    {
        if (getConfig('enable_robot_rank', false)) {
            mt_srand();
            // 任务榜
            foreach (getConfig('robot_rand_player_factor', []) as $phone => $amount) {
                if (mt_rand(0, 1) > 0) {
                    continue;
                }
                Timer::after(mt_rand(1, 70) * 1000, function () use ($phone, $amount) {
                    $key  = sprintf('DailyTaskRank:%s', date('Ymd'));
                    $key1 = sprintf('DailyTaskAmount:%s:%s', date('Ymd'), $phone);
                    $this->redis->zIncrBy($key, 1, $phone);
                    $this->redis->incrByFloat(sprintf('DailyTaskAmount:%s:%s', date('Ymd'), $phone), (float)$amount);
                    $this->redis->set(sprintf('UserAvatar:%s', $phone), '', 86400);
                    $this->redis->expire($key, 86400);
                    $this->redis->expire($key1, 86400);
                });
            }
            //发布榜
            foreach (getConfig('robot_rand_merchant_factor', []) as $phone => $amount) {
                if (mt_rand(0, 1) > 0) {
                    continue;
                }
                Timer::after(mt_rand(1, 70) * 1000, function () use ($phone, $amount) {
                    $key  = sprintf('DailyPublishTaskRank:%s', date('Ymd'));
                    $key1 = sprintf('DailyPublishTaskAmount:%s:%s', date('Ymd'), $phone);
                    $this->redis->zIncrBy($key, 1, $phone);
                    $this->redis->incrByFloat(sprintf('DailyPublishTaskAmount:%s:%s', date('Ymd'), $phone), (float)$amount);
                    $this->redis->set(sprintf('UserAvatar:%s', $phone), '', 86400);
                    $this->redis->expire($key, 86400);
                    $this->redis->expire($key1, 86400);
                });
            }
        }
    }
}