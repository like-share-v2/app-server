<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Kernel\SMS;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use App\Common\Base;
use App\Exception\LogicException;

/**
 *
 * @package App\Kernel\SMS
 */
class AliCloud extends Base implements SMSInterface
{
    /**
     * @inheritDoc
     */
    public function sendSMS(string $phone_number, string $content, string $template_code, string $sign_name): array
    {
        AlibabaCloud::accessKeyClient(env('ALI_CLOUD_APP_KEY'), env('ALI_CLOUD_SECRET_KEY'))
            ->regionId('cn-hangzhou')
            ->asDefaultClient();

        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host(env('ALI_CLOUD_SMS_BASE_URL'))
                ->options([
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'PhoneNumbers' => $phone_number,
                        'SignName' => $sign_name,
                        'TemplateCode' => $template_code,
                        'TemplateParam' => $content
                    ],
                ])
                ->request();
        } catch (ClientException $e) {
            throw new LogicException($e->getMessage());
        } catch (ServerException $e) {
            throw new LogicException($e->getMessage());
        }

        return $result->toArray();
    }
}