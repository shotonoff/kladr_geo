<?php

class Autoloader {

    static public function RegisterAutoloader() {
        spl_autoload_register(array(__CLASS__, "IncludeClass"));
    }

    static public function IncludeClass($class) {

        $pathArr = explode(PATH_SEPARATOR, get_include_path());
        $class = str_replace("\\", "/", $class);
                
        foreach ($pathArr as $path) {
            $includeFile = $path . DIRECTORY_SEPARATOR . $class . ".php";

            if (file_exists($includeFile)) {
                include $includeFile;
                break;
            }
        }
    }

}

