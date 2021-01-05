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
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use function Hyperf\ViewEngine\view;

/**
 * PublicController
 *
 * @Controller()
 * @author
 * @package App\Controller\v1
 */
class PublicController extends AbstractController
{
    /**
     * 充值页面
     *
     * @GetMapping(path="rechargeView")
     * @throws InvalidArgumentException
     */
    public function rechargeView()
    {
        $pay_no = $this->request->input('pay_no');
        $data   = $this->container->get(CacheInterface::class)->get($pay_no);
        return view('formPost', $data);
    }
}