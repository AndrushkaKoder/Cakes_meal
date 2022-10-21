<?php

class ClearStr1
{
    public static function clearStr(string $str, $ecran = true) :string{ //обработка строк и защита от инъекций

        return $ecran ? str_replace(array("\\","\0","\n","\r","\x1a","'",'"'),array("\\\\","\\0","\\n","\\r","\Z","\'",'\"'), trim(strip_tags($str))) : trim(strip_tags($str));

    }

}