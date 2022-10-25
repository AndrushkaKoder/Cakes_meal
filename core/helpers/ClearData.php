<?php

//вспомогательный класс

namespace core\helpers;

class ClearData
{
    public static function clearNum($num){ // обработка входящих чисел. Вернет или число в нужной форме, или 0

        return (isset($num) && $num && preg_match('/\d/', $num)) ? preg_replace('/[^\d.]/', '', str_replace(',', '.', $num)) * 1 : 0;

    }

    public static function clearStr(string $str, $ecran = true) :string{ //обработка строк и защита от инъекций

        return $ecran ? str_replace(array("\\","\0","\n","\r","\x1a","'",'"'),array("\\\\","\\0","\\n","\\r","\Z","\'",'\"'), trim(strip_tags($str))) : trim(strip_tags($str));

    }

}