<?
require "Autoloader.php";
Autoloader::RegisterAutoloader();
require "run.php";

$REGIONCODE = null;
$AREACODE = null;
$CITYCODE = null;

$query = array();
$repo = new \Entities\KladrRepository();
$response = array();

/** init params */
if (isset($_REQUEST['REGIONCODE']))
    $REGIONCODE = kladr_param_filter($_REQUEST['REGIONCODE']);

if (isset($_REQUEST['AREACODE']))
    $AREACODE = kladr_param_filter($_REQUEST['AREACODE']);

if (isset($_REQUEST['CITYCODE']))
    $CITYCODE = kladr_param_filter($_REQUEST['CITYCODE']);

/** select */
if ($REGIONCODE != null && $AREACODE == null && $CITYCODE == null) {
    $response[] = autoBoxBuilderResponse($repo->getAreaList($REGIONCODE), 'AREACODE', '.wrap-area');
    $citiesList = $repo->getCityList($REGIONCODE, "000", "000");
    unset($citiesList[0]);
    $response[] = autoBoxBuilderResponse($citiesList, 'CITYCODE', '.wrap-cities');
}

if ($REGIONCODE == null && $AREACODE != null && $CITYCODE == null) {
    $parseCode = \Entities\KladrRepository::ParseCode($AREACODE);
    $PLACECODE = ($parseCode['AREACODE'] == '000') ? '000' : '___';
    $citiesList = $repo->getCityList($parseCode['REGIONCODE'], $parseCode['AREACODE'], $PLACECODE);
    unset($citiesList[0]);
    $response[] = autoBoxBuilderResponse($citiesList, 'CITYCODE', '.wrap-cities');
}

header('Content-Type: application/json');
echo json_encode($response);

function autoBoxBuilderResponse($items, $codeName, $wrapSelector)
{
    $view = '<select name="%s">%s</select>';
    $viewItem = '<option value="%s--%s" data-code="%s" >%s</option>';
    $_ = '';

    foreach ($items as $index => $item) {
        $_ .= sprintf($viewItem, $codeName, $item->getCode($codeName, true), $item->getCode(), kladr_name_filter($item, true));
    }
    return array(
        'wrapElement' => $wrapSelector,
        'view' => sprintf($view, $codeName, $_)
    );
}