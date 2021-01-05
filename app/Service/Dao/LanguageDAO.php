<?php

declare(strict_types=1);

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\Language;

/**
 * @package App\Service\Dao
 */
class LanguageDAO extends Base
{
    /**
     * 获取翻译文本
     *
     * @param string $key
     * @param string $local
     * @return mixed
     */
    public function getValueByKeyLocal(string $key, string $local)
    {
        return Language::query()->where('local', $local)->where('key', $key)->value('value') ?? $key;
    }

    /**
     * 通过键值获取列表
     *
     * @param string $key
     * @return array
     */
    public function getKeyList(string $key)
    {
        return array_column(Language::query()->where('key', $key)->get()->toArray(), 'value', 'local');
    }
}