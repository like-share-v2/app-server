<?php

declare(strict_types=1);

namespace App\Controller\v1;

use App\Controller\AbstractController;
use App\Service\Dao\CountryBandCodeDAO;
use App\Service\Dao\CountryBankDAO;
use App\Service\Dao\CountryDAO;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

/**
 * 国家控制器
 *
 * @Controller()
 * @package App\Controller\v1
 */
class CountryController extends AbstractController
{
    /**
     * 获取国家列表
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $result = $this->container->get(CountryDAO::class)->get();

        $this->success($result);
    }

    /**
     * 获取国家银行卡
     *
     * @GetMapping(path="bank")
     */
    public function getBank()
    {
        $country_id = (int)$this->request->input('country_id', 0);

        $result = $this->container->get(CountryBankDAO::class)->firstByCountryId($country_id);

        if (!$result) {
            $this->error('logic.COUNTRY_NOT_HAVE_BANK');
        }

        $this->success($result);
    }

    /**
     * @GetMapping(path="country_bank")
     */
    public function getLocaleCountry()
    {
        $country_id = (int)$this->request->input('country_id', 0);

        $country = $this->container->get(CountryDAO::class)->firstById($country_id);

        $bank = $this->container->get(CountryBankDAO::class)->firstByCountryId($country_id);

        if (!$bank) {
            $bank = [
                'id' => '',
                'country_id' => '',
                'bank_name' => '',
                'bank_address' => '',
                'bank_account' => '',
                'address' => '',
                'created_at' => '',
                'updated_at' => ''
            ];
        }

        $this->success([
            'country' => $country,
            'bank' => $bank
        ]);
    }

    /**
     * 银行代码列表
     *
     * @GetMapping(path="bank_code")
     */
    public function getBankCode()
    {
        $country_id = (int)$this->request->input('country_id', 0);

        $result = $this->container->get(CountryBandCodeDAO::class)->getListByCountryId($country_id);

        $this->success($result);
    }
}