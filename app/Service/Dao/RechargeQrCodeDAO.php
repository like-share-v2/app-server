<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\RechargeQrCode;

/**
 * 充值二维码DAO
 *
 *
 * @package App\Service\Dao
 */
class RechargeQrCodeDAO extends Base
{
    /**
     * 随机获取二维码
     *
     * @return mixed
     */
    public function getQrCodeImage()
    {
        return RechargeQrCode::query()->where('status', 1)->select(['image'])->inRandomOrder()->first();
    }
}