<?php

declare(strict_types=1);

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\Invitation;
use Hyperf\Contract\TranslatorInterface;

/**
 * 邀请函DAO
 *
 * @package App\Service\Dao
 */
class InvitationDAO extends Base
{
    public function first()
    {
        $locale = $this->container->get(TranslatorInterface::class)->getLocale();

        $image = Invitation::query()->where('locale', $locale)->value('image');

        if (!$image) {
            $image = Invitation::query()->value('image');
        }

        return $image;
    }
}