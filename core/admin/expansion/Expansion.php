<?php


namespace core\admin\expansion;

use core\system\Controller;
use core\traites\Singleton;

class Expansion extends Controller
{

    use Singleton;

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