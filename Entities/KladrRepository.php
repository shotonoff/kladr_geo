<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/10/12
 * Time: 10:30 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Entities;
class KladrRepository
{
    public function getEntityManager()
    {
        return \Env::$em;
//        return \Env::$em->createQueryBuilder();
    }

    /**
     * @desc метод возвращает список регионов
     * @return array
     */
    public function getRegionList()
    {
        $qb = $this->doSelect(array(
            'AREACODE' => '000',
            'CITYCODE' => '000',
            'PLACECODE' => '000',
            'ATTRIBUTE' => '00',
        ));

        return $qb->getQuery()->getResult();
    }

    /**
     * @desc метод возвращает список округов внутри региона
     * @param $REGIONCODE
     * @return array
     */
    public function getAreaList($REGIONCODE)
    {
        $qb = $this->doSelect(array(
            'REGIONCODE' => $REGIONCODE,
            'CITYCODE' => '000',
            'PLACECODE' => '000',
            'ATTRIBUTE' => '00',
        ));
        return $qb->getQuery()->getResult();
    }

    /**
     * @desc метод возвращает список городов внутри
     * @param $REGIONCODE
     * @param $AREACODE
     * @param string $PLACECODE
     * @return array
     */
    public function getCityList($REGIONCODE, $AREACODE, $PLACECODE = "___")
    {
        $qb = $this->doSelect(array(
            'REGIONCODE' => $REGIONCODE,
            'AREACODE' => $AREACODE,
            'PLACECODE' => $PLACECODE,
            'ATTRIBUTE' => '00',
        ));
        return $qb->getQuery()->getResult();
    }

    /**
     * @desc возвращает регион по коду
     * @param $REGIONCODE
     * @return mixed
     */
    public function getRegion($REGIONCODE)
    {
        $qb = $this->doSelect(array(
            'REGIONCODE' => $REGIONCODE,
            'AREACODE' => '000',
            'CITYCODE' => '000',
            'PLACECODE' => '000',
            'ATTRIBUTE' => '00',
        ));
        return $qb->getQuery()->getSingleResult();
    }

    /**
     * @desc возвращает округ по коду
     * @param $REGIONCODE
     * @param $AREACODE
     * @return mixed
     */
    public function getArea($REGIONCODE, $AREACODE)
    {
        $qb = $this->doSelect(array(
            'REGIONCODE' => $REGIONCODE,
            'AREACODE' => $AREACODE,
            'CITYCODE' => '000',
            'PLACECODE' => '000',
            'ATTRIBUTE' => '00',
        ));
        return $qb->getQuery()->getSingleResult();
    }

    /**
     * @desc возвращает город по коду
     * @param $REGIONCODE
     * @param $AREACODE
     * @param $CITYCODE
     * @return mixed
     */
    public function getCity($REGIONCODE, $AREACODE, $CITYCODE)
    {
        $qb = $this->doSelect(array(
            'REGIONCODE' => $REGIONCODE,
            'AREACODE' => $AREACODE,
            'CITYCODE' => $CITYCODE,
            'PLACECODE' => '000',
            'ATTRIBUTE' => '00',
        ));
        return $qb->getQuery()->getSingleResult();
    }

    private function doSelect(array $code)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('k')->from('\Entities\Kladr', 'k');

        $codeDefault = array(
            'REGIONCODE' => '__',
            'AREACODE' => '___',
            'CITYCODE' => '___',
            'PLACECODE' => '___',
            'ATTRIBUTE' => '__'
        );

        $code = array_merge($codeDefault, $code);
        $qb->Where($qb->expr()->like('k.code', $qb->expr()->literal(implode($code, ""))));
        return $qb;
    }

    /**
     * @param $code string
     * @desc
     * формат: СС РРР ГГГ ППП АА
     * СС – код субъекта Российской Федерации (региона), коды регионов представлены в Приложении 2 к Описанию классификатора адресов Российской Федерации (КЛАДР);
     * РРР – код района;
     * ГГГ – код города;
     * ППП – код населенного пункта,
     * АА – признак актуальности наименования адресного объекта.
     * @return array
     */
    static public function ParseCode($code)
    {
        return array(
            'REGIONCODE' => substr($code, 0, 2), // Регион
            'AREACODE' => substr($code, 2, 3), // Район
            'CITYCODE' => substr($code, 5, 3), // Город
            'PLACECODE' => substr($code, 8, 3), // Код населенного пункта
            'ATTRIBUTE' => substr($code, 11, 2), // Признак актуальности
        );
    }

    /**
     * @desc конвертируем сокращения(г обл рес и т.д.) в полные наименования
     * @param $scname string
     * @return mixed
     */
    public function ConvertShortToLong($scname)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $query = $qb->select("s")->from("\Entities\Socrbase", "s")
            ->andWhere($qb->expr()->eq('s.scname', ":scname"))
            ->setParameter("scname", $scname)
            ->getQuery();

        $query->useResultCache(true)
            ->setResultCacheLifeTime(3600);

        $res = $query->getResult();
        return current($res);
    }
}
