<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/3/12
 * Time: 3:20 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Entities;

/**
 * @Entity
 * @Table(name="geolocation")
 */
class GeoLocation
{
    /**
     * @Id
     * @Column(type="integer", unique=true)
     */
    protected $ip;
    /**
     * @Column(type="float")
     */
    protected $latitude;
    /**
     * @Column(type="float")
     */
    protected $longitude;
    /**
     * @Column(type="string")
     */
    protected $country;
    /**
     * @Column(type="string", length=60, nullable=true)
     */
    protected $region;

    /**
     * @Column(type="string")
     */
    protected $city;
    /**
     * @Column(type="string")
     */
    protected $address;

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getAddress()
    {
        return $this->address;
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

    public function setIp($ip)
    {
        if (is_string($ip))
            $ip = ip2long($ip);
        $this->ip = $ip;
    }

    public function getIp()
    {
        return long2ip($this->ip);
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

    public function setRegion($region)
    {
        $this->region = $region;
    }

    public function getRegion()
    {
        return $this->region;
    }
}
