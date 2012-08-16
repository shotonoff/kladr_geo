<?php

namespace Geo\Providers\Deliveries\Ems;

class ENUM_EMS
{
    const METHOD_LOCATION = "ems.get.locations";
    const METHOD_MAX_WEIGHT = "ems.get.max.weight";
    const METHOD_CALCULATE = "ems.calculate";

    const TYPE_LOCATION_CITIES = "cities";
    const TYPE_LOCATION_REGIONS = "regions";
    const TYPE_LOCATION_COUNTRIES = "countries";
    const TYPE_LOCATION_RUSSIA = "russia";

    const URL = "http://emspost.ru/api/rest/?";
}