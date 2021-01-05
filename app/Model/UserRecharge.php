<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\Relations\HasOne;

/**
 * 用户充值模型
 *
 * @property int $id
 * @property int $user_id 
 * @property int $level 
 * @property float $balance 
 * @property int $payment_id 
 * @property int $recharge_time 
 * @property int $channel 
 * @property int $admin_id 
 * @property string $remark 
 * @property \Carbon\Carbon $updated_at 
 */
class UserRecharge extends Model
{
    public $dateFormat = 'U';

    // 创建时间
    public const CREATED_AT = 'recharge_time';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_recharge';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'level' => 'integer', 'balance' => 'float', 'payment_id' => 'integer', 'recharge_time' => 'date:Y-m-d H:i:s', 'channel' => 'integer', 'admin_id' => 'integer', 'updated_at' => 'date:Y-m-d H:i:s'];

    /**
     * 关联支付表
     *
     * @return BelongsTo
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    /**
     * 关联会员等级
     *
     * @return BelongsTo
     */
    public function userLevel()
    {
        return $this->belongsTo(UserLevel::class, 'level', 'level');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}