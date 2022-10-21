<?php

//вспомогательный класс

namespace core\helpers;

class ClearData
{
    public static function clearNum($num){ // обработка входящих чисел. Вернет или число в нужной форме, или 0

        return (isset($num) && $num && preg_match('/\d/', $num)) ? preg_replace('/[^\d.]/', '', str_replace(',', '.', $num)) * 1 : 0;

    }

}