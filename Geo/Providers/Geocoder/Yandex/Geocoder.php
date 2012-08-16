<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/3/12
 * Time: 4:04 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Geo\Providers\Geocoder\Yandex;
use \Geo\Providers\Geocoder\MapApiBase;

class Geocoder extends MapApiBase
{
    const URL = " http://geocode-maps.yandex.ru/1.x/?geocode=";

    public function getUrl()
    {
        return self::URL;
    }

    public function format()
    {
        /**
         * @desc Запрос может принимать вид прямого геокодирования и обратного:
         * прямое геокодирование состоит из последовательного прилеженеия к точности запрашиваемого гео объекта
         * пример запроса приямого геокодирования: Москва, Тверская улица, дом 18, корпус 1
         * обратное геокодирование состоит из передчи широты и долготы
         * пример запроса обратного геокодирования: 104.308640,52.275261
         */
        $req = array();
        if ($this->getLongitude() != null && $this->getLatitude() != null) {
            $req[] = $this->getLongitude();
            $req[] = $this->getLatitude();
        } else {
            $req[] = $this->getCountry();
            $req[] = $this->getCity();
            $req[] = $this->getStreet() . " улица";
            $req[] = "дом " . $this->getHome();
            $this->getParams();
        }
        return implode(",", $req);
    }

}
