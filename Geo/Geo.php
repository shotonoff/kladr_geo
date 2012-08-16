<?php
namespace Geo;

class Geo
{
    protected $location;

    public function __construct($ip)
    {
        $this->setLocation(Location::loadForIp($ip,
            Location::FIELD_LONGITUDE,
            Location::FIELD_LATITUDE,
            Location::FIELD_REGION,
            Location::FIELD_CITY));
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getLocation($key = null)
    {
        return ($key == null) ? $this->location : $this->location[$key];
    }
}


