<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/13/12
 * Time: 5:22 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Geo\Providers\Geocoder;

abstract class MapApiBase
{
    protected $params = array();

    protected $country;

    protected $city;

    protected $street;

    protected $home;

    protected $flat;

    protected $latitude;

    protected $longitude;

    /**
     * @param $requestCallback \Closure
     */
    public function load(\Closure $requestCallback)
    {
        $this->preRequestCallback();
        $result = $this->doRequest();

        /**
         * @param $data string
         * @return mixed
         * @desc $data - строка формата opengis gml
         */
        $requestCallback($result);
    }

    protected function doRequest()
    {
        $requestUrl = str_replace(" ", "+", trim($this->getUrl() . $this->format()));
        $context = stream_context_create(array(
            'http' => array(
                'request_fulluri' => true
            )
        ));
        $data = file_get_contents($requestUrl, false, $context);
        return $data;
    }

    public function addParam($param)
    {
        $this->params[] = $param;
    }

    public function getParam($key)
    {
        return $this->param[$key];
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setHome($home)
    {
        $this->home = $home;
    }

    public function getHome()
    {
        return $this->home;
    }

    public function setStreet($street)
    {
        $this->street = $street;
    }

    public function getStreet()
    {
        return $this->street;
    }

    abstract public function getUrl();

    abstract public function format();

    public function setFlat($flat)
    {
        $this->flat = $flat;
    }

    public function getFlat()
    {
        return $this->flat;
    }

    public function preRequestCallback()
    {
    }

    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }
}
