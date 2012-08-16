<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/3/12
 * Time: 3:24 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Entities;

/**
 * @Entity
 * @Table(name="userlocation")
 */
class UserLocation
{
    /**
     * @Id
     * @Column(type="string", length=10, unique=true)
     * @desc ZP аккаунт
     */
    protected $account;
    /**
     * @Column(type="string")
     */
    protected $code;
    /**
     * @Column(type="float")
     */
    protected $latitude;
    /**
     * @Column(type="float")
     */
    protected $longitude;
    /**
     * @Column(type="integer", length=6)
     */
    protected $zip;
    /**
     * @Column(type="integer", length=4)
     */
    protected $home;
    /**
     * @Column(type="string", nullable=false)
     */
    protected $street;
    /**
     * @Column(type="string", nullable=true)
     */
    protected $section;
    /**
     * @Column(type="string")
     */
    protected $geoAddress;


    public function setAccount($account)
    {
        $this->account = $account;
    }

    public function getAccount()
    {
        return $this->account;
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

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setGeoAddress($geoAddress)
    {
        $this->geoAddress = $geoAddress;
    }

    public function getGeoAddress()
    {
        return $this->geoAddress;
    }

    public function setHome($home)
    {
        $this->home = $home;
    }

    public function getHome()
    {
        return $this->home;
    }

    public function setSection($section)
    {
        $this->section = $section;
    }

    public function getSection()
    {
        return $this->section;
    }

    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    public function getZip()
    {
        return $this->zip;
    }

    public function setStreet($street)
    {
        $this->street = $street;
    }

    public function getStreet()
    {
        return $this->street;
    }
}
