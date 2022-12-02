<?php


namespace core\admin\expansion;


use core\base\controller\BaseMethods;
use core\base\controller\Singleton;

class Expansion
{

    use BaseMethods;

    protected $className;

    /**
     * @param array $args
     * @param bool $obj
     * @throws \ReflectionException
     */

    public function expansion($args = [], $obj = false){

        $this->className = explode('Controller', (new \ReflectionClass($obj))->getShortName())[0];

    }

}