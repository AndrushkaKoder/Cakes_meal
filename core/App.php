<?php
// глобальный объект. Он же - точка входа де факто
use core\exceptions\DuplicateConfigurationParametersException; //используем простанство имен
use core\exceptions\NotConfiguredException;

final class App //final - класс от которого нельзя наследоваться
{

//    приватные свойства класса App
    private static array $properties = []; // приватное свойство 'СВОЙСТВА'

    private static string $configPath = 'config'; // путь до core/config

    public static string $webDirectory = 'web'; // путь относительно Корня до web.php


    private function __construct(){} // делаем приватный конструктор для того, чтобы нельзя было сделать new App

    public static function init() : void{ //метод точки входа. В нём вызываем подключение путей и динамическое подключение классов,
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

        self::run(); // запуск приложения

    }

    public static function run(){ // публичный статичный метод запуска приложения

        session_start();

        $route = \core\system\Router::createRoute(); //пришел массив с именем контоллера и аргументов строки запроса, если они есть

        $controller = self::getWebPath() . trim(self::WEB('controllersPath', 'userControllers'), '/') . '/' . $route['controller'];

        $controller = str_replace('/', '\\', $controller); // в $controller залетает "\web\user\controllers\indexController"
        
        


        try{
//            $rf = new \ReflectionFunction ('Vasya');
//            $rFile = $rf->getFileName();
//            $rLine = $rf->getStartLine();
//            $a = (new \ReflectionFunction('Vasya'))->getFileName();

            //  здесь ложится отражение метода request у класса $controller
            $object = new \ReflectionMethod($controller, 'request'); // Проверка есть ли метод request у $controller

            //по средствам метода ivoke отражение request вызывается у нового экземпляра класса $controller
            $object->invoke(new $controller, $route['parameters'] ?? []); //invoke - метод объекта Reflection, который вызвает метод, поданый в конструктор, создавая при этом новый экземпляр класса

        }catch (ReflectionException $e){

            exit($e->getMessage()); //

        }catch (\core\exceptions\RouteException $e){

            exit($e->getMessage());

        }

    }

    private static function setPathes() : void{ //подключение путей

        self::$properties['FULL_PATH'] = realpath(__DIR__ . '/../') . '/'; //формируем абсолютный путь и кладем его в $properties['FULL_PATH']

        if(!empty($_SERVER['DOCUMENT_ROOT']) && strpos(self::$properties['FULL_PATH'], $_SERVER['DOCUMENT_ROOT']) === 0){

            self::$properties['PATH'] = str_ireplace($_SERVER['DOCUMENT_ROOT'], '', self::$properties['FULL_PATH']); // если вызов сделан по средствам веб-сервера

        }else{

            self::$properties['PATH'] = '/'; //если вызов сделан из консоли

        }

        include_once realpath(__DIR__) . '/helpers/AppH.php'; // возвращает реальный путь к дериктории (абсолютный). Относительно папки core подтягиваем класс appH

    }

    private static function registerAutoload() : void{ //Автозагрузка классов

        spl_autoload_register(function($className){  // передаем колбэк

            $fileName = str_replace('\\', '/', $className); // меняем слеш с обратного на прямой

            if(is_readable(self::FULL_PATH() . $fileName . '.php')){ //если существует файл и он доступен для чтения

                include_once (self::FULL_PATH() . $fileName . '.php'); //инклюдим файл (класс) по полному пути

            }elseif (is_readable(self::FULL_PATH() . 'vendor/autoload.php')){ //если проект лежит в папке Vendor

                include_once self::FULL_PATH() . 'vendor/autoload.php'; //инклюдим файл (класс) из папки Vendor

            }

        });

    }

    private static function configApp() : void{

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

        static $path = ''; //сюда придет относительный путь 1 раз

        static $fullPath = ''; //сюда придет абсолютный путь 1 раз

        !$path && $path = (!empty(self::WEB('path')) ? rtrim(self::WEB('path')) : '') . '/' . self::$webDirectory . '/';

        !$fullPath && $fullPath = preg_replace('/\/{2,}/', '/', str_replace('\\', '/', self::FULL_PATH() . $path));

        return !$includeFullPath ? $path : $fullPath;

    }

    public function getTargetWebPath(){
        // написать метод
    }

    public static function __callStatic(string $name, array $arguments){

        if(!array_key_exists($name, self::$properties)){

            return null;

        }

        $data = self::$properties[$name];

        if(is_array($data)){

            foreach ($arguments as $value){

                $value = (array)$value;

                foreach ($value as $item){

                    if(!array_key_exists($item, $data)){

                        return $data;

                    }

                    $data = $data[$item];

                }

            }

        }

        return $data;

    }



}