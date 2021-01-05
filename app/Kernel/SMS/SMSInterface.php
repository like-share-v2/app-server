<?php
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */
namespace App\Kernel\SMS;

/**
 *
 * @package App\Kernel\SMS
 */
interface SMSInterface
{
    /**
     * 发送短信
     *
     * @param string $phone_number
     * @param string $content
     * @param string $template_code
     * @param string $sign_name
     * @return array
     */
    public function sendSMS(string $phone_number, string $content, string $template_code, string $sign_name): array;
}