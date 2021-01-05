<?php

declare (strict_types=1);

namespace App\Model;


use App\Service\Dao\LanguageDAO;
use Hyperf\Contract\TranslatorInterface;
use Hyperf\Database\Model\Relations\HasOne;

/**
 * 用户等级模型
 *
 * @property int            $id
 * @property int            $level
 * @property string         $name
 * @property string         $icon
 * @property float          $price
 * @property int            $task_num
 * @property int            $duration
 * @property int            $day
 * @property int            $hour
 * @property int            $minute
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int            $max_buy_num
 */
class UserLevel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_level';
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
        'id'          => 'integer',
        'level'       => 'integer',
        'price'       => 'float',
        'task_num'    => 'int',
        'created_at'  => 'date:Y-m-d H:i',
        'updated_at'  => 'date:Y-m-d H:i',
        'max_buy_num' => 'integer'
    ];

    protected $appends = [
        'day',
        'hour',
        'minute'
    ];

    /**
     * 关联充值奖励
     *
     * @return HasOne
     */
    public function rechargeLevelRebate()
    {
        return $this->hasOne(UserLevelRebate::class, 'level_id', 'id')->where('type', 1);
    }

    /**
     * 关联任务奖励
     *
     * @return HasOne
     */
    public function taskLevelRebate()
    {
        return $this->hasOne(UserLevelRebate::class, 'level_id', 'id')->where('type', 2);
    }

    /**
     * 名字获取器
     *
     * @param $value
     *
     * @return mixed
     */
    public function getNameAttribute($value)
    {
        $local = $this->getContainer()->get(TranslatorInterface::class)->getLocale();

        $name = $this->getContainer()->get(LanguageDAO::class)->getValueByKeyLocal($value, $local);

        return $name;
    }

    public function getDayAttribute()
    {
        $day = (int)($this->duration / 86400);
        return $day;
    }

    public function getHourAttribute()
    {
        $hour = (int)(($this->duration - $this->day * 86400) / 3600);
        return $hour;
    }

    public function getMinuteAttribute()
    {
        $minute = (int)(($this->duration - $this->day * 86400 - $this->hour * 3600) / 60);

        return $minute;
    }
}