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

}