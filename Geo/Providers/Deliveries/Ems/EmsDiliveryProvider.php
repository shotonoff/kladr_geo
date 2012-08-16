<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/3/12
 * Time: 2:43 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Geo\Providers\Deliveries\Ems;

use \Geo\Providers\Deliveries\Ems\ENUM_EMS;
use \Geo\Providers\Deliveries\DeliveryProviderBase;

class EmsDiliveryProvider extends \Geo\Providers\Deliveries\DeliveryProviderBase
{
    public function doRequest($params)
    {
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET'
            )
        ));
        $urlReq = ENUM_EMS::URL . $this->buildReqParams($params);
        $content = file_get_contents($urlReq, false, $context);
        /** @var $resultObj \stdClass */
        $resultObj = json_decode($content, true);
        if ($resultObj['rsp']['stat'] == 'ok') {
            return $resultObj['rsp'];
        }
        return false;
    }

    public function administrativeAreaNameFilter($value)
    {
        $value = \String::strtoupper_utf8($value);
        $pos = strpos($value, 'РЕСПУБЛИКА');
        if ($pos !== false && $pos == 0) {
            $value = substr($value, strpos($value, " ") + 1) . 'РЕСПУБЛИКА';
        }
        return str_replace(" ", '', $value);
    }

    public function cityNameFilter($value)
    {
        return 'city--' . $this->transliteFilter(\String::strtolower_utf8($value));
    }

    public function getCities()
    {
        $params = array(
            'method' => ENUM_EMS::METHOD_LOCATION,
            'type' => 'cities',
            'plain' => true
        );
        $result = $this->doRequest($params);
        if (false !== $result) {
            return $result['locations'];
        }
        return null;
    }

    public function getCountries()
    {
        $params = array(
            'method' => ENUM_EMS::METHOD_LOCATION,
            'type' => 'countries',
            'plain' => true
        );
        $collection = $this->doRequest($params);
    }

    public function getRegions()
    {
        $params = array(
            'method' => ENUM_EMS::METHOD_LOCATION,
            'type' => 'regions',
            'plain' => true
        );
        $result = $this->doRequest($params);
        if (false !== $result) {
            return $result['locations'];
        }
        return null;
    }

    private function transliteFilter($value)
    {
        $letter = array(
            'й' => "j", 'ц' => "ts", 'у' => "u", 'к' => "k", 'е' => "e", 'н' => "n", 'г' => "g", 'ш' => "sh", 'щ' => "sch",
            'з' => "z", 'х' => "h", 'ъ' => "", 'ф' => "f", 'ы' => "y", 'в' => "v", 'а' => "a", 'п' => "p", 'р' => "r",
            'о' => "o", 'л' => "l", 'д' => "d", 'ж' => "zh", 'э' => "e", 'я' => "ja", 'ч' => "ch", 'с' => "s", 'м' => "m",
            'и' => "i", 'т' => "t", 'ь' => "", 'б' => "b", 'ю' => "ju", 'ё' => 'e');

        return str_replace(array_keys($letter), $letter, $value);
    }
}
