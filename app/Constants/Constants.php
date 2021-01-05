<?php

declare(strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Constants;

/**
 * 常量合集
 *
 *
 * @package App\Constants
 */
class Constants
{
    /**
     * Authorization
     *
     * @var string
     */
    const AUTHORIZATION = 'Authorization';

    /**
     * Token 有效期
     *
     * @var int
     */
    const AUTHORIZATION_EXPIRE = 86400;

    /**
     * Token 提前续期时间
     *
     * @var integer
     */
    const AUTHORIZATION_RENEW = 2 * 60 * 60;

    /**
     * 文件类型白名单
     *
     * @var array
     */
    const UPLOADS_CONFIG = [
        [
            'directory' => 'images',
            'mime' => ['image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'image/bmp'],
            'maxSize' => 10485760
        ],
        [
            'directory' => 'videos',
            'mime' => ['video/mpeg', 'video/x-msvideo', 'video/mp4', 'application/mp4', 'video/x-flv', 'video/x-m4v', 'video/ogg', 'application/octet-stream', 'application/octet-stream'],
            'maxSize' => 10485760
        ]
    ];
}
