<?php
declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Controller\v1;

use App\Controller\AbstractController;
use App\Service\Dao\AppVersionDAO;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

/**
 * VersionController
 *
 * @Controller()
 * @author
 * @package App\Controller\v1
 */
class VersionController extends AbstractController
{
    /**
     * @GetMapping(path="")
     */
    public function index()
    {
        $params  = $this->request->all();
        $version = (int)($params['version'] ?? 0);
        if ($version === 0) {
            $this->success([
                'update' => false
            ]);
        }
        // 未获取到版本
        if (!$newest = $this->container->get(AppVersionDAO::class)->getNewestVersion()) {
            $this->success([
                'update' => false
            ]);
        }
        // 当前已经是最新版本
        if ($version >= $newest->version_number) {
            $this->success([
                'update' => false
            ]);
        }

        $this->success([
            'update'      => true,
            'version'     => $newest->version_number,
            'update_mode' => $newest->update_mode,
            'url'         => $newest->download_url,
            'mandatory'   => $newest->is_mandatory,
            'description' => $newest->description
        ]);
    }
}