<?
defined("ROOT_PATH") || define("ROOT_PATH", $_SERVER['DOCUMENT_ROOT']);

set_include_path(get_include_path() . PATH_SEPARATOR . ROOT_PATH . DIRECTORY_SEPARATOR . "vendors");

$view = array();

require "Autoloader.php";
Autoloader::RegisterAutoloader();
require "run.php";

?>
<!doctype html>
<html>
<head>
    <title>Geo Z-Payment</title>
    <meta charset=utf-8>

    <script src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script>

    <script src="<?=BaseUrl?>js/jquery-1.7.2.js"></script>
    <script src="<?=BaseUrl?>js/plugins/jquery.form.js"></script>
    <script src="<?=BaseUrl?>js/plugins/autocomplete/jquery.autocomplete.js"></script>
    <script src="<?=BaseUrl?>js/plugins/jquery.validate.js"></script>
    <script src="<?=BaseUrl?>js/plugins/additional-methods.js"></script>

    <script src="<?=BaseUrl?>js/initialize.js"></script>
    <script src="<?=BaseUrl?>js/cabinet.js"></script>

    <link rel="stylesheet" href="<?=BaseUrl?>css/960/reset.css"/>
    <link rel="stylesheet" href="<?=BaseUrl?>css/960/text.css"/>
    <link rel="stylesheet" href="<?=BaseUrl?>css/960/960.css"/>
    <link rel="stylesheet" href="<?=BaseUrl?>css/layout.css"/>
    <??>
    <script type="text/javascript">
        ZPayment.location = {
            lng: <?=$geoLocationEntity->getLongitude()?>,
            lat: <?=$geoLocationEntity->getLatitude()?>
        }
    </script>
    <script type="text/javascript">
        ZPayment.BaseUlr = "<?=BaseUrl?>";
    </script>
</head>
<body>
<style type="text/css">
    .geo-select {
        /*float: left;*/
    }

    #loading {
        display: none;
    }

    .ac_results ul {
        background-color: white;
        border: 1px solid #CCC;
    }

    .ac_results ul li {
        padding-left: 5px;
        margin-left: 0px;
        list-style-type: none;
    }

    .ac_results ul li.ac_over {
        background-color: #ccc;
    }
</style>

<div class="wrapper">
    <header>
        <div class="container_12"></div>
    </header>

    <div id="page" class="container_12">
        <div id="zpaymnet-map" style="width: 100%; height: 400px;"></div>
        <p>

        <div>IP Страна: <?=$geoLocationEntity->getCountry()?></div>
        <div>IP Регион: <?=$geoLocationEntity->getRegion()?></div>
        <div>IP Город: <?=$geoLocationEntity->getCity()?></div>
        </p>
        <form method="post">
            <div>
                <label>Укажите ваше место расположение</label>
            </div>
            <div>
                <?if (isset($view['userEntity']) && $view['userEntity'] instanceof \Entities\UserLocation): ?>
                <input size="100" name="location" value="<?=$view['userEntity']->getGeoAddress()?>"/>
                <? else: ?>
                <input size="100" name="location" value=""/>
                <?endif?>
                <?/*Иркутск, Румянцева улица, 26*/?>
                <input name="btn-search" type="button" value="показать на карте"/>
            </div>
        </form>

        <?/* * * * * * * * * * * * * * * * * */?>

        <br/><br/><br/>
        <hr/>

        <div class="autobox">
            <form action="" method="post">
                <div class="geo-select wrap-country">
                    <select disabled="true" name="country">
                        <option value="0">Россия</option>
                    </select>
                </div>
                <?/*?>
                <div class="geo-select wrap-administrative-area">
                    <select name="administartive-area">
                        <option value="0">Москва</option>
                        <option value="1">Иркутск</option>
                        <option value="2">Санкт-Петербург</option>
                    </select>
                </div>
                <?*/?>
                <div class="geo-select wrap-REGIONCODE">
                    <select name="REGIONCODE">
                        <option value="0">Выберите регион</option>
                        <?foreach ($view['regions'] as $code => $region): ?>
                        <option <?=($view['state']['region']->getCode('REGIONCODE') == $region->getCode('REGIONCODE')) ? "selected" : ""?>
                            value="REGIONCODE--<?=$region->getCode('REGIONCODE')?>"><?=kladr_name_filter($region, true)?></option>
                        <? endforeach?>
                    </select>
                </div>
                <div class="geo-select wrap-area">
                    <?if (isset($view['area'])): ?>
                    <select name="AREACODE">
                        <? foreach ($view['area'] as $area): ?>
                        <option value="AREACODE--<?=$area->getCode()?>"
                            <?= ($view['state']['area']->getCode('AREACODE') == $area->getCode('AREACODE')) ? "selected" : "" ?>
                                data-code="<?=$area->getCode()?>"><?=kladr_name_filter($area, true)?></option>
                        <? endforeach?>
                    </select>
                    <? endif?>
                </div>
                <div class="geo-select wrap-cities">
                    <?if (isset($view['cities'])): ?>
                    <select name="CITYCODE">
                        <? foreach ($view['cities'] as $city): ?>
                        <option value="CITYCODE--<?=$city->getCode()?>"
                            <?= ($view['state']['city']->getCode('CITYCODE') == $city->getCode('CITYCODE')) ? "selected" : "" ?>
                                data-code="<?=$city->getCode()?>"><?=kladr_name_filter($city, true)?></option>
                        <? endforeach?>
                    </select>
                    <? endif?>
                </div>
                <div style="clear: left;">
                    <b id="loading">Loading...</b>
                </div>
                <br/>

                <div>
                    <div>
                        Улица
                    </div>
                    <div>
                        <input size="100" type="text" name="street"/>
                    </div>
                    <div class="description">
                        <div>
                            <i>Название улицы</i>
                        </div>
                    </div>
                </div>
                <br/>

                <div>
                    <div>
                        Дом
                    </div>
                    <div>
                        <input size="100" type="text" name="home"/>
                    </div>
                    <div class="description">
                        <div>
                            <i>Номер дома</i>
                        </div>
                    </div>

                </div>
                <br/>

                <div>
                    <div>Индекс</div>
                    <input type="text" name="zip"/>
                </div>

                <div>
                    <input type="submit" name="save" value="save"/>
                    <span></span>
                </div>
            </form>
        </div>
        <br/>
        <br/>

        <div style="border: 1px solid #ccc;padding: 10px;">
            <div>Рассчёт стоимости</div>
            <div>
                <form id="delivery-calculate" action="delivery.php" method="post">
                    <select name="delivery">
                        <option value="ems">EMS</option>
                        <option value="ruspost">Russian Post</option>
                    </select>
                    <input type="submit"/>

                    <div class="calc-response"></div>
                </form>
            </div>
        </div>

        <div>
            <script type="text/javascript" src="http://app.ecwid.com/script.js?1445034" charset="utf-8"></script>
            <script type="text/javascript"> xProductBrowser("categoriesPerRow=3","views=grid(3,3) list(10) table(20)","categoryView=grid","searchView=list","style="); </script>
            <noscript>Ваш браузер не поддерживает JavaScript. Пожалуйста, перейдите на <a href="http://app.ecwid.com/jsp/1445034/catalog">HTML версию Голубев Дмитрий Евгеньевич's store</a></noscript>
        </div>

    </div>
</div>


<div id="footer">
    <div id="footer-inner" class="container_12 clear-block">
        <div id="footer-message" class="grid_12"></div>
    </div>
</div>
<div id="debug"></div>
</body>
</html>