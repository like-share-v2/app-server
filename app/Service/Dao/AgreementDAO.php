<?php

declare(strict_types=1);

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\Agreement;
use Hyperf\Contract\TranslatorInterface;

/**
 * 协议DAO
 *
 * @package App\Service\Dao
 */
class AgreementDAO extends Base
{
    public function get(array $params)
    {
        $locale = $this->container->get(TranslatorInterface::class)->getLocale();

        $model = Agreement::query()->where('locale', $locale);

        if (isset($params['type'])) {
            $model->where('type', $params['type']);
        }

        return $model->select(['content'])->value('content');
    }
}