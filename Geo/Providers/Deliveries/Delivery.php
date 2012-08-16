<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/3/12
 * Time: 2:40 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Geo\Providers\Deliveries;

class DeliveryLocation
{
    private $city;
    private $area;

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setArea($area)
    {
        $this->area = $area;
    }

    public function getArea()
    {
        return $this->area;
    }
}

class Delivery extends DeliveryBase
{
    /**
     * @desc откуда
     */
    protected $from;
    /**
     * @desc куда
     */
    protected $to;
    /**
     * @desc вес
     */
    protected $weight;
    /**
     * @desc ценность
     */
    protected $valuation;
    /**
     * @desc цена доставки
     */
    protected $price;
    /**
     * @время доставки
     */
    protected $term;

    public function __construct($from, $to, $weight, $valuation = null)
    {
        if($from == null)
            throw new \Exception;

        if($to == null)
            throw new \Exception;

        $repo = new \Entities\KladrRepository();
        $deliveryFrom = new DeliveryLocation();
        $deliveryTo = new DeliveryLocation();

        $fromParseCode = \Entities\KladrRepository::ParseCode($from->getCode());
        $toParseCode = \Entities\KladrRepository::ParseCode($to->getCode());

        /** @var $fromKladrEntity \Entities\Kladr */
        $fromKladrEntity = $repo->getCity($fromParseCode['REGIONCODE'], $fromParseCode['AREACODE'], $fromParseCode['CITYCODE']);
        /** @var $toKladrEntity \Entities\Kladr */
        $toKladrEntity = $repo->getCity($toParseCode['REGIONCODE'], $toParseCode['AREACODE'], $toParseCode['CITYCODE']);

        $deliveryFrom->setCity($fromKladrEntity->getName());
        $deliveryTo->setCity($toKladrEntity->getName());

        $fromKladrEntity = $repo->getArea($fromParseCode['REGIONCODE'], $fromParseCode['AREACODE']);
        $toKladrEntity = $repo->getArea($toParseCode['REGIONCODE'], $toParseCode['AREACODE']);

        $deliveryFrom->setArea(kladr_name_filter($fromKladrEntity, true));
        $deliveryTo->setArea(kladr_name_filter($toKladrEntity, true));

        $this->setFrom($deliveryFrom);
        $this->setTo($deliveryTo);
        $this->setWeight($weight);
        $this->setValuation($valuation);
    }

    public function Calc()
    {
        return $this;
    }

    /**
     * @param  $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param  $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * @return
     */
    public function getTo()
    {
        return $this->to;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setTerm($term)
    {
        $this->term = $term;
    }

    public function getTerm()
    {
        return $this->term;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setValuation($valuation)
    {
        $this->valuation = $valuation;
    }

    public function getValuation()
    {
        return $this->valuation;
    }

}