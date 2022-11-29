<?php

namespace core\system;

use core\exceptions\RouteException;
use settings\Settings;

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

        if(\AppH::isAjax()){

            exit(Ajax::route());

        }

        return $route;

    }

    public static function createRoute(){

        $adress_str = $_SERVER['REQUEST_URI'];

        $path = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'index.php'));

        if($path === \App::PATH()){

            $url = preg_split('/(\/)|(\?.*)/', $adress_str, 0, PREG_SPLIT_NO_EMPTY);

            if(!empty($url[0]) && $url[0] === \App::config()->WEB('admin', 'alias')){

                array_shift($url);

                self::$controller = \App::config()->WEB('controllersPath', 'admin');

                $hrUrl = \App::config()->WEB('admin', 'hrUrls');

            }else{

                self::$mode = 'user';

                if(!\AppH::isPost() && \AppH::isHtmlRequest()){

                    $pattern = '';

                    $replacement = '';

                    if(\App::config()->WEB('end_slash')){

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

                        \AppH::redirect($adress_str, 301);

                    }

                }

                $hrUrl = \App::config()->WEB('user', 'hrUrl');

                self::$controller = \App::config()->WEB('controllersPath', 'user');

            }

            self::setData($url);

            self::setParameters($url, $hrUrl);

            return ['controller' => self::$controller, 'parameters' => self::$parameters];

        }else{

            throw new RouteException('Не корректная директория сайта', 1);

        }

    }

    protected static function setParameters($urlArr, $hrUrl){

        if(!empty($urlArr[1])){

            $count = count($urlArr);

            $key = '';

            if(!$hrUrl){

                $i = 1;

            }else{

                self::$parameters['alias'] = \AppH::clearStr($urlArr[1]);

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

        $controllerName = \App::config()->WEB('default', self::$mode, 'controller');

        if(!empty($arr[0])){

            if(\App::config()->WEB('user', 'landingMode') && self::$mode !== 'admin'){

                self::$controller .= \App::config()->WEB('default', 'user', 'controller');

                $newArr = [];

                $i = 0;

                foreach ($arr as $item){

                    $newArr[++$i] = $item;

                }

                $arr = $newArr;

            }else{

                if(!empty(\App::config()->ROUTES(self::$mode)[$arr[0]]) || !empty(\App::config()->ROUTES($arr[0]))){

                    $targetPath = \App::config()->ROUTES(self::$mode)[$arr[0]] || \App::config()->ROUTES($arr[0]);

                    $route = explode('/', $targetPath);

                    $controllerName = $route[0];

                    self::$method = !empty($route[1]) ? $route[1] : null;

                    self::$outputMethod = !empty($route[2]) ? $route[2] : null;

                }else{

                    $controllerName = $arr[0];

                }

            }


        }

        self::$controller = \AppH::setClassPath(self::$controller, preg_replace('/[-_]+/', '', ucwords($controllerName, '-_')) . 'Controller');

    }

    public static function getInputMethod(){

        return self::$method ?: \App::config()->WEB('default', self::$mode, 'method');

    }

    public static function getOutputMethod(){

        return self::$outputMethod ?: \App::config()->WEB('default', self::$mode, 'outputMethod');

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