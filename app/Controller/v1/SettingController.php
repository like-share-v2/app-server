<?php
declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link
 */

namespace App\Controller\v1;

use App\Controller\AbstractController;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

/**
 * SettingController
 *
 * @Controller()
 * @author
 * @package App\Controller\v1
 */
class SettingController extends AbstractController
{
    /**
     * @GetMapping(path="")
     */
    public function index()
    {
        $this->success([
            'loginPageContent'      => getConfig('loginPageContent', ''),
            'withdrawalPageTip'     => getConfig('withdrawalPageTip', ''),
            'enableRobotAutoSubmit' => getConfig('isEnableRobotAutoSubmit', false),
            'robotPopupContent'     => getConfig('robotPopupContent', ''),
            'appDownloadUrl'        => getConfig('app_download_url', ''),
            'firstLoadImageUrl'     => getConfig('firstLoadImageUrl', ''),
            'enable_register_sms'   => getConfig('enable_register_sms', false)
        ]);
    }
}