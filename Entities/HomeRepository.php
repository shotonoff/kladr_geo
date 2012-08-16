<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/10/12
 * Time: 10:30 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Entities;
class HomeRepository
{
    public function getEntityManager()
    {
        return \Env::$em;
    }

    /**
     * @param $CITYCODE
     * @return array
     *
     * @desc
     * СС РРР ГГГ ППП УУУУ ДДДД, где
     * СС – код субъекта Российской Федерации (региона), коды регионов представлены в Приложении 2 к Описанию
     * классификатора адресов Российской Федерации (КЛАДР);
     * РРР – код района;
     * ГГГ – код города;
     * ППП – код населенного пункта;
     * УУУУ – код улицы (если адрес не содержит наименования улицы, т.е. дома привязаны непосредственно к городу или
     * населенному пункту, то код улицы будет содержать нули – 0000);
     * ДДДД – порядковый номер позиции классификатора с обозначениями домов.
     */

    public function getHomeList($search, $REGIONCODE, $AREACODE, $CITYCODE, $PLACECODE, $STREETCODE)
    {
        $qb = $this->doSelect(array(
            'REGIONCODE' => $REGIONCODE,
            'AREACODE' => $AREACODE,
            'CITYCODE' => $CITYCODE,
            'PLACECODE' => $PLACECODE,
            'STREETCODE' => $STREETCODE,
//            'HOMECODE' => '%',
        ));
        return $qb->getQuery()->getScalarResult();
//        return $qb->andWhere($qb->expr()->like('h.name', $qb->expr()->literal("%".$search."%")))->getQuery()->getScalarResult();
    }

    public function getHome($REGIONCODE, $AREACODE, $CITYCODE, $PLACECODE, $STREETCODE, $HOMECODE)
    {
        $qb = $this->doSelect(array(
            'REGIONCODE' => $REGIONCODE,
            'AREACODE' => $AREACODE,
            'CITYCODE' => $CITYCODE,
            'PLACECODE' => $PLACECODE,
            'STREETCODE' => $STREETCODE,
            'HOMECODE' => $HOMECODE,
        ));
        return $qb->getQuery()->getSingleResult();
    }

    private function doSelect(array $code)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('h')->from('\Entities\Doma', 'h');

        $codeDefault = array(
            'REGIONCODE' => '__',
            'AREACODE' => '___',
            'CITYCODE' => '___',
            'PLACECODE' => '___',
            'STREETCODE' => '____',
            'HOMECODE' => '____'
        );
        $code = array_merge($codeDefault, $code);
        $qb->Where($qb->expr()->like('h.code', $qb->expr()->literal(implode($code, ""))));
        return $qb;
    }

    static public function ParseCode($code)
    {
        return array(
            'REGIONCODE' => substr($code, 0, 2), // Регион
            'AREACODE' => substr($code, 2, 3), // Район
            'CITYCODE' => substr($code, 5, 3), // Город
            'PLACECODE' => substr($code, 8, 3), // Код населенного пункта
            'STREETCODE' => substr($code, 11, 4), // Код населенного пункта
            'HOMECODE' => substr($code, 15, 4), // Код позиции классификатора с обозначениями домов
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
