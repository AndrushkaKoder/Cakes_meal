<?php


namespace webQAdmin\expansion;

use webQSystem\Controller;
use webQTraits\Singleton;

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