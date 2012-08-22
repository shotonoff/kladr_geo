<?
require "Autoloader.php";
Autoloader::RegisterAutoloader();
require "run.php";
?>
<!doctype html>
<html>
<head>
    <title>geo kladr</title>
    <meta charset=utf-8>

    <script src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script>

    <script src="<?=BaseUrl?>js/jquery-1.7.2.js"></script>
    <script src="<?=BaseUrl?>js/colorbox/colorbox/jquery.colorbox.js"></script>
    <script src="<?=BaseUrl?>js/plugins/jquery.form.js"></script>
    <script src="<?=BaseUrl?>js/plugins/autocomplete/jquery.autocomplete.js"></script>

    <script src="<?=BaseUrl?>js/initialize.js"></script>
    <script src="<?=BaseUrl?>js/cabinet.js"></script>

    <link rel="stylesheet" href="<?=BaseUrl?>css/960/reset.css"/>
    <link rel="stylesheet" href="<?=BaseUrl?>css/960/text.css"/>
    <link rel="stylesheet" href="<?=BaseUrl?>css/960/960.css"/>
    <link rel="stylesheet" href="<?=BaseUrl?>css/layout.css"/>
    <link rel="stylesheet" href="<?=BaseUrl?>js/colorbox/colorbox.css"/>

    <script type="text/javascript">
        ZPayment.location = {
            lng: <?=$geoLocationEntity->getLongitude()?>,
            lat: <?=$geoLocationEntity->getLatitude()?>
        }
    </script>
    <script type="text/javascript">
        ZPayment.BaseUlr = "<?=BaseUrl?>";
        $(function () {
            $(".colorbox").colorbox({
                href:"<?=BaseUrl?>_content.php",
                onComplete:function () {
                    ZPayment.init();
                    client();
                }
            });
        })
    </script>
</head>
<body>
<div class="wrapper">
    <header>
        <div class="container_12"></div>
    </header>

    <div id="page" class="container_12">
        <a id="geo" class="colorbox" href="#">popup</a>
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