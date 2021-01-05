<?php
declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link       
 */

namespace App\Service;

use App\Common\Base;
use App\Model\UserMember;

use Hyperf\Database\Model\Relations\BelongsTo;

/**
 * IndexNotifyService
 *
 * @author
 * @package App\Service
 */
class IndexNotifyService extends Base
{
    public function getNewUserRecharge()
    {
        return array_map(
            function ($item) {
                $phone = '****' . substr($item['user']['phone'], -6, 6);
                return __('logic.MEMBER_RECHARGE_LEVEL', ['name' => $phone, 'level' => $item['user_level']['name'] ?? '']);
            },
            UserMember::with([
                'userLevel' => function (BelongsTo $builder) {
                    $builder->select('level', 'name');
                },
                'user'      => function (BelongsTo $builder) {
                    $builder->select('id', 'phone');
                }
            ])
                ->whereHas('user')
                ->orderByDesc('id')
                ->limit(50)
                ->get()
                ->toArray()
        );
    }

    /**
     * 获取今日完成任务排名
     */
    public function getTodayTaskCompleteRank()
    {
        $key = sprintf('DailyTaskRank:%s', date('Ymd'));

        return array_map(function ($member) use ($key) {
            $avatar = $this->redis->get(sprintf('UserAvatar:%s', $member));
            return [
                'phone'  => '****' . substr($member, -6, 6),
                'score'  => $this->redis->zScore($key, $member),
                'amount' => (float)$this->redis->get(sprintf('DailyTaskAmount:%s:%s', date('Ymd'), $member)),
                'avatar' => config('static_url') . ($avatar === '' ? 'images/05d0ac969a2ba835dd57a5cf200afba9.png' : $avatar)
            ];
        }, $this->redis->zRevRange($key, 0, 10));
    }

    /**
     * 获取今日发布任务排名
     */
    public function getTodayPublishTaskRank()
    {
        $key = sprintf('DailyPublishTaskRank:%s', date('Ymd'));

        return array_map(function ($member) use ($key) {
            return [
                'phone'  => '****' . substr($member, -6, 6),
                'score'  => $this->redis->zScore($key, $member),
                'amount' => (float)$this->redis->get(sprintf('DailyPublishTaskAmount:%s:%s', date('Ymd'), $member)),
                'avatar' => config('static_url') . $this->redis->get(sprintf('UserAvatar:%s', $member))
            ];
        }, $this->redis->zRevRange($key, 0, 10));
    }
}