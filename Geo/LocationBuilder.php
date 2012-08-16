<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/14/12
 * Time: 3:22 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Geo;

class LocationBuilder implements ILocationBuilder
{
    /**
     * @param $data
     * @return Entities\UserLocation|object
     */
    public function build($data)
    {
        /** @var $xml \SimpleXmlElement */
        $xml = simplexml_load_string($data['yandex']);
        $GeocoderMetaData = $xml->GeoObjectCollection->featureMember->GeoObject->metaDataProperty->GeocoderMetaData;
        $kind = (string)$GeocoderMetaData->kind;
        $AddressLine = (string)$GeocoderMetaData->AddressDetails->Country->AddressLine;
        $AddressDetails = $GeocoderMetaData->AddressDetails->Country;
        $cityName = (string)$AddressDetails->AdministrativeArea->Locality->LocalityName;
        $administrativeAreaName = (string)$AddressDetails->AdministrativeArea->AdministrativeAreaName;
        $CountryNameCode = (string)$GeocoderMetaData->AddressDetails->Country->CountryNameCode;
        $CountryName = (string)$GeocoderMetaData->AddressDetails->Country->CountryName;

        $pos = $xml->GeoObjectCollection->featureMember->GeoObject->Point->pos[0];
        $pos = explode(" ", $pos);
        /**
         * @var $latitude float
         * @desc Широта
         */
        $latitude = $pos[0];
        /**
         * @var $longitude float
         * @desc Долгота
         */
        $longitude = $pos[1];
        $location = new \Entities\UserLocation();
        $location->setAccount(\Env::$account);
        $location->setLongitude($longitude);
        $location->setLatitude($latitude);
        return $location;
    }
}