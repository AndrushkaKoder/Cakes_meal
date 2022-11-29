<?php
// глобальный объект. Он же - точка входа де факто
use core\exceptions\DuplicateConfigurationParametersException; //используем простанство имен
use core\exceptions\NotConfiguredException;

final class App //final - класс от которого нельзя наследоваться
{

//    приватные свойства класса App

    private static $model = null;

    private static array $properties = []; // приватное свойство 'СВОЙСТВА'

    private static string $configPath = 'config'; // путь до core/config

    public static string $webDirectory = 'web'; // путь относительно Корня до web.php

    private function __construct(){} // делаем приватный конструктор для того, чтобы нельзя было сделать new App

    public static function init($run = true) : void{ //метод точки входа. В нём вызываем подключение путей и динамическое подключение классов,
        //и метод конфигурации приклады и вызываем метод run

        self::setPathes(); // подключение путей appH

        self::registerAutoload(); // динамическое подключение классов, всех классов

        try {   //по блоку try/catch конфигурируем приложение. Если try, то вызываем конфигурацию, если нет, идём по блоку catch

            self::configApp(); // метод конфигурации приложения

        }catch (DuplicateConfigurationParametersException $e){ // вызываем метод, который наследуется из страшного класса Exeption

            exit($e->getMessage()); // конец скрипта с генерацией сообщения об ошибке

        }catch (NotConfiguredException $e){ // вызываем метод, который наследуется из страшного класса Exeption

            exit($e->getMessage()); // конец скрипта с генерацией сообщения об ошибке

        }

        session_start();

        $run && self::run(); // запуск приложения

    }

    public static function run() : void{ // публичный статичный метод запуска приложения

        self::execute(\core\system\Router::setRoute()); //пришел массив с именем контоллера и аргументов строки запроса, если они есть

    }

    public static function execute(?array $route, $arguments = []){

        $route['controller'] = $route['controller'] ?? \core\system\Router::getController();

        $route['parameters'] = $route['parameters'] ?? \core\system\Router::getParameters();

        $controller = str_replace('/', '\\', $route['controller']); // в $controller залетает "\web\user\controllers\indexController"

        try{

            try{

                //  здесь ложится отражение метода request у класса $controller
                $object = new \ReflectionMethod($controller, 'request'); // Проверка есть ли метод request у $controller

                //по средствам метода ivoke отражение request вызывается у нового экземпляра класса $controller
                return $object->invoke(new $controller, $route['parameters'] ?? [], $arguments); //invoke - метод объекта Reflection, который вызвает метод, поданый в конструктор, создавая при этом новый экземпляр класса

            }catch (ReflectionException $e){

                throw new \core\exceptions\RouteException($e->getMessage() . "\r\n" . $_SERVER['REQUEST_URI']);

            }

        }catch (\core\exceptions\RouteException $e){

            exit($e->showMessage());

        }


    }

    private static function setPathes() : void{ //подключение путей

        self::$properties['FULL_PATH'] = str_replace('\\', '/', realpath(__DIR__ . '/../') . '/'); //формируем абсолютный путь и кладем его в $properties['FULL_PATH']

        if(!empty($_SERVER['DOCUMENT_ROOT']) && strpos(self::$properties['FULL_PATH'], $_SERVER['DOCUMENT_ROOT']) === 0){

            self::$properties['PATH'] = str_ireplace($_SERVER['DOCUMENT_ROOT'], '', self::$properties['FULL_PATH']); // если вызов сделан по средствам веб-сервера

        }else{

            self::$properties['PATH'] = '/'; //если вызов сделан из консоли

        }

    }

    private static function registerAutoload() : void{ //Автозагрузка классов

        spl_autoload_register(function($className){  // передаем колбэк

            $fileName = str_replace('\\', '/', $className); // меняем слеш с обратного на прямой

            if(is_readable(self::FULL_PATH() . $fileName . '.php')){ //если существует файл и он доступен для чтения

                include_once (self::FULL_PATH() . $fileName . '.php'); //инклюдим файл (класс) по полному пути

            }

        });

        include_once realpath(__DIR__) . '/helpers/AppH.php'; // возвращает реальный путь к дериктории (абсолютный). Относительно папки core подтягиваем класс appH

        if (is_readable(self::FULL_PATH() . 'vendor/autoload.php')){ //если проект лежит в папке Vendor

            include_once self::FULL_PATH() . 'vendor/autoload.php'; //инклюдим файл (класс) из папки Vendor

        }

    }

    private static function configApp() : void{ //парсинг папки config

        $path = realpath(__DIR__) . '/' . self::$configPath; //получаем папку с конфигурацией

        $fileProperties = [];

        AppH::scanDir($path, function ($file, $path) use (&$fileProperties){

            $configArr = require $path . '/' . $file;

            if(is_array($configArr)){

                foreach ($configArr as $key => $item){

                    if(array_key_exists(strtoupper($key), self::$properties)){

                        throw new DuplicateConfigurationParametersException('Parameter ' . $key . ' was already declared ' . ($fileProperties[$key]) ?? null);

                    }

                    $fileProperties[$key] = $file;

                    self::$properties[strtoupper($key)] = $item;

                }

            }

        });

        if(empty($fileProperties)){

            throw new NotConfiguredException('Application hasn`t configuration data');

        }

    }

    public static function getWebPath($includeFullPath = false){ // возвращает пути. App::PATH() || App::FULL_PATH()

        static $path = ''; //сюда придет путь относительно домена 1 раз

        static $fullPath = ''; //сюда придёт абсолютный путь относительно корня ОС 1 раз

        !$path && $path = (!empty(self::config()->WEB('path')) ? rtrim(self::config()->WEB('path')) : '') . '/' . self::$webDirectory . '/';

        !$fullPath && $fullPath = preg_replace('/\/{2,}/', '/', str_replace('\\', '/', self::FULL_PATH() . $path));

        return !$includeFullPath ? $path : $fullPath;

    }

    public function getTargetWebPath(){
        // написать метод
    }

    public static function PATH(){

        return self::$properties['PATH'] ?? null;

    }

    public static function FULL_PATH(){

        return self::$properties['FULL_PATH'] ?? null;

    }

    public static function config(){

        static $config;

        if(!$config){

            $config = new class(self::$properties){

                private array $properties;

                public function __construct($properties){

                    $this->properties = &$properties;

                }

                public function WEB(){

                    $mode = \core\system\Router::getMode();

                    $args = func_get_args();

                    if(($key = array_search($mode, $args)) !== false){

                        unset($args[$key]);

                        $args = array_values($args);

                    }

                    $mode = strtoupper($mode);

                    if(array_key_exists($mode, $this->properties)){

                        $res = $this->$mode(...$args);

                        if($res){

                            return $res;

                        }

                    }

                    return $this->searchProperty('WEB', func_get_args());

                }

                public function __call($name, $arguments){

                    return $this->searchProperty($name, $arguments);

                }

                private function searchProperty($name, $arguments){

                    if(!array_key_exists($name, $this->properties)){

                        return null;

                    }

                    $data = $this->properties[$name];

                    if(is_array($data)){

                        foreach ($arguments as $value){

                            $value = (array)$value;

                            foreach ($value as $item){

                                if(!array_key_exists($item, $data)){

                                    return null;

                                }

                                $data = $data[$item];

                            }

                        }

                    }

                    return $data;

                }

            };

        }

        return $config;

    }

    public static function setModel($model){

        self::$model = $model;

    }

    public static function model(){

        return self::$model;

    }

}