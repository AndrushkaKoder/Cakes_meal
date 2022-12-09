<?php

class WqH
{

    use webQHelpers\traits\ClearDataHelper;
    use webQHelpers\traits\RecursiveHelper;
    use webQHelpers\traits\ParentsChildrenHelper;
    use webQHelpers\traits\DateFormatHelper;
    use webQHelpers\traits\TextModify;


    public static function scanDir(string $path, callable $callback, $sort = false){

        return \Wq::scanDir($path, $callback, $sort);

    }

    public static function setClassPath($path, $controllerName, $asNamespace = true){

        $controller = $path . '/' . $controllerName;

        $controller = preg_replace('/\/{2,}/', '/', $controller);

        if($asNamespace){

            $controller = str_replace('/', '\\', $controller);

        }

        return $controller;

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
        else $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : \Wq::PATH();

        header("Location: $redirect");

        exit;

    }

    public static function set404(){

        header("HTTP/1.1 404 Not Found", true, 404);
        header ('Status: 404 Not Found');

    }

}
