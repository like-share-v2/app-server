<?php
/**
 * @copyright 
 * @version 1.0.0
 * @link
 */
namespace App\Kernel\SMS;

use Psr\Container\ContainerInterface;

/**
 * 短信工厂
 *
 *
 * @package App\Kernel\SMS
 */
class SMSFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * SMSFactory constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * 阿里云短信
     *
     * @return SMSInterface
     */
    public function getAliCloud(): SMSInterface
    {
        return $this->container->get(AliCloud::class);
    }
}