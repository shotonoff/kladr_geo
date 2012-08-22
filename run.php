<?
defined("ROOT_PATH") || define("ROOT_PATH", $_SERVER['DOCUMENT_ROOT']);
set_include_path(ROOT_PATH . PATH_SEPARATOR . ROOT_PATH . DIRECTORY_SEPARATOR . "vendors");
require ROOT_PATH . DIRECTORY_SEPARATOR . "Geo" . DIRECTORY_SEPARATOR . "common.php";
require ROOT_PATH . DIRECTORY_SEPARATOR . "Test.php";
defined("BaseUrl") || define("BaseUrl", "/");

$view = array();

class Env
{
    /**
     * @var $em \Doctrine\ORM\EntityManager
     */
    static public $em;
    static public $account = "ZP00000002";
}

use Doctrine\ORM\Tools\Setup;

$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . DIRECTORY_SEPARATOR . "Entities"), $isDevMode);
$config->setAutoGenerateProxyClasses(true);
$config->setProxyDir(realpath(ROOT_PATH . DIRECTORY_SEPARATOR . "tmp"));

$conn = array(
    'driver' => 'pdo_mysql',
    'host' => "localhost",
    'dbname' => "kladr_two",
    'user' => "root",
    'password' => "",
    'charset' => 'utf8',
    'driverOptions' => array(
        1002 => 'SET NAMES utf8'
    )
);
/** @var $em \Doctrine\ORM\EntityManager */
$em = \Doctrine\ORM\EntityManager::create($conn, $config);

Env::$em = $em;

$ip = "91.210.161.36";

$geoLocationEntity = \Env::$em->find("\Entities\GeoLocation", ip2long($ip));
if (null == $geoLocationEntity) {
    $country = "Россия";
    $geo = new \Geo\Geo($ip);
    if (false !== $geo->getLocation()) {
        $geoLocationEntity = new \Entities\GeoLocation();
        $geoLocationEntity->setIp($ip);
        $geoLocationEntity->setCountry($country);
        $geoLocationEntity->setRegion($geo->getLocation(\Geo\Location::FIELD_REGION));
        $geoLocationEntity->setCity($geo->getLocation(\Geo\Location::FIELD_CITY));
        $geoLocationEntity->setLongitude($geo->getLocation(\Geo\Location::FIELD_LONGITUDE));
        $geoLocationEntity->setLatitude($geo->getLocation(\Geo\Location::FIELD_LATITUDE));
        $address = array(
            $country,
            $geo->getLocation(\Geo\Location::FIELD_REGION),
            $geo->getLocation(\Geo\Location::FIELD_CITY)
        );
        $geoLocationEntity->setAddress(implode(", ", $address));
        \Env::$em->persist($geoLocationEntity);
        \Env::$em->flush();
    }
}


$qb = \Env::$em->createQueryBuilder();
$regionName = trim(str_replace(array('республика', 'область', 'край', 'автономный округ'), '', String::strtolower_utf8($geoLocationEntity->getRegion())));
/** @var $region \Entities\Kladr */
$GLOBALS['view']['state']['region'] = $region = $qb->select("k")->from("\Entities\Kladr", "k")
    ->where($qb->expr()->like('k.name', $qb->expr()->literal($regionName)))
    ->andWhere($qb->expr()->like('k.code_gninmb', $qb->expr()->literal("__00")))
    ->getQuery()
    ->getSingleResult();

$repo = new \Entities\KladrRepository();
$GLOBALS['view']['regions'] = $regions = $repo->getRegionList();

$qb = \Env::$em->createQueryBuilder();
$city = $qb->select("k")->from("\Entities\Kladr", "k")
    ->where($qb->expr()->like('k.name', $qb->expr()->literal($geoLocationEntity->getCity())))
    ->andWhere($qb->expr()->like("k.code", $qb->expr()->literal($region->getCode("REGIONCODE") . "%")))
    ->getQuery()->getResult();

$city = $GLOBALS['view']['state']['city'] = current($city);

$cities = $repo->getCityList($region->getCode("REGIONCODE"), $region->getCode("AREACODE"), $region->getCode("PLACECODE"));
$GLOBALS['view']['area'] = $repo->getAreaList($region->getCode("REGIONCODE"));
unset($cities[0]);

$GLOBALS['view']['cities'] = $cities;
$GLOBALS['view']['state']['area'] = $repo->getArea($city->getCode("REGIONCODE"), $city->getCode("AREACODE"));

if (isset($_REQUEST['migration']) && $_REQUEST['migration'] == '1') {
    $conn = $em->getConnection();
    $conn->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

    $tool = new \Doctrine\ORM\Tools\SchemaTool($em);

    $classes = array(
        $em->getClassMetadata('Entities\GeoLocation'),
        $em->getClassMetadata('Entities\UserLocation'),
        $em->getClassMetadata('Entities\Socrbase'),
//        $em->getClassMetadata('Entities\Altnames'),
        $em->getClassMetadata('Entities\Doma'),
//        $em->getClassMetadata('Entities\Flat'),
        $em->getClassMetadata('Entities\Kladr'),
        $em->getClassMetadata('Entities\Street')
    );
//    $tool->dropSchema($classes);
//    $tool->createSchema($classes);
    $tool->updateSchema($classes);
}