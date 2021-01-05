<?php

declare (strict_types=1);
namespace App\Model;

use App\Service\Dao\LanguageDAO;
use Hyperf\Contract\TranslatorInterface;

/**
 * 帮助中心模型
 *
 * @property int $id 
 * @property string $title 
 * @property string $content 
 * @property int $status 
 * @property int $sort 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Help extends Model
{
    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'help';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'status' => 'integer', 'sort' => 'integer', 'created_at' => 'date:Y-m-d H:i:s', 'updated_at' => 'date:Y-m-d H:i:s'];

    /**
     * 内容获取器
     *
     * @param $value
     * @return array
     */
    public function getContentAttribute($value)
    {
        $local = $this->getContainer()->get(TranslatorInterface::class)->getLocale();

        $value = HelpContent::query()->where('help_id', $this->id)->where('locale', $local)->value('content') ?? $value;

        return explode(PHP_EOL, $value);
    }

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
}