<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/3/12
 * Time: 4:27 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Geo;
class GoecoderCreater
{
    /**
     * @param $value
     * @return \Geo\Geocoder\MapApiBase
     */
    public function Create($value)
    {
//        Запрос «Москва, Тверская, 18, корпус 1»: http://geocode-maps.yandex.ru/1.x/?geocode=город Москва, Тверская улица, дом 18, корпус
        $geocoder = new \Geo\Providers\Geocoder\Yandex\Geocoder();

        if (preg_match("/(?:\d+(?:\.\d+)?[,]?){2}/", $value))
            $this->reverse($geocoder, $value);
        else
            $this->direct($geocoder, $value);
        return $geocoder;
    }

    private function direct($geocoder, $value)
    {
        $arr = explode(",", $value);
        if (!isset($arr[0]))
            throw new \Exception("Не указана страна");
        if (!isset($arr[1]))
            throw new \Exception("Не указан город");
        if (!isset($arr[2]))
            throw new \Exception("Не указана улица");
        if (!isset($arr[3]))
            throw new \Exception("Не указан дом");

        $geocoder->setCountry(trim($arr[0]));
        $geocoder->setCity(trim($arr[1]));
        $geocoder->setStreet(trim($arr[2]));
        $geocoder->setHome(trim($arr[3]));
        if (isset($arr[4]))
            $geocoder->setFlat(trim($arr[4]));

    }

    private function reverse($geocoder, $value)
    {
        $arr = explode(",", $value);
        $longitude = trim($arr[0]);
        $latitude = trim($arr[1]);
        $geocoder->setLongitude($longitude);
        $geocoder->setLatitude($latitude);
    }
}