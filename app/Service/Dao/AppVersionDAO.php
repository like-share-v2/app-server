<?php
declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Service\Dao;

use App\Model\AppVersion;

/**
 * AppVersionDAO
 *
 * @author
 * @package App\Service\Dao
 */
class AppVersionDAO
{
    /**
     * 获取最新的版本
     *
     * @return mixed
     */
    public function getNewestVersion(): ?AppVersion
    {
        return AppVersion::orderByDesc('version_number')->first();
    }
}