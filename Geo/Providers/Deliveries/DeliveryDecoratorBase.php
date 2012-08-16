<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/3/12
 * Time: 2:41 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Geo\Providers\Deliveries;

abstract class DeliveryDecoratorBase extends DeliveryBase
{
    /**
     * @var Delivery
     */
    protected $delivery;

    function __construct(DeliveryBase $delivery)
    {
        $this->delivery = $delivery;
    }
}