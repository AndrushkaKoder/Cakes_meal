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

    public static function singleSlashes(){

        $str = '';

        foreach (func_get_args() as $item){

            $str .= $item . '/';

        }

        return preg_replace('/\/{2,}/', '/', $str);

    }

    public static function singleSlashesLtrim(){

        return ltrim(self::singleSlashes(...func_get_args()), '/');

    }

    public static function singleSlashesRtrim(){

        return rtrim(self::singleSlashes(...func_get_args()), '/');

    }

    public static function singleSlashesTrim(){

        return trim(self::singleSlashes(...func_get_args()), '/');

    }

    public static function withSlashes(){

        return '/' . trim(self::singleSlashes(...func_get_args()), '/') . '/';

    }

}