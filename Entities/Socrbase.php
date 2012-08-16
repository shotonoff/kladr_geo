<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/10/12
 * Time: 9:54 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Entities;

/**
 * @Entity
 * @Table(name="socrbase")
 */
class Socrbase
{
    /**
     * @Column(type="string")
     */
    protected $level;
    /**
     * @Column(type="string")
     */
    protected $scname;
    /**
     * @Column(type="string")
     */
    protected $socrname;
    /**
     * @id
     * @Column(type="string")
     */
    protected $kod_t_st;

    public function setKodTSt($kod_t_st)
    {
        $this->kod_t_st = $kod_t_st;
    }

    public function getKodTSt()
    {
        return $this->kod_t_st;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setScname($scname)
    {
        $this->scname = $scname;
    }

    public function getScname()
    {
        return $this->scname;
    }

    public function setSocrname($socrname)
    {
        $this->socrname = $socrname;
    }

    public function getSocrname()
    {
        return $this->socrname;
    }
}
