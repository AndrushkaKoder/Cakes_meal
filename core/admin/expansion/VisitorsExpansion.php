<?php


namespace core\admin\expansion;


use core\base\controller\BaseMethods;
use core\base\controller\Singleton;

class VisitorsExpansion extends Expansion
{

    use Singleton;
    use BaseMethods;

    public function expansion($args = [], $obj = false)
    {
        parent::expansion($args, $obj);

        if($this->className === 'Add' || $this->className === 'Edit'){

            $this->translate['name'] = ['ФИО'];

        }
    }

}