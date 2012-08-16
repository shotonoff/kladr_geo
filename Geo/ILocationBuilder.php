<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/14/12
 * Time: 3:21 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Geo;

interface ILocationBuilder
{
    /**
     * @abstract
     * @param $data string
     * @return Entities\UserLocation|object
     */
    public function build($data);
}