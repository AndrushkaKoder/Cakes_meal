<?php

use core\exceptions\RouteException;

class AppH
{

    use \core\helpers\traites\ClearDataHelper;
    use \core\helpers\traites\RecursiveHelper;
    use \core\helpers\traites\ParentsChildrenHelper;
    use \core\helpers\traites\DateFormatHelper;
    use \core\helpers\traites\TextModify;


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

        $result = false;

        if(empty($_SERVER['HTTP_ACCEPT'])){

            $url = preg_split('/(\/)|(\?.*)/', $_SERVER['REQUEST_URI'], 0, PREG_SPLIT_NO_EMPTY);

            if(!preg_match('/\.[^\.]{2,6}$/', array_pop($url))){

                $result = true;

            }

        }elseif (preg_match('/^((text\/html)|(\W+$))/i', $_SERVER['HTTP_ACCEPT'])){

            $result = true;

        }

        return $result;

    }

    public static function redirect($http = false, $code = false){

        if($code){
            $codes = ['301' => 'HTTP/1.1 301 Move Permanently'];

            if($codes[$code]) header($codes[$code]);
        }

        if($http) $redirect = $http;
        else $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : \App::PATH();

        header("Location: $redirect");

        exit;

    }

}
