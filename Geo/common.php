<?php

class ARRAY_CACHE
{
    protected $cache_dictionary = array();

    static protected $instance = null;

    private function __constructor()
    {
    }

    static public function getInstance()
    {
        if (self::$instance == null)
            self::$instance = new self();
        return self::$instance;
    }

    static public function add($ns, $key, $value)
    {
        self::getInstance()->_add($ns, $key, $value);
    }

    static public function get($ns, $key)
    {
        self::getInstance()->_add($ns, $key);
    }

    private function _add($ns, $key, $value)
    {
        $cache_namespace = $this->cache_dictionary[$ns];
        $cache_namespace[$key] = array(
            'create' => new DateTime('now'),
            'value' => $value
        );
    }

    private function _get($ns, $key)
    {
        $cache_namespace = $this->cache_dictionary[$ns];
        return $cache_namespace[$key];
    }

}


function kladr_param_filter($param)
{
    if (strpos($param, "--") !== false) {
        return substr($param, strpos($param, "--") + 2);
    }
    return $param;
}

function kladr_name_filter(\Entities\Kladr $kladrEntity, $shortFlag = false)
{
    $subjectType = $kladrEntity->getSocr();

//    ARRAY_CACHE::add("db", 'socrname', $subjectType);

    if ($shortFlag) {
        $repo = new \Entities\KladrRepository();
        $o = $repo->ConvertShortToLong($subjectType);
        if (null !== $o)
            $subjectType = $o->getSocrname();
    }

    switch ($kladrEntity->getSocr()) {
        case 'р-н':
        case 'обл':
            return $kladrEntity->getName() . ' ' . $subjectType;
        default:
            return $subjectType . ' ' . $kladrEntity->getName();
    }
}