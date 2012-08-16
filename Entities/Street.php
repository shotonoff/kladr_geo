<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/10/12
 * Time: 10:11 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Entities;


/**
 * @Entity
 * @Table(name="street")
 */
class Street
{
    /**
     * @Column(type="string")
     */
    protected $name;
    /**
     * @Column(type="string")
     */
    protected $socr;
    /**
     * @id
     * @Column(type="string")
     */
    protected $code;
    /**
     * @Column(type="string", length=25, nullable=true)
     */
    protected $index;
    /**
     * @Column(type="string", length=25, nullable=true)
     */
    protected $gninmb;
    /**
     * @Column(type="string")
     */
    protected $uno;
    /**
     * @Column(type="string")
     */
    protected $ocatd;

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setGninmb($gninmb)
    {
        $this->gninmb = $gninmb;
    }

    public function getGninmb()
    {
        return $this->gninmb;
    }

    public function setIndex($index)
    {
        $this->index = $index;
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setOcatd($ocatd)
    {
        $this->ocatd = $ocatd;
    }

    public function getOcatd()
    {
        return $this->ocatd;
    }

    public function setSocr($socr)
    {
        $this->socr = $socr;
    }

    public function getSocr()
    {
        return $this->socr;
    }

    public function setUno($uno)
    {
        $this->uno = $uno;
    }

    public function getUno()
    {
        return $this->uno;
    }
}