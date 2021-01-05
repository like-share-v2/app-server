<?php

declare (strict_types=1);
namespace App\Model;

use App\Service\Dao\LanguageDAO;
use Hyperf\Contract\TranslatorInterface;

/**
 * 任务分类
 *
 * @property int $id 
 * @property string $name 
 * @property string $icon
 * @property string $banner
 * @property float $lowest_price
 * @property int $sort 
 * @property int $status 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class TaskCategory extends Model
{
    public $dateFormat = 'U';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'task_category';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'lowest_price' => 'float', 'sort' => 'integer', 'status' => 'integer', 'created_at' => 'date:Y-m-d H:i', 'updated_at' => 'date:Y-m-d H:i'];

    /**
     * 分类名获取器
     *
     * @param $value
     * @return mixed
     */
    public function getNameAttribute($value)
    {
        $local = $this->getContainer()->get(TranslatorInterface::class)->getLocale();

        $name = $this->getContainer()->get(LanguageDAO::class)->getValueByKeyLocal($value, $local);

        return $name;
    }
}