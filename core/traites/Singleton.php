<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 07.02.2019
 * Time: 15:23
 */

namespace core\traites;


trait Singleton
{

    static private $_instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    static public function instance(){

        if(static::$_instance instanceof static){
            return static::$_instance;
        }

        static::$_instance = new static;

        if(method_exists(self::$_instance, 'connect')){

            static::$_instance->connect();

            \App::setModel(static::$_instance);

        }

        return static::$_instance;
    }

}