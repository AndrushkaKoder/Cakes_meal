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

        if(self::$_instance instanceof self){
            return self::$_instance;
        }

        self::$_instance = new self;

        if(method_exists(self::$_instance, 'connect')){
            self::$_instance->connect();
        }

        return self::$_instance;
    }

}