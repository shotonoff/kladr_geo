<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/10/12
 * Time: 9:06 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Geo\Providers\Deliveries\RussianPost;

class RussianPostDeliveryDecorator extends \Geo\Providers\Deliveries\DeliveryDecoratorBase
{
    protected $provider;

    public function __construct(\Geo\Providers\Deliveries\DeliveryBase $delivery, \Geo\Providers\Deliveries\RussianPost\RussianPostDeliveryProvider $provider)
    {
        parent::__construct($delivery);
        $this->provider = $provider;
    }

    public function Calc()
    {
        $params['Valuation'] = $this->delivery->getValuation();
        $params['Weight'] = $this->delivery->getWeight();
        $params['From'] = $this->delivery->getFrom()->getCity();
        $params['To'] = $this->delivery->getTo()->getCity();

        $result = $this->provider->doRequest($params);
        $this->delivery->setPrice($result['ЦеннаяПосылка']['Доставка']);
        $this->delivery->setTerm(array(
            'min' => $result['Магистраль']['ДоставкаСтандарт'],
            'max' => null
        ));
        return $this->delivery;
    }
}
