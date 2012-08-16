<?php

class String
{
    static function strtolower_utf8($text)
    {
        return mb_convert_case($text, MB_CASE_LOWER, "UTF-8");
    }

    static function strtoupper_utf8($text)
    {
        return mb_convert_case($text, MB_CASE_UPPER, "UTF-8");
    }
}
