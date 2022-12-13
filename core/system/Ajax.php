<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 08.09.2019
 * Time: 15:23
 */

namespace webQSystem;

use webQModels\Crypt;

class Ajax
{

    public static $data;

    public static function route(){

        $controller = \WqH::setClassPath(\Wq::config()->WEB('user', 'namespace'), 'AjaxController');

        self::$data = \WqH::isPost() ? $_POST : $_GET;

        if(!empty(self::$data['ajax']) && self::$data['ajax'] === 'token'){

            return self::generateToken();

        }

        $requestScheme = $_SERVER['REQUEST_SCHEME'] ?? '';

        if(!$requestScheme){

            if(preg_match('/^https?/', $_SERVER['HTTP_REFERER'], $matches)){

                $requestScheme = $matches[0];

            }else{

                $requestScheme = 'http';

            }

        }

        $httpReferer = str_replace('/', '\/', $requestScheme . 's?://' . (preg_quote($_SERVER['SERVER_NAME'] . \Wq::PATH() . \Wq::config()->WEB('admin', 'alias'))));

        if(isset(self::$data['ADMIN_MODE']) || preg_match('/^' . $httpReferer . '(\/?|$)/', $_SERVER['HTTP_REFERER'])){

            unset(self::$data['ADMIN_MODE']);

            $controller = \WqH::setClassPath(\Wq::config()->WEB('admin', 'namespace'), 'AjaxController');

        }

        $res = \Wq::execute(['controller' => $controller], true);

        if(is_array($res) || is_object($res)) $res = json_encode($res, JSON_UNESCAPED_UNICODE);
        elseif (is_int($res)) $res = (float)$res;

        return $res;

    }

    private static function generateToken(){

        return $_SESSION['token'] = Crypt::pwd(mt_rand(0, 999999) . microtime());

    }


}