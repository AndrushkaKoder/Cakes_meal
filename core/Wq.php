<?php
// глобальный объект. Он же - точка входа де факто

final class Wq //final - класс от которого нельзя наследоваться
{

//    приватные свойства класса Wq

    private static $model = null;

    private static string $configPath = 'config'; // путь до core/config

    private function __construct(){} // делаем приватный конструктор для того, чтобы нельзя было сделать new Wq

    public static function init(?bool $run = true) : void{ //метод точки входа. В нём вызываем подключение путей и динамическое подключение классов,
        //и метод конфигурации приклады и вызываем метод run

        self::setPathes(); // подключение путей appH

        self::registerAutoload(); // динамическое подключение классов, всех классов

        self::configApp(); // метод конфигурации приложения

        session_start();

        $run && self::run(); // запуск приложения

    }

    public static function run() : void{ // публичный статичный метод запуска приложения

        self::execute(\webQSystem\Router::setRoute()); //пришел массив с именем контоллера и аргументов строки запроса, если они есть

    }

    public static function execute(?array $route, $arguments = []){

        $route['controller'] = $route['controller'] ?? \webQSystem\Router::getController();

        $route['parameters'] = $route['parameters'] ?? \webQSystem\Router::getParameters();

        $controller = str_replace('/', '\\', $route['controller']); // в $controller залетает "\webQApplication\controllers\indexController"

        try{

            try{

                //  здесь ложится отражение метода request у класса $controller
                $object = new \ReflectionMethod($controller, 'request'); // Проверка есть ли метод request у $controller

                //по средствам метода ivoke отражение request вызывается у нового экземпляра класса $controller
                return $object->invoke(new $controller, $route['parameters'] ?? [], $arguments); //invoke - метод объекта Reflection, который вызвает метод, поданый в конструктор, создавая при этом новый экземпляр класса

            }catch (ReflectionException $e){

                throw new \webQExceptions\RouteException($e->getMessage() . "\r\n" . $_SERVER['REQUEST_URI']);

            }

        }catch (\webQExceptions\RouteException $e){

            exit($e->showMessage());

        }catch (\webQExceptions\DbException $e){

            exit($e->showMessage());

        }


    }

    private static function setPathes() : void{ //подключение путей

        self::config()->set('FULL_PATH', preg_replace('/\/{2,}/', '/', preg_replace('/\\\+/', '/', realpath(__DIR__ . '/../'))) . '/'); //формируем абсолютный путь и кладем его в $properties['FULL_PATH']

        self::config()->set('PATH', self::getRelativePath(self::config()->FULL_PATH()));

    }

    private static function registerAutoload() : void{ //Автозагрузка классов

        spl_autoload_register(function($className){  // передаем колбэк

            $fileName = self::convertNamespace($className); // меняем слеш с обратного на прямой

            if(is_readable(self::FULL_PATH() . $fileName . '.php')){ //если существует файл и он доступен для чтения

                include_once (self::FULL_PATH() . $fileName . '.php'); //инклюдим файл (класс) по полному пути

            }

        });

        if (is_readable(self::FULL_PATH() . 'vendor/autoload.php')){ //если проект лежит в папке Vendor

            include_once self::FULL_PATH() . 'vendor/autoload.php'; //инклюдим файл (класс) из папки Vendor

        }

    }

    private static function configApp() : void{ //парсинг папки config

        $path = realpath(__DIR__) . '/' . self::$configPath; //получаем папку с конфигурацией

        $fileProperties = [];

        self::scanDir($path, function ($file, $path) use (&$fileProperties){

            $configArr = require $path . '/' . $file;

            if(is_array($configArr)){

                foreach ($configArr as $key => $item){

                    $fileProperties[$key] = $file;

                    if(!self::config()->set($key, $item)){

                        exit('Parameter ' . $key . ' was already declared ' . ($fileProperties[$key]) ?? null);

                    }

                }

            }

        });

        if(empty($fileProperties)){

            exit('Application hasn`t configuration data');

        }

        include_once realpath(__DIR__) . '/helpers/WqH.php'; // возвращает реальный путь к дериктории (абсолютный). Относительно папки core подтягиваем класс appH

    }

    public static function getRelativePath(string $directory, ?string $documentRoot = null){

        $currentPath = '';

        !$documentRoot && $documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? null;

        if(!empty($documentRoot) && preg_match('/^[^\/]*\//', $documentRoot)){

            $documentRootArr = preg_split('/\/+/', $documentRoot, 0, PREG_SPLIT_NO_EMPTY);

            $currentPath = preg_replace('/\\\+/', '/', $directory);

            foreach ($documentRootArr as $key => $item){

                $mold = implode('/', $documentRootArr);

                if(stripos($currentPath, $mold) !== false){

                    $currentPath = str_ireplace($mold, '' , $currentPath);

                    break;

                }

                unset($documentRootArr[$key]);

            }

        }

        return preg_replace('/\/{2,}/', '/', '/' . $currentPath . '/');

    }

    public static function PATH(){

        return self::config()->PATH() ?? null;

    }

    public static function FULL_PATH(){

        return self::config()->FULL_PATH() ?? null;

    }

    public function set(string $name, $value) : bool{

        if(array_key_exists($name, $this->properties) && !$this->force){

            return false;

        }

        if(!empty($this->properties[$name]) && is_array($value) && is_array($this->properties[$name])){

            foreach ($value as $key => $item){

                $this->properties[$name][$key] = $item;

            }

        }else{

            $this->properties[mb_strtoupper($name)] = $value;

        }

        $this->force = false;

        return true;

    }

    public static function setModel($model){

        self::$model = $model;

    }

    public static function model(){

        return self::$model;

    }

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

    private static function convertNamespace($className){

        static $nameSpaces = null;

        $className = trim(preg_replace('/\\\+/', '/', $className));

        if(!$nameSpaces){

            $nameSpaces = self::config()->force()->WEB('namespaces');

            uksort($nameSpaces, function ($a, $b){

                $lenthA = strlen($a);

                $lenthB = strlen($b);

                return $lenthA === $lenthB ? 0 : ($lenthA < $lenthB ? 1 : -1);

            });

            foreach ($nameSpaces as $key => $item){

                unset($nameSpaces[$key]);

                $nameSpaces[preg_replace('/\\\+/', '/', $key)] = $item;

            }

        }

        if(strpos('webQModels', $className) !== false){

            $a = 1;

        }

        if(!empty($nameSpaces)){

            foreach ($nameSpaces as $key => $item){

                if(preg_match('/^' . str_replace('/', '\/', preg_quote($key)) . '(\/|([A-Z][^\/]*))/', $className, $matches)){

                    if(!empty($matches[2])){

                        $item .= '/' . strtolower(preg_replace('/([^A-Z])([A-Z])/', '$1/$2', $matches[2]));

                        $key .= $matches[2];

                    }

                    $className = preg_replace('/^' . str_replace('/', '\/', preg_quote($key)) . '/', $item, $className);

                    break;

                }

            }

        }

        return $className;

    }

}