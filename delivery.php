<?php
require "Autoloader.php";
Autoloader::RegisterAutoloader();
require "run.php";

/** @var $locationFrom \Entities\UserLocation */
$locationFrom = \Env::$em->find("\Entities\UserLocation", "ZP00000001");
/** @var $locationTo \Entities\UserLocation */
$locationTo = \Env::$em->find("\Entities\UserLocation", "ZP00000002");
$delivery = new \Geo\Providers\Deliveries\Delivery($locationFrom, $locationTo, 2);

if (isset($_REQUEST['delivery']) && $_REQUEST['delivery'] == 'ruspost') {
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
$result = $DeliveryDecorator->Calc();


$response = <<<EOT
<div>
    <div>Стоимость доставки по направлению <b>%s -> %s</b>: <span>%s р.</span></div>
    <div>Время доставки: <span>%s</span> дней</div>
    <div>Вес: <span>%s</span> кг. </div>
</div>
EOT;
$term = (array)$result->getTerm();
$timeDelivery = (isset($term['max']) && !empty($term['max']))?$term['max']:$term['min'];

echo sprintf($response, $result->getFrom()->getCity(), $result->getTo()->getCity(), $result->getPrice(),
    $timeDelivery, $result->getWeight());