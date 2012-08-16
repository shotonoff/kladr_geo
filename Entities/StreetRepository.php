<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/10/12
 * Time: 10:30 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Entities;
class StreetRepository
{
    public function getEntityManager()
    {
        return \Env::$em;
//        return \Env::$em->createQueryBuilder();
    }

    /**
     * @param $CITYCODE
     * @return array
     *
     * @desc
     * СС РРР ГГГ ППП УУУУ АА, где
     * СС – код субъекта Российской Федерации (региона), коды регионов представлены в Приложении 2 к Описанию классификатора адресов Российской Федерации (КЛАДР);
     * РРР – код района;
     * ГГГ – код города;
     * ППП – код населенного пункта;
     * УУУУ – код улицы;
     * АА – признак актуальности наименования адресного объекта.
     */

    public function getStreetList($search, $REGIONCODE, $AREACODE, $CITYCODE, $PLACECODE)
    {
        $qb = $this->doSelect(array(
            'REGIONCODE' => $REGIONCODE,
            'AREACODE' => $AREACODE,
            'CITYCODE' => $CITYCODE,
            'PLACECODE' => $PLACECODE,
            'ATTRIBUTE' => '00',
        ));
        return $qb->andWhere($qb->expr()->like('s.name', $qb->expr()->literal($search."%")))->getQuery()->getScalarResult();
    }

    public function getStreet($REGIONCODE, $AREACODE, $CITYCODE, $PLACECODE, $STREETCODE)
    {
        $qb = $this->doSelect(array(
            'REGIONCODE' => $REGIONCODE,
            'AREACODE' => $AREACODE,
            'CITYCODE' => $CITYCODE,
            'PLACECODE' => '000',
            'STREETCODE' => $STREETCODE,
            'ATTRIBUTE' => '00',
        ));
        return $qb->getQuery()->getSingleResult();
    }

    private function doSelect(array $code)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('s')->from('\Entities\Street', 's');

        $codeDefault = array(
            'REGIONCODE' => '__',
            'AREACODE' => '___',
            'CITYCODE' => '___',
            'PLACECODE' => '___',
            'SREETCODE' => '____',
            'ATTRIBUTE' => '__'
        );

        $code = array_merge($codeDefault, $code);
        $qb->Where($qb->expr()->like('s.code', $qb->expr()->literal(implode($code, ""))));
        return $qb;
    }

    static public function ParseCode($code)
    {
        return array(
            'REGIONCODE' => substr($code, 0, 2), // Регион
            'AREACODE' => substr($code, 2, 3), // Район
            'CITYCODE' => substr($code, 5, 3), // Город
            'PLACECODE' => substr($code, 8, 3), // Код населенного пункта
            'STREETCODE' => substr($code, 11, 3), // Код населенного пункта
            'ATTRIBUTE' => substr($code, 15, 2), // Признак актуальности
        );
    }

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
