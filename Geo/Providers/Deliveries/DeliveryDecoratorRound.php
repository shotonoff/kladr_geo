<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/3/12
 * Time: 2:41 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Geo\Providers\Deliveries;

class DeliveryDecoratorRound extends DeliveryDecoratorBase
{
    public function Calc()
    {
        $delivery = $this->delivery->Calc();
        $delivery->setPrice(number_format($delivery->getPrice(), 2, ".", ""));
        return $delivery;
    }
}
