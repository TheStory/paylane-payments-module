<?php
/**
 * Created by PhpStorm.
 * User: urfin
 * Date: 01.03.15
 * Time: 07:00
 */

namespace Payments\Service;

use Zend\Cache\Storage\Adapter\Apc;
use Zend\Http\Client;

/**
 * EU taxes operations
 * @package Payments\Service
 */
class Taxes extends AbstractService
{
    /**
     * Get VAT tax for provided country
     * @param $countryCode ISO country code eg. PL
     * @return int base tax rate or 0 if country not found
     * @throws \Exception if HTTP response status code not 200
     */
    public function getTaxForCountry($countryCode)
    {
        /** @var Apc $cache */
        $cache = $this->getServiceLocator()->get('cache.longlife');

        // check if data is in cache
        if ($cache->hasItem('taxes')) {
            $data = $cache->getItem('taxes');
        } else {

            // API request for all countries data

            $client = new Client();
            $client->setUri('http://euvatrates.com/rates.json')
                ->setOptions([
                    'ssltransport' => 'tls',
                    'sslverifypeer' => false,
                ])
                ->send();

            $response = $client->getResponse();

            // wrong http response
            if ($response->getStatusCode() != 200) {
                throw new \Exception('Response error. Status code: ' . $response->getStatusCode());
            }

            $responseData = json_decode($response->getBody());
            $data = $responseData->rates;

            // store in cache

            $cache->setItem('taxes', $data);
        }

        // search for provided country code

        foreach ($data as $k => $v) {
            if ($k == strtoupper($countryCode)) {
                return $v->standard_rate;
            }
        }

        // country not found in data - return default base tax

        return 0;
    }
}