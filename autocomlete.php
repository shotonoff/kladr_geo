<?
defined("ROOT_PATH") || define("ROOT_PATH", $_SERVER['DOCUMENT_ROOT']);
set_include_path(ROOT_PATH . PATH_SEPARATOR . ROOT_PATH . DIRECTORY_SEPARATOR . "vendors");
require "Autoloader.php";
Autoloader::RegisterAutoloader();
require "run.php";

if (!isset($_REQUEST['code']))
    return;

if (!isset($_REQUEST['type']))
    return;

$code = kladr_param_filter($_REQUEST['code']);
$type = $_REQUEST['type'];
$q = $_REQUEST['q'];

$response = array();


switch ($type) {
    case 'street':
        $streetRepository = new \Entities\StreetRepository();
        $parseCode = \Entities\StreetRepository::ParseCode($code);
        $streets = $streetRepository->getStreetList($q, $parseCode['REGIONCODE'], $parseCode['AREACODE'], $parseCode['CITYCODE'], "000");
        foreach ($streets as $street) {
            $response[] = array(
                'name' => $street['s_name'],
                'code' => $street['s_code']
            );
        }
        break;
    case 'home':
        $homeRepository = new \Entities\HomeRepository();
        $parseCode = \Entities\HomeRepository::ParseCode($code);
        $homes = $homeRepository->getHomeList(null,
            $parseCode['REGIONCODE'],
            $parseCode['AREACODE'],
            $parseCode['CITYCODE'],
            $parseCode['PLACECODE'],
            $parseCode['STREETCODE']);
        $numbers = array();
        foreach ($homes as $home) {
            $merge = array();
            foreach (parseHomeItem($home, $numbers) as $item)
                if (substr($item['name'], 0, strlen($q)) == $q)
                    $response[] = $item;
        }
        break;
    case 'zip':
        $homeRepository = new \Entities\HomeRepository();
        $parseCode = \Entities\HomeRepository::ParseCode($code);
        $homes = $homeRepository->getHomeList(null,
            $parseCode['REGIONCODE'],
            $parseCode['AREACODE'],
            $parseCode['CITYCODE'],
            $parseCode['PLACECODE'],
            $parseCode['STREETCODE']);
        foreach ($homes as $home) {
            if (false !== ($response = intersectionHome($q, $home)))
                break;
        }
        break;
}
echo json_encode($response);

function parseHomeItem($home, &$numbers)
{
    $output = array();
    $j = 0;
    foreach (explode(",", $home['h_name']) as $val) {
        if (preg_match("/(:?Н|Ч)\((\d+)\-(\d+)\)$/", $val, $matches)) {
            if($matches[3] == 999 || $matches[3] == 998)
                $matches[3] = 200;

            for ($i = $matches[2]; $i <= $matches[3]; $i += 2) {
                if (!in_array($i, $numbers)) {
                    $numbers[] = $i;
                    $output[] = array(
                        'code' => $home['h_code'],
                        'zip' => $home['h_index'],
                        'name' => $i
                    );
                }
            }
        } elseif (preg_match("/(:?(?:влд)|(:?д?влд))(\d+)$/", $val, $matches)) {
            if (!in_array($matches[2], $numbers)) {
                $output[] = array(
                    'code' => $home['h_code'],
                    'zip' => $home['h_index'],
                    'name' => $matches[2]
                );
            }
        } else {
            if (!in_array($val, $numbers)) {
                $output[] = array(
                    'code' => $home['h_code'],
                    'zip' => $home['h_index'],
                    'name' => $val
                );
            }
        }
        $j++;
    }
    return $output;
}

function intersectionHome($numb, $home)
{
    foreach (explode(",", $home['h_name']) as $val) {
        if (preg_match("/(:?Н|Ч)\((\d+)\-(\d+)\)$/", $val, $matches)) {
            for ($i = $matches[2]; $i <= $matches[3]; $i += 2) {
                if ($matches[2] <= $numb && $matches[3] >= $numb) {
                    return array(
                        'zip' => $home['h_index'],
                    );
                }
            }
        } elseif (preg_match("/(:?(?:влд)|(:?д?влд))(\d+)$/", $val, $matches)) {
            if ($matches[2] == $numb) {
                return array(
                    'zip' => $home['h_index'],
                );
            }
        } else {
            if ($val == $numb) {
                return array(
                    'zip' => $home['h_index'],
                );
            }
        }
    }
    return false;
}