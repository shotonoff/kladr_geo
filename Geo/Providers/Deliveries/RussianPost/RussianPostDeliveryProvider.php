<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/10/12
 * Time: 9:34 AM
 * To change this template use File | Settings | File Templates.
 *
 * Документация http://postcalc.ru/api.html
 */

namespace Geo\Providers\Deliveries\RussianPost;

class RussianPostDeliveryProvider extends \Geo\Providers\Deliveries\DeliveryProviderBase
{

    const URL = "http://postcalc.ru/get.php?";

    /** максимально допустимый вес, в кг. */
    const MAX_WEIGHT = 100;
    const MAX_VALUATION = 100;

    /**
     * @param array $params
     * @example
     *
     * Form [f] Москва || 101000
     * To [t] Санкт-Петербург || 190000
     * Weight [w] 1000 Вес отправления в граммах. Не более 100 кг.
     * Valuation [v] Оценка товарного вложения в рублях. Не более 100 тыс. рублей. По умолчанию 0.
     * Date [d] Дата, на которую нужно рассчитать отправку, в любом формате, который распознается функцией PHP strtotime().
     * По умолчанию now
     *
     * Step [s] Если 0, выводится подробный расчет для данного отправления, с учетом веса и оценки. Если равно 100, 500
     * или 1000, выдается таблица тарифов для отправлений разного веса с шагом 100, 500 или 1000 граммов соответственно.
     * Таблица содержит 20 строк.
     *
     * Country [c] Двузначный код страны (для международных доставок) из списка. Можно также использовать трехбуквенный
     * код или название страны по-русски либо по-английски. По умолчанию RU.
     *
     * Output [o] {HTML, PHP array, XML, JSON, ARR}
     *
     * Extended [e] Детализация ответа. При e=0 выдаются основные данные (тариф, страховка, оценка, наложенный платеж,
     * сроки доставки, адреса и телефоны отделений связи), при других значениях могут выдаваться некоторые
     * недокументированные сведения. При разработке собственных модулей, обращающихся к API PostCalc.RU,
     * настоятельно рекомендуется ограничиваться только основными данными, так как расширенные данные могут меняться
     * без уведомления.
     *
     * @example http://postcalc.ru/get.php?From=101000&Country=RU&To=190000&Weight=1000&Valuation=1000&Step=0&Date=10.02.2011
     *
     * @return mixed
     */
    public function doRequest(array $params)
    {
        $la = array(
            'Step' => '0',
            'Output' => 'json',
            'Country' => 'RU',
            'Date' => 'now',
            'Extended' => '0'
        );
        $params = array_merge($la, $params);

        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET'
            )
        ));
        $urlReq = self::URL . $this->buildReqParams($params);
        $content = file_get_contents($urlReq, false, $context);
        /** @var $resultObj \stdClass */
        $resultObj = json_decode($content, true);
//
//        Тариф
        if ($resultObj['Status'] == 'OK')
            return $resultObj;

        return false;
    }
}