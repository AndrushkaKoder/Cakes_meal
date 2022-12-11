<?php


namespace webQAdmin\expansion;


class VisitorsExpansion extends Expansion
{

    public function expansion($args = [], $obj = false)
    {
        parent::expansion($args, $obj);

        if($this->className === 'Add' || $this->className === 'Edit'){

            $this->translate['name'] = ['ФИО'];

        }
    }

}