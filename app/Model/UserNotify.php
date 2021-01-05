<?php

declare (strict_types=1);
namespace App\Model;

use App\Service\Dao\LanguageDAO;
use Hyperf\Contract\TranslatorInterface;

/**
 * 用户消息模型
 *
 * @property int $id 
 * @property int $type 
 * @property int $user_id 
 * @property string $title 
 * @property string $content 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class UserNotify extends Model
{
    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_notify';
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
    protected $casts = ['id' => 'integer', 'type' => 'integer', 'user_id' => 'integer', 'created_at' => 'date:Y-m-d H:i:s', 'updated_at' => 'date:Y-m-d H:i:s'];

    /**
     * 获取标题
     *
     * @param $value
     * @return mixed
     */
    public function getTitleAttribute($value)
    {
        $local = $this->getContainer()->get(TranslatorInterface::class)->getLocale();

        $name = $this->getContainer()->get(LanguageDAO::class)->getValueByKeyLocal($value, $local);

        return $name;
    }

    /**
     * 内容获取器
     *
     * @param $value
     * @return array
     */
    public function getContentAttribute($value)
    {
        $local = $this->getContainer()->get(TranslatorInterface::class)->getLocale();

        $value = UserNotifyContent::query()->where('notify_id', $this->id)->where('locale', $local)->value('content') ?? $value;

        return $value;
    }
}