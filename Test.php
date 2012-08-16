<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/3/12
 * Time: 2:42 PM
 * To change this template use File | Settings | File Templates.
 */

class Test
{
    static public function LoaderText()
    {
        $GLOBALS['view']['userEntity'] = \Env::$em->find("\Entities\UserLocation", \Env::$account);
    }

    static public function DeliveryTest()
    {
        /** @var $locationFrom \Entities\UserLocation */
        $locationFrom = \Env::$em->find("\Entities\UserLocation", "ZP00000001");
        /** @var $locationTo \Entities\UserLocation */
        $locationTo = \Env::$em->find("\Entities\UserLocation", "ZP00000002");
        $delivery = new \Geo\Providers\Deliveries\Delivery($locationFrom, $locationTo, 2);

        if (isset($_GET['delivery']) && $_GET['delivery'] == 'ruspost') {
            /** @var $provider \Geo\Providers\Deliveries\DeliveryProviderBase */
            $provider = new \Geo\Providers\Deliveries\RussianPost\RussianPostDeliveryProvider();
            $delivery->setValuation(0);
            $DeliveryDecorator = new \Geo\Providers\Deliveries\RussianPost\RussianPostDeliveryDecorator($delivery, $provider);
        } else {
            /** @var $provider Geo\Providers\Deliveries\DeliveryProviderBase */
            $provider = new \Geo\Providers\Deliveries\Ems\EmsDiliveryProvider();
            $DeliveryDecorator = new \Geo\Providers\Deliveries\Ems\EmsDeliveryDecorator($delivery, $provider);
        }

        $DeliveryDecorator = new \Geo\Providers\Deliveries\DeliveryDecoratorRound($DeliveryDecorator);
        $amount = $DeliveryDecorator->Calc();
    }

    static function IpGeoTest()
    {

    }

    static function GeocoderReverseTest()
    {
        $str = "104.308640,52.275261";
        $GoecoderCreater = new \Geo\GoecoderCreater();
        /** @var $geocoder \Geo\Providers\Yandex\Geocoder */
        $geocoder = $GoecoderCreater->Create($str);
        $builder = new \Geo\LocationBuilder();

        $callback = function($data) use($builder)
        {
            /** @var $location \Entities\UserLocation */
            $_location = \Env::$em->find("\Entities\UserLocation", \Env::$account);
            $location = $builder->build($data);
            if (null === $_location)
                \Env::$em->persist($location);
            else
                \Env::$em->merge($location);
            \Env::$em->flush();
        };
        $geocoder->load($callback);
    }

    static function GeocoderTest()
    {
        $str = "Россия, Иркутск, Румянцева, 26";
        $GoecoderCreater = new \Geo\GoecoderCreater();
        /** @var $geocoder \Geo\Providers\Geocoder\Yandex\Geocoder */
        $geocoder = $GoecoderCreater->Create($str);
        $builder = new \Geo\LocationBuilder();

        $callback = function($data) use($builder)
        {
            /** @var $location \Entities\UserLocation */
            $_location = \Env::$em->find("\Entities\UserLocation", \Env::$account);
            $location = $builder->build($data);
            if (null === $_location)
                \Env::$em->persist($location);
            else
                \Env::$em->merge($location);
            \Env::$em->flush();
        };

        $geocoder->load($callback);
    }

    static public function KladrTest()
    {
//        $repo = new Entities\KladrRepository();
//        $region = $repo->getRegionList();


        //61 0 00 011 000 020 800
//        $autoList = $repo->getAutoList('38');
//        $qb = $repo->getEntityManager()->createQueryBuilder();
//        $autoList = $qb->select('a')->from('\Entities\AddrObj', 'a')
//            ->andWhere($qb->expr()->eq('a.PLAINCODE', ':plain'))
//            ->andWhere($qb->expr()->eq('a.ACTSTATUS', ':status'))
//            ->setParameters(array('plain' => '610000011000000', 'status' => '1'))
//            ->getQuery()
//            ->getResult();
//
//        var_dump($autoList);
//        die();
    }

    static public function LocationSave()
    {
        if (!isset($_REQUEST['save']) || $_REQUEST['save'] != 'save')
            return;
        /**
         * @var $arr
         */
        $kladrInformation = array();
        $kladrInformation['country'] = "Россия";

        $userLocationEntity = new \Entities\UserLocation();
        $REGIONCODE = (isset($_REQUEST['REGIONCODE'])) ? $_REQUEST['REGIONCODE'] : null;
        $AREACODE = (isset($_REQUEST['AREACODE'])) ? $_REQUEST['AREACODE'] : null;
        $CITYCODE = (isset($_REQUEST['CITYCODE'])) ? $_REQUEST['CITYCODE'] : null;

        $repo = new \Entities\KladrRepository();
        $userLocationEntity = new \Entities\UserLocation();

        $region = $area = $city = null;
        if ($CITYCODE != null) {
            $parseCode = \Entities\KladrRepository::ParseCode(kladr_param_filter($CITYCODE));
        } elseif ($AREACODE != null) {
            $parseCode = \Entities\KladrRepository::ParseCode($AREACODE);
        } elseif ($REGIONCODE != null) {
            $parseCode = \Entities\KladrRepository::ParseCode($REGIONCODE);
        }

        $region = $repo->getRegion($parseCode['REGIONCODE']);
        $kladrInformation['region'] = kladr_name_filter($region, true);
        $area = $repo->getArea($parseCode['REGIONCODE'], $parseCode['AREACODE']);
//        $kladrInformation['area'] = kladr_name_filter($area, true);
        $city = $repo->getCity($parseCode['REGIONCODE'], $parseCode['AREACODE'], $parseCode['CITYCODE']);
        $kladrInformation['city'] = kladr_name_filter($city, true);

        $p = explode(",", $_REQUEST['street']);

        if (isset($_REQUEST['street']))
            $additionAddress['street'] = $_REQUEST['street'];
        if (isset($_REQUEST['home']))
            $additionAddress['home'] = $_REQUEST['home'];
        if (isset($_REQUEST['section']))
            $additionAddress['section'] = $_REQUEST['section'];

        $additionAddress['zip'] = $_REQUEST['zip'];

        $kladrInformation['address'] = implode(", ", $additionAddress);
        $GoecoderCreater = new \Geo\GoecoderCreater();
        /** @var $geocoder \Geo\Providers\Geocoder\Yandex\Geocoder */
        $strLocation = implode(", ", $kladrInformation);
        $geocoder = $GoecoderCreater->Create($strLocation);
        $builder = new \Geo\LocationBuilder();

        $callback = function($data) use($builder, $kladrInformation, $additionAddress, $parseCode)
        {
            $geoParams['yandex'] = $data;
            $geoParams['kladr'] = $kladrInformation;
            /** @var $location \Entities\UserLocation */
            $_location = \Env::$em->find("\Entities\UserLocation", \Env::$account);
            $location = $builder->build($geoParams);
            $location->setCode(implode('', $parseCode));

            $GeoAddress = implode(", ", $kladrInformation);
            $location->setGeoAddress($GeoAddress);

            if (isset($additionAddress['home']))
                $location->setHome($additionAddress['home']);
            if (isset($additionAddress['section']))
                $location->setSection($additionAddress['section']);
            if (isset($additionAddress['street']))
                $location->setStreet($additionAddress['street']);
            $location->setZip($additionAddress['zip']);

            if (null === $_location)
                \Env::$em->persist($location);
            else
                \Env::$em->merge($location);
            \Env::$em->flush();
        };
        $geocoder->load($callback);
    }
}