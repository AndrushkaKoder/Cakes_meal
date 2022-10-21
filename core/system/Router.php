<<<<<<< HEAD
<?php

namespace core\system;

class Router
{

    public static function createRoute() : array{

        $url = preg_split('/(\/)|(\?.*)/', $_SERVER['REQUEST_URI'], 0, PREG_SPLIT_NO_EMPTY);

        $controller = !empty($url[0]) ? array_shift($url) : \App::WEB('default', 'user', 'controller');

        $controller = preg_replace('/[-_]+/', '', ucwords($controller, '-_')) . 'Controller';
        // если будет вызов custom-site, имя контроллера сформируется как CustomSiteController
        $parameters = $url;

        return compact('controller', 'parameters');

    }

=======
<?php

namespace core\system;

class Router
{

    public static function createRoute() : array{

        $url = preg_split('/(\/)|(\?.*)/', $_SERVER['REQUEST_URI'], 0, PREG_SPLIT_NO_EMPTY); //В $url попадает url страницы после cakes-meal.loc/

        $controller = !empty($url[0]) ? array_shift($url) : \App::WEB('default', 'user', 'controller'); //В $controller попадет либо первый элемент $url, либо defaul из конфигурации

        $controller = preg_replace('/[-_]+/', '', ucwords($controller, '-_')) . 'Controller';
        // если будет вызов custom-site, имя контроллера сформируется как CustomSiteController
        $parameters = $url;

        return compact('controller', 'parameters');

    }

>>>>>>> 2e2162608b52d77abe9c5daf01b432e99b9bf943
}