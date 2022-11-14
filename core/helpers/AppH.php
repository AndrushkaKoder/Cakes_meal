<?php

use core\exceptions\RouteException;

class AppH
{

    use \core\helpers\traites\ClearDataHelper;
    use \core\helpers\traites\RecursiveHelper;
    use \core\helpers\traites\ParentsChildrenHelper;
    use \core\helpers\traites\DateFormatHelper;


    public static function scanDir(string $path, callable $callback, $sort = false){

        if(file_exists($path)){

            $list = scandir($path); //сюда возвращается список директорий и файлов

            if($sort){

                sort($list);

            }

            foreach ($list as $file){

                if($file !== '.' && $file !== '..'){

                   if(($res = $callback($file, $path)) !== null){

                       return $res;

                   }  // если у файла нет . .. то вызывваем колбэк

                }

            }

        }

    }

    public static function translit($str){ //метод транслитерации

        $translitArr = [ 'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ъ' => 'y', 'ы' => 'y',
            'ь' => 'y', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', ' ' => '-',
        ];
        $lowelLetter = ['а', 'е', 'и', 'о', 'у', 'э'];

        $str = mb_strtolower($str);
        $temp_arr = [];

        for($i = 0; $i < mb_strlen($str); $i++){
            $temp_arr[] = mb_substr($str, $i, 1);
        }

        $link = '';

        if($temp_arr){
            foreach ($temp_arr as $key => $char){

                if(array_key_exists($char, $translitArr)){

                    switch($char){

                        case 'ъ':
                            if(!empty($temp_arr[$key + 1]) && $temp_arr[$key + 1] == 'е') $link .= 'y';
                            break;

                        case 'ы':
                            if(!empty($temp_arr[$key + 1]) && $temp_arr[$key + 1] == 'й') $link .= 'i';
                            else $link .= $translitArr[$char];
                            break;

                        case 'ь':
                            if(!empty($temp_arr[$key + 1]) && $temp_arr[$key + 1] !== count($temp_arr) && in_array($temp_arr[$key + 1], $lowelLetter)){
                                $link .= $translitArr[$char];
                            }
                            break;

                        default:
                            $link .= $translitArr[$char];
                            break;

                    }

                }else{

                    $link .= $char;

                }

            }

        }

        if($link){

            $link = preg_replace('/[^a-z0-9_-]/iu', '', $link);
            $link = preg_replace('/-{2,}/iu', '-', $link);
            $link = preg_replace('/_{2,}/iu', '', $link);
            $link = preg_replace('/(^[-_]+)|([-_]+$)/iu', '', $link);

        }

        return $link;

    }

    public static function setClassPath($path, $controllerName, $asNamespace = true){

        $controller = $path . '/' . $controllerName;

        $controller = preg_replace('/\/{2,}/', '/', $controller);

        if($asNamespace){

            $controller = str_replace('/', '\\', $controller);

        }

        return $controller;

    }

    public static function getRelativePath($directory){

        return  '/' . str_replace(\App::FULL_PATH(), '', str_replace('\\', '/', $directory));

    }


    public static function isPost(){

        return $_SERVER['REQUEST_METHOD'] === 'POST';

    }

    public static function isAjax(){

        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

    }

    public static function isHtmlRequest(){

        return empty($_SERVER['HTTP_ACCEPT']) || preg_match('/^((text\/html)|(\W+$))/i', $_SERVER['HTTP_ACCEPT']);

    }

    public static function redirect($http = false, $code = false){

        if($code){
            $codes = ['301' => 'HTTP/1.1 301 Move Permanently'];

            if($codes[$code]) header($codes[$code]);
        }

        if($http) $redirect = $http;
        else $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : PATH;

        header("Location: $redirect");

        exit;

    }

}
