<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\Database\Model\Relations\HasOne;

/**
 * 用户模型
 *
 * @property int $id
 * @property int $parent_id
 * @property int $type
 * @property int $level
 * @property string $effective_time
 * @property string $account
 * @property string $password
 * @property string $trade_pass
 * @property string $phone
 * @property string $email
 * @property string $nickname
 * @property string $avatar
 * @property int $gender
 * @property float $balance
 * @property int $integral
 * @property int $credit
 * @property int $status
 * @property string $ip
 * @property int $last_login_time
 * @property string $created_at
 * @property int $updated_at
 * @property UserInfo $info
 */
class User extends Model
{
    /**
     * @var string
     */
    protected $dateFormat = 'U';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';
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
    protected $casts = [
        'id'              => 'integer',
        'parent_id'       => 'integer',
        'level'           => 'integer',
        'gender'          => 'integer',
        'balance'         => 'float',
        'integral'        => 'integer',
        'credit'          => 'integer',
        'status'          => 'integer',
        'last_login_time' => 'date:Y-m-d H:i:s',
        'created_at'      => 'date:Y-m-d H:i:s',
        'updated_at'      => 'integer'
    ];
    /**
     * @var array
     */
    protected $hidden = [
        'updated_at'
    ];

    /**
     * 密码转hash
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = password_hash($value, PASSWORD_DEFAULT);
        }
    }

    /**
     * 交易密码转hash
     *
     * @param $value
     */
    public function setTradePassAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['trade_pass'] = password_hash($value, PASSWORD_DEFAULT);
        }
    }

    /**
     * 关联用户信息
     *
     * @return HasOne
     */
    public function info()
    {
        return $this->hasOne(UserInfo::class, 'user_id', 'id');
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

    /**
     * 头像获取器
     *
     * @param $value
     * @return mixed
     */
    public function getAvatarAttribute($value)
    {
        if (empty($value)) {
            return getConfig('default_avatar', '');
        }

        return $value;
    }

    /**
     * 到期时间获取器
     *
     * @param $value
     * @return false|string
     */
    public function getEffectiveTimeAttribute($value)
    {
        if ($value === -1) {
            return '-';
        }
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * 关联用户等级
     *
     * @return HasMany
     */
    public function userMember()
    {
        return $this->hasMany(UserMember::class, 'user_id', 'id')
            ->with(['userLevel:level,name,task_num,duration']);
    }

    /**
     * 关联充值记录
     *
     * @return HasMany
     */
    public function recharge()
    {
        return $this->hasMany(UserRecharge::class, 'user_id');
    }
}