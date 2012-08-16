<?php


namespace Entities;

/**
 * @Entity
 * @Table(name="kladr")
 */
class Kladr
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
     * @Column(type="string", nullable=true)
     */
    protected $zip;
    /**
     * @Column(type="string", nullable=true)
     */
    protected $code_gninmb;
    /**
     * @Column(type="string")
     */
    protected $uno;
    /**
     * @Column(type="string")
     */
    protected $ocatd;
    /**
     * @Column(type="string")
     */
    protected $status;

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode($codeName = null, $flag = false)
    {
        if (null == $codeName)
            return $this->code;

        $codeArr = array(
            'REGIONCODE' => substr($this->code, 0, 2), // Регион
            'AREACODE' => substr($this->code, 2, 3), // Район
            'CITYCODE' => substr($this->code, 5, 3), // Город
            'PLACECODE' => substr($this->code, 8, 3), // Код населенного пункта
            'ATTRIBUTE' => substr($this->code, 11, 2), // Признак актуальности
        );
        if (!$flag)
            return $codeArr[$codeName];

        $output = "";
        foreach ($codeArr as $cn => $cv) {
            if ($codeName == $cn) {
                return $output .= $cv;
            }
            $output .= $cv;
        }

    }

    public function setCodeGninmb($code_gninmb)
    {
        $this->code_gninmb = $code_gninmb;
    }

    public function getCodeGninmb()
    {
        return $this->code_gninmb;
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

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setUno($uno)
    {
        $this->uno = $uno;
    }

    public function getUno()
    {
        return $this->uno;
    }

    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    public function getZip()
    {
        return $this->zip;
    }
}