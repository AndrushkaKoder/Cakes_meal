<?php

namespace webQSystem;

use webQExceptions\RouteException;
use webQAdminSettings\Settings;

class Router
{

    protected static string $controller;

    protected static ?string $method = null;

    protected static ?string $outputMethod = null;

    protected static string $mode = 'admin';

    protected static array $parameters = [];

    public static function setRoute() : array{

        if(Settings::get('executeShellScripts')){

            try{

                (new \libraries\commandExecutor\CommandExecutor())->execute();

            }catch (\Exception $e){


            }

        }

        $route = self::createRoute();

        if(\WqH::isAjax()){

            exit(Ajax::route());

        }

        return $route;

    }

    public static function createRoute(){

        $adress_str = $_SERVER['REQUEST_URI'];

        $url = preg_split('/(\/)|(\?.*)/', $adress_str, 0, PREG_SPLIT_NO_EMPTY);

        if((!empty($url[0]) && $url[0] === \Wq::config()->WEB('admin', 'alias')) ||
            (!empty($_SERVER['HTTP_REFERER']) && \WqH::isAjax()) && preg_match('/\/' . preg_quote(\Wq::config()->WEB('admin', 'alias')) . '\//', $_SERVER['HTTP_REFERER'])){

            array_shift($url);

        }else{

            self::$mode = 'user';

            if(!\WqH::isPost() && \WqH::isHtmlRequest()){

                $pattern = '';

                $replacement = '';

                if(\Wq::config()->WEB('end_slash')){

                    if(!preg_match('/\/(\?|$)/', $adress_str)){

                        $pattern = '/(^.*?)(\?.*)?$/';

                        $replacement = '$1/';

                    }

                }else{

                    if(preg_match('/\/(\?|$)/', $adress_str)){

                        $pattern = '/(^.*?)\/(\?.*)?$/';

                        $replacement = '$1';

                    }

                }

                if($pattern){

                    $adress_str = preg_replace($pattern, $replacement, $adress_str);

                    if(!empty($_SERVER['QUERY_STRING'])){

                        $adress_str .= '?' . $_SERVER['QUERY_STRING'];

                    }

                    \WqH::redirect($adress_str, 301);

                }

            }

        }

        $hrUrl = \Wq::config()->WEB('hrUrl');

        self::$controller = preg_replace('/\/+/', '\\', \Wq::config()->WEB('namespace'));

        self::setData($url);

        self::setParameters($url, $hrUrl);

        return ['controller' => self::$controller, 'parameters' => self::$parameters];

    }

    protected static function setParameters($urlArr, $hrUrl){

        if(!empty($urlArr[1])){

            $count = count($urlArr);

            $key = '';

            if(!$hrUrl){

                $i = 1;

            }else{

                self::$parameters['alias'] = \WqH::clearStr($urlArr[1]);

                $i = 2;

            }

            for( ; $i < $count; $i++){

                if(!$key){

                    $key = $urlArr[$i];

                    self::$parameters[$key] = '';

                }else{

                    self::$parameters[$key] = $urlArr[$i];

                    $key = '';

                }
            }

        }

    }

    protected static function setData(&$arr){

        $controllerName = \Wq::config()->WEB('default', self::$mode, 'controller');

        if(!empty($arr[0])){

            if(\Wq::config()->WEB('user', 'landingMode') && self::$mode !== 'admin'){

                self::$controller .= \Wq::config()->WEB('default', 'user', 'controller');

                $newArr = [];

                $i = 0;

                foreach ($arr as $item){

                    $newArr[++$i] = $item;

                }

                $arr = $newArr;

            }else{

                if(!empty(\Wq::config()->ROUTES(self::$mode)[$arr[0]]) || !empty(\Wq::config()->ROUTES($arr[0]))){

                    $targetPath = \Wq::config()->ROUTES(self::$mode)[$arr[0]] || \Wq::config()->ROUTES($arr[0]);

                    $route = explode('/', $targetPath);

                    $controllerName = $route[0];

                    self::$method = !empty($route[1]) ? $route[1] : null;

                    self::$outputMethod = !empty($route[2]) ? $route[2] : null;

                }else{

                    $controllerName = $arr[0];

                }

            }


        }

        self::$controller = \WqH::setClassPath(self::$controller, preg_replace('/[-_]+/', '', ucwords($controllerName, '-_')) . 'Controller');

    }

    public static function getInputMethod(){

        return self::$method ?: \Wq::config()->WEB('default', self::$mode, 'method');

    }

    public static function getOutputMethod(){

        return self::$outputMethod ?: \Wq::config()->WEB('default', self::$mode, 'outputMethod');

    }

    public static function getMode(){

        return self::$mode;

    }

    public static function getController(){

        return self::$controller;

    }

    public static function getParameters(){

        return self::$parameters;

    }

}