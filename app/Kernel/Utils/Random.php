<?php
declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Kernel\Utils;

/**
 * 随机码生成
 *
 *
 * @package App\Kernel\Utils
 */
class Random
{
    /**
     * 生成六位随机码
     *
     * @return string
     */
    public static function generatorCode6(): string
    {
        mt_srand();

        return (string)mt_rand(100000,999999);
    }
}