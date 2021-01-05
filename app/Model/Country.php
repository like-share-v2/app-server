<?php

declare (strict_types=1);
namespace App\Model;

use App\Service\Dao\LanguageDAO;
use Hyperf\Contract\TranslatorInterface;

/**
 * 国家模型
 *
 * @property int $id 
 * @property string $code 
 * @property string $name 
 * @property string $lang 
 * @property string $image
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Country extends Model
{
    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'country';
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
    protected $casts = ['id' => 'integer', 'created_at' => 'date:Y-m-d H:i:s', 'updated_at' => 'date:Y-m-d H:i:s'];

    /**
     * 获取
     *
     * @return mixed
     */
    public function getNameAttribute($value)
    {
        $local = $this->getContainer()->get(TranslatorInterface::class)->getLocale();

        $name = $this->getContainer()->get(LanguageDAO::class)->getValueByKeyLocal($value, $local);

        return $name;
    }
}