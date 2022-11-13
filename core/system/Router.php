<?php

namespace core\system;

use core\exceptions\RouteException;
use settings\Settings;

class Router
{

    protected static string $controller;

    protected static ?string $method = null;

    protected static string $mode;

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

            if(!empty($url[0]) && $url[0] === \App::WEB('admin', 'alias')){

                array_shift($url);

                self::$controller = \App::WEB('controllersPath', 'admin');

                $hrUrl = \App::WEB('admin', 'hrUrls');

                self::$mode = 'admin';


            }else{

                if(!\AppH::isPost()){

                    $pattern = '';

                    $replacement = '';

                    if(\App::WEB('end_slash')){

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

                $hrUrl = \App::WEB('user', 'hrUrl');

                self::$controller = \App::WEB('controllersPath', 'user');

                self::$mode = 'user';

            }

            self::setData($url);

            self::setParameters($url, $hrUrl);

            return ['controller' => self::$controller, 'parameters' => self::$parameters];

        }else{

            throw new RouteException('Не корректная директория сайта', 1);

        }

    }

    protected static function setParameters($urlArr, $hrUrl){

        if(!empty($url[1])){

            $count = count($url);

            $key = '';

            if(!$hrUrl){

                $i = 1;

            }else{

                self::$parameters['alias'] = \AppH::clearStr($url[1]);

                $i = 2;

            }

            for( ; $i < $count; $i++){

                if(!$key){

                    $key = $url[$i];

                    self::$parameters[$key] = '';

                }else{

                    self::$parameters[$key] = $url[$i];

                    $key = '';

                }
            }

        }

    }

    protected static function setData(&$arr){

        $controllerName = \App::WEB('default', self::$mode, 'controller');

        if(!empty($arr[0])){

            if(\App::WEB('user', 'landingMode') && self::$mode !== 'admin'){

                self::$controller .= \App::WEB('default', 'user', 'controller');

                $newArr = [];

                $i = 0;

                foreach ($arr as $item){

                    $newArr[++$i] = $item;

                }

                $arr = $newArr;

            }else{

                if(!empty(\App::ROUTES(self::$mode)[$arr[0]]) || !empty(\App::ROUTES($arr[0]))){

                    $targetPath = \App::ROUTES(self::$mode)[$arr[0]] || \App::ROUTES($arr[0]);

                    $route = explode('/', $targetPath);

                    $controllerName = $route[0];

                    self::$method = !empty($route[1]) ? $route[1] : null;

                }else{

                    $controllerName = $arr[0];

                }

            }


        }

        self::$controller = \AppH::setClassPath(self::$controller, preg_replace('/[-_]+/', '', ucwords($controllerName, '-_')) . 'Controller');

    }

    public static function getInputMethod(){

        return self::$method ?: \App::getWebConfig('default', self::$mode, 'method');

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