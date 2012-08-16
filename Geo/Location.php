<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/3/12
 * Time: 2:39 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Geo;

class Location
{
    /**
     * API
     * http://blog.ipgeobase.ru/
     */


    const FIELD_ALL = "all";
    const FIELD_INETNUM = "inetnum";
    const FIELD_INET_DESCR = "inet-descr";
    const FIELD_INET_STATUS = "inet-status";
    const FIELD_CITY = "city";
    const FIELD_REGION = "region";
    const FIELD_DISTRICT = "district";
    const FIELD_LATITUDE = "lat";
    const FIELD_LONGITUDE = "lng";

    const API_URL = "http://194.85.91.253:8090/geo/geo.html";

    static public function loadForIp($ip)
    {
        $pattern = "<%s/>";
        $fields = array();
        $args = func_get_args();
        if (func_num_args() > 1)
            for ($i = 1; $i < func_num_args(); $i++)
                $fields[$args[$i]] = sprintf($pattern, $args[$i]);
        else
            $fields[self::FIELD_ALL] = sprintf($pattern, self::FIELD_ALL);

        $keys = array_keys($fields);

        $xml = "<ipquery><fields>%s</fields><ip-list>"
            . "<ip>%s</ip></ip-list></ipquery>";

        $xml = sprintf($xml, implode($fields, ""), $ip);
        $ch = curl_init(self::API_URL);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $result = curl_exec($ch);
        if (curl_errno($ch) != 0)
            die("curl_errno(" . curl_errno($ch) . "), curl_error(" . curl_error($ch) . ")");
        curl_close($ch);
        if (strpos($result, '<message>Not found</message>') !== false)
            return false;
        $pattern = "/<%s>(.*)<\/%s>/";
        $response = array();

        foreach ($keys as $key) {
            $regexp = sprintf($pattern, $key, $key);
            preg_match($regexp, $result, $param);
            if (mb_detect_encoding($param[1], 'UTF-8,CP1251') == 'Windows-1251')
                $response[$key] = iconv("Windows-1251", "UTF-8", $param[1]);
            else
                $response[$key] = $param[1];
        }
        return $response;
    }
}