<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/9/12
 * Time: 3:02 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Entities;
interface IGeoObjectEntity
{
    public function getRegionList();

    public function getCityList();

    public function getStreetList();
}
