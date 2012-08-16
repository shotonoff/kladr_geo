<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/10/12
 * Time: 9:08 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Geo\Providers\Deliveries;

abstract class DeliveryProviderBase
{
    protected function buildReqParams($params)
    {
        foreach ($params as $key => &$param)
            $param = $key . "=" . $param;
        return implode("&", $params);
    }
}
