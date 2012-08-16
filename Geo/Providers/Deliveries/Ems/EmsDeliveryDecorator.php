<?php

namespace Geo\Providers\Deliveries\Ems;

use Geo\Providers\Deliveries\DeliveryDecoratorBase;

use \Geo\Providers\Deliveries\DeliveryBase;
use \Geo\Providers\Deliveries\Ems\EmsDiliveryProvider;
use \Geo\Providers\Deliveries\Ems\ENUM_EMS;


class EmsDeliveryDecorator extends DeliveryDecoratorBase
{
    const MAX_WEIGHT = 31.5;

    protected $provider;

    public function __construct(DeliveryBase $delivery, EmsDiliveryProvider $provider)
    {
        parent::__construct($delivery);
        $this->provider = $provider;
    }

    public function Calc()
    {
        /** @var $from \Entities\UserLocation */
        $from = $this->delivery->getFrom();
        /** @var $to \Entities\UserLocation */
        $to = $this->delivery->getTo();
//        $result = $this->doRequest(ENUM_EMS::METHOD_LOCATION, $params);

        $fromCity = $this->provider->cityNameFilter($from->getCity());
        $toCity = $this->provider->cityNameFilter($to->getCity());
//        $fromCity = $this->provider->cityNameFilter('Иркутск');
//        $toCity = $this->provider->cityNameFilter("Кострома");

        $weight = $this->delivery->getWeight();

        if ($weight > self::MAX_WEIGHT)
            throw new \Exception('Превышен максимально допустимый вес посылки');

        $params = array(
            'weight' => $weight
        );
        $regions = null;
        function _search($collection, $city = null, $region = null)
        {
            if ($city !== null)
                foreach ($collection as $item) {
                    if ($item['value'] == $city)
                        return $item['value'];
                }
            if ($region !== null)
                foreach ($collection as $item) {
                    if ($item['name'] == $region)
                        return $item['value'];
                }
            return null;
        }


        $cities = $this->provider->getCities();
        $params['from'] = _search($cities, $fromCity);
        $params['to'] = _search($cities, $toCity);


        if (null == $params['from']) {
            $regions = $this->provider->getRegions();
            $fromAA = $this->provider->administrativeAreaNameFilter($from->getArea());
            $params['from'] = _search($regions, null, $fromAA);
        }
        if (null == $params['to']) {
            ($regions == null) && $regions = $this->provider->getRegions();
            $toAA = $this->provider->administrativeAreaNameFilter($to->getArea());
            $params['to'] = _search($regions, null, $toAA);
        }

        $params['method'] = ENUM_EMS::METHOD_CALCULATE;
        $result = $this->provider->doRequest($params);

        if ($result['stat'] == 'ok'){
            $this->delivery->setPrice($result['price']);
            $this->delivery->setTerm($result['term']);
        }
        return $this->delivery;
    }

}
